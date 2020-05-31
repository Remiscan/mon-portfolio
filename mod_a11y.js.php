// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { simulateClick } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 1).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



///////////////////////////////////
// Spécifie quel objet est en focus
let focused = false;
function iFocus(element)
{
  if (focused != element)
    focused = element;
}



//////////////////////////////////////////////////////////////////////////////////////////////
// Quand un objet est en focus, on surveille les appuis sur entrée et on simule un clic dessus
export const focusable = Array.from(document.getElementsByClassName('focusable'));

focusable.forEach(e => {
  // Quand le focus est placé sur un élément, on le met dans la variable focused
  e.addEventListener('focus', () => iFocus(e));

  // Quand on appuie sur entrée alors qu'un élément est en focus, on simule un clic dessus
  // sauf si c'est un a avec attribut href.
  e.addEventListener('keydown', event => {
    if (focused === e && e.tagName.toLowerCase() != 'button')
    {
      const key = event.which || event.keyCode;
      if (key === 13 || key === 32)
      {
        if (!e.getAttribute('href'))
          simulateClick(e, 1, 1);
      }
    }
  });

  // On perd le focus après avoir cliqué sur l'élément, sinon il garde son style :focus
  e.addEventListener('mouseup', () => e.blur());
});