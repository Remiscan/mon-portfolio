<p class="info-contact"><?=$translation->get('contact-intro')?></p>

<form id="formulaire_contact" aria-label="<?=$translation->get('nav-contact')?>">
  <input type="text" id="source_mail" name="source_mail">

  <p class="input-grid">
    <label for="adresse_mail" class="h4"><?=$translation->get('contact-champ-email')?></label>
    <input type="email" id="adresse_mail" name="adresse_mail">
    <i class="svg_error"><svg viewBox="0 0 24 24"><use href="#error" /></svg></i>
  </p>

  <p class="input-grid">
    <label for="message_mail" class="h4"><?=$translation->get('contact-champ-message')?></label>
    <textarea id="message_mail" name="message_mail" rows="8"></textarea>
    <i class="svg_error"><svg viewBox="0 0 24 24"><use href="#error" /></svg></i>
  </p>

  <p class="button">
    <button type="submit" class="expandable" aria-label="<?=$translation->get('contact-bouton-envoyer')?>">
      <span><?=$translation->get('contact-bouton-envoyer')?></span>
    </button>
  </p>
  
  <div id="button-animation">
    <div class="dot"><div class="dottle"></div></div>
    <div class="dot"><div class="dottle"></div></div>
    <div class="dot"><div class="dottle"></div></div>
    <div id="button-animation-text"></div>
  </div>
</form>