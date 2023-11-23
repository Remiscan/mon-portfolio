<?php
// COULEURS

// - Pages
$c_default_bgcolor = new Couleur('oklch(25% 0.0525 327)');
$c_article_parcours = new Couleur('oklch(40% 0.11 20)');
$c_article_portfolio = new Couleur('oklch(35% 0.13 275)');

// - Projets
$c_projet_cesite = new Couleur('oklch(50% 0.1 327)');
$c_projet_shinydex = new Couleur('oklch(50% 0.13 254)');
$c_projet_solaire = new Couleur('oklch(50% 0.1 2)');
$c_projet_csswitch = new Couleur('oklch(50% 0 0)');
$c_projet_colori = new Couleur('oklch(50% 0.13 169)');

// - Skills
$c_competence_html = new Couleur('oklch(60% 0.19 34)');
$c_competence_css = new Couleur('oklch(55% 0.19 248)');
$c_competence_php = new Couleur('oklch(60% 0.065 274)');
$c_competence_sql = new Couleur('oklch(60% 0.128 65)');
$c_competence_javascript = new Couleur('oklch(60% 0.18 133)');
$c_competence_typescript = new Couleur('oklch(57% 0.14 253)');

// - Socials
$c_github = new Couleur('oklch(50% 0.10 301)');
$c_codepen = new Couleur('oklch(50% 0.2 306)');
$c_linkedin = new Couleur('oklch(50% 0.13 242)');
$c_twitter = new Couleur('hsl(205, 99%, 55%)');
$c_facebook = new Couleur('#1E77F0');
$c_google = new Couleur('hsl(5, 69%, 56%)');
$c_email = new Couleur('oklch(50% 0 0)');

// - Misc
$c_topcolor = new Couleur('hsla(0, 0%, 0%, .5)');

// PROJETS
$projets = [
  /*new Projet('mon-portfolio', 'Ce site', $c_projet_cesite, ''),*/
  new Projet('', 'Solaire', $c_projet_solaire, 'https://remiscan.fr/solaire/'),
  new Projet('', 'Colori', $c_projet_colori, 'https://remiscan.fr/colori/'),
  new Projet('', 'Shinydex', $c_projet_shinydex, 'https://remiscan.fr/shinydex/'),
  new Projet('', 'CSSwitch', $c_projet_csswitch, 'https://remiscan.fr/csswitch/')
];

// COMPÉTENCES
$competences = [
  new Competence('HTML', '2006', $c_competence_html, 1),
  new Competence('CSS', '2006', $c_competence_css, 1),
  new Competence('PHP', '2008', $c_competence_php, 2),
  new Competence('SQL', '2009', $c_competence_sql, 2),
  new Competence('JavaScript', '2010', $c_competence_javascript, 3),
  new Competence('TypeScript', '2021', $c_competence_typescript, 4)
];