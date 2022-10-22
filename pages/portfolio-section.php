<article id="projets-personnels" aria-labelledby="titre-projets-perso">
  <h4 class="sous-section" id="titre-projets-perso" data-string="titre-projets-perso"><?=$translation->get('titre-projets-perso')?></h4>

  <div class="liste-projets" style="--nmax: <?=count($projets)?>">
    <?php
    usort($projets, array('Projet', 'sortByFeatured'));
    foreach(array_reverse($projets) as $n => $projet)
    {
      $featured = $projet->featured ? 'featured' : '';

      $etudes = array(
        dirname(__DIR__, 1) . '/projets/' . $projet->id . '/etude-fr.htm',
        dirname(__DIR__, 1) . '/projets/' . $projet->id . '/etude-en.htm'
      );

      $en_exists = file_exists($etudes[1]);

      $imageProjet = 'projets/' . $projet->id . '/preview' . $projet->image_preview;
      $versionImageProjet = version([__DIR__.'/../'.$imageProjet.'.png']);
      $imageProjet = $imageProjet . '--' . $versionImageProjet . '.png';

      $couleurProjet = $projet->couleur->change('l', '50%', true);
      // Amélioration du contraste entre la couleur du projet et le texte blanc
      while(Couleur::contrast($couleurProjet, new Couleur('white')) < 4.5) {
        $couleurProjet = $couleurProjet->change('bk', '+5%')->change('w', '-5%');
        if ($couleurProjet->w < 0.05 && $couleurProjet->bk > 0.95) break;
      }
      ?>

      <a href="<?=$projet->lien?>" target="_blank"
          class="projet-conteneur focusable expandable <?=$featured?>" tabindex="0"
          aria-labelledby="titre-projets-perso projet-titre-<?=$projet->id?>"
          id="projet-preview-<?=$projet->id?>"
          style="--projet-color:<?=$couleurProjet->hsl()?>;"
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
            <span data-string="projet-<?=$projet->id?>-description"><?=$translation->get('projet-'.$projet->id.'-description')?></span>
          </div>
        </div>

      </a>

      <?php
    }
    ?>
  </div>
</article>

<?php /*<article id="projets-professionnels" aria-labelledby="titre-projets-perso">
  <h4 class="sous-section" id="titre-projets-perso" data-string="titre-projets-pro"><?=$translation->get('titre-projets-pro')?></h4>
  
  <p>J'attends de pouvoir remplir cette section avec impatience !</p>
</article>*/ ?>