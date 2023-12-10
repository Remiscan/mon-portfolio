import { recalcOnResize } from 'Params';
import { navigationSideEffects } from 'navigation';
import 'remiscan-logo';



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

if (!document.startViewTransition) document.documentElement.classList.add('no-view-transitions');

recalcOnResize();
navigationSideEffects(sectionActuelle);
setTimeout(() => document.getElementById('loading')?.remove(), 150); // Supprime l'écran de chargement du DOM