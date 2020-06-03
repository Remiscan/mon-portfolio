// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { getString, getLangage } from '../../_common/js/traduction.js';
import { Params, isVisible, wait } from './mod_Params.js.php';
import { naviguer, getNavActuelle, getTitrePage } from './mod_navigation.js.php';
import { changeThemeColor } from './mod_changeCouleur.js.php';
import { placeholderNoMore } from './mod_loadImages.js.php';
import { focusable } from './mod_a11y.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



const elProjet = document.getElementById('projet');
const projetContenu = document.getElementById('projet-contenu');
const projetDetailsPourquoi = document.getElementById('projet-details-pourquoi');
const projetDetailsLoading = document.getElementById('projet-details-loading');
const projetTransition = document.getElementById('projet-transition');
const projetObfuscator = document.getElementById('projet-obfuscator');

let transiSource = false;
let currentProjet = false;
let lastProjetNav;



/////////////////////////
// Navigation vers projet
export function openProjet(event)
{
  event.preventDefault();
  
  lastProjetNav = Date.now();
  const thisProjetNav = lastProjetNav;

  const source = event.currentTarget;
  const id = source.dataset.id;
  const couleur = source.style.getPropertyValue('--projet-color');
  const titre = getString('projet-' + id + '-titre');
  const lien = source.dataset.lien;
  const description = getString('projet-' + id + '-description');
  const longueDescription = getString('projet-' + id + '-longue-description');
  const versionProjet = source.dataset.version;

  let waitForEtude;

  elProjet.removeAttribute('aria-hidden');
  elProjet.removeAttribute('hidden');

  const iconeProjet = document.getElementById('projet-details-icone');
  const iconeProjetLien = ['/' + id + '/icons/icon.svg'];
  placeholderNoMore([iconeProjetLien], [iconeProjet]);

  const listeImages = [`/mon-portfolio/projets/${id}/preview-phone.png`, `/mon-portfolio/projets/${id}/preview-pc.png`];
  const listeConteneurs = [document.getElementById('projet-details-image-phone'), document.getElementById('projet-details-image-pc')];
  placeholderNoMore(listeImages, listeConteneurs);

  let directionPreview = [0, 2];

  return Promise.resolve()
  .then(() => {
    // On navigue vers le portfolio avant d'ouvrir le projet, si on n'y est pas déjà
    if (getNavActuelle() != 'nav_portfolio')
      return naviguer(event, document.getElementById('nav_portfolio'), true, false);
    else
      return;
  })
  .then(() => {
    if (thisProjetNav != lastProjetNav)
      throw 'expired';

    // Si l'event est un vrai clic (vs simulation par popstate event), on ajoute à l'historique
    if (history.state.onav != 'projet' || history.state.oprojet_id != id)
    {
      if (event.clientX != -1 && event.clientY != -1)
        history.pushState({onav: 'projet', oprojet_id: id, oprojet_titre: titre, oprojet_couleur: couleur}, '', '/projet/' + id);
      else
        history.replaceState({onav: 'projet', oprojet_id: id, oprojet_titre: titre, oprojet_couleur: couleur}, '', '/projet/' + id);
    }
    document.title = getTitrePage(false, getString('titre-projet') + titre);

    // On anime l'apparition de la section Projet
    currentProjet = id;

    //document.body.style.overflowY = 'hidden';
    document.documentElement.style.overflowY = 'hidden';
    elProjet.classList.add('on');
    elProjet.style.setProperty('--projet-color', couleur);
    projetDetailsPourquoi.style.opacity = 0;
    projetDetailsPourquoi.innerHTML = '';

    // On anime l'interface des projets et on récupère le "pourquoi du comment" en même temps
    // et quand les deux seront finis, on placera le "pourquoi du comment" dans l'interface
    //// Promesse 1 : animation de l'interface
    const promiseInterface = new Promise((resolve, reject) => {
      animProjet(id)
      .then(provenance => {
        //directionPreview = provenance;
        
        // On écoute le bouton Échap pour fermer le projet
        window.addEventListener('keydown', window.cp = event => {
          const key = event.which || event.keyCode;
          const button = document.getElementById('projet-close');
          if (key == 27) simulateClick(button, 1, 1);
        });

        if (thisProjetNav != lastProjetNav)
          throw 'expired';
        
        // On anime l'apparition des infos de base du projet choisi
        changeThemeColor(couleur);
        projetContenu.style.opacity = 1;
        projetTransition.style.display = 'none';
    
        focusable.forEach(function(e) {
          if (elProjet.contains(e))
            e.tabIndex = 0;
          else
          {
            e.dataset.savedTabIndex = e.tabIndex;
            e.tabIndex = -1;
          }
        });
        
        // On insère les infos basiques du projet
        const boutonFermer = document.getElementById('projet-close');
        boutonFermer.addEventListener('click', boutonFermer.clickhandler = () => {
          closeProjet();
          history.pushState({onav: 'nav_portfolio'}, '', '/portfolio');
          document.title = getTitrePage('portfolio');
        });
        
        const boutonVisiter = document.getElementById('projet-details-lien');
        document.getElementById('projet-details-titre').innerHTML = titre;
        document.getElementById('projet-details-description').innerHTML = description;
        document.getElementById('projet-details-longue_description').innerHTML = longueDescription;
        if (lien == '')
          boutonVisiter.style.display = 'none';
        else
        {
          boutonVisiter.style.display = 'unset';
          boutonVisiter.href = lien;
        }
    
        projetContenu.style.display = 'block';
        projetContenu.scrollTop = 0;
    
        const projetKeyframes = [
          { opacity: '0', transform: 'translate3D(' + directionPreview[0] + 'rem, ' + directionPreview[1] + 'rem, 0) '},
          { opacity: '1', transform: 'translate3D(0, 0, 0) '}
        ];
        const projetOptions = k => {
          return {
            easing: Params.easingDecelerate,
            duration: 100,
            delay: k * 20,
            fill: 'backwards'
          };
        };
    
        const toAnimate = ['projet-details-top', 'projet-details-images', 'projet-details-longue_description', 'projet-details-ligne', 'projet-details'];
    
        // On anime chaque sous-section, et on signale quand la dernière a terminé
        toAnimate.forEach((e, k) => {
          const anim = document.getElementById(e).animate(projetKeyframes, projetOptions(k));
          if (k == toAnimate.length - 1) // si c'est la dernière sous-section
            anim.addEventListener('finish', resolve);
        });
      })
      .catch(error => reject(error));
    });

    //// Promesse 2 : récupération du "pourquoi du comment"
    const promiseEtude = new Promise((resolve, reject) => {
      // On affiche les points de chargement si le chargement prend du temps
      waitForEtude = setTimeout(() => {
        projetDetailsLoading.classList.add('needstoload');
        if (isVisible(projetDetailsLoading, true, projetContenu))
          projetDetailsLoading.classList.add('loadingnow');
      }, 1000);

      // Récupère le "pourquoi du comment" dans la langue demandée
      function fetchEtude(lang) {
        if (lang == 'en' && !source.dataset.enExists) return Promise.reject('etude-en.htm inexistant');
        return fetch('/mon-portfolio/projets/' + id + '/etude-' + lang + '--' + versionProjet + '.htm')
        .then(response => {
          if (response.status == 200)
            return response;
          else
            throw '[:(] Erreur ' + response.status + ' lors de la requête';
        });
      }

      // On récupère le "pourquoi du comment" sur le serveur ou dans le cache
      fetchEtude(getLangage())
      .catch(() => fetchEtude('fr'))
      .then(response => response.text())
      .then(data => resolve(data))
      .catch(error => {
        console.error(error);
        reject('fetch failed');
      })
    });
  
    return Promise.all([promiseInterface, promiseEtude]);
  })
  .then(result => {
    const data = result[1];
    clearTimeout(waitForEtude);
    
    let delay = 0;
    let animDelay = 100;
    if (projetDetailsLoading.classList.contains('needstoload'))
      delay = 250;
    
    // On affiche les données, mais seulement quand la section projet est encore ouverte
    if (elProjet.classList.contains('on') && currentProjet == id)
    {
      projetDetailsLoading.classList.add('loaded');

      return wait(delay)
      .then(() => {
        projetDetailsLoading.classList.remove('needstoload');
        projetDetailsLoading.classList.remove('loadingnow');
        projetDetailsPourquoi.innerHTML = data;
        Array.from(projetDetailsPourquoi.querySelectorAll('a')).forEach(e => e.classList.add('focusable'));

        const anim_projetDetails = projetDetailsPourquoi.animate([
          { opacity: '0', transform: 'translate3D(' + directionPreview[0] + 'rem, ' + directionPreview[1] + 'rem, 0) '},
          { opacity: '1', transform: 'translate3D(0, 0, 0) '}
        ], {
            easing: Params.easingDecelerate,
            duration: 150,
            delay: animDelay,
            fill: 'both'
        });

        anim_projetDetails.addEventListener('finish', () => {
          projetDetailsPourquoi.style.opacity = 1;
          anim_projetDetails.cancel();
          [...projetDetailsPourquoi.querySelectorAll('img'), ...projetDetailsPourquoi.querySelectorAll('video')].forEach(img => {
            img.parentElement.classList.add('loading');
            const eventType = (img.tagName == 'VIDEO') ? 'loadeddata' : 'load';
            img.addEventListener(eventType, () => img.parentElement.classList.remove('loading'));
          });
          return;
        });
      })
    }
    else
      throw 'La section projet est fermée, les détails ne peuvent pas être affichés.';
  })
  .catch(error => {
    if (error == 'expired')
    {
      console.log('Ouverture de projet expirée');
      closeProjet();
    }
    else if (error == 'fetch failed')
    {
      clearTimeout(waitForEtude);
      projetDetailsLoading.classList.remove('needstoload');
      projetDetailsLoading.classList.remove('loadingnow');
      projetDetailsPourquoi.innerHTML = '<p>' + getString('projet-etude-erreur-fetch') + '</p>';
      projetDetailsPourquoi.style.opacity = 1;
    }
    else
      console.log(error);
  });
}



///////////////////////////////////////////////////
// On écoute les demandes de navigation vers projet
Array.from(document.querySelectorAll('.projet-conteneur')).forEach(e => {
  e.addEventListener('click', event => openProjet(event));
});

let isProjetClosing = 0;

projetObfuscator.addEventListener('click', () => {
  if (isProjetClosing == 1) return;
  isProjetClosing = 1;
  closeProjet();
  history.pushState({onav: 'nav_portfolio'}, '', '/portfolio');
  document.title = getTitrePage('portfolio');
}, {passive: true});



//////////////////
// Ferme un projet
export function closeProjet()
{
  document.body.style.removeProperty('--projet-color');
  
  elProjet.setAttribute('aria-hidden', 'true');
  elProjet.setAttribute('hidden', true);
  lastProjetNav = Date.now();
  focusable.forEach(function(e) {
    if (elProjet.contains(e))
      e.tabIndex = -1;
    else
    {
      e.tabIndex = e.dataset.savedTabIndex || 0;
      e.removeAttribute('data-saved-tab-index');
    }
  });
  const boutonFermer = document.getElementById('projet-close');
  boutonFermer.removeEventListener('click', boutonFermer.clickhandler);
  window.removeEventListener('keydown', window.cp);
  changeThemeColor(document.body.style.getPropertyValue('--article-color'));
  projetTransition.style.display = 'block';
  projetContenu.style.opacity = 0;
  projetDetailsPourquoi.style.opacity = 0;
  projetDetailsPourquoi.innerHTML = '';
  document.getElementById('projet-details-images').scrollLeft = 0;
  projetDetailsLoading.classList.remove('loaded', 'needstoload', 'loadingnow', 'failed');
  const listeConteneurs = [document.getElementById('projet-details-image-phone'), document.getElementById('projet-details-image-pc')];
  placeholderNoMore(false, listeConteneurs);
  placeholderNoMore(false, [document.getElementById('projet-details-icone')]);
  if (apparitionProjetConteneur)
  {
    animProjet(false, true);
    apparitionProjetConteneur.addEventListener('finish', hideProjet);
  }
  const lastOpened = document.querySelector(`.projet-conteneur[data-id=${currentProjet}`);
  lastOpened.focus();
  lastOpened.blur();

  function hideProjet()
  {
    if (transiSource)
      transiSource.style.opacity = 'unset';
    const transiProjOpa = elProjet.animate([
      { opacity: 1 },
      { opacity: 0 }
    ], {
      duration: 100,
      fill: 'forwards'
    });
    transiProjOpa.addEventListener('finish', () => {
      isProjetClosing = 0;
      elProjet.classList.remove('on');
      transiProjOpa.cancel();
      document.documentElement.style.overflowY = 'auto';
      if (typeof obfuscatorProjet !== 'undefined')
        obfuscatorProjet.cancel();
    });
    apparitionProjetConteneur.removeEventListener('finish', hideProjet);
  }
}



/////////////////////////////////
// Anime l'apparition d'un projet
function animProjet(id = false, reverse = false)
{
  let trueid;
  if (id)
    trueid = id;
  else
    trueid = currentProjet;
  
  // On prépare l'animation de transition
  transiSource = document.getElementById('projet-preview-' + trueid);
  const transiSourceClip = window.getComputedStyle(transiSource).getPropertyValue('clip-path');
  const transiSourcePos = transiSource.getBoundingClientRect();
  const transiPosDebut = {
                            top: transiSourcePos.top,
                            left: transiSourcePos.left,
                            width: transiSource.offsetWidth / Params.owidth,
                            height: transiSource.offsetHeight / Params.oheight
                         };
  const projetContenuPos = projetContenu.getBoundingClientRect();
  const transiPosFin = {
                         top: 0,
                         left: projetContenuPos.left,
                         width: projetContenu.offsetWidth / Params.owidth,
                         height: 1
                       };
  
  // Si on est sur mobile, modifier l'animation pour qu'elle tienne compte du clip-path
  const transiTop = document.getElementById('projet-transition-top');
  const transiBottom = document.getElementById('projet-transition-bottom');
  if (transiSourceClip != 'none')
  {
    const transiSourceClipValue = transiSourceClip.split('px,')[0].replace('polygon(0px ', '');
    projetTransition.style.setProperty('--clip-height', String(transiSourceClipValue + 2) + 'px');
    transiPosDebut.top = transiPosDebut.top + Number(transiSourceClipValue);
    transiPosDebut.height = transiPosDebut.height - 2 * transiSourceClipValue / Params.oheight;
  }

  let transiDuration, transiTiming, istart, iend;
  if (reverse)
  {
    //transiDuration = 100;
    transiDuration = 150;
    //transiTiming = Params.easingAccelerate;
    transiTiming = Params.easingDecelerate;
    istart = 1;
    iend = 0;
  }
  else
  {
    //transiDuration = 200;
    transiDuration = 250;
    //transiTiming = Params.easingDecelerate;
    transiTiming = 'cubic-bezier(0.4, 0.15, 0.3, 0.8)';
    istart = 0;
    iend = 1;
  }

  const transiOptions = {
    easing: transiTiming,
    duration: transiDuration,
    fill: 'both'
  };
  const transiKeyframes = [
    { transform: 'translate3D(' + transiPosDebut.left + 'px, ' + transiPosDebut.top + 'px, 0) scale(' + transiPosDebut.width + ', ' + transiPosDebut.height + ')' },
    { transform: 'translate3D(' + transiPosFin.left + 'px, ' + transiPosFin.top + 'px, 0) scale(' + transiPosFin.width + ', ' + transiPosFin.height + ')' }
  ];
  const transiTopKeyframes = [
    { transform: 'scaleY(' + String(1 / transiPosDebut.height) + ')' },
    { transform: 'scaleY(' + String(1 / transiPosFin.height) + ')'}
  ];
  const obfuscatorKeyframes = [
    { opacity: '0' },
    { opacity: '1' }
  ];
  
  // On lance l'animation de transition
  transiSource.style.opacity = 0;
  window['apparitionProjetConteneur'] = projetTransition.animate([transiKeyframes[istart], transiKeyframes[iend]], transiOptions);
  if (transiSourceClip != 'none')
  {
    window['animTransiTop'] = transiTop.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
    window['animTransiBottom'] = transiBottom.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
  }

  // On anime le fond transparent noir en même temps
  if (reverse)
    projetObfuscator.classList.remove('on');
  else
    projetObfuscator.classList.add('on');
  if (!window.matchMedia('(max-width: ' + Params.breakpointMobile + 'px)').matches)
  {
    window['obfuscatorProjet'] = projetObfuscator.animate([obfuscatorKeyframes[istart], obfuscatorKeyframes[iend]], {
      easing: 'linear',
      duration: transiDuration,
      fill: 'both'
    });
  }

  // On calcule la direction d'apparition du projet
  /*const direction = [
    (transiSourcePos.left + transiSourcePos.right) / 2 - owidth / 2,
    (transiSourcePos.top + transiSourcePos.bottom) / 2
  ];
  const a = Math.sqrt(Math.pow(direction[0], 2) + Math.pow(direction[1], 2));*/

  return new Promise(resolve => {
    apparitionProjetConteneur.addEventListener('finish', () => {
      if (!reverse) {
        const couleur = elProjet.style.getPropertyValue('--projet-color');
        document.body.style.setProperty('--projet-color', couleur);
      }
      resolve(/*[
        direction[0] / a,
        direction[1] / a
      ]*/);
    });
  });
}