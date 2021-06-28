<?php
$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir.'/php/autochargeClasses.php';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/getStrings.php';
require_once $commonDir.'/php/version.php';

require_once __DIR__.'/donnees/projets.php';



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



$urlLang = substr(htmlspecialchars($_GET['lang']), 0, 2);
$userLang = $_COOKIE['lang'] ?: httpLanguage() ?: 'en';
$lang = $urlLang ?: $userLang;
$Textes = new Textes('mon-portfolio', $lang);

// Gestion de l'URL demandée et adaptation de la page

//// Récupération de la section de départ
$start_section = 'accueil';
if (isset($_GET['onav'])) {
  $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);

  if (in_array($onav, array('bio', 'projets', 'blog', 'contact')))
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
    case 'projets':
      $titre_page = $Textes->getString('nav-projets');
      break;
    case 'blog':
      $titre_page = $Textes->getString('nav-articles');
      break;
    case 'contact':
      $titre_page = $Textes->getString('nav-contact');
      break;
  }
}

//// Donne le titre de la page
$titre = 'Rémi S., ' . $Textes->getString('job');
if ($titre_page != false)
  $titre = $titre_page . ' — ' . $titre;

//// Liste des fichiers style-*.css critiques ou non
$styles_critiques = ['variables', 'global'];
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
<html lang="<?=$lang?>"
      <?php if ($urlLang && $urlLang != $userLang) { ?>data-url-lang="<?=$urlLang?>"<?php } ?>
      data-version="<?=version(__DIR__)?>"
      data-theme="<?=$_COOKIE['theme'] ?? 'auto'?>">

  <head>
    <meta charset="utf-8">
    <title><?=$titre?></title>

    <meta name="description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:title" content="Rémi S., <?=$Textes->getString('job')?>">
    <meta property="og:description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/images/mosaique-preview.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="">
    <meta name="color-scheme" content="light dark">
    
    <link rel="icon" type="image/png" href="/mon-portfolio/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/mon-portfolio/icons/apple-touch-icon.png">
    <link rel="manifest" href="/mon-portfolio/manifest.json">

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
        <link rel="stylesheet" href="/mon-portfolio/pages/<?=$section?>-style.css.php">
      <?php } ?>

    <?php } else { ?>

      <!-- CSS critique (inline) -->
      <?php
      echo '<style id="css-critique" data-sections-critiques="' . implode(',', $styles_critiques) . '">';
      foreach($styles_critiques as $section) {
        include __DIR__ . '/pages/' . $section . '-style.css.php';
      }
      echo '</style>';
      ?>

    <?php } ?>

    <!-- CSS non-critique (préchargé) -->
    <?php
    foreach($styles_non_critiques as $section) {
      ?>
      <link rel="preload" as="style" href="/mon-portfolio/pages/<?=$section?>-style.css.php"
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
      <nav class="s6">
        <a href="/" data-section="accueil" class="logo lien-nav">
          <strong></strong>
        </a>

        <ul>
          <li>
            <a href="/bio" data-section="bio" data-string="nav-bio"
              class="lien-interne lien-nav" <?=($start_section == 'bio') ? 'aria-current="page"' : ''?> style="--hue: 350">
              <?=$Textes->getString('nav-bio')?>
            </a>
          </li>
          <li>
            <a href="/<?=($lang == 'fr' ? 'projets' : 'projects')?>" data-section="projets" data-string="nav-projets"
              class="lien-interne lien-nav" <?=($start_section == 'projets') ? 'aria-current="page"' : ''?> style="--hue: 230">
              <?=$Textes->getString('nav-projets')?>
            </a>
          </li>
          <?php $conditionBlog = false;
          if ($conditionBlog) { ?>
          <li>
            <a href="/articles" data-section="articles" data-string="nav-articles"
              class="lien-interne lien-nav" <?=($start_section == 'blog') ? 'aria-current="page"' : ''?> style="--hue: 20">
              <?=$Textes->getString('nav-articles')?>
            </a>
          </li>
          <?php } ?>
          <li>
            <a href="/contact" data-section="contact" data-string="nav-contact"
              class="lien-interne lien-nav" <?=($start_section == 'contact') ? 'aria-current="page"' : ''?> style="--hue: 100">
              <?=$Textes->getString('nav-contact')?>
            </a>
          </li>
        </ul>
      </nav>
    </header>

    <main>
      <!-- -------------- -->
      <!-- Page d'accueil -->
      <!-- -------------- -->
      <article id="accueil" data-section="accueil">
        <!-- Mini-bio -->
        <section class="accueil-moi s1">
          <span data-string="je-suis-remi"><?=$Textes->getString('je-suis-remi')?></span>
          <span data-string="je-suis-remi-2"><?=$Textes->getString('je-suis-remi-2')?></span>
          <span data-string="je-suis-remi-3"><?=$Textes->getString('je-suis-remi-3')?></span>
          <a href="/bio" data-section="bio" class="lien-interne lien-fleche s5 bouton" data-string="lien-more-bio"><?=$Textes->getString('lien-more-bio')?></a>
        </section>

        <!-- Mini-articles -->
        <!--<section class="accueil-articles">
          <div class="section-titre">
            <h4>Articles récents :</h4>
            <a href="/articles" data-section="articles" class="lien-fleche h5">Tous mes articles</a>
            <span class="section-titre-fin"></span>
          </div>
        </section>-->

        <!-- Mini-projets -->
        <section class="accueil-projets">
          <div class="section-titre">
            <h2 data-string="projets-recents" class="s2"><?=$Textes->getString('projets-recents')?></h2>
            <a href="/projets" data-section="projets" class="lien-interne lien-fleche s5" data-string="lien-more-projets"><?=$Textes->getString('lien-more-projets')?></a>
            <span class="section-titre-fin"></span>
          </div>

          <div class="accueil-conteneur-projets">
            <?php
            foreach($PROJETS as $projet) {
              if ($projet['featured']) {
                $imageDark = __DIR__."/projets/{$projet['id']}/preview-dark.png";
                $imageLight = __DIR__."/projets/{$projet['id']}/preview-light.png";
                if (file_exists($imageDark)) {
                  $versionDark = version(__DIR__, $imageDark);
                  $versionLight = version(__DIR__, $imageLight);
                  $imageDark = __DIR__."/projets/{$projet['id']}/preview-dark--{$versionDark}.png";
                  $imageLight = __DIR__."/projets/{$projet['id']}/preview-light--{$versionLight}.png";
                  $style = "--image-dark: url('/mon-portfolio/projets/{$projet['id']}/preview-dark--{$versionDark}.png'); --image-light: url('/mon-portfolio/projets/{$projet['id']}/preview-light--{$versionLight}.png');";
                } else {
                  $style = "--image: url('/mon-portfolio/projets/{$projet['id']}/preview.png');";
                }
                ?>

            <a href="/projet/<?=$projet['id']?>" class="apercu-projet">
              <div class="apercu-projet-image" style="<?=$style?>"></div>
              <div class="apercu-projet-infos">
                <span class="apercu-projet-titre s5"><?=$projet['titre']?></span>
                <span class="apercu-projet-description s7" data-string="projet-<?=$projet['id']?>-description"><?=$Textes->getString('projet-'.$projet['id'].'-description')?></span>
                <span class="lien-interne lien-fleche apercu-projet-lien s7"><span class="apercu-projet-lien-texte" data-string="lien-details-projet"><?=$Textes->getString('lien-details-projet')?></span></span>
              </div>
            </a>

                <?php
              }
            }
            ?>
          </div>
        </section>
      </article>

      <!-- ----------- -->
      <!-- Qui je suis -->
      <!-- ----------- -->
      <article id="bio" data-section="bio">
        <h1 class="article-titre s2">Qui je suis</h1>
      </article>

      <!-- ----------- -->
      <!-- Mes projets -->
      <!-- ----------- -->
      <article id="projets" data-section="projets">
        <section class="liste-projets" style="--nombre-lignes: <?=round(ceil($PROJETS) / 2)?>;">
          <?php
            foreach($PROJETS as $projet) {
              $imageDark = __DIR__."/projets/{$projet['id']}/preview-dark.png";
              $imageLight = __DIR__."/projets/{$projet['id']}/preview-light.png";
              if (file_exists($imageDark)) {
                $versionDark = version(__DIR__, $imageDark);
                $versionLight = version(__DIR__, $imageLight);
                $imageDark = __DIR__."/projets/{$projet['id']}/preview-dark--{$versionDark}.png";
                $imageLight = __DIR__."/projets/{$projet['id']}/preview-light--{$versionLight}.png";
                $style = "--image-dark: url('/mon-portfolio/projets/{$projet['id']}/preview-dark--{$versionDark}.png'); --image-light: url('/mon-portfolio/projets/{$projet['id']}/preview-light--{$versionLight}.png');";
              } else {
                $style = "--image: url('/mon-portfolio/projets/{$projet['id']}/preview.png');";
              }
              ?>

          <div class="grand-apercu-projet">
            <a href="/projet/<?=$projet['id']?>" data-projet="<?=$projet['id']?>" class="grand-apercu-projet-lien">
              <h2 class="apercu-projet-titre s2"><?=$projet['titre']?></h2>
              <span class="lien-interne lien-fleche apercu-projet-lien s5">
                <span class="apercu-projet-lien-texte" data-string="lien-details-projet"><?=$Textes->getString('lien-details-projet')?></span>
              </span>

              <div class="grand-apercu-projet-image" style="<?=$style?>"></div>
            </a>

            <p class="apercu-projet-description s5" data-string="projet-<?=$projet['id']?>-longue-description"><?=$Textes->getString('projet-'.$projet['id'].'-longue-description')?></p>
          </div>

              <?php
            }
            ?>
        </section>
      </article>
    </main>

    <footer class="s7">
      <div class="liens-bottom">
        <a href="https://github.com/Remiscan" target="_blank" rel="noopener"
            class="lien-interne lien-social" style="
            --color-dark: <?=(new Couleur('#6e5494'))->improveContrast('black', 8, 1)->hsl()?>;
            --color-light: <?=(new Couleur('#6e5494'))->improveContrast('white', 8, 1)->hsl()?>;
            ">
          <svg viewBox="0 0 16 16"><use href="#github" /></svg>
          <span class="social-nom">GitHub</span>
        </a>
        <a href="https://codepen.io/remiscan" target="_blank" rel="noopener"
            class="lien-interne lien-social" style="
            --color-dark: <?=(new Couleur('hsl(275, 70%, 40%)'))->improveContrast('black', 8, 1)->hsl()?>;
            --color-light: <?=(new Couleur('hsl(275, 70%, 40%)'))->improveContrast('white', 8, 1)->hsl()?>;
            ">
          <svg viewBox="20 20 80 80"><use href="#codepen" /></svg>
          <span class="social-nom">CodePen</span>
        </a>
        <a href="https://www.linkedin.com/in/remiscan/" target="_blank" rel="noopener"
            class="lien-interne lien-social" style="
            --color-dark: <?=(new Couleur('#0077B5'))->improveContrast('black', 8, 1)->hsl()?>;
            --color-light: <?=(new Couleur('#0077B5'))->improveContrast('white', 8, 1)->hsl()?>;
            ">
          <svg viewBox="-1 -1 30 30"><use href="#linkedin" /></svg>
          <span class="social-nom">LinkedIn</span>
        </a>
        <a href="https://twitter.com/Remiscan" target="_blank" rel="noopener"
            class="lien-interne lien-social" style="
            --color-dark: <?=(new Couleur('hsl(205, 99%, 55%)'))->improveContrast('black', 8, 1)->hsl()?>;
            --color-light: <?=(new Couleur('hsl(205, 99%, 55%)'))->improveContrast('white', 8, 1)->hsl()?>;
            ">
          <svg viewBox="60 60 280 280"><use href="#twitter" /></svg>
          <span class="social-nom">Twitter</span>
        </a>
        
        <div class="spacer"></div>

        <a href="?lang=fr" class="lien-interne bouton-langage" lang="fr" <?php if ($lang == 'fr') { ?>disabled tabindex="-1"<?php } ?>>Français</a>
        <a href="?lang=en" class="lien-interne bouton-langage" lang="en" <?php if ($lang == 'en') { ?>disabled tabindex="-1"<?php } ?>>English</a>
        <theme-selector position="top"></theme-selector>
      </div>
    </footer>

    <div class="cookie-consent-container"></div>
    <!-- FIN : CONTENU DU SITE -->

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