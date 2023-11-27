import { Params, recalcOnResize } from 'Params';
import 'mediaProjet';
import { getNavActuelle, naviguer, setNavActuelle } from 'navigation';
import { closeProjet, initProjets } from 'projets';
import 'remiscan-logo';



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', async event => {
  const elProjet = document.getElementById('projet');
  const onav = event.state.onav;
  if (onav == 'projet') {
    if (elProjet.style.display != 'none' && elProjet.style.display) {
      await closeProjet();
    }

    const entrees = Array.from(document.getElementsByClassName('projet-conteneur'));
    entrees.forEach(e => {
      if (e.dataset.id == event.state.oprojet_id) {
        return e.click();
      }
    });
  } else {
    if (document.getElementById('projet').classList.contains('on')) {
      await closeProjet();
    }
    
    document.getElementById(onav)?.click();
  }
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
document.addEventListener('DOMContentLoaded', async event => {
  if (Params.startArticle == 'accueil') {
    history.replaceState({onav: 'nav_accueil'}, '', `/${location.search}`);
  } else {
    history.replaceState({ onav: `nav_${Params.startArticle}` }, '', `/${Params.startArticle}${location.search}`);
  }

  recalcOnResize();

  // Adapte les liens du portfolio (par défaut, ils mènent directement aux projets si JavaScript est désactivé)
  Array.from(document.querySelectorAll('a.projet-conteneur')).forEach(e => {
    if (e.id === 'projet-preview-more') return;
    e.href = '/projet/' + e.dataset.id;
    e.removeAttribute('target');
  });
  // -- fin --

  initProjets();

  // Supprime l'écran de chargement
  setTimeout(() => {
    if (document.getElementById('loading')) {
      document.getElementById('loading').remove();
      document.body.style.setProperty('--load-color', null);
    }
  }, 100);

  setNavActuelle('nav_' + Params.startArticle);
  
  if (Params.startArticle == 'accueil') {
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