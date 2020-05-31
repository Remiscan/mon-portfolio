///////////////////////////////////////////////
// Charge toutes les images de l'array d'entrée
export function loadAllImages(liste)
{
  let promises = [];
  liste.forEach((e, k) => {
    promises[k] = new Promise((resolve, reject) => {
      const img = new Image();
      img.src = e;
      img.onload = () => { resolve(k) }
      img.onerror = () => { reject(k) }
    });
  });
  return Promise.all(promises);
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Charge des images pendant que leurs conteneurs affichent une animation d'attente, puis affiche ces images avec animation
export function placeholderNoMore(listeImages = false, listeConteneurs, sequence = false, grid = false)
{
  if (listeImages !== false)
  {
    function endOnePlaceholder(e, n, nmax, k = 0) {
      return new Promise(resolve => {
        e.style.setProperty('--n', (nmax == 0) ? 0 : n);
        e.style.setProperty('--nmax', (nmax == 0) ? 0 : nmax);
        e.style.setProperty('--image', `url('${listeImages[k || n]}')`);
  
        const actualImage = document.createElement('div');
        actualImage.classList.add('actual-image');
        e.appendChild(actualImage);
  
        const d = (nmax == 0) ? 0 : n * 75;
        const t = (nmax == 0) ? 200 : 300;
  
        e.classList.add('loaded');
        const loadImg = e.querySelector('.actual-image').animate([
          { opacity: 0 },
          { opacity: 1 }
        ], {
            easing: 'ease-out',
            duration: t,
            delay: d,
            fill: 'forwards'
        });
  
        loadImg.addEventListener('finish', resolve);
      });
    }

    listeConteneurs.forEach(e => e.classList.add('loading'));
    const promesses = [];

    if (sequence)
    {
      return loadAllImages(listeImages)
      .then(() => {
        const nmax = (grid !== false) ? getComputedStyle(grid).gridTemplateColumns.split(' ').length
                                      : listeConteneurs.length;
        listeConteneurs.forEach((e, n) => {
          let _n = n;
          if (grid !== false) {
            const currentRow = Math.floor(n / nmax);
            const currentColumn = n % nmax;
            _n = currentRow + currentColumn;
          }
          promesses.push(new Promise(res => endOnePlaceholder(e, _n, nmax, n).then(res)));
        });
        return Promise.all(promesses);
      })
      .catch(raison => console.error('[:(] L\'image ' + raison + ' n\'a pas pu être chargée...'));
    }
    else
    {
      listeConteneurs.forEach((e, n) => {
        const unePromesse = loadAllImages([listeImages[n]])
        .then(() => endOnePlaceholder(e, n, 0))
        .catch(() => { throw '[:(] L\'image ' + n + ' n\'a pas pu être chargée...'; });

        promesses.push(unePromesse);
      });
      return Promise.all(promesses)
      .catch(raison => console.error(raison));
    }
  }
  else
  {
    return Promise.resolve().then(() => {
      listeConteneurs.forEach(e => {
        e.classList.remove('loaded', 'loading');
        Array.from(e.getElementsByClassName('actual-image')).forEach(c => c.remove());
      });
      return;
    });
  }
}



///////////////////////////////////////////////////////////////
// Chargement des images de preview des projet sur le portfolio
export function loadProjetImages()
{
  const listeProjets = Array.from(document.getElementsByClassName('projet-actual-image'));
  let listeImages = [];
  listeProjets.forEach(e => listeImages.push(e.dataset.image));
  placeholderNoMore(listeImages, listeProjets, true, document.querySelector('.liste-projets'));
}



/////////////////////////
// Chargement de ma photo
export function loadMaPhoto()
{
  const maPhoto = document.getElementById('photo');
  placeholderNoMore(['/mon-portfolio/images/moi.jpg'], [maPhoto]);
}