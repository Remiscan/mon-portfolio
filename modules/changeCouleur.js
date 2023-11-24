import { Params } from 'Params';



let couleurEnCours = false;



/////////////////////////////////////////////////////
// Gère le changement de couleur de la section du bas
//   event : permet de récupérer les coordonnées du clic
//   element : l'élément sur lequel je considère avoir cliqué - utilisé par défaut comme source du Fond, sauf si elementExpand est spécifié
//   call : fonction(event, element) à appeler après changement de couleur
//   color : couleur du Fond, récupérée automatiquement sur les éléments de nav - sinon, teinte aléatoire si non spécifiée
//   elementExpand : l'élément utilisé comme source du Fond, si il est différent de element
export function changeCouleur(event, element = false, color = false, elementExpand = false) {
  const Fond = document.getElementById('couleur');
  return new Promise((resolve, reject) => {
    let couleur;
    if (element !== false) {
      couleur = element.style.getPropertyValue('--article-color') || getComputedStyle(element).getPropertyValue('--article-color');
    } else {
      if (color) couleur = color;
      else {
        const r = Math.floor((Math.random() * 360));
        couleur = 'hsl(' + r + ', 40%, 30%)';
      }
    }

    couleurEnCours = true;
    const posX = event.clientX;
    const posY = event.clientY;
    let scaleX = 0;
    let scaleY = 0;
    let angle = 0;
    let elPos = { left: posX, top: posY, right: posX + 1, bottom: posY + 1 };
    let keyframesColoration;

    let isAccueil = false;

    if (element) {
      let el = element;
      if (element.id === 'nav_accueil') isAccueil = true;
      if (elementExpand) el = elementExpand;

      // J'essaye de placer le Fond avec la même taille et position que l'élément sur lequel on a cliqué
      const elRect = el.getBoundingClientRect();
      const rotation = el.style.transform.match(/rotate\((.+)\)/);
      angle = (rotation != null) ? rotation[1] : 0;

      if (elRect.left > 0 || elRect.top > 0 || isAccueil) {
        elPos = elRect;
      }

      const elWidth = elPos.right - elPos.left;
      const elHeight = elPos.bottom - elPos.top;
      scaleX = isAccueil ? 1 : elWidth / Params.owidth;
      scaleY = isAccueil ? 0 : elHeight / Params.oheight;
    }

    Fond.style.backgroundColor = couleur;

    /* Méthode clip-path
     * non-utilisée pour des raisons de performances
    const polygon = elPos.left + 'px ' + elPos.top + 'px, ' + elPos.right + 'px ' +  elPos.top + 'px, ' +
                    elPos.right + 'px ' + elPos.bottom + 'px, ' + elPos.left + 'px ' + elPos.bottom + 'px';
    const polygonfin = '0 0, 100% 0, 100% 100%, 0 100%';

    keyframesColoration = [
      { clipPath: 'polygon(' + polygon + ')' },
      { clipPath: 'polygon(' + polygonfin + ')' }
    ];
     */

    /* Méthode transform: translate + scale
     * plus performante que clip-path */
    keyframesColoration = [
      { transform: 'translate3D(' + elPos.left + 'px, ' + elPos.top + 'px, 0) scale(' + scaleX + ', ' + scaleY + ') rotate(' + angle + ')' },
      { transform: 'translate3D(0, 0, 0) scale(1, 1) rotate(0)'}
    ];

    const isMotionReduced = Params.isMotionReduced();
    const coloration = Fond.animate(
      keyframesColoration, {
        easing: 'cubic-bezier(0.2, 0.45, 0.3, 1)',
        duration: isMotionReduced ? 0 : 400,
        fill: 'forwards'
    });

    if (isAccueil) changeThemeColor(couleur);

    coloration.addEventListener('finish', () => {
      if (!isAccueil) changeThemeColor(couleur);
      document.body.style.setProperty('--article-color', couleur);
      couleurEnCours = false;
      return resolve();
    });
  });
}



/////////////////////////////////////////////////////////////////////////////////////////////////
// Change la couleur du thème via la balise meta theme-color, à partir de 'couleur' au format hsl
export function changeThemeColor(couleur) {
  const metaThemeColor = document.querySelector("meta[name=theme-color]");
  const metaCouleur = couleur.split(',');
  metaCouleur[2] = Math.round(Number(metaCouleur[2].replace('%)', '')) * 0.72) + '%)';
  metaThemeColor.setAttribute("content", metaCouleur[0] + ',' + metaCouleur[1] + ',' + metaCouleur[2]);
}