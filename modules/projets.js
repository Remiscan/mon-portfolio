import { Params, wait } from 'Params';
import { cancelableAsync } from 'cancelable-async';
import { changeThemeColor } from 'changeCouleur';
import { getString, getTitrePage } from 'traduction';



const elProjet = document.getElementById('projet');
const projetObfuscator =  document.querySelector('.projet-obfuscator');
const projetTransition = document.querySelector('.projet-transition');

let lastProjetNav;



/////////////////////////////////////////////////
// Prépare les vidéos des projets au lazy loading
const videoObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.removeAttribute('preload');
      entry.target.setAttribute('autoplay', '');
      entry.target.setAttribute('muted', '');
      entry.target.setAttribute('loop', '');
      entry.target.setAttribute('playsinline', '');
      entry.target.play();
      videoObserver.unobserve(entry.target);
    }
  });
});

document.querySelectorAll('#projet video').forEach(v => {
  const width = v.getAttribute('width');
  const height = v.getAttribute('height');
  v.style.setProperty('--width', width);
  v.style.setProperty('--aspect-ratio', `${width} / ${height}`);
})



/////////////////////////
// Navigation vers projet
/*export function* openProjet(event) {*/
export function* openProjet(projet) {
  lastProjetNav = Date.now();

  const source = document.querySelector(`#projet-preview-${projet}`);
  const titre = getString('projet-' + projet + '-titre');
  const couleur = source.style.getPropertyValue('--projet-color');

  const article = document.querySelector(`article[id="projet_${projet}"]`);
  const projetContenu = article.querySelector('.projet-contenu');
  const projetDetailsImages = article.querySelector('.projet-details-images');

  try {
    document.title = getTitrePage(false, getString('titre-projet') + titre);

    // On anime l'apparition de l'article Projet
    document.body.setAttribute('data-projet-actuel', projet);

    //document.body.style.overflowY = 'hidden';
    document.documentElement.style.overflowY = 'hidden';
    elProjet.style.setProperty('--projet-color', couleur);

    //// Animation de l'interface
    yield animProjet(projet);

    // On écoute le bouton Échap pour fermer le projet
    window.addEventListener('keydown', window.cp = event => {
      const key = event.which || event.keyCode;
      const button = article.querySelector('.projet-close');
      if (key == 27) button.click();
    });

    // On anime l'apparition des infos de base du projet choisi
    changeThemeColor(couleur);
    projetContenu.classList.add('on');

    document.querySelector('main').setAttribute('inert', '');
    document.querySelector('header').setAttribute('inert', '');
    elProjet.removeAttribute('inert');

    projetContenu.scrollTop = 0;
    projetDetailsImages.scrollTo(0, 0);

    const projetKeyframes = [
      { opacity: '0', transform: 'translate3D(0rem, 2rem, 0) '},
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

    const toAnimate = ['.projet-details-top', '.projet-details-images', '.projet-details-longue_description', '.projet-details-ligne', '.projet-details'];

    // On anime chaque sous-section, et on signale quand la dernière a terminé
    yield Promise.all(Array.from(toAnimate.entries()).map(([k, e]) => {
      const anim = article.querySelector(e).animate(projetKeyframes, projetOptions(k));
      return wait(anim);
    }));

    article.querySelectorAll('video').forEach(v => videoObserver.observe(v));
  }
  
  catch(error) {
    console.log(error);
  };
}

openProjet = cancelableAsync(openProjet);



//////////////////
// Ferme un projet
let isProjetClosing = 0;
export async function closeProjet() {
  isProjetClosing = 1;
  document.body.style.removeProperty('--projet-color');

  const id = document.body.getAttribute('data-projet-actuel') ?? '';
  const article = document.querySelector(`article[id="projet_${id}"]`);

  const projetContenu = article.querySelector('.projet-contenu');
  const projetDetailsImages = article.querySelector('.projet-details-images');

  lastProjetNav = Date.now();

  elProjet.setAttribute('inert', '');
  document.querySelector('main').removeAttribute('inert');
  document.querySelector('header').removeAttribute('inert');

  window.removeEventListener('keydown', window.cp);
  changeThemeColor(document.body.style.getPropertyValue('--theme-color'));
  projetContenu.classList.remove('on');
  projetDetailsImages.scrollTo(0, 0);

  await animProjet(false, true);

  const lienProjet = document.querySelector(`#projet-preview-${id}`);
  lienProjet.style.setProperty('opacity', 1);
  const isMotionReduced = Params.isMotionReduced();
  const transiProjOpa = elProjet.animate([
    { opacity: 1 },
    { opacity: 0 }
  ], {
    duration: isMotionReduced ? 0 : 100,
    fill: 'backwards'
  });
  await new Promise(resolve => transiProjOpa.addEventListener('finish', resolve));
  
  transiProjOpa.cancel();
  document.documentElement.style.overflowY = 'auto';
  document.body.setAttribute('data-projet-actuel', '');
  lienProjet.style.removeProperty('opacity');
  if (typeof obfuscatorProjet !== 'undefined') obfuscatorProjet.cancel();

  isProjetClosing = 0;

  article.querySelectorAll('video').forEach(v => {
    v.pause();
    v.currentTime = 0;
    v.setAttribute('preload', 'none');
    v.removeAttribute('autoplay');
    v.removeAttribute('muted');
    v.removeAttribute('loop');
    v.removeAttribute('playsinline');
    videoObserver.unobserve(v);
  });

  const lastOpened = document.querySelector(`.projet-conteneur[data-id=${id}]`);
  lastOpened.focus();
}



/////////////////////////////////
// Anime l'apparition d'un projet
function animProjet(id = false, reverse = false) {
  const trueid = id ? id : document.body.getAttribute('data-projet-actuel');
  const article = document.querySelector(`article[id="projet_${trueid}"]`);

  const projetContenu = article.querySelector('.projet-contenu');
  
  // On prépare l'animation de transition
  const transiSource = document.getElementById('projet-preview-' + trueid);
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
  const transiTop = document.querySelector('.projet-transition-top');
  const transiBottom = document.querySelector('.projet-transition-bottom');
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
  projetTransition.classList.remove('off');
  const apparitionProjetConteneur = projetTransition.animate([transiKeyframes[istart], transiKeyframes[iend]], transiOptions);
  if (transiSourceClip != 'none') {
    window['animTransiTop'] = transiTop.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
    window['animTransiBottom'] = transiBottom.animate([transiTopKeyframes[istart], transiTopKeyframes[iend]], transiOptions);
  }

  // On anime le fond transparent noir en même temps
  if (!window.matchMedia('(max-width: ' + Params.breakpointMobile + 'px)').matches) {
    window['obfuscatorProjet'] = projetObfuscator.animate([obfuscatorKeyframes[istart], obfuscatorKeyframes[iend]], {
      easing: 'linear',
      duration: transiDuration,
      fill: 'both'
    });
  }

  return new Promise(resolve => {
    apparitionProjetConteneur.addEventListener('finish', () => {
      projetTransition.classList.add('off');
      if (!reverse) {
        const couleur = elProjet.style.getPropertyValue('--projet-color');
        document.body.style.setProperty('--projet-color', couleur);
      }
      resolve();
    });
  });
}