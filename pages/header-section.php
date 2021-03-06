<div class="background" aria-hidden="true"></div>

<article id="intro">

  <div class="moi">
    <div class="vraiment-moi">
      <h1 class="nom">Rémi Scandella</h1>
      <h2 class="h3 job" data-string="job"><?=$Textes->getString('job')?></h2>
    </div>

    <a id="nav_accueil" href="/"
        class="focusable" tabIndex="-1"
        data-label="nav-accueil" aria-label="<?=$Textes->getString('nav-accueil')?>"
        style="--article-color:<?=$c_default_bgcolor->hsl()?>;">
      <i class="svg"><svg viewBox="0 0 24 24"><use href="#arrow-back" /></svg></i>
    </a>
  </div>

  <div id="liens_contact" class="h4">
    <h3 class="h4" data-string="me-contacter" aria-hidden="true" hidden><?=$Textes->getString('me-contacter')?></h3>

    <a id="nav_contact" href="/contact"
       class="focusable expandable nav" tabindex="0"
       data-label="contact-email" aria-label="<?=$Textes->getString('contact-email')?>"
       style="--social-color:<?=$c_email->hsl()?>; 
              --article-color:<?=$c_email->hsl()?>;">
      <i class="svg"><svg viewBox="0 0 24 24" id="svg-email"><use href="#email-closed" /></svg></i>
      <div class="underline"></div>
    </a>

    <noscript>
      <a href="mailto:contact@remiscan.fr" target="_blank" rel="noopener"
         class="focusable expandable" tabindex="0"
         data-label="contact-email" aria-label="<?=$Textes->getString('contact-email')?>"
         style="--social-color:<?=$c_email->hsl()?>;">
        <i class="svg"><svg viewBox="0 0 24 24" id="svg-email"><use href="#email-closed" /></svg></i>
      </a>
    </noscript>

    <!--<a href="https://www.facebook.com/remiscan" target="_blank" rel="noopener"
         class="focusable expandable" tabindex="0"
         data-label="contact-facebook" aria-label="<?=$Textes->getString('contact-facebook')?>"
         style="--social-color:<?=$c_facebook->hsl()?>;">
      <i class="svg" style="width: 1.7rem; height: 1.7rem;"><svg viewBox="0 0 266.893 266.895"><use href="#facebook" /></svg></i>
    </a>-->

    <a href="https://www.linkedin.com/in/remiscan" target="_blank" rel="noopener"
       class="focusable expandable" tabindex="0"
       data-label="contact-linkedin" aria-label="<?=$Textes->getString('contact-linkedin')?>"
       style="--social-color:<?=$c_linkedin->hsl()?>;">
      <i class="svg" style="width: 1.25rem; height: 1.25rem;"><svg viewBox="0 0 24 24"><use href="#linkedin" /></svg></i>
    </a>

    <!--<a href="https://twitter.com/Remiscan" target="_blank" rel="noopener"
       class="focusable expandable" tabindex="0"
       data-label="contact-twitter" aria-label="<?=$Textes->getString('contact-twitter')?>"
       style="--social-color:<?=$c_twitter->hsl()?>;">
      <i class="svg" style="width: 1.7rem; height: 1.7rem; transform: scale(1.3)"><svg viewBox="0 0 400 400"><use href="#twitter" /></svg></i>
    </a>-->

    <a href="https://github.com/Remiscan" target="_blank" rel="noopener"
       class="focusable expandable" tabindex="0"
       data-label="contact-github" aria-label="<?=$Textes->getString('contact-github')?>"
       style="--social-color:<?=$c_github->hsl()?>;">
      <i class="svg" style="width: 1.4rem; height: 1.4rem;"><svg viewBox="0 0 16 16"><use href="#github" /></svg></i>
    </a>

    <a href="https://codepen.io/remiscan" target="_blank" rel="noopener"
       class="focusable expandable" tabindex="0"
       data-label="contact-github" aria-label="<?=$Textes->getString('contact-codepen')?>"
       style="--social-color:<?=$c_codepen->hsl()?>;">
      <i class="svg" style="width: 1.5rem; height: 1.5rem;"><svg viewBox="20 20 80 80"><use href="#codepen" /></svg></i>
    </a>
  </div>
  
</article>

<nav>
  <a id="nav_bio" class="focusable expandable nav"
     href="/bio"
     tabindex="0" data-label="nav-bio" aria-label="<?=$Textes->getString('nav-bio')?>"
     style="--article-color:<?=$c_section_parcours->hsl()?>;"
  >
    <h3 data-string="nav-bio"><?=$Textes->getString('nav-bio')?></h3>
    <div class="underline"></div>
  </a>

  <a id="nav_portfolio" class="focusable expandable nav"
     href="/portfolio"
     tabindex="0" data-label="nav-portfolio" aria-label="<?=$Textes->getString('nav-portfolio')?>"
     style="--article-color:<?=$c_section_portfolio->hsl()?>;"
  >
    <h3 data-string="nav-portfolio"><?=$Textes->getString('nav-portfolio')?></h3>
    <div class="underline"></div>
  </a>
</nav>

<div class="groupe-langages">
  <button class="bouton-langage focusable h6" data-lang="fr">Français</button>
  <button class="bouton-langage focusable h6" data-lang="en" disabled>English</button>
</div>