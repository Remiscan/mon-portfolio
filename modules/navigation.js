import { Params, wait } from 'Params';
import { anim_competences } from 'animations';
import { changeCouleur } from 'changeCouleur';
import { loadMaPhoto, loadProjetImages, placeholderNoMore } from 'loadImages';
import { closeProjet, openProjet } from 'projets';
import { getTitrePage } from 'traduction';



export const navs = ['nav_accueil', 'nav_bio', 'nav_portfolio'];

let navEnCours = false;
let lastNav;

// Empêche la restoration de la position de scroll à l'event popstate
if ('scrollRestoration' in history) history.scrollRestoration = 'manual';



////////////////////////////
// Navigation entre articles
//export function* naviguer(event, nav, start = false, historique = true) {
export function* naviguer(pathname) {
  let needAnimations = false;
  let currentScroll = window.scrollY;

  // On détermine vers quelle section et/ou projet on navigue
  let section = '';
  let projet = '';
  const parts = pathname.split('/');
  switch (parts[1]) {
    case 'projet':
      projet = parts[2] ?? '';
    default:
      section = parts[1] ?? '';
  }

  // Si on navigue vers un projet, d'abord naviguer vers la liste des projets avant de l'ouvrir
  if (projet) {
    yield naviguer('/portfolio');
    yield openProjet(projet);
    return;
  }
  // Sinon, si un projet est ouvert, on le ferme avant de poursuivre la navigation
  else if (document.body.getAttribute('data-projet-actuel') !== '') {
    yield closeProjet();
  }
  
  const ancienneSection = document.body.getAttribute('data-section-actuelle');
  const article = document.querySelector(`#${section || 'accueil'}`);

  // Ne rien faire si :
  // - on demande à aller sur l'article déjà ouvert alors qu'aucune navigation n'est en cours (!navEnCours && nav_actuelle == nav.id && !start)
  // - on demande à aller sur l'article vers laquelle la navigation déjà en cours est en train d'aller (navEnCours && lastNav == nav)
  // - on demande à aller sur un article qui n'existe pas
  if (!navEnCours && ancienneSection === section) return;
  else if (navEnCours && lastNav === section)       return;
  else if (navs.indexOf(`nav_${section || 'accueil'}`) === -1) throw `Navigation demandée vers un article inexistant: ${section}`;

  lastNav = section;
  navEnCours = true;

  // On fixe la hauteur de la page pour éviter un janky scroll pendant la navigation
  const main = document.querySelector('main');
  main.style.height = getComputedStyle(main).height;

  // On cache le footer pendant la navigation
  const footer = document.querySelector('footer');
  footer.classList.add('off');

  // On désactive le scroll pendant la navigation
  document.documentElement.style.overflowY = 'hidden';
  
  // On masque l'ancienne section et affiche la nouvelle
  document.body.classList.remove('start');
  document.body.setAttribute('data-section-actuelle', section);

  // On met à jour le titre de la page
  document.title = getTitrePage(section);

  // Cette promesse sera résolue quand la nouvelle section aura terminé son animation d'apparition
  // (utilisé plus tard pour attendre avant d'appliquer les side effects de la navigation)
  const articleAppeared = new Promise(resolve => article ? article.addEventListener('animationend', resolve, { once: true }) : Promise.resolve());

  // 🔽🔽🔽 ANIMATIONS PAGE D'ACCUEIL <==> ARTICLE 🔽🔽🔽

  let k0, k1; // indices des keyframes, utilisés pour inverser l'animation si besoin
  let animationSign = ''; // + ou -, utilisé pour inverser la direction d'animation si besoin
  let animationOppositeSign = ''; // + ou -, utilisé pour inverser la direction d'animation si besoin

  // Si on va de la page d'accueil ==vers==> un article
  if (ancienneSection === '' && section !== '') {
    needAnimations = true;
    k0 = 0; k1 = 1;
    animationSign = '+';
    animationOppositeSign = '-';
  }
  // Si on va d'un article ==vers==> la page d'accueil
  else if (ancienneSection !== '' && section === '') {
    needAnimations = true;
    k0 = 1; k1 = 0;
    animationSign = '-';
    animationOppositeSign = '+';

    // On scrolle tout en haut de la page pour éviter un janky scroll pendant l'animation
    window.scrollTo(0, 0);
    currentScroll = 0;
  }

  if (needAnimations) {
    // Paramètres des animations
    const isMotionReduced = Params.isMotionReduced();
    const options = {
      easing: Params.easingStandard,
      duration: isMotionReduced ? 0 : 300,
      fill: 'backwards'
    };

    const animations = [];

    { // Déplacement du header
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${Params.decalageHeader}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.querySelector('header');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    { // Compression apparente du fond du header
      const headerBgMove = Params.owidth > Params.breakpointMobile ? Params.decalageHeader : 0;
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${headerBgMove}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.querySelector('header .background');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    { // Déplacement de mon nom
      const keyframes = [
        { transform: `translate3D(0, ${animationOppositeSign}${Params.decalageIntro}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.getElementById('intro');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    { // Déplacement des liens de nav
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${Params.decalageNav}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.querySelector('nav');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    // Horizontalisation / verticalisation des liens de nav sur mobile
    const navs = document.querySelectorAll('nav > a');
    navs.forEach((nav, k) => {
      const keyframes = [
        { transform: `translate3D(${Params.decalageNavLinks[k][0]}px, ${Params.decalageNavLinks[k][1]}, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const anim = nav.animate([keyframes[k0], keyframes[k1]], options);
      animations.push(anim);
    });
  }

  // 🔼🔼🔼 ----------- Fin des animations ----------- 🔼🔼🔼

  // On lance l'animation de changement de couleur du fond de page
  const navLink = document.querySelector(`#nav_${section || 'accueil'}`);
  yield changeCouleur(navLink);

  // On annule les réglages temporaires dont on avait besoin pendant la navigation
  main.style.height = 'auto';
  footer.classList.remove('off');
  document.documentElement.style.overflowY = 'auto';
  window.scrollTo(0, currentScroll);

  navEnCours = false;

  // On applique les side effects post-navigation (par exemple des animations propres à la section)
  // une fois l'animation d'apparition de la section terminée.
  yield Promise.race([articleAppeared, wait(250)]);
  navigationSideEffects(section);

  return;
}

export let lastNavCheck = new Object();

export function updateLastNavCheck() {
  lastNavCheck = new Object();
}

export class CanceledNavigationWarning extends Error {
  constructor(...args) {
    super('Navigation canceled ; a more recent locationchange happened', ...args);
    this.name = 'CanceledNavigationWarning';
  }
}

function cancelableNavigation(generator) {
  return async function (...args) {
    const localCheck = lastNavCheck;
    const iterator = generator(...args);
    let lastValue;
    for (;;) {
      const next = iterator.next(lastValue);
      if (next.done)
        return next.value;
      lastValue = await next.value;
      if (localCheck !== lastNavCheck)
        throw new CanceledNavigationWarning();
    }
  };
}

naviguer = cancelableNavigation(naviguer);



///////////////////////////////////////////////////////////////////////
// Exécute les animations et autres effets à l'apparition d'une section
export function navigationSideEffects(section) {
  switch (section) {
    case 'bio':
      anim_competences();
      loadMaPhoto();
      document.getElementById('photosecret').classList.remove('nope');
      break;

    case 'portfolio':
      loadProjetImages();
      break;
  }
  
  if (section != 'portfolio' && document.getElementById('portfolio').querySelector('.actual-image') != null) {
    const listeProjets = Array.from(document.getElementsByClassName('projet-actual-image'));
    placeholderNoMore(false, listeProjets);
  }

  if (section != 'bio') {
    anim_competences(false);
    if (document.getElementById('bio').querySelector('.actual-image') != null) {
      const maPhoto = document.getElementById('photo');
      placeholderNoMore(false, [maPhoto]);
    }
  }

  document.documentElement.style.overflowY = 'auto'; // ici pour que ce soit exécuté au lancement
}



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