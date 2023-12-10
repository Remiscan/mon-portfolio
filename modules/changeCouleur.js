import { Params, wait } from 'Params';



/**
 * Anime le changement de couleur du fond de la page.
 * @param {HTMLElement} element - L'élément à partir duquel la couleur de propage.
 */
let couleurEnCours = false;
export async function changeCouleur(element) {
  let couleur, themeCouleur;
  let posX = 0;
  let posY = 0;
  let scaleX = 0;
  let scaleY = 0;
  let angle = 0;
  let elPos = { left: posX, top: posY, right: posX + 1, bottom: posY + 1 };
  const isPC = Params.owidth > Params.breakpointMobile;
  
  let isAccueil = element && element.id === 'nav_accueil';
  couleurEnCours = true;

  if (element) {
    couleur = element.style.getPropertyValue('--article-color') || getComputedStyle(element).getPropertyValue('--article-color');
    themeCouleur = element.style.getPropertyValue('--theme-color') || getComputedStyle(element).getPropertyValue('--theme-color');

    if (isAccueil) {
      isAccueil = true;
      scaleX = 1;
      scaleY = isPC ? document.querySelector('header > .background').offsetHeight / Params.oheight : 0;
    } else {
      // J'essaye de placer le Fond avec la même taille et position que l'élément sur lequel on a cliqué
      const elRect = element.getBoundingClientRect();
      const rotation = element.style.transform.match(/rotate\((.+)\)/);
      angle = (rotation != null) ? rotation[1] : 0;

      if (elRect.left > 0 || elRect.top > 0 || isAccueil) {
        elPos = elRect;
      }

      const elWidth = elPos.right - elPos.left;
      const elHeight = elPos.bottom - elPos.top;
      scaleX = elWidth / Params.owidth;
      scaleY = elHeight / Params.oheight;
    }
  }

  // On choisit une couleur aléatoire si aucun élément n'a été passé en argument
  couleur = couleur || `hsl(${Math.floor((Math.random() * 360))}, 40%, 30%)`;
  themeCouleur = themeCouleur || couleur;

  /* Méthode transform: translate + scale
    * plus performante que clip-path */
  const keyframesColoration = [
    { transform: `translate3D(${elPos.left}px, ${elPos.top}px, 0) scale(${scaleX}, ${scaleY}) rotate(${angle})` },
    { transform: 'translate3D(0px, 0px, 0) scale(1, 1) rotate(0)'}
  ];

  const isMotionReduced = Params.isMotionReduced();
  const isAccueilPCAnimation = isAccueil && isPC;

  const Fond = document.getElementById('couleur');
  Fond.style.backgroundColor = couleur;
  const coloration = Fond.animate(keyframesColoration, {
    easing: isAccueilPCAnimation ? Params.easingStandard : 'cubic-bezier(0.2, 0.45, 0.3, 1)',
    duration: isMotionReduced ? 0 : isAccueil ? 300 : 400,
    fill: 'both'
  });

  if (isAccueil) changeThemeColor(themeCouleur);
  await wait(coloration);
  if (!isAccueil) changeThemeColor(themeCouleur);
  document.body.style.setProperty('--article-color', couleur);
  document.body.style.setProperty('--theme-color', themeCouleur);
  couleurEnCours = false;
  return;
}



/**
 * Change la couleur du thème via la balise meta theme-color.
 * @param {string} couleur - La couleur à appliquer.
 */
export function changeThemeColor(couleur) {
  const metaThemeColor = document.querySelector("meta[name=theme-color]");
  metaThemeColor.setAttribute("content", couleur);
}