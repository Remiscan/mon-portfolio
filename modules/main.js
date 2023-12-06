import { recalcOnResize } from 'Params';
import { navigationSideEffects, naviguer, updateLastNavCheck } from 'navigation';
import 'remiscan-logo';



///////////////////////////////////////////////
// Déclenche une navigation au changement d'URL
const pushState = history.pushState;
const replaceState = history.replaceState;

history.pushState = function() {
  const val = pushState.apply(this, arguments);
  window.dispatchEvent(new Event('pushstate'));
  window.dispatchEvent(new CustomEvent('locationchange', { detail: { cause: 'pushstate' } }));
  return val;
};

history.replaceState = function() {
  const val = replaceState.apply(this, arguments);
  window.dispatchEvent(new Event('replacestate'));
  window.dispatchEvent(new CustomEvent('locationchange', { detail: { cause: 'replacestate' } }));
  return val;
};

window.addEventListener('popstate', () => {
  window.dispatchEvent(new CustomEvent('locationchange', { detail: { cause: 'popstate' } }));
});

window.addEventListener('locationchange', event => {
  if (!['pushstate', 'popstate'].includes(event.detail?.cause)) return;
  updateLastNavCheck();
  naviguer(location.pathname);
});

document.querySelectorAll('a[href^="/"]').forEach(a => a.addEventListener('click', event => {
  if (a.href && event.button === 0 && a.origin === document.location.origin) {
    event.preventDefault();
    history.pushState({}, '', `${a.pathname}${location.search}`);
  }
}));



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
const sectionActuelle = document.body.getAttribute('data-section-actuelle');
const projetActuel = document.body.getAttribute('data-projet-actuel');

if (projetActuel !== '') {
  history.replaceState({ onav: 'projet', oprojet_id: projetActuel }, '', `/projet/${projetActuel}${location.search}`);
} else if (sectionActuelle === '') {
  history.replaceState({ onav: 'nav_accueil' }, '', `/${location.search}`);
} else {
  history.replaceState({ onav: `nav_${sectionActuelle}` }, '', `/${sectionActuelle}${location.search}`);
}

recalcOnResize();
setTimeout(() => document.getElementById('loading')?.remove(), 100); // Supprime l'écran de chargement du DOM
navigationSideEffects(sectionActuelle);