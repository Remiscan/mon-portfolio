// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { wait } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 1).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



///////////////////////////////////
// Clip quand on passe sur la photo
function photoclip(event, bool = true)
{
  const photo = document.getElementById('photosecret');
  const photorect = photo.getBoundingClientRect();
  const tx = event.clientX - photorect.left;
  const ty = event.clientY - photorect.top;
  if (bool)
  {
    photo.style.opacity = 1;
    photo.style.setProperty('-webkit-clip-path', 'circle(25% at ' + tx + 'px '+ ty + 'px)');
    photo.style.setProperty('clip-path', 'circle(25% at ' + tx + 'px '+ ty + 'px)');
  }
  else
  {
    photo.animate([
      { clipPath: 'circle(25% at ' + tx + 'px '+ ty + 'px)', opacity: 1 },
      { clipPath: 'circle(0% at ' + tx + 'px '+ ty + 'px)', opacity: 1 }
    ], {
        easing: 'cubic-bezier(1, 0.6, 1, 0.6)',
        duration: 60
    });
    photo.style.setProperty('-webkit-clip-path', 'unset');
    photo.style.setProperty('clip-path', 'unset');
    photo.style.opacity = 0;
  }
}

document.getElementById('photosecret').addEventListener('mousemove', event => {
  photoclip(event);
});
document.getElementById('photosecret').addEventListener('mouseleave', event => {
  photoclip(event, false);
});



////////////////////////////////////
// Anime la timeline des compétences
export function anim_competences(t = true)
{
  if (t && !document.querySelector('.competence-conteneur').classList.contains('colored'))
  {
    Array.from(document.getElementsByClassName('competence-conteneur')).forEach(e => {
      const delai = parseFloat(e.style.getPropertyValue('--delai'));
      wait(1000 * delai).then(() => e.classList.add('colored'));
    });
  }
  else if (!t && document.querySelector('.competence-conteneur').classList.contains('colored'))
  {
    Array.from(document.getElementsByClassName('competence-conteneur')).forEach(e => {
      e.classList.remove('colored');
    });
  }
}