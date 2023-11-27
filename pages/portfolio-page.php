<section id="projets-personnels" aria-labelledby="titre-projets-perso">
  <h4 class="sous-section" id="titre-projets-perso"><?=$translation->get('titre-projets-perso')?></h4>

  <div class="liste-projets" style="--nmax: <?=count($projets)?>">
    <?php
    foreach($projets as $n => $projet) {
      $etudes = array(
        dirname(__DIR__, 1) . '/projets/' . $projet->id . '/etude-fr.htm',
        dirname(__DIR__, 1) . '/projets/' . $projet->id . '/etude-en.htm'
      );

      $en_exists = file_exists($etudes[1]);

      $imageProjet = 'projets/' . $projet->id . '/preview' . $projet->image_preview;
      $versionImageProjet = version([__DIR__.'/../'.$imageProjet.'.png']);
      $imageProjet = $imageProjet . '--' . $versionImageProjet . '.png';
      ?>

      <a href="<?=$projet->lien?>" target="_blank"
         aria-labelledby="titre-projets-perso projet-titre-<?=$projet->id?>"
         id="projet-preview-<?=$projet->id?>"
         class="projet-conteneur expandable"
         style="
          --projet-color: <?=$projet->couleur->hsl()?>;
          --theme-color: <?=Couleur::blend($projet->couleur, $c_topcolor)->rgb()?>;
         "
         data-id="<?=$projet->id?>"
         data-lien="<?=$projet->lien?>"
         data-en-exists="<?=$en_exists?>"
         data-version="<?=version($etudes)?>">

        <div class="projet-conteneur-enfant">
          <div class="projet-image">
            <div class="projet-actual-image"
                 data-image="<?='/mon-portfolio/' . $imageProjet?>"
                 style="--n: <?=$n?>; --image: url('<?='/mon-portfolio/' . $imageProjet?>');">
            </div>
          </div>

          <div class="projet-titre">
            <h5 class="h4" id="projet-titre-<?=$projet->id?>"><?=$projet->titre?></h5>
            <span><?=$translation->get('projet-'.$projet->id.'-description')?></span>
          </div>
        </div>
        
      </a>

      <?php
    }
    ?>

    <a href="https://github.com/remiscan" target="_blank" rel="noopener" id="projet-preview-more"><?=$translation->get('projet-more-description')?></a>
  </div>
</section>

<?php /*<section id="projets-professionnels" aria-labelledby="titre-projets-perso">
  <h4 class="sous-section" id="titre-projets-perso"><?=$translation->get('titre-projets-pro')?></h4>
  
  <p>J'attends de pouvoir remplir cette section avec impatience !</p>
</section>*/ ?>