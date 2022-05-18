import { cancelableAsync } from 'cancelable-async';
import { Params, wait } from 'Params';



const sections = ['accueil', 'bio', 'projets', 'blog', 'contact'];



const Navigation = {
  init() {
    // Liens de navigation
    for (const lien of [...document.querySelectorAll('a[data-section]')]) {
      lien.addEventListener('click', event => {
        event.preventDefault();
        Navigation.go(lien.dataset.section);
      });
    }
  },


  *go(section, history = true) {
    if (document.body.dataset.section == section) return;
    const styles = getComputedStyle(document.documentElement);
    const oldSection = document.body.dataset.section;
    const couleur = styles.getPropertyValue(`--${oldSection}-bg-color`);
    const previousLinkColor = styles.getPropertyValue(`--${oldSection}-link-color`);
    const nextLinkColor = styles.getPropertyValue(`--${section}-link-color`)
                        ||`hsl(${Number(styles.getPropertyValue(`--${section}-primary-hue`)) + 180}, 50%, 80%)`;

    // Active le bon lien
    for (const lien of [...document.querySelectorAll(`a[data-section]`)]) {
      if (lien.dataset.section == section) {
        lien.setAttribute('aria-current', 'page');
        lien.tabIndex = -1;
        lien.style.setProperty('--previous-link-color', previousLinkColor);
        lien.style.setProperty('--next-link-color', nextLinkColor);

        if (lien.dataset.section != 'accueil') {
          const texte = lien.innerHTML;
          const string = lien.dataset.string;
          lien.innerHTML = `<h1 data-string="${string}">${texte}</h1>`;
          lien.removeAttribute('data-string');
        }
      } else {
        lien.removeAttribute('aria-current');
        lien.tabIndex = 0;
        lien.style.setProperty('--next-link-color', '');

        if (lien.dataset.section != 'accueil') {
          const texte = lien.querySelector('h1')?.innerHTML || lien.innerHTML;
          const string = lien.querySelector('h1')?.dataset.string || lien.dataset.string;
          lien.innerHTML = texte;
          lien.dataset.string = string;
        }
      }
    }

    // Détecte le sens de l'animation
    const oldSectionIndex = sections.findIndex(e => e == document.body.dataset.section);
    const newSectionIndex = sections.findIndex(e => e == section);
    const reversed = oldSectionIndex > newSectionIndex;

    // Détecte la complexité de l'animation
    const distance = '1ch';
    const moveTo = Params.reducedMotion() ? '0' : reversed ? distance : `-${distance}`;
    const moveFrom = Params.reducedMotion() ? '0' : reversed ? `-${distance}` : distance;
    
    // 1e animation : disparition de section
    const main = document.querySelector('main');
    const anim1 = main.animate([
      { transform: 'translate3d(0, 0, 0)', opacity: '1' },
      { transform: `translate3D(${moveTo}, 0, 0)`, opacity: '0' }
    ], {
      duration: Params.reducedMotion() ? 0 : 100,
      easing: Params.easingAccelerate,
      fill: 'both'
    });
    yield wait(anim1);

    // On applique le style, le titre et l'url de la nouvelle section
    //document.title = Navigation.getUrl(section);
    if (history) window.history.pushState({ section }, '', Navigation.getUrl(section));
    document.body.dataset.section = section;

    // 2e animation : transition colorée
    const anim2 = yield Navigation.changeCouleur(couleur, reversed);
    
    // 3e animation : apparition de section
    const anim3 = main.animate([
      { transform: `translate3d(${moveFrom}, 0, 0)`, opacity: '0' },
      { transform: 'translate3D(0, 0, 0)', opacity: '1' }
    ], {
      duration: Params.reducedMotion() ? 0 : 100,
      easing: Params.easingDecelerate,
      fill: 'both'
    });
    yield wait(anim3);

    // Animations terminées
    for (const a of [anim1, anim2, anim3]) { a.cancel(); }
  },


  async changeCouleur(couleur, reversed = false) {
    // Détecte la complexité de l'animation
    let keyframes;
    if (Params.reducedMotion()) {
      keyframes = [
        { opacity: '1', transform: 'scaleX(1)' },
        { opacity: '0', transform: 'scaleX(1)' }
      ];
    } else {
      keyframes = [
        { transform: 'scaleX(1)' },
        { transform: 'scaleX(0)' }
      ];
    }

    const background = document.getElementById('couleur');
    if (!reversed) background.style.setProperty('transform-origin', 'top left');
    else           background.style.setProperty('transform-origin', 'top right');
    background.style.setProperty('background-color', couleur);
    const animation = background.animate(keyframes, {
      duration: Params.reducedMotion() ? 0 : 250,
      delay: Params.reducedMotion() ? 0 : 10,
      endDelay: Params.reducedMotion() ? 0 : 10,
      easing: Params.easingStandard,
      fill: 'both'
    });
    await wait(animation);
    return animation;
  },


  getUrl(section) {
    const sectionUrls = {
      bio: { fr: 'bio', en: 'bio' },
      projets: { fr: 'projets', en: 'projects' },
      blog: { fr: 'blog', en: 'blog' },
      contact: { fr: 'contact', en: 'contact' }
    };

    const lang = document.documentElement.lang || 'en';
    const url = (section == 'accueil') ? '' : sectionUrls[section][lang];
    const suffix = new URLSearchParams(window.location.search);
    if (!document.documentElement.dataset.urlLang) suffix.delete('lang');
    return `/${url}${suffix.toString() ? `?${suffix.toString()}` : ''}`;
  }
}

Navigation.go = cancelableAsync(Navigation.go);
export default Navigation;