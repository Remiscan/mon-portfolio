import { Params, isVisible, wait } from 'Params';
import { cancelableAsync } from 'cancelable-async';
import { changeThemeColor } from 'changeCouleur';
import { Loader, dePlaceholder, placeholderNoMore } from 'loadImages';
import { getNavActuelle, naviguer } from 'navigation';
import { getString, getTitrePage } from 'traduction';



const elProjet = document.getElementById('projet');
const projetContenu = document.getElementById('projet-contenu');
const projetDetailsImages = document.getElementById('projet-details-images');
const projetDetailsPourquoi = document.getElementById('projet-details-pourquoi');
const projetDetailsLoading = document.getElementById('projet-details-loading');
const projetTransition = document.getElementById('projet-transition');
const projetObfuscator = document.getElementById('projet-obfuscator');

let transiSource = false;
let currentProjet = false;
let lastProjetNav;



/////////////////////////
// Navigation vers projet
export function* openProjet(event)
{
  event.preventDefault();
  
  lastProjetNav = Date.now();
  const thisProjetNav = lastProjetNav;

  const source = event.currentTarget;
  const id = source.dataset.id;
  const couleur = source.style.getPropertyValue('--projet-color');
  const themeCouleur = source.style.getPropertyValue('--theme-color');
  const titre = getString('projet-' + id + '-titre');
  const lien = source.dataset.lien;
  const description = getString('projet-' + id + '-description');
  const longueDescription = getString('projet-' + id + '-longue-description');
  const versionProjet = source.dataset.version;

  let waitForEtude;

  elProjet.removeAttribute('aria-hidden');
  elProjet.removeAttribute('hidden');

  const images = [{
    url: '/' + (id === 'colori' ? 'colori/demo' : id) + '/icons/icon.svg',
    conteneur: document.getElementById('projet-details-icone')
  }, {
    url: `/mon-portfolio/projets/${id}/preview-phone--${source.getAttribute('data-version-image-phone') ?? '0'}.webp`,
    conteneur: document.getElementById('projet-details-image-phone')
  }, {
    url: `/mon-portfolio/projets/${id}/preview-pc--${source.getAttribute('data-version-image-pc') ?? '0'}.webp`,
    conteneur: document.getElementById('projet-details-image-pc')
  }];

  let directionPreview = [0, 2];

  try {

    // On navigue vers le portfolio avant d'ouvrir le projet, si on n'y est pas déjà
    if (getNavActuelle() != 'nav_portfolio')
      yield naviguer(event, document.getElementById('nav_portfolio'), true, false);

    /*if (thisProjetNav != lastProjetNav)
      throw 'expired';*/

    // Si l'event est un vrai clic (vs simulation par popstate event), on ajoute à l'historique
    if (history.state.onav != 'projet' || history.state.oprojet_id != id)
    {
      const newUrl = `/projet/${id}${location.search}`;
      if (event.clientX != -1 && event.clientY != -1)
        history.pushState({onav: 'projet', oprojet_id: id, oprojet_titre: titre, oprojet_couleur: couleur}, '', newUrl);
      else
        history.replaceState({onav: 'projet', oprojet_id: id, oprojet_titre: titre, oprojet_couleur: couleur}, '', newUrl);
    }
    document.title = getTitrePage(false, getString('titre-projet') + titre);

    // On anime l'apparition de l'article Projet
    currentProjet = id;

    //document.body.style.overflowY = 'hidden';
    document.documentElement.style.overflowY = 'hidden';
    elProjet.classList.add('on');
    elProjet.style.setProperty('--projet-color', couleur);
    projetDetailsPourquoi.style.opacity = 0;
    projetDetailsPourquoi.innerHTML = '';

    // On anime l'interface des projets et on récupère le "pourquoi du comment" en même temps
    // et quand les deux seront finis, on placera le "pourquoi du comment" dans l'interface
    //// Promesse 1 : chargement des images
    const loadImages = async () => {
      return await Promise.all(images.map(async img => {
        img.conteneur.classList.add('loading');
        const loader = new Loader(img.url);
        await loader.load();
        if (thisProjetNav != lastProjetNav) throw 'expired';
        return dePlaceholder(img.conteneur, img.url);
      }));
    };

    //// Promesse 2 : animation de l'interface
    const loadInterface = async () => {
      //const directionPreview = await animProjet(id);
      await animProjet(id);
        
      // On écoute le bouton Échap pour fermer le projet
      window.addEventListener('keydown', window.cp = event => {
        const key = event.which || event.keyCode;
        const button = document.getElementById('projet-close');
        if (key == 27) button.click();
      });

      if (thisProjetNav != lastProjetNav) throw 'expired';
      
      // On anime l'apparition des infos de base du projet choisi
      changeThemeColor(couleur);
      projetContenu.style.opacity = 1;
      projetTransition.style.display = 'none';
  
      document.querySelector('main').setAttribute('inert', '');
      document.querySelector('header').setAttribute('inert', '');
      elProjet.removeAttribute('inert');
      
      const boutonVisiter = document.getElementById('projet-details-lien');
      document.getElementById('projet-details-titre').innerHTML = titre;
      document.getElementById('projet-details-description').innerHTML = description;
      document.getElementById('projet-details-longue_description').innerHTML = longueDescription;
      if (lien == '')
        boutonVisiter.style.display = 'none';
      else {
        boutonVisiter.style.display = 'unset';
        boutonVisiter.href = lien;
      }
  
      projetContenu.style.display = 'block';
      projetContenu.scrollTop = 0;
      projetDetailsImages.scrollTo(0, 0);
  
      const projetKeyframes = [
        { opacity: '0', transform: 'translate3D(' + directionPreview[0] + 'rem, ' + directionPreview[1] + 'rem, 0) '},
        { opacity: '1', transform: 'translate3D(0, 0, 0) '}
      ];
      const projetOptions = k => {
        const isMotionReduced = Params.isMotionReduced();
        return {
          easing: Params.easingDecelerate,
          duration: isMotionReduced ? 0 : 100,
          delay: isMotionReduced ? 0 : k * 20,
          fill: 'backwards'
        };
      };
  
      const toAnimate = ['projet-details-top', 'projet-details-images', 'projet-details-longue_description', 'projet-details-ligne', 'projet-details'];
  
      // On anime chaque sous-section, et on signale quand la dernière a terminé
      return await Promise.all(Array.from(toAnimate.entries()).map(([k, e]) => {
        const anim = document.getElementById(e).animate(projetKeyframes, projetOptions(k));
        return new Promise(resolve => anim.onfinish = resolve);
      }));
    };

    //// Promesse 3 : récupération du "pourquoi du comment"
    const loadEtude = async () => {
      // On affiche les points de chargement si le chargement prend du temps
      waitForEtude = setTimeout(() => {
        projetDetailsLoading.classList.add('needstoload');
        if (isVisible(projetDetailsLoading, true, projetContenu))
          projetDetailsLoading.classList.add('loadingnow');
      }, 1000);

      // Récupère le "pourquoi du comment" dans la langue demandée
      const fetchEtude = async lang => {
        if (lang == 'en' && !source.dataset.enExists) return Promise.reject('etude-en.htm inexistant');
        const response = await fetch('/mon-portfolio/projets/' + id + '/etude-' + lang + '--' + versionProjet + '.htm');
        if (response.status == 200) return Promise.resolve(response);
        else return Promise.reject('[:(] Erreur ' + response.status + ' lors de la requête');
      }

      // On récupère le "pourquoi du comment" sur le serveur ou dans le cache
      return fetchEtude(document.documentElement.getAttribute('lang'))
      .catch(() => fetchEtude('fr'))
      .then(response => response.text())
      .catch(error => {
        console.error(error);
        throw 'fetch failed';
      });
    };
  
    const result = yield Promise.all([loadImages(), loadInterface(), loadEtude()]);
    const data = result[2];
    clearTimeout(waitForEtude);
    
    let delay = (projetDetailsLoading.classList.contains('needstoload')) ? 250 : 0;
    let animDelay = 100;
    
    // On affiche les données, mais seulement quand l'article projet est encore ouvert
    if (thisProjetNav != lastProjetNav) throw 'expired';

    if (elProjet.classList.contains('on') && currentProjet == id) {
      projetDetailsLoading.classList.add('loaded');

      yield wait(delay);

      projetDetailsLoading.classList.remove('needstoload');
      projetDetailsLoading.classList.remove('loadingnow');
      projetDetailsPourquoi.innerHTML = data;

      const isMotionReduced = Params.isMotionReduced();
      const anim_projetDetails = projetDetailsPourquoi.animate([
        { opacity: '0', transform: 'translate3D(' + directionPreview[0] + 'rem, ' + directionPreview[1] + 'rem, 0) '},
        { opacity: '1', transform: 'translate3D(0, 0, 0) '}
      ], {
          easing: Params.easingDecelerate,
          duration: isMotionReduced ? 0 : 150,
          delay: isMotionReduced ? 0 : animDelay,
          fill: 'both'
      });

      yield new Promise(resolve => anim_projetDetails.onfinish = resolve);

      projetDetailsPourquoi.style.opacity = 1;
      anim_projetDetails.cancel();
      [...projetDetailsPourquoi.querySelectorAll('img'), ...projetDetailsPourquoi.querySelectorAll('video')].forEach(img => {
        img.parentElement.classList.add('loading');
        const eventType = (img.tagName == 'VIDEO') ? 'loadeddata' : 'load';
        img.addEventListener(eventType, () => img.parentElement.classList.remove('loading'));
      });
      return;
    }
    else throw 'L\'article projet est fermé, les détails ne peuvent pas être affichés.';
  }
  
  catch(error) {
    if (error == 'expired') {
      console.log('Ouverture de projet expirée');
      //closeProjet();
    }
    else if (error == 'fetch failed') {
      clearTimeout(waitForEtude);
      projetDetailsLoading.classList.remove('needstoload');
      projetDetailsLoading.classList.remove('loadingnow');
      projetDetailsPourquoi.innerHTML = '<p>' + getString('projet-etude-erreur-fetch') + '</p>';
      projetDetailsPourquoi.style.opacity = 1;
    }
    else console.log(error);
  };
}

openProjet = cancelableAsync(openProjet);



///////////////////////////////////////////////////
// On écoute les demandes de navigation vers projet
let isProjetClosing = 0;
export function initProjets() {
  Array.from(document.querySelectorAll('.projet-conteneur')).forEach(e => {
    if (e.id === 'projet-preview-more') return;
    e.addEventListener('click', event => openProjet(event));
  });

  // On insère les infos basiques du projet
  const boutonFermer = document.getElementById('projet-close');
  boutonFermer.addEventListener('click', boutonFermer.clickhandler = (event) => {
    event.preventDefault();
    closeProjet();
    history.pushState({onav: 'nav_portfolio'}, '', `/portfolio${location.search}`);
    document.title = getTitrePage('portfolio');
  });

  projetObfuscator.addEventListener('click', () => {
    if (isProjetClosing == 1) return;
    isProjetClosing = 1;
    closeProjet();
    history.pushState({onav: 'nav_portfolio'}, '', `/portfolio${location.search}`);
    document.title = getTitrePage('portfolio');
  }, {passive: true});
}



//////////////////
// Ferme un projet
export async function closeProjet() {
  document.body.style.removeProperty('--projet-color');
  
  elProjet.setAttribute('aria-hidden', 'true');
  elProjet.setAttribute('hidden', true);
  lastProjetNav = Date.now();

  elProjet.setAttribute('inert', '');
  document.querySelector('main').removeAttribute('inert');
  document.querySelector('header').removeAttribute('inert');

  window.removeEventListener('keydown', window.cp);
  changeThemeColor(document.body.style.getPropertyValue('--theme-color'));
  projetTransition.style.display = 'block';
  projetContenu.style.opacity = 0;
  projetDetailsPourquoi.style.opacity = 0;
  projetDetailsPourquoi.innerHTML = '';
  projetDetailsImages.scrollTo(0, 0);
  projetDetailsLoading.classList.remove('loaded', 'needstoload', 'loadingnow', 'failed');
  const listeConteneurs = [document.getElementById('projet-details-image-phone'), document.getElementById('projet-details-image-pc')];
  placeholderNoMore(false, listeConteneurs);
  placeholderNoMore(false, [document.getElementById('projet-details-icone')]);

  await animProjet(false, true);
  if (transiSource) transiSource.style.opacity = 'unset';
  const isMotionReduced = Params.isMotionReduced();
  const transiProjOpa = elProjet.animate([
    { opacity: 1 },
    { opacity: 0 }
  ], {
    duration: isMotionReduced ? 0 : 100,
    fill: 'forwards'
  });
  await new Promise(resolve => transiProjOpa.addEventListener('finish', resolve));
  isProjetClosing = 0;
  elProjet.classList.remove('on');
  transiProjOpa.cancel();
  document.documentElement.style.overflowY = 'auto';
  if (typeof obfuscatorProjet !== 'undefined') obfuscatorProjet.cancel();

  const lastOpened = document.querySelector(`.projet-conteneur[data-id=${currentProjet}]`);
  lastOpened.focus();
}



/////////////////////////////////
// Anime l'apparition d'un projet
function animProjet(id = false, reverse = false) {
  const trueid = id ? id : currentProjet;
  
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
  if (transiSourceClip != 'none') {
    const transiSourceClipValue = transiSourceClip.split('px,')[0].replace('polygon(0px ', '');
    projetTransition.style.setProperty('--clip-height', String(transiSourceClipValue + 2) + 'px');
    transiPosDebut.top = transiPosDebut.top + Number(transiSourceClipValue);
    transiPosDebut.height = transiPosDebut.height - 2 * transiSourceClipValue / Params.oheight;
  }

  let transiDuration, transiTiming, istart, iend;
  if (reverse) {
    transiDuration = 150;
    transiTiming = Params.easingDecelerate;
    istart = 1;
    iend = 0;
  } else {
    transiDuration = 250;
    transiTiming = 'cubic-bezier(0.4, 0.15, 0.3, 0.8)';
    istart = 0;
    iend = 1;
  }

  const isMotionReduced = Params.isMotionReduced();
  const transiOptions = {
    easing: transiTiming,
    duration: isMotionReduced ? 0 : transiDuration,
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
  const apparitionProjetConteneur = projetTransition.animate([transiKeyframes[istart], transiKeyframes[iend]], transiOptions);
  if (transiSourceClip != 'none') {
    window['animTransiTop'] = transiTop.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
    window['animTransiBottom'] = transiBottom.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
  }

  // On anime le fond transparent noir en même temps
  if (reverse) projetObfuscator.classList.remove('on');
  else         projetObfuscator.classList.add('on');
  if (!window.matchMedia('(max-width: ' + Params.breakpointMobile + 'px)').matches) {
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