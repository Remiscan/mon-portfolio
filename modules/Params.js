/////////////////////////////////////////
// Constantes et paramètres de la fenêtre
export const Params = {
  breakpointMobile: 620,
  breakpointWide: 1200,

  easingStandard: 'cubic-bezier(0.4, 0.0, 0.2, 1)',
  easingDecelerate: 'cubic-bezier(0.0, 0.0, 0.2, 1)',
  easingAccelerate: 'cubic-bezier(0.4, 0.0, 1, 1)',

  owidth: false,
  oheight: false,
  fontsize: false,
  hDiff: 0,
  oblique: 2,

  tailleHeader: null,
  decalageIntro: null,
  decalageNav: null,
  decalageNav1: null,
  decalageNav2: null,
  diffScaleNavs: null,

  startArticle: null,
  startProjet: null,
  articleOuvert: null,

  isMotionReduced: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches
}



///////////////////////////////////////////////////////////
// Calcul des paramètres de la fenêtre au redimensionnement
let resizing = false;

export function recalcOnResize() {
  const largeurPage = document.getElementById('largeurpage');
  const hauteurPage = document.getElementById('hauteurpage');
  const defontsize = document.getElementById('defontsize');
  
  // fontsize = 1rem
  const candidFontsize = parseFloat(window.getComputedStyle(defontsize).width) / 1000;
  if (Params.fontsize != candidFontsize)
    Params.fontsize = candidFontsize;

  // owidth = 100vw = largeur totale de la fenêtre, indépendamment de l'affichage ou non des barres de défilement
  const candidWidth = parseFloat(window.getComputedStyle(largeurPage).width);
  if (Params.owidth != candidWidth)
  {
    Params.owidth = candidWidth;
    if (Params.owidth > Params.breakpointMobile)
      Params.oblique = 3;
    else
      Params.oblique = 2;
  }

  // oheight = 100vh = hauteur totale de la fenêtre, indépendamment de l'affichage ou non de la barre d'URL (au moins pour Chrome)
  //   diffère de window.innerHeight qui dépend de la barre d'URL (et donc change tout le temps => problématique)
  const candidHeight = parseFloat(window.getComputedStyle(hauteurPage).height);
  if (Params.oheight != candidHeight)
  {
    Params.oheight = candidHeight;
  }

  // différence entre 100vh et la hauteur actuelle de la fenêtre, selon les barres affichées
  Params.hDiff = Math.max(0, Math.round(Params.oheight - window.innerHeight)); 

  document.documentElement.style.setProperty('--h-diff', Params.hDiff);
  document.documentElement.style.setProperty('--rem', Params.fontsize);
  document.documentElement.style.setProperty('--vw', Params.owidth / 100);

  // On recalcule à chaque fois ce qui dépend à la fois de la largeur et de la hauteur
  calcul_pos_nav();
  calcul_taille_header();
}



////////////////////////////////////////////////////////////////////////////////////
// On calcule le décalage entre les deux positions possibles des liens de navigation
function calcul_pos_nav() {
  const nav1 = document.getElementById('nav_bio');
  const nav2 = document.getElementById('nav_portfolio');

  const articleOuvert = document.body.getAttribute('data-section-actuelle') !== '';

  if (Params.owidth > Params.breakpointMobile) {
    Params.decalageNav1 = [0, 0];
    Params.decalageNav2 = [0, 0];
  } else {
    let preDecalage1 = nav1.style.getPropertyValue('--decalage-nav').replace('px', '');
    if (isNaN(preDecalage1) || articleOuvert)
      preDecalage1 = 0;
    const nav1Pos = nav1.getBoundingClientRect();
    Params.decalageNav1 = [
      Math.ceil(Params.owidth / 2 - (nav1Pos.left + nav1Pos.right - 2 * preDecalage1) / 2),
      'calc(-50% - .9rem)'
    ];

    let preDecalage2 = nav2.style.getPropertyValue('--decalage-nav').replace('px', '');
    if (isNaN(preDecalage2) || articleOuvert)
      preDecalage2 = 0;
    const nav2Pos = nav2.getBoundingClientRect();
    Params.decalageNav2 = [
      Math.floor(Params.owidth / 2 - (nav2Pos.left + nav2Pos.right - 2 * preDecalage2) / 2),
      'calc(50% + .9rem)'
    ];
  }

  Params.diffScaleNavs = nav1.offsetWidth / nav2.offsetWidth;

  nav1.style.setProperty('--decalage-nav', Params.decalageNav1[0] + 'px');
  nav2.style.setProperty('--decalage-nav',  Params.decalageNav2[0] + 'px');
  nav2.style.setProperty('--diff-scale-navs', Params.diffScaleNavs);
}



//////////////////////////////////////////////////////////////////////
// On calcule le décalage entre les deux positions possibles du header
// - tailleHeader = distance dont le header bouge vers le haut
// - decalageIntro = distance dont mon nom + job bougent vers le bas
// - decalageNav = distance dont les boutons de navigation bougent
function calcul_taille_header()
{
  const fontsize = Params.fontsize;

  if (Params.owidth > Params.breakpointMobile)
  {
    let oheight = Params.oheight;
    const mod = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--mod'));
    const hauteurHeaderText = 1.2 * mod * mod * (mod * mod * fontsize + fontsize);
    const hauteurHeaderSocials = 1.7 * fontsize + 2 * .25 * fontsize + .5 * fontsize;
    const hauteurHeader = hauteurHeaderText + hauteurHeaderSocials + (2 * 2.4 + 1.2) * fontsize;
    Params.tailleHeader = Math.ceil(0.5 * oheight - 0.5 * hauteurHeader);
    Params.decalageIntro = 0;
    Params.decalageNav = 0;
  }
  else
  {
    let oheight = window.innerHeight; // on utilise '100%' au lieu de '100vh' pour tenir compte des barres des navigateurs mobiles
    Params.tailleHeader = Math.ceil(
      (oheight - 3 * fontsize) / 2 // taille de la partie supérieure du header
      - 3.4 * fontsize/*mesInfosHeight*/ - 5 * fontsize // on enlève la taille de mon nom + job + marge en-dessous avec liens de contact
      - 2.4 * fontsize // on ajoute une marge de sécurité au-dessus du nom
    );
    Params.decalageIntro = Math.ceil(oheight / 4 - 5.25 * fontsize);
    document.getElementById('intro').style.setProperty('--decalage', Params.decalageIntro + 'px');
    Params.decalageNav = Math.ceil(oheight / 4 - 2.75 * fontsize);
    document.querySelector('nav').style.setProperty('--decalage', Params.decalageNav + 'px');
  }

  document.body.style.setProperty('--decalage-header', Params.tailleHeader);
  document.body.style.setProperty('--decalage-intro', Params.decalageIntro);
  document.body.style.setProperty('--decalage-nav', Params.decalageNav);
}



//////////////////////////////////
// On détecte le redimensionnement
function callResize() {
  clearTimeout(resizing);
  resizing = setTimeout(recalcOnResize, 33);
}
window.addEventListener('resize', callResize);
window.addEventListener('orientationchange', callResize);



//////////////////////
// Promesse setTimeout
export function wait(time) { 
  if (time instanceof Animation) {
    if (time.playState === 'finished') return Promise.resolve();
    return new Promise(resolve => time.addEventListener('finish', resolve));
  } else if (typeof time === 'number') {
    return new Promise(resolve => setTimeout(resolve, time));
  } else {
    return Promise.resolve();
  }
}



///////////////////////////////////////////////////////////////////////
// Vérifie si un élément est complètement visible avec le scroll actuel
export function isVisible(e, fully = true) {
  const eCoord = e.getBoundingClientRect();
  const daddyScroll = {top: 0, bottom: window.innerHeight}; // on reste safe au cas où l'omnibar du navigateur est affichée sur mobile
  const eScroll = {top: eCoord.top, bottom: eCoord.bottom};
  if (fully)
    return ((daddyScroll.top < eScroll.top) && (daddyScroll.bottom > eScroll.bottom));
  else
    return ((daddyScroll.top <= eScroll.bottom) && (daddyScroll.bottom >= eScroll.top));
}