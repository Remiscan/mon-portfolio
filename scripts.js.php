// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import './modules/comp_mediaProjet.js.php';
import { Traduction } from './modules/mod_traduction.js.php';
import { Params, simulateClick } from './modules/mod_Params.js.php';
import { naviguer, getNavActuelle, setNavActuelle } from './modules/mod_navigation.js.php';
import { initProjets, closeProjet } from './modules/mod_projets.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/mon-portfolio/modules/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {
  const elProjet = document.getElementById('projet');
  const onav = event.state.onav;
  if (onav == 'projet')
  {
    if (elProjet.style.display != 'none' && elProjet.style.display)
      closeProjet();

    const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
    entrees.forEach(e => {
      if (e.dataset.id == event.state.oprojet_id)
        return simulateClick(e);
    });
  }
  else
  {
    if (document.getElementById('projet').classList.contains('on'))
      closeProjet();
    
    simulateClick(document.getElementById(onav));
  }
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
document.addEventListener('DOMContentLoaded', async event => {
  if (Params.startSection == 'accueil')
    history.replaceState({onav: 'nav_accueil'}, '', '/');

  await Traduction.traduire();
  await Traduction.initLanguageButtons();

  // Adapte les liens du portfolio (par défaut, ils mènent directement aux projets si JavaScript est désactivé)
  Array.from(document.querySelectorAll('a.projet-conteneur')).forEach(e => { e.href = '/projet/' + e.dataset.id; e.removeAttribute('target'); });
  // -- fin --

  initProjets();

  // Supprime l'écran de chargement
  setTimeout(() => {
    if (document.getElementById('loading')) {
      document.getElementById('loading').remove();
      document.body.style.setProperty('--load-color', null);
    }
  }, 100);

  setNavActuelle('nav_' + Params.startSection);
  
  if (Params.startSection == 'accueil')
  {
    document.documentElement.style.overflowY = 'auto';
    return;
  }

  await naviguer(event, document.getElementById(getNavActuelle()), true);
  const elProjet = document.getElementById('projet');
  if (Params.startProjet && !elProjet.classList.contains('on'))
  {
    const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
    entrees.forEach(e => {
      if (e.dataset.id == Params.startProjet)
        simulateClick(e);
    });
  }
  return;
});