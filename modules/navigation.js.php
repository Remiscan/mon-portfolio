// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { cancelableAsync } from '../../_common/js/cancelable-async.js';
import { wait } from './Params.js.php';
import { Params } from './Params.js.php';
import { Traduction, getTitrePage } from './traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const sections = ['accueil', 'bio', 'projets', 'articles', 'contact'];



const Navigation = {
  init() {
    // Liens de navigation
    for (const lien of [...document.querySelectorAll('a[data-section]')]) {
      lien.addEventListener('click', event => {
        event.preventDefault();
        Navigation.go(lien.dataset.section);
      });
    }
  },

  *go(section, history = true) {
    if (document.body.dataset.section == section) return;
    const styles = getComputedStyle(document.documentElement);
    const oldSection = document.body.dataset.section;
    const couleur = styles.getPropertyValue(`--${oldSection}-bg-color`);
    const nextLinkColor = styles.getPropertyValue(`--${section}-link-color`)
                        ||`hsl(${Number(styles.getPropertyValue(`--${section}-primary-hue`)) + 180}, 50%, 80%)`;

    // Active le bon lien
    for (const lien of [...document.querySelectorAll(`a[data-section]`)]) {
      if (lien.dataset.section == section) {
        lien.classList.add('on');
        lien.tabIndex = -1;
        lien.style.setProperty('--next-link-color', nextLinkColor);
      } else {
        lien.classList.remove('on');
        lien.tabIndex = 0;
        lien.style.setProperty('--next-link-color', '');
      }
    }

    // Détecte le sens de l'animation
    const oldSectionIndex = sections.findIndex(e => e == document.body.dataset.section);
    const newSectionIndex = sections.findIndex(e => e == section);
    const reversed = oldSectionIndex > newSectionIndex;

    // Détecte la complexité de l'animation
    const distance = '1ch';
    const moveTo = Params.reducedMotion() ? '0' : reversed ? distance : `-${distance}`;
    const moveFrom = Params.reducedMotion() ? '0' : reversed ? `-${distance}` : distance;
    
    // 1e animation : disparition de section
    const main = document.querySelector('main');
    const anim1 = main.animate([
      { transform: 'translate3d(0, 0, 0)', opacity: '1' },
      { transform: `translate3D(${moveTo}, 0, 0)`, opacity: '0' }
    ], {
      duration: 100,
      easing: Params.easingAccelerate,
      fill: 'both'
    });
    yield wait(anim1);

    // On applique le style, le titre et l'url de la nouvelle section
    document.title = getTitrePage(section);
    const url = (section == 'accueil') ? '' : section;
    if (history) window.history.pushState({ section }, '', `/${url}`);
    document.body.dataset.section = section;

    // 2e animation : transition colorée
    const anim2 = yield Navigation.changeCouleur(couleur, reversed);
    
    // 3e animation : apparition de section
    const anim3 = main.animate([
      { transform: `translate3d(${moveFrom}, 0, 0)`, opacity: '0' },
      { transform: 'translate3D(0, 0, 0)', opacity: '1' }
    ], {
      duration: 100,
      easing: Params.easingDecelerate,
      fill: 'both'
    });
    yield wait(anim3);

    // Animations terminées
    for (const a of [anim1, anim2, anim3]) { a.cancel(); }
  },

  async changeCouleur(couleur, reversed = false) {
    // Détecte la complexité de l'animation
    let keyframes;
    if (Params.reducedMotion()) {
      keyframes = [
        { opacity: '1', transform: 'scaleX(1)' },
        { opacity: '0', transform: 'scaleX(1)' }
      ];
    } else {
      keyframes = [
        { transform: 'scaleX(1)' },
        { transform: 'scaleX(0)' }
      ]
    }

    const background = document.getElementById('couleur');
    if (!reversed) background.style.setProperty('transform-origin', 'top left');
    else           background.style.setProperty('transform-origin', 'top right');
    background.style.setProperty('background-color', couleur);
    const animation = background.animate(keyframes, {
      duration: 250,
      delay: 10,
      endDelay: 10,
      easing: Params.easingStandard,
      fill: 'both'
    });
    await wait(animation);
    return animation;
  }
}

Navigation.go = cancelableAsync(Navigation.go);
export default Navigation;