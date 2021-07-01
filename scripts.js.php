// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Navigation from './modules/navigation.js.php';
import { Traduction } from './modules/traduction.js.php';
import Cookie from './modules/cookies.js.php';
import '/_common/components/theme-selector/theme-selector.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



////////////////////////////////
// Gère les changements de thème
window.addEventListener('themechange', event => {
  // Set meta theme-color here

  if (event.detail.theme != 'auto') Cookie.submit('theme', event.detail.theme);
  else                              Cookie.delete('theme');
});



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {
  const section = event.state.section;
  Navigation.go(section, false);
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
const section = document.body.dataset.section;
history.replaceState({ section }, '', Navigation.getUrl(section));

// Personnalisation du theme-selector
const themeSelectors = document.querySelectorAll('theme-selector');
for (const themeSelector of themeSelectors) {
  themeSelector.querySelector('.selector-title').classList.add('s5');
  themeSelector.querySelector('.selector-cookie-notice').classList.add('s8');
  const arrow = document.createElement('div');
  arrow.classList.add('selector-arrow');
  themeSelector.querySelector('.selector').appendChild(arrow);
  Traduction.traduire(themeSelector);
}

Navigation.init();