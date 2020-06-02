<?php 
  include __DIR__ . '/fonctions.php';
  $Textes = new Textes('mon-portfolio');

  // Gestion de l'URL demandée et adaptation de la page

  //// Récupération de la section de départ
  $start_section = 'accueil';
  if (isset($_GET['onav']))
  {
    $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);

    if (in_array($onav, array('competences', 'bio', 'portfolio', 'projet', 'contact')))
      $start_section = $onav;
  }
  $isAccueil = ($start_section == 'accueil');

  //// Récupération du projet de départ
  $start_projet = '';
  if (isset($_GET['projet']))
  {
    $oprojet = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['projet']);

    $idsProjets = array();
    foreach($projets as $projet) { $idsProjets[] = $projet->id; }
    
    if (in_array($oprojet, $idsProjets))
      $start_projet = $oprojet;
  }

  //// Si l'URL demandée est /
  $start_color = $c_default_bgcolor;
  $start_meta_color = $c_default_bgcolor->change('l', '11%', true);
  $titre_page = false;

  //// Si une URL différente de / est demandée
  if (!$isAccueil)
  {
    switch($start_section)
    {
      case 'bio':
        $start_color = $c_section_parcours;
        $titre_page = $Textes->getString('nav-bio');
        break;
      case 'projet':
      case 'portfolio':
        $start_color = $c_section_portfolio;
        $titre_page = $Textes->getString('nav-portfolio');
        break;
      case 'contact':
        $start_color = $c_email;
        $titre_page = $Textes->getString('nav-contact');
        break;
    }
    $start_meta_color = $start_color->change('l', '25%', true);
  }
  $load_color = Couleur::blend($c_topcolor, $start_color);

  //// Donne le titre de la page
  $titre = 'Rémi S., ' . $Textes->getString('job');
  if ($titre_page != false)
    $titre .= ' — ' . $titre_page;

  //// Liste des fichiers style-*.css critiques ou non
  $styles_critiques = ['global'];
  if ($start_section != 'accueil')
    $styles_critiques[] = $start_section;
  if ($start_section == 'projet')
    $styles_critiques[] = 'portfolio';
  $styles_non_critiques = array_diff(['bio', 'portfolio', 'projet', 'contact'], $styles_critiques);

  // Détermine la méthode de chargement du CSS critique : 'push' ou 'inline'
  // (dé/commenter la première ligne pour changer de méthode)
  $css_critique_methode = 'push'; /*
  $css_critique_methode = 'inline'; /**/

  if ($css_critique_methode == 'push')
  {
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
<html data-version="<?=version(__DIR__)?>" data-http-lang="<?=httpLanguage()?>" <?=$isAccueil?'':'class="actif"'?>>

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
    <meta name="theme-color" content="<?=$start_meta_color->hsl()?>">
    
    <link rel="icon" type="image/png" href="/mon-portfolio/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/mon-portfolio/icons/apple-touch-icon.png">
    <link rel="manifest" href="/mon-portfolio/manifest.json">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Raleway|Roboto&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway|Roboto&display=swap" media="print" onload="this.media='all'">

    <link rel="preload" as="fetch" href="/mon-portfolio/strings--<?=version(__DIR__, 'strings.json')?>.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">
    <link rel="modulepreload" href="../_common/js/traduction--<?=version($commonDir.'/js', 'traduction.js')?>.js">
    <?php 
    $mods = preg_filter('/(.+).js.php/', '$1', scandir(__DIR__));
    foreach($mods as $mod) { ?>
    <link rel="modulepreload" href="/mon-portfolio/<?=$mod?>--<?=version(__DIR__, $mod.'.js.php')?>.js.php">
    <?php } ?>
    <!--<link rel="modulepreload" href="/mon-portfolio/mod_a11y--<?=version(__DIR__, 'mod_a11y.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_animations--<?=version(__DIR__, 'mod_animations.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_changeCouleur--<?=version(__DIR__, 'mod_changeCouleur.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_contact--<?=version(__DIR__, 'mod_contact.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_loadImages--<?=version(__DIR__, 'mod_loadImages.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_navigation--<?=version(__DIR__, 'mod_navigation.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_Params--<?=version(__DIR__, 'mod_Params.js.php')?>.js.php">
    <link rel="modulepreload" href="/mon-portfolio/mod_projets--<?=version(__DIR__, 'mod_projets.js.php')?>.js.php">-->

    <?php if ($css_critique_methode == 'push') { ?>
    
      <!-- CSS critique (pushed) -->
      <?php foreach($styles_critiques as $section) {
        $versionStyle = version(__DIR__.'/pages', $section . '-style.css'); ?>
        <link rel="stylesheet" href="/mon-portfolio/pages/<?=$section?>-style--<?=$versionStyle?>.css">
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
      <link rel="preload" as="style" href="/mon-portfolio/pages/<?=$section?>-style--<?=version(__DIR__.'/pages', $section . '-style.css')?>.css"
            onload="this.onload=null; this.rel='stylesheet'">
      <?php
    }
    ?>

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
  
  <body style="--default-color:<?=$c_default_bgcolor->hsl()?>;
               --load-color:<?=$load_color->hsl()?>;
               --article-color:<?=$start_color->hsl()?>;"
        data-start="<?=$start_section?>"
        data-start-projet="<?=$start_projet?>">

    <!-- DÉFINITION DES SVG -->
    <?php include __DIR__.'/images/social.svg' ?>

    <!-- ÉCRANS DE TRANSITION -->
    <div id="loading" aria-hidden="true"></div>
    <div id="couleur" aria-hidden="true"></div>

    <!-- CONTENU DU SITE -->
    <header>
      <?php include './pages/header-section.php'; ?>
    </header>

    <main id="bottom">
      <section id="accueil" data-label="nav-accueil" aria-label="<?=$Textes->getString('nav-accueil')?>"></section>

      <section id="bio" aria-labelledby="nav_bio">
        <?php include './pages/bio-section.php'; ?>
      </section>

      <section id="portfolio" aria-labelledby="nav_portfolio">
        <?php include './pages/portfolio-section.php'; ?>
      </section>

      <section id="contact" aria-labelledby="nav_contact">
        <?php include './pages/contact-section.php'; ?>
      </section>
    </main>

    <section id="projet" data-label="nav-projet" aria-label="<?=$Textes->getString('nav-projet')?>" aria-hidden="true" hidden>
      <?php include './pages/projet-section.php'; ?>
    </section>

    <!-- RÉCUPÉRATION DES PARAMÈTRES DE LA FENÊTRE -->
    <div id="defontsize" style="width: 1000rem; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="largeurpage" style="width: 100vw; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="hauteurpage" style="width: 0; height: 100vh; position: absolute;" aria-hidden="true"></div>

    <!-- SCRIPTS -->
    <script src="/_common/js/test-support--<?=version($commonDir.'/js', 'test-support.js')?>.js" id="test-support-script"></script>
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
    <script type="module" src="/mon-portfolio/scripts--<?=version(__DIR__, 'scripts.js.php')?>.js.php"></script>

  </body>
</html>