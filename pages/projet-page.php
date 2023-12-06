<div class="projet-obfuscator" aria-hidden="true">
  <a href="/portfolio" aria-label="<?=$translation->get('projet-bouton-fermer')?>"></a>
</div>

<div class="projet-transition off" aria-hidden="true">
  <div class="projet-transition-top"></div>
  <div class="projet-transition-bottom"></div>
</div>

<?php
foreach($projets as $n => $projet) {
  $idProjet = $projet->id;
  ?>

  <style>
    body:not([data-projet-actuel="<?=$projet->id?>"]) #projet_<?=$projet->id?> {
      display: none;
    }

    body[data-projet-actuel="<?=$projet->id?>"] #projet-preview-<?=$projet->id?> {
      opacity: 0;
    }
  </style>

  <article id="projet_<?=$idProjet?>" <?=$start_projet === $idProjet ? '' : 'aria-hidden="true" hidden'?> style="
    --projet-color: <?=$projet->couleur->hsl()?>;
  ">
    <div class="projet-contenu <?=$start_projet === $idProjet ? 'on' : ''?>">
      <a class="projet-close" href="/portfolio"
         aria-label="<?=$translation->get('projet-bouton-fermer')?>">
        <i class="svg"><svg viewBox="0 0 24 24"><use href="#close" /></svg></i>
      </a>

      <div class="projet-details-top">
        <div class="projet-details-icone">
          <?php
          $iconeProjet = "/" . ($projet->id === "colori" ? "colori/demo" : $projet->id) . "/icons/icon.svg";
          ?>
          <img src="<?=$iconeProjet?>">
        </div>
        <div class="projet-details-intro">
          <h1 class="h3 projet-details-titre"><?=$translation->get("projet-$idProjet-titre")?></h1>
          <h2 class="h4 projet-details-description"><?=$translation->get("projet-$idProjet-description")?></h2>
        </div>
        <?php if ($projet->lien) { ?>
        <a href="<?=$projet->lien?>" target="_blank" rel="noopener"
           class="projet-details-lien expandable" role="link"
           aria-label="<?=$translation->get('projet-bouton-visiter')?>">
          <span><?=$translation->get('projet-bouton-visiter')?></span>
        </a>
        <?php } ?>
      </div>

      <div class="projet-details-images">
        <?php /*
          Image captured on my phone at 1075 * 2393,
          then converted to oxiPNG with Squoosh to preserve colors,
          then clipped to 1075 * 2214 by removing status and nav bar,
          then resized to 400 * 824 and converted to WebP (90% quality) with Squoosh
        */ 
        $versionImagePhone = version([__DIR__."/../projets/$idProjet/preview-phone.webp"]);
        $imagePhone = "/mon-portfolio/projets/$idProjet/preview-phone--$versionImagePhone.webp";
        $altImagePhone = str_replace('{p}', $translation->get("projet-$idProjet-titre"), $translation->get('projet-preview-phone-alt'));
        ?>
        <div class="projet-details-image-phone" data-type="img">
          <img src="<?=$imagePhone?>" alt="<?=$altImagePhone?>" width="400" height="824" loading="lazy">
        </div>
        <?php /*
          Image captured on my PC dev tools at 1658 * 965,
          then resized to 1416 * 824 and converted to WebP (90% quality) with Squoosh
        */
        $versionImagePC = version([__DIR__.'/../projets/'.$idProjet.'/preview-pc.webp']);
        $imagePC = "/mon-portfolio/projets/$idProjet/preview-pc--$versionImagePC.webp";
        $altImagePC = str_replace('{p}', $translation->get("projet-$idProjet-titre"), $translation->get('projet-preview-pc-alt'));
        ?>
        <div class="projet-details-image-pc" data-type="img">
          <img src="<?=$imagePC?>" alt="<?=$altImagePC?>" width="1416" height="824" loading="lazy">
        </div>
      </div>

      <p class="projet-details-longue_description ignore-scrollbar h5"><?=$translation->get("projet-$idProjet-longue-description")?></p>

      <div class="projet-details-ligne ignore-scrollbar">
        <div class="ligne"></div>
        <span><?=$translation->get('projet-etude-details')?></span>
      </div>

      <div class="projet-details ignore-scrollbar">
        <div class="projet-details-pourquoi h5">
          <?php
          $lienEtude = dirname(__DIR__, 1) . "/projets/$idProjet/etude-$lang.htm";
          if (file_exists($lienEtude)) $etude = $lienEtude;
          else                         $etude = dirname(__DIR__, 1) . "/projets/$idProjet/etude-fr.htm";
          include $etude;
          ?>
        </div>
      </div>
    </div>
  </article>

  <?php
}
?>