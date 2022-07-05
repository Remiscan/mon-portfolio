<article id="presentation_texte" class="h5" aria-labelledby="titre-bio">
  <h4 class="sous-section" id="titre-bio" data-string="titre-bio"><?=$Textes->getString('titre-bio')?></h4>

  <div class="biographie">
    <div id="photo" class="cadre-photo">
      <div id="photosecret" class="nope"></div>
    </div>

    <p><span data-string="bio-intro-avant-age"><?=$Textes->getString('bio-intro-avant-age')?></span><?=age()?><span data-string="bio-intro-apres-age"><?=$Textes->getString('bio-intro-apres-age')?></span></p>

    <p data-string="bio-paragraphe-geek"><?=$Textes->getString('bio-paragraphe-geek')?></p>

    <p data-string="bio-paragraphe-science"><?=$Textes->getString('bio-paragraphe-science')?></p>
      
    <p><span data-string="bio-conclusion-avant-age"><?=$Textes->getString('bio-conclusion-avant-age')?></span><?=agepro()?><span data-string="bio-conclusion-apres-age"><?=$Textes->getString('bio-conclusion-apres-age')?></span></p>

    <p><span data-string="bio-contact-avant-lien"><?=$Textes->getString('bio-contact-avant-lien')?></span><a href="/contact" class="mecontacter focusable" tabIndex="0" data-string="bio-contact-lien"><?=$Textes->getString('bio-contact-lien')?></a><span data-string="bio-contact-apres-lien"><?=$Textes->getString('bio-contact-apres-lien')?></span></p>
  </div>
</article>

<article id="exp" aria-labelledby="titre-exp">
  <h4 class="sous-section" id="titre-exp" data-string="titre-exp"><?=$Textes->getString('titre-exp')?></h4>

  <div class="liste-competences">
    <?php
    foreach($competences as $n => $competence)
    {
      $couleurComp = $competence->couleur;
      // Amélioration du contraste entre la couleur de la compétence et le texte blanc transparent
      while(Couleur::contrast($couleurComp, new Couleur('white')) < 4.5) {
        $couleurComp = $couleurComp->change('bk', '+5%')->change('w', '-5%');
        if ($couleurComp->w < 0.05 && $couleurComp->bk > 0.95) break;
      }
      ?>

      <div class="competence-conteneur <?=$competence->mini?'mini':''?>"
          style="--competence-color:<?=$couleurComp->hsl()?>;
                  --colonne: <?=$competence->colonne?>;
                  --ligne: <?=$n + 2?>;
                  --delai: <?=$n * .1?>s">

        <div class="competence-background"></div>

        <div class="competence-texte">
          <div class="competence-intro">
            <span class="competence-nom h4"><?=$competence->nom?></span>
          </div>

          <div class="competence-exemples">
            <?php
            $nombre_exemples = count($competence->exemples);
            foreach($competence->exemples as $k => $exemple)
            {
              ?>
              <span data-string="competence-<?=strtolower($competence->nom)?>-ex-<?=$exemple?>"><?=$Textes->getString('competence-'.strtolower($competence->nom).'-ex-'.$exemple)?></span><?=($k < $nombre_exemples - 1)?'&nbsp;· ':' …'?>
              <?php
            }
            ?>
          </div>
        </div>

      </div>

      <?php
    }
    ?>

    <span class="competences-frise-separateur" style="--colonne: 1">2006</span>
    <span class="competences-frise-separateur" style="--colonne: 2">2008</span>
    <span class="competences-frise-separateur" style="--colonne: 3">2010</span>
    <span class="competences-frise-separateur" style="--colonne: 4">2021</span>
  </div>
</article>