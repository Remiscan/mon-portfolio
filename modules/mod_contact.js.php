// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { getString } from '../../_common/js/traduction.js';
import { simulateClick } from './mod_Params.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



export const champsContact = ['adresse', 'message'];



//////////////////////////////////////////////////
// Supprime les indicateurs d'erreur du formulaire
export function verifyForm(elem) {
  elem.parentElement.classList.remove('error');
}

champsContact.forEach(e => {
  document.getElementById(e + '_mail').addEventListener('mousedown', event => verifyForm(event.currentTarget));
});



////////////////////////////////////////////////////
// Envoie les données du formulaire de contact à PHP
function sendData()
{
  const mailForm = document.getElementById('formulaire_contact');
  const donnees = new FormData(mailForm);
  let champsVides = 0;
  let reponseRecue = false;
  let animationsToCancel = [];

  champsContact.forEach(e => {
    const ce_champ = document.getElementById(e + '_mail').parentElement;
    ce_champ.classList.remove('error');
    void ce_champ.offsetWidth; // Lance un reflow, qui permet à l'animation CSS de recommencer quand on ajoute la classe error à nouveau juste après l'avoir enlevée.
    if (donnees.get(e + '_mail') == '')
    {
      ce_champ.classList.add('error');
      champsVides++;
    }
  });

  if (champsVides > 0)
  {
    mailForm.querySelector('.button').classList.add('error');
    window.setTimeout(() => { mailForm.querySelector('.button').classList.remove('error'); }, 210);
    return;
  }

  // Étape parallèle 1 : envoi des données
  const promiseEnvoi = fetch('/mon-portfolio/modules/mod_sendMail.php', {
    method: 'POST',
    body: donnees
  })
  .then(response => {
    if (response.status == 200)
      return response;
    else
      throw '[:(] Erreur ' + response.status + ' lors de la requête';
  })
  .then(response => { return response.json(); })
  .then(data => {
    reponseRecue = true;
    return data['envoi_reussi'];
  })
  .catch(error => {
    console.error(error);
  });

  // Étape parallèle 2 : animation du bouton Envoyer
  const boutonEnvoyer = mailForm.querySelector('button');
  const boutonAnimation = document.getElementById('button-animation');
  boutonAnimation.style.display = 'grid';
  boutonAnimation.style.left = boutonEnvoyer.offsetLeft + 'px';
  boutonAnimation.style.top = boutonEnvoyer.offsetTop + 'px';
  boutonAnimation.style.width = boutonEnvoyer.offsetWidth + 'px';
  boutonAnimation.style.height = boutonEnvoyer.offsetHeight + 'px';
  boutonAnimation.style.setProperty('--dot-width', 0.5 * Math.min(boutonEnvoyer.offsetWidth / 3, boutonEnvoyer.offsetHeight) + 'px');
  boutonEnvoyer.disabled = true;
  boutonEnvoyer.tabIndex = -1;
  boutonEnvoyer.style.opacity = 0;
  boutonEnvoyer.blur();

  const dots = Array.from(boutonAnimation.getElementsByClassName('dot'));

  function bouton2dots() {
    return new Promise((resolve, reject) => {
      dots.forEach(function(e, i)
      {
        window['dotShortening' + i] = e.animate([
          { transform: 'scale(5)' },
          { transform: 'scale(1)' }
        ], {
            easing: 'ease-out',
            duration: 200,
            delay: i * 50,
            fill: 'forwards'
        });
        animationsToCancel.push(window['dotShortening' + i]);

        if (i == dots.length - 1)
          dotShortening2.addEventListener('finish', resolve);
      });
    });
  }

  function dotsWave() {
    return new Promise((resolve, reject) => {
      const anims = {};
      dots.forEach((el, j) => {
        anims['dotanim' + j] = el.animate([
          { transform: 'translateY(0)' },
          { transform: 'translateY(-0.7rem)' },
          { transform: 'translateY(0)' }
        ], {
            easing: 'ease-in-out',
            duration: 500,
            delay: 500 + j * 75,
        });
      });

      anims['dotanim2'].addEventListener('finish', () => {
        if (reponseRecue)
          resolve();
        else
          reject();
      });
    })
    .catch(dotsWave);
  }

  const promiseAnim = bouton2dots().then(dotsWave);

  // On lance les étapes 1 et 2 en même temps
  return Promise.all([promiseEnvoi, promiseAnim])
  .then(response => {
    const statutReponse = response[0];

    // Une fois la réponse du serveur reçue
    // Début animation 2 : dots -> stop puis dots -> widen puis texte -> apparaît puis overlay -> disparaît puis retour à zéro     
    let couleurReponse, texteReponse; 
    if (statutReponse === true)
    {
      couleurReponse = 'lightgreen';
      texteReponse = getString('contact-resultat-succes');
      console.log('[:)] Envoi du mail réussi !');
    }
    else if (statutReponse === false)
    {
      couleurReponse = 'pink';
      texteReponse = getString('contact-resultat-erreur');
      console.error('[:(] Erreur lors de l\'envoi de l\'e-mail...');
      document.getElementById('adresse_mail').parentElement.classList.add('error');
    }

    dots.forEach((e, i) => {
      e.animate([
        { transform: 'scale(1)' },
        { transform: 'scale(5)' }
      ], {
          easing: 'ease-in',
          duration: 200,
          delay: i * 50,
          fill: 'forwards'
      });

      window['dotWidening' + i] = e.firstChild.animate([
        { backgroundColor: 'white' },
        { backgroundColor: couleurReponse }
      ], {
          easing: 'ease-in',
          duration: 200,
          delay: i * 50,
          fill: 'forwards'
      });
      animationsToCancel.push(window['dotWidening' + i]);
    });

    window['dotWidening2'].addEventListener('finish', () => {
      const boutonTexte = document.getElementById('button-animation-text');
      boutonTexte.innerHTML = texteReponse;
      boutonTexte.style.zIndex = 1;
      window['textApparition'] = boutonTexte.animate([
        { opacity: 0 },
        { opacity: 1 }
      ], {
          easing: 'ease-in',
          duration: 200,
          fill: 'forwards'
      });
      animationsToCancel.push(window['textApparition']);

      window['textApparition'].addEventListener('finish', () => {
        window.setTimeout(() => { boutonEnvoyer.style.opacity = 1; }, 2000);
        let transformation = 'translateY(-5rem)';
        if (!statutReponse)
          transformation = 'translateY(5rem) rotate(10deg)';

        window['boutonRevient'] = boutonAnimation.animate([
          { transform: 'translateY(0)', opacity: 1 },
          { transform: transformation, opacity: 0 }
        ], {
            easing: 'ease-in',
            duration: 200,
            fill: 'forwards',
            delay: 2000
        });
        animationsToCancel.push(window['boutonRevient']);

        boutonRevient.addEventListener('finish', () => {
          boutonAnimation.style.display = 'none';
          boutonTexte.style.zIndex = 0;
          reponseRecue = false;
          if (statutReponse === true)
            mailForm.reset();
          animationsToCancel.forEach(e => {
            e.cancel();
            e = null;
          });
          boutonEnvoyer.disabled = false;
          boutonEnvoyer.tabIndex = 0;
        });
      });
    });
  });
}



///////////////////////////////////////////////////////////////////////
// Envoie les données du formulaire à PHP au clic sur le bouton Envoyer
document.getElementById('formulaire_contact').addEventListener('submit', event => {
  event.preventDefault();
  sendData();
});



///////////////////////////////////////////////////////////////////////////////////////////////////////
// Scrolle vers le haut de page puis envoie vers la page contact au clic sur "me contacter" dans la bio
document.querySelector('.mecontacter').addEventListener('click', event => {
  event.preventDefault();
  window.scrollTo(0, 0);
  setTimeout(() => simulateClick(document.getElementById('nav_contact'), 1, 1), 100);
});