// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { traduire, switchLangage } from '../_common/js/traduction.js';
import { Params, recalcOnResize, simulateClick } from './mod_Params.js.php';
import { naviguer, getNavActuelle, setNavActuelle, getTitrePage } from './mod_navigation.js.php';
import { closeProjet } from './mod_projets.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 1).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! TEXTE ET TRADUCTION !!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/


/////////////////////////////////////////////
// On expand la fonction traduire() du module
// (si y a des trucs supplémentaires à traduire qui ne sont pas universels au module)
function textualiser()
{
  return traduire('mon-portfolio');
}


///////////////////////////////////////////
// Active le bouton de changement de langue
Array.from(document.querySelectorAll('.bouton-langage')).forEach(bouton => {
  bouton.addEventListener('click', () => {
    switchLangage(bouton.dataset.lang)
    .then(textualiser)
    .then(() => {
      recalcOnResize();
      document.title = getTitrePage(history.state.onav.replace('nav_', ''));
    })
  });
});



/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! NAVIGATION !!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/


////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
const elProjet = document.getElementById('projet');

window.addEventListener('popstate', event => {
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




/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! QUAND TOUT EST PRÊT !!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/


document.addEventListener('DOMContentLoaded', event => {
  return textualiser()
  .then(() => {
    recalcOnResize();
    // Supprime le contenu 'noscript' quand JavaScript est activé
    Array.from(document.querySelectorAll('noscript')).forEach(e => e.remove());
    Array.from(document.querySelectorAll('a.projet-conteneur')).forEach(e => { e.href = '/projet/' + e.dataset.id; e.removeAttribute('target'); });
    // -- fin --
    setTimeout(() => {
      if (document.getElementById('loading'))
      {
        document.getElementById('loading').remove();
        document.body.style.setProperty('--load-color', null);
      }
    }, 100);

    setNavActuelle('nav_' + Params.startSection);
    
    if (Params.startSection == 'accueil')
    {
      history.replaceState({onav: 'nav_accueil'}, '', '/');
      document.title = getTitrePage(history.state.onav.replace('nav_', ''));
      document.documentElement.style.overflowY = 'auto';
    }
    else
    {
      Promise.resolve()
      .then(() => naviguer(event, document.getElementById(getNavActuelle()), true))
      .then(() => {
        const elProjet = document.getElementById('projet');
        document.title = getTitrePage(history.state.onav.replace('nav_', ''));
        if (Params.startProjet && !elProjet.classList.contains('on'))
        {
          const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
          entrees.forEach(e => {
            if (e.dataset.id == Params.startProjet)
              simulateClick(e);
          });
        }
      });
    }
  });
});