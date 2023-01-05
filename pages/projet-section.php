<div id="projet-obfuscator" aria-hidden="true"></div>

<div id="projet-transition" aria-hidden="true">
  <div id="projet-transition-top"></div>
  <div id="projet-transition-bottom"></div>
</div>

<div id="projet-contenu">
  <a id="projet-close" role="link" href="#"
      data-label="projet-bouton-fermer" aria-label="<?=$translation->get('projet-bouton-fermer')?>">
    <i class="svg"><svg viewBox="0 0 24 24"><use href="#close" /></svg></i>
  </a>

  <div id="projet-details-top">
    <div id="projet-details-icone"></div>
    <div id="projet-details-intro">
      <h1 class="h3" id="projet-details-titre"></h1>
      <h2 class="h4" id="projet-details-description"></h2>
    </div>
    <a id="projet-details-lien" target="_blank" rel="noopener" class="expandable" role="link"
        data-label="projet-bouton-visiter" aria-label="<?=$translation->get('projet-bouton-visiter')?>">
      <span data-string="projet-bouton-visiter"><?=$translation->get('projet-bouton-visiter')?></span>
    </a>
  </div>

  <div id="projet-details-images">
    <div id="projet-details-images_conteneur">
      <div id="projet-details-image-phone"></div>
      <div id="projet-details-image-pc"></div>
    </div>
  </div>

  <p id="projet-details-longue_description" class="ignore-scrollbar h5"></p>

  <div id="projet-details-ligne" class="ignore-scrollbar">
    <div class="ligne"></div>
    <span data-string="projet-etude-details"><?=$translation->get('projet-etude-details')?></span>
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