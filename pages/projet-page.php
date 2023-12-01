<div id="projet-obfuscator" aria-hidden="true"></div>

<div id="projet-transition" aria-hidden="true">
  <div id="projet-transition-top"></div>
  <div id="projet-transition-bottom"></div>
</div>

<div id="projet-contenu">
  <a id="projet-close" role="link" href="#"
     aria-label="<?=$translation->get('projet-bouton-fermer')?>">
    <i class="svg"><svg viewBox="0 0 24 24"><use href="#close" /></svg></i>
  </a>

  <div id="projet-details-top">
    <div id="projet-details-icone"></div>
    <div id="projet-details-intro">
      <h1 class="h3" id="projet-details-titre"></h1>
      <h2 class="h4" id="projet-details-description"></h2>
    </div>
    <a id="projet-details-lien" target="_blank" rel="noopener" class="expandable" role="link"
       aria-label="<?=$translation->get('projet-bouton-visiter')?>">
      <span><?=$translation->get('projet-bouton-visiter')?></span>
    </a>
  </div>

  <div id="projet-details-images">
    <?php /*
      Image captured on my phone at 1075 * 2393,
      then converted to oxiPNG with Squoosh to preserve colors,
      then clipped to 1075 * 2214 by removing status and nav bar,
      then resized to 400 * 824 and converted to WebP (90% quality) with Squoosh
    */ ?>
    <img id="projet-details-image-phone" width="400" height="824">
    <?php /*
      Image captured on my PC dev tools at 1658 * 965,
      then resized to 1416 * 824 and converted to WebP (90% quality) with Squoosh
    */ ?>
    <img id="projet-details-image-pc" width="1416" height="824">
  </div>

  <p id="projet-details-longue_description" class="ignore-scrollbar h5"></p>

  <div id="projet-details-ligne" class="ignore-scrollbar">
    <div class="ligne"></div>
    <span><?=$translation->get('projet-etude-details')?></span>
  </div>

  <div id="projet-details" class="ignore-scrollbar">
      <div id="projet-details-loading" aria-hidden="true">
        <div class="dot" style="--n: 0"><div class="dottle"></div></div>
        <div class="dot" style="--n: 1"><div class="dottle"></div></div>
        <div class="dot" style="--n: 2" id="projet-dot2"><div class="dottle"></div></div>
      </div>

      <div id="projet-details-pourquoi" class="h5"></div>
  </div>
</div>