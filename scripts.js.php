// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

//import { Traduction } from './modules/mod_traduction.js.php';
import { Params } from './modules/Params.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {

}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
document.addEventListener('DOMContentLoaded', async event => {
  if (Params.startSection == 'accueil')
    history.replaceState({ section: 'accueil' }, '', '/');

  /*await Traduction.traduire();
  await Traduction.initLanguageButtons();*/
  
  return;
});