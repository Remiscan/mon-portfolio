<?php
$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir.'/php/autochargeClasses.php';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/getStrings.php';
require_once $commonDir.'/php/version.php';



// Calcule l'âge d'une date par rapport à aujourd'hui
function age($date = '1993-10-31') {
  $origine = date_create($date);
  $aujourdhui = date_create(date('Y-m-d'));
  $age = date_diff($origine, $aujourdhui);
  $age = $age->format('%y');
  return $age;
}

// Calcule le temps depuis que je programme
function agepro() {
  $mod = 2;
  $agepro = age('2006-10-31');
  $agepro = intdiv($agepro, $mod) * $mod;
  return $agepro;
}



$Textes = new Textes('mon-portfolio');

// Gestion de l'URL demandée et adaptation de la page

//// Récupération de la section de départ
$start_section = 'accueil';
if (isset($_GET['onav'])) {
  $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);

  if (in_array($onav, array('competences', 'bio', 'portfolio', 'projet', 'contact')))
    $start_section = $onav;
}
$isAccueil = ($start_section == 'accueil');

//// Si l'URL demandée est /
$titre_page = false;

//// Si une URL différente de / est demandée
if (!$isAccueil) {
  switch($start_section) {
    case 'bio':
      $titre_page = $Textes->getString('nav-bio');
      break;
    case 'projet':
    case 'portfolio':
      $titre_page = $Textes->getString('nav-portfolio');
      break;
    case 'contact':
      $titre_page = $Textes->getString('nav-contact');
      break;
  }
}

//// Donne le titre de la page
$titre = 'Rémi S., ' . $Textes->getString('job');
if ($titre_page != false)
  $titre .= ' — ' . $titre_page;

//// Liste des fichiers style-*.css critiques ou non
$styles_critiques = ['global'];
if ($start_section != 'accueil')
  $styles_critiques[] = $start_section;
$styles_non_critiques = array_diff(['bio', 'portfolio', 'projet', 'contact'], $styles_critiques);

// Détermine la méthode de chargement du CSS critique : 'push' ou 'inline'
// (dé/commenter la première ligne pour changer de méthode)
$css_critique_methode = 'push'; /*
$css_critique_methode = 'inline'; /**/

if ($css_critique_methode == 'push') {
  //// On demande au serveur de PUSH le css critique avec HTTP2
  $linkHeader = 'Link: ';
  foreach($styles_critiques as $k => $section) {
    $versionStyle = version(__DIR__.'/pages', $section . '-style.css');
    if ($k > 0) $linkHeader .= ', ';
    $linkHeader .= '</mon-portfolio/pages/' . $section . '-style--' . $versionStyle . '.css>; rel=preload; as=style';
  }
  header($linkHeader);
}
?>
<!doctype html>
<html data-version="<?=version(__DIR__)?>" data-http-lang="<?=httpLanguage()?>" lang="<?=httpLanguage()?>">

  <head>
    <meta charset="utf-8">
    <title><?=$titre?></title>

    <meta name="description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:title" content="Rémi S., <?=$Textes->getString('job')?>">
    <meta property="og:description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/images/mosaique-preview.png">

    <meta name="viewport" content="initial-scale=1">
    <meta name="theme-color" content="">
    
    <link rel="icon" type="image/png" href="/mon-portfolio/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/mon-portfolio/icons/apple-touch-icon.png">
    <link rel="manifest" href="/mon-portfolio/manifest.json">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Raleway&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway&display=swap" media="print" onload="this.media='all'">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <!-- Préchargement des textes -->
    <link rel="preload" as="fetch" href="/mon-portfolio/strings.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">
    <!-- Préchargement des modules -->
    <link rel="modulepreload" href="/_common/js/traduction.js">
    <link rel="modulepreload" href="/_common/js/cancelable-async.js">
    <?php $mods = preg_filter('/(.+)\.js\.php/', '$1', scandir(__DIR__.'/modules'));
    foreach($mods as $mod) { ?>
    <link rel="modulepreload" href="/mon-portfolio/modules/<?=$mod?>.js.php">
    <?php } ?>

    <?php if ($css_critique_methode == 'push') { ?>
    
      <!-- CSS critique (pushed) -->
      <?php foreach($styles_critiques as $section) { ?>
        <link rel="stylesheet" href="/mon-portfolio/pages/<?=$section?>-style.css">
      <?php } ?>

    <?php } else { ?>

      <!-- CSS critique (inline) -->
      <?php
      echo '<style id="css-critique" data-sections-critiques="' . implode(',', $styles_critiques) . '">';
      foreach($styles_critiques as $section) {
        include __DIR__ . '/pages/' . $section . '-style.css';
      }
      echo '</style>';
      ?>

    <?php } ?>

    <!-- CSS non-critique (préchargé) -->
    <?php
    foreach($styles_non_critiques as $section) {
      ?>
      <link rel="preload" as="style" href="/mon-portfolio/pages/<?=$section?>-style.css"
            onload="this.onload=null; this.rel='stylesheet'">
      <?php
    }
    ?>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

    <script id="preload-polyfill">
      {
        let support = false;
        let script = document.getElementById('preload-polyfill');
        try {
          support = document.createElement('link').relList.supports('preload');
        } catch(e) {
          support = false;
        }

        if (support) script.remove();
      }
      <?php include $commonDir.'/polyfills/link-preload-stylesheet.min.js'; ?>
    </script>

    <noscript>
      <link rel="stylesheet" href="/mon-portfolio/style-noscript.css">
    </noscript>
  </head>
  
  <body data-start="<?=$start_section?>" data-section="<?=$start_section?>">

    <div id="couleur"></div>

    <!-- DÉFINITION DES SVG -->
    <?php include __DIR__.'/images/social.svg' ?>

    <!-- CONTENU DU SITE -->
    <header>
      <nav>
        <a href="/" data-section="accueil" class="logo lien-nav">
          <strong></strong>
        </a>
        <a href="/bio" data-section="bio" data-string="nav-bio"
           class="lien-nav" style="--hue: 350">
          <?=$Textes->getString('nav-bio')?>
        </a>
        <a href="/projets" data-section="projets" data-string="nav-projets"
           class="lien-nav" style="--hue: 230">
          <?=$Textes->getString('nav-projets')?>
        </a>
        <a href="/articles" data-section="articles" data-string="nav-articles"
           class="lien-nav" style="--hue: 20">
          <?=$Textes->getString('nav-articles')?>
        </a>
        <a href="/contact" data-section="contact" data-string="nav-contact"
           class="lien-nav" style="--hue: 100">
          <?=$Textes->getString('nav-contact')?>
        </a>
      </nav>
    </header>

    <main>Contenu</main>

    <footer>
      <div class="bottom-links">
        <div class="socials">
          <a href="https://github.com/Remiscan" target="_blank" rel="noopener"
             class="social-link lien-nav" style="--color: #6e5494;">
            <svg viewBox="0 0 16 16"><use href="#github" /></svg>
            <span class="social-name">GitHub</span>
          </a>
          <a href="https://codepen.io/remiscan" target="_blank" rel="noopener"
             class="social-link lien-nav" style="--color: hsl(275, 70%, 40%);">
            <svg viewBox="20 20 80 80"><use href="#codepen" /></svg>
            <span class="social-name">CodePen</span>
          </a>
          <a href="https://twitter.com/Remiscan" target="_blank" rel="noopener"
             class="social-link lien-nav" style="--color: hsl(205, 99%, 55%);">
            <svg viewBox="60 60 280 280"><use href="#twitter" /></svg>
            <span class="social-name">Twitter</span>
          </a>
          <a href="https://www.linkedin.com/in/remiscan/" target="_blank" rel="noopener"
             class="social-link lien-nav" style="--color: #0077B5;">
            <svg viewBox="-1 -1 30 30"><use href="#linkedin" /></svg>
            <span class="social-name">LinkedIn</span>
          </a>
          <a href="/contact" data-section="contact"
             class="social-link lien-nav">
            <svg viewBox="0 0 24 24"><use href="#email-closed" /></svg>
            <span class="social-name">E-mail</span>
          </a>
        </div>

        <div class="options">
          <button class="bouton-langage lien-nav" data-lang="fr">Français</button>
          <button class="bouton-langage lien-nav" data-lang="en">English</button>
        </div>
      </div>
    </footer>
    <!-- FIN : CONTENU DU SITE -->

    <!-- RÉCUPÉRATION DES PARAMÈTRES DE LA FENÊTRE -->
    <div id="defontsize" style="width: 1000rem; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="largeurpage" style="width: 100vw; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="hauteurpage" style="width: 0; height: 100vh; position: absolute;" aria-hidden="true"></div>

    <!-- SCRIPTS -->
    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <script src="/_common/js/test-support.js" id="test-support-script"></script>
    <script id="test-support-script-exe">
      TestSupport.getSupportResults([
        { name: 'CSS clip-path', priority: 0 },
        { name: 'CSS custom properties', priority: 1 },
        { name: 'localStorage', priority: 0 },
        { name: 'web animations', priority: 0 },
        { name: 'ES const & let', priority: 1 },
        { name: 'ES template literals', priority: 1 },
        { name: 'ES modules', priority: 1 }
      ]);
    </script>
    <script type="module" src="/mon-portfolio/scripts.js.php"></script>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

  </body>
</html>