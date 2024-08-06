<?php
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
?>

<section id="presentation_texte" class="h5" aria-labelledby="titre-bio">
  <h4 class="sous-section" id="titre-bio"><?=$translation->get('titre-bio')?></h4>

  <div class="biographie">
    <div id="photo" class="cadre-photo">
      <div id="photosecret" class="nope"></div>
    </div>

    <p>
      <span><?=$translation->get('bio-intro-avant-age')?></span>
      <span><?=age()?></span>
      <span><?=$translation->get('bio-intro-apres-age')?></span>
    </p>

    <p><?=$translation->get('bio-paragraphe-geek')?></p>

    <p><?=$translation->get('bio-paragraphe-science')?></p>
      
    <p>
      <span><?=$translation->get('bio-conclusion')?></span>
    </p>

    <p>
      <span><?=$translation->get('bio-contact-avant-lien')?></span><a href="mailto:contact@remiscan.fr" target="_blank" rel="noopener" class="mecontacter"><?=$translation->get('bio-contact-lien')?></a><span><?=$translation->get('bio-contact-apres-lien')?></span>
    </p>
  </div>
</section>

<section id="exp" aria-labelledby="titre-exp">
  <h4 class="sous-section" id="titre-exp"><?=$translation->get('titre-exp')?></h4>

  <div class="liste-competences" style="--max-competences: <?=count($competences)?>;">
    <?php
    foreach($competences as $n => $competence) {
      ?>

      <div class="competence-conteneur <?='mini'//$competence->mini?'mini':''?>"
          style="--competence-color:<?=$competence->couleur->improveContrast('white', 80, as: 'background')->hsl()?>;
                 --colonne: <?=$competence->colonne?>;
                 --ligne: <?=$n + 2?>;
                 --n: <?=$n?>;
      ">

        <div class="competence-background"></div>

        <div class="competence-texte">
          <div class="competence-intro">
            <span class="competence-nom h4"><?=$competence->nom?></span>
          </div>

          <div class="competence-exemples">
            <?php $nombre_exemples = count($competence->exemples);
            foreach($competence->exemples as $k => $exemple) { ?>
              <span><?=$translation->get('competence-'.strtolower($competence->nom).'-ex-'.$exemple)?></span>
              <?=($k < $nombre_exemples - 1)?'&nbsp;· ':' …'?>
            <?php } ?>
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
</section>