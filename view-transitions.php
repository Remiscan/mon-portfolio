@view-transition {
  navigation: auto;
  types: <?=join(', ', $viewTransitionTypes)?>;
}

<?php foreach ($sections as $section) {
  if ($section !== $start_article) { ?>
    ::view-transition-group(couleur-vers-<?=$section?>) {
      z-index: 1;
      animation-direction: reverse; /* pour que l'élément reste grand à la fin de son animation, plutôt que de rétrécir */
      animation-delay: -1s; /* pour que l'animation soit déjà "finie" dès son début, pour que la couleur prenne tout l'écran tout du long */
      animation-fill-mode: forwards;
    }

    ::view-transition-old(couleur-vers-<?=$section?>) {
      animation: none;
    }

    <?php if ($start_article === '' || !$section_precedente) { ?>
      ::view-transition-old(couleur-vers-<?=$section?>) {
        display: none;
      }
    <?php } ?>
    
    ::view-transition-new(couleur-vers-<?=$section?>) {
      display: none;
    }

    ::view-transition-new(nav-link-<?=$section?>-titre) {
      opacity: .85;
    }

    ::view-transition-old(nav-link-<?=$section?>-underline) {
      height: 100%;
      animation: none;
    }
  <?php } else { ?>
    ::view-transition-group(couleur-vers-<?=$section?>) {
      z-index: 2;
      background: green;
    }

    ::view-transition-old(couleur-vers-<?=$section?>) {
      animation: none;
    }

    ::view-transition-new(couleur-vers-<?=$section?>) {
      animation: none;
      width: 100%;
      height: 100%;
    }

    ::view-transition-new(nav-link-<?=$section?>-underline) {
      height: 100%;
      animation: none;
    }
  <?php } ?>

  ::view-transition-group(nav-link-<?=$section?>-underline) {
    animation-fill-mode: both;
    z-index: 6;
  }
<?php } ?>

<?php foreach ($projets as $projet) { ?>
  <?php if (!$start_projet && !$projet_precedent) { ?>
    &::view-transition-group(conteneur-projet-<?=$projet->id?>) {
      opacity: 0;
    }
  <?php } else { ?>
    <?php if ($projet->id !== $start_projet) { ?>
      &::view-transition-group(conteneur-projet-<?=$projet->id?>) {
        opacity: 0;
      }
    <?php } else { ?>

    <?php } ?>
  <?php } ?>
<?php } ?>