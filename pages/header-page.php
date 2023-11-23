<div class="background" aria-hidden="true"></div>

<section id="intro">

  <div class="moi">
    <div class="vraiment-moi">
      <h1 class="nom">Rémi Scandella</h1>
      <h2 class="h3 job"><?=$translation->get('job')?></h2>
    </div>

    <a id="nav_accueil" href="/" aria-label="<?=$translation->get('nav-accueil')?>"
       style="--article-color:<?=$c_default_bgcolor->hsl()?>;">
      <i class="svg">
        <svg viewBox="0 0 24 24"><use href="#arrow-back" /></svg>
      </i>
    </a>
  </div>

  <div id="liens_contact" class="h4">
    <h3 class="h4"aria-hidden="true" hidden><?=$translation->get('me-contacter')?></h3>

    <a href="https://github.com/Remiscan" target="_blank" rel="noopener"
       aria-label="<?=$translation->get('contact-github')?>"
       class="expandable" style="--social-color:<?=$c_github->hsl()?>;">
      <i class="svg" style="width: 1.4rem; height: 1.4rem;">
        <svg viewBox="0 0 16 16"><use href="#github" /></svg>
      </i>
    </a>

    <!--<a href="https://codepen.io/remiscan" target="_blank" rel="noopener"
       aria-label="<?=$translation->get('contact-codepen')?>"
       class="expandable" style="--social-color:<?=$c_codepen->hsl()?>;">
      <i class="svg" style="width: 1.5rem; height: 1.5rem;">
        <svg viewBox="20 20 80 80"><use href="#codepen" /></svg>
      </i>
    </a>-->

    <!--<a href="https://twitter.com/Remiscan" target="_blank" rel="noopener"
           aria-label="<?=$translation->get('contact-twitter')?>"
           class="expandable" style="--social-color:<?=$c_twitter->hsl()?>;">
      <i class="svg" style="width: 1.7rem; height: 1.7rem; transform: scale(1.3)">
        <svg viewBox="0 0 400 400"><use href="#twitter" /></svg>
      </i>
    </a>-->

    <a href="https://www.linkedin.com/in/remiscan" target="_blank" rel="noopener"
           aria-label="<?=$translation->get('contact-linkedin')?>"
           class="expandable" style="--social-color:<?=$c_linkedin->hsl()?>;">
      <i class="svg" style="width: 1.25rem; height: 1.25rem;">
        <svg viewBox="0 0 24 24"><use href="#linkedin" /></svg>
      </i>
    </a>

    <a href="mailto:contact@remiscan.fr" target="_blank" rel="noopener"
       aria-label="<?=$translation->get('contact-email')?>"
       class="expandable" style="--social-color:<?=$c_email->hsl()?>;">
      <i class="svg">
        <svg viewBox="0 0 24 24" id="svg-email"><use href="#email-closed" /></svg>
      </i>
    </a>
  </div>
  
</section>

<nav>
  <a id="nav_bio" href="/bio" aria-label="<?=$translation->get('nav-bio')?>"
     class="expandable nav" style="--article-color:<?=$c_article_parcours->hsl()?>;">
    <h3><?=$translation->get('nav-bio')?></h3>
    <div class="underline"></div>
  </a>

  <a id="nav_portfolio" href="/portfolio" aria-label="<?=$translation->get('nav-portfolio')?>"
     class="expandable nav" style="--article-color:<?=$c_article_portfolio->hsl()?>;">
    <h3><?=$translation->get('nav-portfolio')?></h3>
    <div class="underline"></div>
  </a>
</nav>

<div class="groupe-langages">
  <a href="?lang=fr" class="bouton-langage h6" data-lang="fr">Français</a>
  <a href="?lang=en" class="bouton-langage h6" data-lang="en">English</a>
</div>