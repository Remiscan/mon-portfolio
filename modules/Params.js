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

  decalageHeader: null,
  decalageIntro: null,
  decalageNav: null,
  decalageNavLinks: [],

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