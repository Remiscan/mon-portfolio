import { Params, recalcOnResize } from 'Params';
import 'mediaProjet';
import { getNavActuelle, naviguer, setNavActuelle } from 'navigation';
import { closeProjet, initProjets } from 'projets';



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {
  const elProjet = document.getElementById('projet');
  const onav = event.state.onav;
  if (onav == 'projet') {
    if (elProjet.style.display != 'none' && elProjet.style.display) {
      closeProjet();
    }

    const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
    entrees.forEach(e => {
      if (e.dataset.id == event.state.oprojet_id) {
        return e.click();
      }
    });
  } else {
    if (document.getElementById('projet').classList.contains('on')) {
      closeProjet();
    }
    
    document.getElementById(onav)?.click();
  }
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
document.addEventListener('DOMContentLoaded', async event => {
  if (Params.startSection == 'accueil') {
    history.replaceState({onav: 'nav_accueil'}, '', '/');
  } else {
    history.replaceState({ onav: `nav_${Params.startSection}` }, '', `/${Params.startSection}`);
  }

  recalcOnResize();

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
  
  if (Params.startSection == 'accueil') {
    document.documentElement.style.overflowY = 'auto';
    return;
  }

  await naviguer(event, document.getElementById(getNavActuelle()), true);

  const elProjet = document.getElementById('projet');
  if (Params.startProjet && !elProjet.classList.contains('on')) {
    const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
    entrees.forEach(e => {
      if (e.dataset.id == Params.startProjet) e.click();
    });
  }
  return;
});