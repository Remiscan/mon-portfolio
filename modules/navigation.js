import { Params } from 'Params';
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

  let section = '';
  let projet = '';
  const parts = pathname.split('/');
  switch (parts[1]) {
    case 'projet':
      projet = parts[2] ?? '';
    default:
      section = parts[1] ?? '';
  }

  if (projet) {
    yield naviguer('/portfolio');
    yield openProjet(projet);
    return;
  } else if (document.body.getAttribute('data-projet-actuel') !== '') {
    yield closeProjet();
  }
  
  const header = document.querySelector('header');
  const main = document.querySelector('main');
  const footer = document.querySelector('footer');
  const nav = document.querySelector(`#nav_${section || 'accueil'}`);
  const ancienneSection = document.body.getAttribute('data-section-actuelle');

  // ÉTAPE 1 : Navigation autorisée ?
  // Ne rien faire si :
    // - on demande à aller sur l'article déjà ouvert alors qu'aucune navigation n'est en cours (!navEnCours && nav_actuelle == nav.id && !start)
    // - on demande à aller sur l'article vers laquelle la navigation déjà en cours est en train d'aller (navEnCours && lastNav == nav)
  if (!navEnCours && ancienneSection === section)
    return;
  else if (navEnCours && lastNav === section)
    return;

  // ÉTAPE 2.1 : Animations accueil <==> article + couleur
  // et ÉTAPE 2.2 en parallèle : Masquer les éléments du viel article
  lastNav = section;
  navEnCours = true;

  // Si aucune condition de rejet n'est remplie, on continue :
  main.style.height = getComputedStyle(main).height;
  document.documentElement.style.overflowY = 'hidden';
  
  document.body.classList.remove('start');
  document.body.setAttribute('data-section-actuelle', section);
  footer.classList.add('off');

  document.title = getTitrePage(nav.id.replace('nav_', ''));

  // 2.1 : Préparation des animations de passage accueil <==> article
  const headerBgMove = Params.owidth > Params.breakpointMobile ? Params.tailleHeader : 0;

  let e0, e1;
  let animationSign = '';
  let animationOppositeSign = '';

  if (ancienneSection === '' && section !== '') {
    needAnimations = true;
    e0 = 0; e1 = 1;
    animationSign = '+';
    animationOppositeSign = '-';
    document.getElementById('nav_portfolio').style.setProperty('--diff-scale-navs', 0);
  } else if (ancienneSection !== '' && section === '') {
    needAnimations = true;
    e0 = 1; e1 = 0;
    animationSign = '-';
    animationOppositeSign = '+';
    document.getElementById('nav_portfolio').style.setProperty('--diff-scale-navs', Params.diffScaleNavs);
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

    // Déplacement du header
    {
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${Params.tailleHeader}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = header;
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    // Compression apparente du fond du header
    {
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${headerBgMove}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.querySelector('header .background');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    // Déplacement de mon nom
    {
      const keyframes = [
        { transform: `translate3D(0, ${animationOppositeSign}${Params.decalageIntro}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.getElementById('intro');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    // Déplacement des liens de nav
    {
      const keyframes = [
        { transform: `translate3D(0, ${animationSign}${Params.decalageNav}px, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.querySelector('nav');
      const anim = element.animate(keyframes, options);
      animations.push(anim);
    }

    // Horizontalisation / verticalisation des liens de nav sur mobile
    {
      const keyframes = [
        { transform: `translate3D(${Params.decalageNav1[0]}px, ${Params.decalageNav1[1]}, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.getElementById('nav_bio');
      const anim = element.animate([keyframes[e0], keyframes[e1]], options);
      animations.push(anim);
    } {
      const keyframes = [
        { transform: `translate3D(${Params.decalageNav2[0]}px, ${Params.decalageNav2[1]}, 0)` },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const element = document.getElementById('nav_portfolio');
      const anim = element.animate([keyframes[e0], keyframes[e1]], options);
      animations.push(anim);
    }
  }

  // Si on navigue vers un article qui existe
  if (navs.indexOf(nav.id) != -1) {
    if (nav.id == 'nav_bio')
      anim_competences(false);
    
    // Animation de la propagation de la couleur de article choisi
    yield changeCouleur(event, nav);
  }

  else throw 'Navigation demandée vers un article inexistant: ' + nav.id;
  
  const article_id = nav.id.replace('nav_', '');

  main.style.height = 'auto';
  footer.classList.remove('off');
  window.scrollTo(0, currentScroll);
  navEnCours = false;

  navigationSideEffects(article_id);
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
  if (section != 'bio' && document.getElementById('bio').querySelector('.actual-image') != null) {
    const maPhoto = document.getElementById('photo');
    placeholderNoMore(false, [maPhoto]);
  }

  document.documentElement.style.overflowY = 'auto';
}



/////////////////////////////////////////////////////////////////////
// Récupère le lien de navigation correspondant à la section actuelle
export function getNavLinkActuel() {
  const section = document.body.getAttribute('data-section-actuelle');
  return `nav_${section || 'accueil'}`;
}