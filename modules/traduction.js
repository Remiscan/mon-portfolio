import strings from 'strings' assert { type: 'json' };



const currentLang = document.documentElement.getAttribute('lang') ?? 'en';
export const getString = id => strings[currentLang]?.[id] ?? strings['en']?.[id] ?? 'undefined string';



/////////////////////////////////////
// Donne le titre de la page en cours
export function getTitrePage(o = false, titre = false)
{
  const titrePrefix = 'Rémi S., ' + getString('job');
  const titreSeparator = ' — ';

  if (titre)
    return titre + titreSeparator + titrePrefix;
  
  let titreCore;
  switch (o)
  {
    case 'competences':
    case 'quijesuis':
    case 'biographie':
    case 'bio':
      titreCore = getString('nav-bio');
      break;
    case 'portfolio':
      titreCore = getString('nav-portfolio');
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

  if (titreCore)
    return titreCore + titreSeparator + titrePrefix;
  else
    return titrePrefix;
}