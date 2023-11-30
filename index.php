<?php
  $commonDir = dirname(__DIR__, 1).'/_common';
  require_once __DIR__.'/modules/class_Competence.php';
  require_once __DIR__.'/modules/class_Couleur.php';
  require_once __DIR__.'/modules/class_Projet.php';
  
  include __DIR__ . '/donnees.php'; // Définition des variables de données, comme les couleurs

  require_once $commonDir.'/php/Translation.php';
  $translation = new Translation(__DIR__.'/strings.json');
  $httpLanguage = $translation->getLanguage();
  
  $urlLang = isset($_GET['lang']) ? htmlspecialchars($_GET['lang']) : null;
  $cookieLang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : null;
  $lang = $urlLang ?: $cookieLang ?: $httpLanguage ?: 'en';
  $translation->setLanguage($lang);

  // Gestion de l'URL demandée et adaptation de la page

  //// Récupération de l'article de départ
  $start_article = 'accueil';
  if (isset($_GET['onav'])) {
    $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);
    if (in_array($onav, ['competences', 'bio', 'portfolio', 'projet', 'contact'])) {
      $start_article = $onav;
    }
  }
  $isAccueil = ($start_article === 'accueil');

  //// Récupération du projet de départ
  $start_projet = '';
  if (isset($_GET['projet'])) {
    $oprojet = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['projet']);

    $idsProjets = array();
    foreach($projets as $projet) { $idsProjets[] = $projet->id; }
    
    if (in_array($oprojet, $idsProjets)) $start_projet = $oprojet;
  }

  //// Si l'URL demandée est /
  $start_color = $c_default_bgcolor;
  $titre_page = false;

  //// Si une URL différente de / est demandée
  if (!$isAccueil) {
    switch($start_article) {
      case 'bio':
        $start_color = $c_article_parcours;
        $titre_page = $translation->get('nav-bio');
        break;
      case 'projet':
      case 'portfolio':
        $start_color = $c_article_portfolio;
        $titre_page = $translation->get('nav-portfolio');
        break;
      case 'contact':
        $start_color = $c_email;
        $titre_page = $translation->get('nav-contact');
        break;
    }
  }
  $load_color = Couleur::blend($start_color, $c_topcolor);
  $start_meta_color = $load_color;

  //// Donne le titre de la page
  $titre = 'Rémi S., ' . $translation->get('job');
  if ($titre_page) $titre .= ' — ' . $titre_page;

  //// Liste des fichiers style-*.css critiques ou non
  $styles_critiques = ['global'];
  if ($start_article != 'accueil') $styles_critiques[] = $start_article;
  if ($start_article == 'projet')  $styles_critiques[] = 'portfolio';
  $styles_non_critiques = array_diff(['bio', 'portfolio', 'projet', 'contact'], $styles_critiques);

  // Détermine la méthode de chargement du CSS critique : 'push' ou 'inline'
  // (dé/commenter la première ligne pour changer de méthode)
  $css_critique_methode = 'push'; /*
  $css_critique_methode = 'inline'; /**/

  if ($css_critique_methode == 'push') {
    //// On demande au serveur de PUSH le css critique avec HTTP2
    $linkHeader = 'Link: ';
    foreach($styles_critiques as $k => $article) {
      $versionStyle = version([__DIR__.'/pages/'.$article.'-style.css']);
      if ($k > 0) $linkHeader .= ', ';
      $linkHeader .= '</mon-portfolio/pages/' . $article . '-style--' . $versionStyle . '.css>; rel=preload; as=style';
    }
    header($linkHeader);
  }
?>
<!doctype html>
<html data-version="<?=version([__DIR__])?>" lang="<?=$lang?>" <?=$isAccueil?'':'class="actif"'?>>

  <head>
    <meta charset="utf-8">
    <title><?=$titre?></title>

    <meta name="description" content="<?=$translation->get('description-site')?>">
    <meta property="og:title" content="Rémi S., <?=$translation->get('job')?>">
    <meta property="og:description" content="<?=$translation->get('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/images/mosaique-preview.png">

    <meta name="viewport" content="initial-scale=1">
    <meta name="theme-color" content="<?=$start_meta_color->rgb()?>">
    <meta name="color-scheme" content="light dark">
    
    <link rel="icon" type="image/svg" href="/mon-portfolio/icons/icon.svg">
    <link rel="apple-touch-icon" href="/mon-portfolio/icons/apple-touch-icon.png">
    <link rel="manifest" href="/mon-portfolio/manifest.json">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Raleway|Roboto&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway|Roboto&display=swap" media="print" onload="this.media='all'">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php versionizeStart(); ?>-->

    <!-- Import map et polyfills -->
    <script defer src="../_common/polyfills/inert.min.js"></script>
    <script defer src="../_common/polyfills/adoptedStyleSheets.min.js"></script>
    <script>window.esmsInitOptions = { polyfillEnable: ['css-modules', 'json-modules'] }</script>
    <script defer src="../_common/polyfills/es-module-shims.js"></script>
    <script type="importmap"><?php include __DIR__.'/import-map.json'; ?></script>

    <!-- Préchargement des modules -->
    <link rel="modulepreload" href="/_common/js/cancelable-async/mod.js">
    <?php
    $mods = preg_filter('/(.+)\.js$/', '$1', scandir(__DIR__.'/modules'));
    foreach($mods as $mod) {
      ?>
      <link rel="modulepreload" href="/mon-portfolio/modules/<?=$mod?>.js">
      <?php
    } ?>

    <?php if ($css_critique_methode == 'push') { ?>

      <!-- CSS critique (pushed) -->
      <?php foreach($styles_critiques as $article) { ?>
        <link rel="stylesheet" href="/mon-portfolio/pages/<?=$article?>-style.css">
      <?php } ?>

    <?php } else { ?>

      <!-- CSS critique (inline) -->
      <?php echo '<style id="css-critique" data-sections-critiques="' . implode(',', $styles_critiques) . '">';
      foreach($styles_critiques as $article) {
        include __DIR__ . '/pages/' . $article . '-style.css';
      }
      echo '</style>'; ?>

    <?php } ?>

    <!-- CSS non-critique (préchargé) -->
    <?php foreach($styles_non_critiques as $article) { ?>
      <link rel="preload" as="style" href="/mon-portfolio/pages/<?=$article?>-style.css"
            onload="this.onload=null; this.rel='stylesheet'">
    <?php } ?>

    <!-- Scripts -->
    <script type="module" src="/mon-portfolio/modules/main.js"></script>

    <!--<?php versionizeEnd(__DIR__); ?>-->

    <noscript>
      <link rel="stylesheet" href="/mon-portfolio/style-noscript.css">
    </noscript>
  </head>
  
  <body style="--default-color:<?=$c_default_bgcolor->hsl()?>;
               --load-color:<?=$load_color->hsl()?>;
               --article-color:<?=$start_color->hsl()?>;"
        data-start="<?=$start_article?>"
        data-start-projet="<?=$start_projet?>">

    <!-- DÉFINITION DES SVG -->
    <?php include __DIR__.'/images/social.svg' ?>

    <!-- ÉCRANS DE TRANSITION -->
    <div id="loading" aria-hidden="true"></div>
    <div id="couleur" aria-hidden="true"></div>

    <!-- CONTENU DU SITE -->
    <header>
      <?php include './pages/header-page.php'; ?>
    </header>

    <main id="bottom">
      <article id="accueil" aria-label="<?=$translation->get('nav-accueil')?>"></article>

      <article id="bio" aria-labelledby="nav_bio">
        <?php include './pages/bio-page.php'; ?>
      </article>

      <article id="portfolio" aria-labelledby="nav_portfolio">
        <?php include './pages/portfolio-page.php'; ?>
      </article>

      <article id="contact" aria-labelledby="nav_contact">
        <?php include './pages/contact-page.php'; ?>
      </article>
    </main>

    <article id="projet" aria-label="<?=$translation->get('nav-projet')?>" aria-hidden="true" hidden inert>
      <?php include './pages/projet-page.php'; ?>
    </article>

    <footer>
      <div class="logo-container">
        <?php include dirname(__DIR__, 1) . '/_common/components/remiscan-logo/logo.svg'; ?>
      </div>
      <div class="groupe-langages">
        <a href="?lang=fr" class="bouton-langage h6" data-lang="fr">Français</a>
        <a href="?lang=en" class="bouton-langage h6" data-lang="en">English</a>
      </div>
    </footer>

    <!-- RÉCUPÉRATION DES PARAMÈTRES DE LA FENÊTRE -->
    <div id="defontsize" style="width: 1000rem; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="largeurpage" style="width: 100vw; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="hauteurpage" style="width: 0; height: 100vh; position: absolute;" aria-hidden="true"></div>
  </body>
</html>