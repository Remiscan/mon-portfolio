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
  $start_article = '';
  if (isset($_GET['onav'])) {
    $onav = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['onav']);
    if (in_array($onav, ['competences', 'bio', 'portfolio', 'projet'])) {
      $start_article = $onav;
    }
  }
  $isAccueil = ($start_article === '');

  //// Récupération du projet de départ
  $start_projet = '';
  $projet_couleur = '';
  if (isset($_GET['projet'])) {
    $oprojet = preg_replace('/[^A-Za-z0-9-­]/', '', $_GET['projet']);

    $idsProjets = array();
    foreach($projets as $projet) { 
      $idsProjets[] = $projet->id;
      if ($projet->id === $oprojet) {
        $start_projet = $oprojet;
        $projet_couleur = $projet->couleur->hsl();
      }
    }
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
      case 'portfolio':
        $start_color = $c_article_portfolio;
        $titre_page = $translation->get('nav-portfolio');
        break;
      case 'projet':
        $start_color = $c_article_portfolio;
        $titre_page = $translation->get('titre-projet') . $translation->get("projet-$start_projet-titre");
        $start_article = 'portfolio';
        try {
          $start_meta_color = $projets[array_search($start_projet, array_map(fn($p) => $p->id, $projets))]->couleur;
        } catch (\Throwable $e) {}
        break;
    }
  }
  $load_color = Couleur::blend($start_color, $c_topcolor);
  $start_meta_color = $start_meta_color ?? $load_color;

  //// Donne le titre de la page
  $titre = 'Rémi S., ' . $translation->get('job');
  if ($titre_page) $titre = $titre_page . ' — ' . $titre;

  //// Liste des fichiers style-*.css critiques ou non
  $styles_critiques = ['global'];
  if ($start_article !== '') $styles_critiques[] = $start_article;
  if ($start_projet !== '')  $styles_critiques[] = 'projet';
  $styles_non_critiques = array_diff(['bio', 'portfolio', 'projet'], $styles_critiques);

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

  $searchParams = isset($_GET['lang']) ? '?lang=' . $_GET['lang'] : '';
?>
<!doctype html>
<html data-version="<?=version([__DIR__])?>" lang="<?=$lang?>">

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
    <link rel="canonical" href="https://remiscan.fr">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Raleway%7CRoboto&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway%7CRoboto&display=swap" media="print" onload="this.media='all'">

    <script type="speculationrules">
    {
      "prerender": [{
        "where": {
          "href_matches": "/*"
        },
        "eagerness": "moderate"
      }]
    }
    </script>

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php versionizeStart(); ?>-->

    <?php if ($css_critique_methode == 'push') { ?>

      <!-- CSS critique (pushed) -->
      <?php foreach($styles_critiques as $article) { ?>
        <link rel="stylesheet" href="/mon-portfolio/pages/<?=$article?>-style.css" blocking="render">
      <?php } ?>

    <?php } else { ?>

      <!-- CSS critique (inline) -->
      <?php echo '<style id="css-critique" data-sections-critiques="' . implode(',', $styles_critiques) . '">';
      foreach($styles_critiques as $article) {
        include __DIR__ . '/pages/' . $article . '-style.css';
      }
      echo '</style>'; ?>

    <?php } ?>

    <style>
      <?php foreach ($projets as $projet) { ?>
        body:not([data-projet-actuel="<?=$projet->id?>"]) #projet_<?=$projet->id?> {
          display: none;
        }

        body[data-projet-actuel="<?=$projet->id?>"] #projet-preview-<?=$projet->id?> {
          opacity: 0;
        }

        html:not(:has(#view-transition-styles[data-from-projet="<?=$projet->id?>"], #view-transition-styles[data-to-projet="<?=$projet->id?>"])) #projet-preview-<?=$projet->id?> .background-color {
          opacity: 0;
        }
      <?php }

      switch ($start_article) {
        case 'portfolio':
          for ($k = 10; $k > 0; $k--) {
            ?>
              @container (width <= calc(<?=$k * 24?>rem + <?=$k - 1?> * 1.2rem)) {
                .projet-conteneur {
                  --columns: <?=$k - 1?>;
                }
              }
            <?php
          }
          ?>

          <?php
          break;
      }
      ?>
    </style>

    <?php
    $sections = ['bio', 'portfolio'];

    // Utile pour détecter de quelle section on vient
    $section_precedente = $_COOKIE['section-actuelle'] ?? null;
    setcookie('section-actuelle', $start_article, path: '/');
    $projet_precedent = $_COOKIE['projet-actuel'] ?? null;
    setcookie('projet-actuel', $start_projet, path: '/');
    
    $viewTransitionTypes = [];
    if ($isAccueil) $viewTransitionTypes[] = 'vers-accueil';
    //else if ($section_precedente === '') $viewTransitionTypes[] = 'depuis-accueil';
    
    if ($start_projet) $viewTransitionTypes[] = 'vers-projet';
    else if ($projet_precedent) $viewTransitionTypes[] = 'depuis-projet';
    
    if (!$isAccueil && !$start_projet && !$projet_precedent) $viewTransitionTypes[] = 'vers-page';
    ?>

    <style
      id="view-transition-styles"
      data-view-transition-types="<?=join(' ', $viewTransitionTypes)?>"
      data-from-projet="<?=$projet_precedent?>"
      data-to-projet="<?=$start_projet?>"
      data-from-page="<?=$section_precedente?>"
      data-to-page="<?$start_article?>"
    >
      <?php include __DIR__.'/view-transitions.php'; ?>
    </style>

    <link rel="stylesheet" href="/mon-portfolio/style-noscript.css" blocking="render">
    <link rel="expect" blocking="render" href="#hauteurpage">

    <!--<?php versionizeEnd(__DIR__); ?>-->
  </head>
  
  <body style="
    --default-color: <?=$c_default_bgcolor->hsl()?>;
    --load-color: <?=$load_color->hsl()?>;
    --article-color: <?=$start_color->hsl()?>;
    <?= $projet_couleur ? '--projet-color: '.$projet_couleur.';' : '' ?>
        "
        data-section-actuelle="<?=$start_article?>"
        data-projet-actuel="<?=$start_projet?>"
        class="start">

    <!-- DÉFINITION DES SVG -->
    <?php include __DIR__.'/images/social.svg' ?>

    <!-- ÉCRANS DE TRANSITION -->
    <div id="couleur" aria-hidden="true" style="
      view-transition-name: couleur-vers-<?=$start_article?>;
    "></div>

    <!-- CONTENU DU SITE -->
    <header <?=$start_projet ? 'inert' : ''?>>
      <?php include './pages/header-page.php'; ?>
    </header>

    <main id="bottom" <?=$start_projet ? 'inert' : ''?>>
      <article id="accueil" aria-label="<?=$translation->get('nav-accueil')?>"></article>

      <article id="bio" aria-labelledby="nav_bio">
        <?php include './pages/bio-page.php'; ?>
      </article>

      <article id="portfolio" aria-labelledby="nav_portfolio">
        <?php include './pages/portfolio-page.php'; ?>
      </article>
    </main>

    <div
      id="projet"
      <?=$start_projet ? 'data-current-projet="'.$start_projet.'"' : 'aria-hidden="true" hidden inert'?>
      style="
        <?= $projet_couleur ? '--projet-color: '.$projet_couleur.';' : '' ?>
      "
    >
      <?php include './pages/projet-page.php'; ?>
    </div>

    <footer>
      <div class="logo-container">
        <?php include dirname(__DIR__, 1) . '/_common/components/remiscan-logo/logo.svg'; ?>
      </div>
      <div class="groupe-langages">
        <a href="?lang=fr" class="bouton-langage h6" style="view-transition-name: bouton-lang-fr;" data-lang="fr">Français</a>
        <a href="?lang=en" class="bouton-langage h6" style="view-transition-name: bouton-lang-en;" data-lang="en">English</a>
      </div>
    </footer>

    <!-- RÉCUPÉRATION DES PARAMÈTRES DE LA FENÊTRE -->
    <div id="defontsize" style="width: 1000rem; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="largeurpage" style="width: 100vw; height: 0; position: absolute;" aria-hidden="true"></div>
    <div id="hauteurpage" style="width: 0; height: 100vh; position: absolute;" aria-hidden="true"></div>
  </body>
</html>