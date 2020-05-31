// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Params } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 1).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



/////////////////////////////////////////////////////
// Gère le changement de couleur de la section du bas
let couleurEnCours = false;

//   event : permet de récupérer les coordonnées du clic
//   element : l'élément sur lequel je considère avoir cliqué - utilisé par défaut comme source du Fond, sauf si elementExpand est spécifié
//   call : fonction(event, element) à appeler après changement de couleur
//   color : couleur du Fond, récupérée automatiquement sur les éléments de nav - sinon, teinte aléatoire si non spécifiée
//   elementExpand : l'élément utilisé comme source du Fond, si il est différent de element
export function changeCouleur(event, element = false, color = false, elementExpand = false)
{
  const Fond = document.getElementById('couleur');
  return new Promise((resolve, reject) => {
    let couleur;
    if (element !== false)
      couleur = element.style.getPropertyValue('--article-color') || getComputedStyle(element).getPropertyValue('--article-color');
    else
    {
      if (color)
        couleur = color;
      else
      {
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

    if (element !== false)
    {
      let el = element;
      if (elementExpand)
        el = elementExpand;

      // J'essaye de placer le Fond avec la même taille et position que l'élément sur lequel on a cliqué
      elPos = el.getBoundingClientRect();
      const rotation = el.style.transform.match(/rotate\((.+)\)/);
      angle = (rotation != null) ? rotation[1] : 0;

      const elWidth = elPos.right - elPos.left;
      const elHeight = elPos.bottom - elPos.top;
      scaleX = elWidth / Params.owidth;
      scaleY = elHeight / Params.oheight;
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

    const coloration = Fond.animate(
      keyframesColoration, {
        easing: 'cubic-bezier(0.2, 0.45, 0.3, 1)',
        //duration: 350,
        duration: 400,
        fill: 'forwards'
    });

    coloration.addEventListener('finish', () => {
      changeThemeColor(couleur);
      document.body.style.setProperty('--article-color', couleur);
      couleurEnCours = false;
      return resolve();
    });
  });
}



/////////////////////////////////////////////////////////////////////////////////////////////////
// Change la couleur du thème via la balise meta theme-color, à partir de 'couleur' au format hsl
export function changeThemeColor(couleur)
{
  const metaThemeColor = document.querySelector("meta[name=theme-color]");
  const metaCouleur = couleur.split(',');
  metaCouleur[2] = Math.round(Number(metaCouleur[2].replace('%)', '')) * 0.72) + '%)';
  metaThemeColor.setAttribute("content", metaCouleur[0] + ',' + metaCouleur[1] + ',' + metaCouleur[2]);
}