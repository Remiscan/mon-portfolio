import { Params, wait } from 'Params';



///////////////////////////////////
// Clip quand on passe sur la photo
export function photoclip(event, bool = true) {
  const photo = document.getElementById('photosecret');
  const photorect = photo.getBoundingClientRect();
  const tx = event.clientX - photorect.left;
  const ty = event.clientY - photorect.top;

  if (bool) {
    photo.style.opacity = 1;
    photo.style.setProperty('-webkit-clip-path', 'circle(25% at ' + tx + 'px '+ ty + 'px)');
    photo.style.setProperty('clip-path', 'circle(25% at ' + tx + 'px '+ ty + 'px)');
  } else {
    const isMotionReduced = Params.isMotionReduced();
    photo.animate([
      { clipPath: 'circle(25% at ' + tx + 'px '+ ty + 'px)', opacity: 1 },
      { clipPath: 'circle(0% at ' + tx + 'px '+ ty + 'px)', opacity: 1 }
    ], {
        easing: 'cubic-bezier(1, 0.6, 1, 0.6)',
        duration: isMotionReduced ? 0 : 60
    });
    photo.style.setProperty('-webkit-clip-path', 'unset');
    photo.style.setProperty('clip-path', 'unset');
    photo.style.opacity = 0;
  }
}

const photoSecret = document.getElementById('photosecret');
photoSecret.addEventListener('pointerenter', event => {
  const moveHandler = event => { photoclip(event) };

  const cancelHandler = event => {
    photoclip(event, false);
    photoSecret.removeEventListener('pointermove', moveHandler);
    photoSecret.removeEventListener('pointerup', cancelHandler);
    photoSecret.removeEventListener('pointercancel', cancelHandler);
    photoSecret.removeEventListener('pointerout', cancelHandler);
    photoSecret.removeEventListener('pointerleave', cancelHandler);
  }

  photoSecret.addEventListener('pointermove', moveHandler);
  photoSecret.addEventListener('pointerup', cancelHandler);
  photoSecret.addEventListener('pointercancel', cancelHandler);
  photoSecret.addEventListener('pointerout', cancelHandler);
  photoSecret.addEventListener('pointerleave', cancelHandler);
});



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