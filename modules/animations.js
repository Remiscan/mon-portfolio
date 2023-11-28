import { Params, wait } from 'Params';



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

// Effet au survol de ma photo
const photoSecret = document.getElementById('photosecret');
monitorHoveredElement(photoSecret);



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