// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import DefTraduction from '/_common/js/traduction.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



class ExtTraduction extends DefTraduction {
  constructor() {
    const version = document.querySelector('link#strings').dataset.version || document.documentElement.dataset.version || 0;
    const path = `/mon-portfolio/strings--${version}.json`;
    super('mon-portfolio', path, 'fr');
  }

  async traduire(element = document) {
    await super.traduire(element);
    if (element == document)
      document.title = getTitrePage(history.state.section);
    return;
  }
}

export const Traduction = new ExtTraduction();
export const getString = Traduction.getString.bind(Traduction);



/////////////////////////////////////
// Donne le titre de la page en cours
export function getTitrePage(o = false, titre = false) {
  const titrePrefix = 'Rémi S., ' + getString('job');
  const titreSeparator = ' — ';

  if (titre)
    return titre + titreSeparator + titrePrefix;
  
  let titreCore;
  switch (o) {
    case 'competences':
    case 'quijesuis':
    case 'biographie':
    case 'bio':
      titreCore = getString('nav-bio');
      break;
    case 'portfolio':
    case 'projets':
      titreCore = getString('nav-projets');
      break;
    case 'articles':
    case 'blog':
      titreCore = getString('nav-articles');
      break;
    case 'contact':
      titreCore = getString('nav-contact');
      break;
    case 'projet':
      titreCore = getString('titre-projet') + history.state.oprojet_titre;
      break;
    default:
      titreCore = false;
  }

  if (titreCore)  return titreCore + titreSeparator + titrePrefix;
  else            return titrePrefix;
}