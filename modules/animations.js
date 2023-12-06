import { Params, wait } from 'Params';



////////////////////////////////////
// Anime la timeline des compétences
export function anim_competences(t = true) {
  const firstConteneur = document.querySelector('.competence-conteneur');
  const allConteneurs = [...document.getElementsByClassName('competence-conteneur')];

  if (t && !firstConteneur.classList.contains('colored')) {
    const isMotionReduced = Params.isMotionReduced();
    allConteneurs.forEach(e => {
      const delai = isMotionReduced ? 0 : parseFloat(e.style.getPropertyValue('--delai'));
      wait(1000 * delai).then(() => e.classList.add('colored'));
    });
  } else if (!t && firstConteneur.classList.contains('colored')) {
    allConteneurs.forEach(e => e.classList.remove('colored'));
  }
}



///////////////////////////////////////////////////
// Surveille le survol d'un élément par le pointeur
// et lui passe les coordonnées locales du pointeur
function monitorHoveredElement(element) {
  const callback = (event) => {
    const rect = element.getBoundingClientRect();
    const tx = event.clientX - rect.left;
    const ty = event.clientY - rect.top;

    element.style.setProperty('--tx', Math.round(tx));
    element.style.setProperty('--ty', Math.round(ty));
  }

  const cancelEventTypes = ['pointercancel', 'pointerout', 'pointerleave']
  element.addEventListener('pointerenter', event => {
    const cancelHandler = () => {
      element.removeEventListener('pointermove', callback);
      cancelEventTypes.forEach(type => {
        element.removeEventListener(type, cancelHandler);
      });
    };

    element.addEventListener('pointermove', callback);
    cancelEventTypes.forEach(type => {
      element.addEventListener(type, cancelHandler);
    });
  })
}

// EASTER EGG
// Effet au survol de ma photo
const photoSecret = document.getElementById('photosecret');
monitorHoveredElement(photoSecret);



/////////////
// EASTER EGG
// Animation du logo remiscan du footer
let footerlogoSlideInTimeout;
let footerLogoChangeSideTimeout;
const footerObserver = new IntersectionObserver((entries) => {
  for (const entry of entries) {
    entry.target.classList.remove('touched-left', 'touched-right');
    if (entry.isIntersecting && document.body.getAttribute('data-section-actuelle') !== '') {
      // Animate logo here

      // 1. Slide logo in
      wait(1000)
      .then(() => entry.target.classList.add('visible'))

      // 2. Make it blink
      // in css

      // 3. Slide logo out
      .then(() => {
        footerlogoSlideInTimeout = setTimeout(() => {
          entry.target.classList.remove('visible');
        }, 3500);
      });
    } else {
      entry.target.classList.remove('visible');
      clearTimeout(footerlogoSlideInTimeout);
      clearTimeout(footerLogoChangeSideTimeout);
    }
  }
}, {
  threshold: [0, 1]
});

const footer = document.querySelector('footer');
footerObserver.observe(footer);

footer.addEventListener('pointerdown', event => {
  clearTimeout(footerLogoChangeSideTimeout);

  const rect = footer.getBoundingClientRect();
  const tx = event.clientX - rect.left;

  let touchedSide = 'left';
  if (tx > rect.width / 2) touchedSide = 'right';

  footerLogoChangeSideTimeout = setTimeout(() => {
    footer.classList.remove('touched-left', 'touched-right');
    footer.classList.add(`touched-${touchedSide}`);
  }, 100);
});