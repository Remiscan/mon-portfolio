// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { wait } from './Params.js.php';
import { Params } from './Params.js.php';
import { Traduction } from './traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const Navigation = {
  init: () => {
    // Liens de navigation
    for (const lien of [...document.querySelectorAll('a[data-section]')]) {
      lien.addEventListener('click', event => {
        event.preventDefault();
        Navigation.go(lien.dataset.section);
      });
    }

    // Liens de changement de langue
    for (const lien of [...document.querySelectorAll('a[lang]')]) {
      lien.addEventListener('click', event => {
        event.preventDefault();
        Traduction.switchLanguage(lien.lang);
      });
    }
  },

  go: async section => {
    if (document.body.dataset.section == section) return;
    
    const couleur = getComputedStyle(document.documentElement).getPropertyValue(`--${section}-bg-color`);
    const main = document.querySelector('main');

    const anim1 = main.animate([
      { transform: 'translate3d(0, 0, 0)', opacity: '1' },
      { transform: 'translate3D(-2rem, 0, 0)', opacity: '0' }
    ], {
      duration: 200,
      easing: Params.easingAccelerate,
      fill: 'both'
    });
    await wait(anim1);

    const anim2 = await Navigation.changeCouleur(couleur);
    document.body.dataset.section = section;

    const anim3 = main.animate([
      { transform: 'translate3d(2rem, 0, 0)', opacity: '0' },
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

  changeCouleur: async couleur => {
    const background = document.getElementById('couleur');
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