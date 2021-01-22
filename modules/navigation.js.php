// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { wait } from './Params.js.php';
import { Params } from './Params.js.php';
import { Traduction, getTitrePage } from './traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const sections = ['accueil', 'bio', 'projets', 'articles', 'contact'];



const Navigation = {
  init: () => {
    // Liens de navigation
    for (const lien of [...document.querySelectorAll('a[data-section]')]) {
      lien.addEventListener('click', event => {
        event.preventDefault();
        Navigation.go(lien.dataset.section);
      });
    }
  },

  go: async (section, history = true) => {
    if (document.body.dataset.section == section) return;

    const oldSectionIndex = sections.findIndex(e => e == document.body.dataset.section);
    const newSectionIndex = sections.findIndex(e => e == section);
    const reversed = oldSectionIndex > newSectionIndex;
    
    const couleur = getComputedStyle(document.documentElement).getPropertyValue(`--${section}-bg-color`);
    const main = document.querySelector('main');

    const anim1 = main.animate([
      { transform: 'translate3d(0, 0, 0)', opacity: '1' },
      { transform: `translate3D(${reversed ? 2 : -2}rem, 0, 0)`, opacity: '0' }
    ], {
      duration: 200,
      easing: Params.easingAccelerate,
      fill: 'both'
    });
    await wait(anim1);

    const anim2 = await Navigation.changeCouleur(couleur, reversed);
    document.body.dataset.section = section;
    document.title = getTitrePage(section);
    const url = (section == 'accueil') ? '' : section;
    if (history) window.history.pushState({ section }, '', `/${url}`);

    const anim3 = main.animate([
      { transform: `translate3d(${reversed ? -2 : 2}rem, 0, 0)`, opacity: '0' },
      { transform: 'translate3D(0, 0, 0)', opacity: '1' }
    ], {
      duration: 200,
      delay: 10,
      easing: Params.easingDecelerate,
      fill: 'both'
    });
    await wait(anim3);

    for (const a of [anim1, anim2, anim3]) { a.cancel(); }
  },

  changeCouleur: async (couleur, reversed = false) => {
    const background = document.getElementById('couleur');
    if (reversed) background.style.setProperty('transform-origin', 'top left');
    else          background.style.setProperty('transform-origin', 'top right');
    background.style.setProperty('background-color', couleur);
    const animation = background.animate([
      { transform: 'scaleX(0)' },
      { transform: 'scaleX(1)' }
    ], {
      duration: 300,
      delay: 10,
      easing: Params.easingStandard,
      fill: 'both'
    });
    await wait(animation);
    return animation;
  }
}

export default Navigation;