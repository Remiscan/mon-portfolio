<?php
// COULEURS
$c_bgcolor = new Couleur('hsl(0, 0%, 13%)');
$c_topcolor = new Couleur('hsla(0, 0%, 0%, .5)');
$c_white = new Couleur('hsl(0, 0%, 100%)');
$c_default_bgcolor = new Couleur('hsl(300, 30%, 15%)');
$c_email = new Couleur('hsl(0, 0%, 30%)');
$c_facebook = new Couleur('hsl(220, 46%, 48%)');
//$c_facebook = new Couleur('#1E77F0'); // La nouvelle couleur Facebook 2019
$c_linkedin = new Couleur('#0077B5');
$c_twitter = new Couleur('hsl(205, 99%, 55%)');
$c_google = new Couleur('hsl(5, 69%, 56%)');
$c_github = new Couleur('#6e5494');
$c_codepen = new Couleur('hsl(275, 70%, 40%)');
$c_article_parcours = new Couleur('hsl(355, 50%, 32%)');
$c_article_portfolio = new Couleur('hsl(238, 50%, 32%)');
$c_competence_html = new Couleur('rgb(228, 77, 38)');
$c_competence_css = new Couleur('rgb(0, 112, 186)');
$c_competence_php = new Couleur('rgb(137, 147, 190)');
$c_competence_sql = new Couleur('rgb(208, 136, 56)');
$c_competence_javascript = new Couleur('rgb(99, 168, 20)');
$c_competence_typescript = new Couleur('#3178C6');
//$c_projet_cesite = new Couleur('hsl(261, 52%, 47%)');
$c_projet_cesite = new Couleur('hsl(300, 30%, 15%)');
$c_projet_shinydex = new Couleur('rgb(63, 81, 181)');
$c_projet_solaire = new Couleur('#211216');
$c_projet_csswitch = new Couleur('hsl(200, 0%, 40%)');
$c_projet_colori = new Couleur('aquamarine');

// PROJETS
$projets = [
  /*new Projet('mon-portfolio', 'Ce site', $c_projet_cesite, ''),*/
  new Projet('', 'Shinydex', $c_projet_shinydex, 'https://remiscan.fr/shinydex/'),
  new Projet('', 'Solaire', $c_projet_solaire, 'https://remiscan.fr/solaire/', true),
  new Projet('', 'CSSwitch', $c_projet_csswitch, 'https://remiscan.fr/csswitch/'),
  new Projet('', 'Colori', $c_projet_colori, 'https://remiscan.fr/colori/')
];

// COMPÉTENCES
$competences = [
  new Competence('HTML', '2006', $c_competence_html, 1),
  new Competence('CSS', '2006', $c_competence_css, 1, 'variables,grid,animations'),
  new Competence('PHP', '2008', $c_competence_php, 2, 'poo'),
  new Competence('SQL', '2009', $c_competence_sql, 2, 'mysql'),
  new Competence('JavaScript', '2010', $c_competence_javascript, 3, 'webanimations,serviceworkers,webapps'),
  new Competence('TypeScript', '2021', $c_competence_typescript, 4)
];