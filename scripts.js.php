// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Params } from './modules/Params.js.php';
import Navigation from './modules/navigation.js.php';
import { Traduction } from './modules/traduction.js.php';
import './modules/theme-selector.js.php';
import Theme from './modules/theme.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



////////////////////////////////
// Gère les changements de thème
window.addEventListener('themechange', event => {
  const theme = event.detail.theme;
  Theme.set(theme);
});



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {
  const section = event.state.section;
  Navigation.go(section, false);
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
document.addEventListener('DOMContentLoaded', async event => {
  const section = document.body.dataset.section;
  const url = (section == 'accueil') ? '' : section;
  history.replaceState({ section }, '', `/${url}`);

  Navigation.init();
  await Traduction.initLanguageButtons();
  await Traduction.traduire();
  
  return;
});