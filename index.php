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



// Gestion de l'URL demandée et adaptation de la page

//// Récupération de la section de départ
$start_section = 'accueil';
if (isset($_GET['onav'])) {
  $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);

  if (in_array($onav, array('bio', 'projets', 'blog', 'contact')))
    $start_section = $onav;
}
$isAccueil = ($start_section == 'accueil');

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
    $versionStyle = version(__DIR__.'/pages', $section . '-style.css.php');
    if ($k > 0) $linkHeader .= ', ';
    $linkHeader .= '</mon-portfolio/pages/' . $section . '-style--' . $versionStyle . '.css.php>; rel=preload; as=style';
  }
  header($linkHeader);
}

//// Détermination de la langue

$urlLang = isset($_GET['lang']) ? substr(htmlspecialchars($_GET['lang']), 0, 2) : null;
$cookieLang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : null;
$userLang = $cookieLang ?: httpLanguage() ?: 'en';
$lang = $urlLang ?: $userLang;
$Textes = new Textes('mon-portfolio', $lang);

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
if ($titre_page != false) $titre = $titre_page . ' — ' . $titre;
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

    <!-- ▼ Cache-busted files -->
    <!--<?php versionizeStart(); ?>-->
    
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

    <!-- Import map -->
    <script defer src="/_common/polyfills/es-module-shims.js"></script>
    <script type="importmap"><?php include 'importMap.json'; ?></script>

    <!-- Scripts principaux -->
    <script type="module" src="/mon-portfolio/modules/main.js"></script>

    <!--<?php versionizeEnd(__DIR__); ?>-->

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

    <div class="background"></div>

    <!-- CONTENU DU SITE -->
    <header>
      <nav class="s6-s5">
        <remiscan-logo animate></remiscan-logo>

        <ul class="liste-liens">
          <li>
            <a href="/bio" data-section="bio" data-string="nav-bio"
              class="lien-interne lien-nav" data-tappable <?=($start_section == 'bio') ? 'aria-current="page" tabindex="-1"' : ''?> style="--hue: 350">
              <?=$Textes->getString('nav-bio')?>
            </a>
          </li>
          <li>
            <a href="/<?=($lang == 'fr' ? 'projets' : 'projects')?>" data-section="projets" data-string="nav-projets"
              class="lien-interne lien-nav" data-tappable <?=($start_section == 'projets') ? 'aria-current="page" tabindex="-1"' : ''?> style="--hue: 230">
              <?=$Textes->getString('nav-projets')?>
            </a>
          </li>
          <?php $conditionBlog = true;
          if ($conditionBlog) { ?>
          <li>
            <a href="/blog" data-section="blog" data-string="nav-articles"
              class="lien-interne lien-nav" data-tappable <?=($start_section == 'blog') ? 'aria-current="page" tabindex="-1"' : ''?> style="--hue: 20">
              <?=$Textes->getString('nav-articles')?>
            </a>
          </li>
          <?php } ?>
        </ul>
      </nav>
    </header>

    <main>
      <!-- -------------- -->
      <!-- Page d'accueil -->
      <!-- -------------- -->
      <article id="accueil" data-section="accueil">
        <h1 class="article-titre s2">Bonjour</h1>
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
        <h1 class="article-titre s2">Mes projets</h1>
      </article>

      <!-- ---- -->
      <!-- Blog -->
      <!-- ---- -->
      <article id="blog" data-section="blog">
        <h1 class="article-titre s2">Blog</h1>
      </article>
    </main>

  </body>
</html>