import { Params } from 'Params';
import { anim_competences } from 'animations';
import { cancelableAsync } from 'cancelable-async';
import { changeCouleur } from 'changeCouleur';
import { champsContact, verifyForm } from 'contact';
import { loadMaPhoto, loadProjetImages, placeholderNoMore } from 'loadImages';
import { getTitrePage } from 'traduction';



export const navs = ['nav_accueil', 'nav_bio', 'nav_portfolio'];

let nav_actuelle = 'nav_accueil';
let nav_etat = 'accueil';
let navEnCours = false;
let lastNav;

// Empêche la restoration de la position de scroll à l'event popstate
if ('scrollRestoration' in history) history.scrollRestoration = 'manual';



////////////////////////////
// Navigation entre articles
export function* naviguer(event, nav, start = false, historique = true)
{
  let needAnimations = false;
  let currentScroll = window.scrollY;
  const header = document.querySelector('header');
  const main = document.querySelector('main');
  const boutonLangage = document.querySelector('.groupe-langages');

  try {

    // ÉTAPE 1 : Navigation autorisée ?
    // Ne rien faire si :
      // - on demande à aller sur l'article déjà ouvert alors qu'aucune navigation n'est en cours (!navEnCours && nav_actuelle == nav.id && !start)
      // - on demande à aller sur l'article vers laquelle la navigation déjà en cours est en train d'aller (navEnCours && lastNav == nav)
    if (!navEnCours && nav_actuelle == nav.id && !start)
      throw 'Navigation rejetée : l\'article demandé (' + nav.id + ') est déjà ouverte';
    else if (navEnCours && lastNav == nav)
      throw 'Navigation rejetée : navigation déjà en cours vers l\'article demandé (' + nav.id + ')';


    // ÉTAPE 2.1 : Animations accueil <==> article + couleur
    // et ÉTAPE 2.2 en parallèle : Masquer les éléments du viel article
    lastNav = nav;

    // Si aucune condition de rejet n'est remplie, on continue :
    navEnCours = true;
    main.style.height = getComputedStyle(main).height;
    document.documentElement.style.overflowY = 'hidden';
    boutonLangage.classList.add('off');

    // Création de l'entrée de l'historique + URL et titre de la page
    let newurl;
    if (nav.id.replace('nav_', '') == 'accueil')
      newurl = `/${location.search}`;
    else
      newurl = `/${nav.id.replace('nav_', '')}${location.search}`;

    if (historique && event.clientX != -1 && event.clientY != -1)
    {
      if (!start && history.state != null && history.state.onav != nav.id)
        history.pushState({onav: nav.id}, '', newurl);
      else if (start)
        history.replaceState({onav: nav.id}, '', newurl);
    }
    document.title = getTitrePage(nav.id.replace('nav_', ''));

    // 2.1 : Préparation des animations de passage accueil <==> article
    let headerBgMove = 0;
    if (Params.owidth > Params.breakpointMobile)
      headerBgMove = Params.tailleHeader;

    let dureeTransition = 300;
    let e0, e1;

    if (!Params.articleOuvert && nav.id != 'nav_accueil') {
      needAnimations = true;
      e0 = 0;
      e1 = 1;
      document.getElementById('nav_portfolio').style.setProperty('--diff-scale-navs', 0);
    }
    else if (Params.articleOuvert && nav.id == 'nav_accueil') {
      needAnimations = true;
      e0 = 1;
      e1 = 0;
      document.documentElement.classList.remove('actif');
      document.getElementById('nav_portfolio').style.setProperty('--diff-scale-navs', Params.diffScaleNavs);
      window.scrollTo(0, 0);
      currentScroll = 0;
    }

    if (needAnimations) {
      // Paramètres des animations
      const isMotionReduced = Params.isMotionReduced();
      const options = {
        easing: Params.easingStandard,
        duration: isMotionReduced ? 0 : dureeTransition,
        fill: 'forwards'
      };

      // Déplacement du header
      const headerMoveKeyframes = [
        { transform: 'translate3D(0, 0, 0)' },
        { transform: 'translate3D(0, -' + Params.tailleHeader + 'px, 0)' }
      ];
      const headerMove = header.animate([
        headerMoveKeyframes[e0],
        headerMoveKeyframes[e1]
      ], options);

      // Compression apparente du fond du header
      const headerSizeKeyframes = [
        { transform: 'translate3D(0, 0, 0)' },
        { transform: 'translate3D(0, -' + headerBgMove + 'px, 0)' }
      ];
      const headerSize = document.querySelector('header .background').animate([
        headerSizeKeyframes[e0],
        headerSizeKeyframes[e1]
      ], options);

      // Déplacement de mon nom
      const introMoveKeyframes = [
        { transform: 'translate3D(0, 0, 0)' },
        { transform: 'translate3D(0, ' + Params.decalageIntro + 'px, 0)' }
      ];
      const introMove = document.getElementById('intro').animate([
        introMoveKeyframes[e0],
        introMoveKeyframes[e1]
      ], options);

      // Déplacement des liens de nav
      const navMoveKeyframes = [
        { transform: 'translate3D(0, 0, 0)' },
        { transform: 'translate3D(0, -' + Params.decalageNav + 'px, 0)' }
      ];
      const navMove = document.querySelector('nav').animate([
        navMoveKeyframes[e0],
        navMoveKeyframes[e1]
      ], options);

      // Horizontalisation / verticalisation des liens de nav sur mobile
      const nav1MoveKeyframes = [
        { transform: 'translate3D(' + Params.decalageNav1[0] + 'px, ' + Params.decalageNav1[1] + ', 0)' },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const nav2MoveKeyframes = [
        { transform: 'translate3D(' + Params.decalageNav2[0] + 'px, ' + Params.decalageNav2[1] + ', 0)' },
        { transform: 'translate3D(0, 0, 0)' },
      ];
      const nav1Move = document.getElementById('nav_bio').animate([
        nav1MoveKeyframes[e0],
        nav1MoveKeyframes[e1]
      ], options);
      const nav2Move = document.getElementById('nav_portfolio').animate([
        nav2MoveKeyframes[e0],
        nav2MoveKeyframes[e1]
      ], options);

      // Avant la fin des animations pour bien gérer les popstate events
      Params.articleOuvert = (e0 == 0); // true si on quitte l'accueil et ouvre un article, false si on ferme un article pour aller sur l'accueil

      // Une fois les animations finies
      headerSize.addEventListener('finish', () => {
        if (e0 == 1) { // si on va sur l'accueil
          nav_etat = 'accueil';
        }
        else {
          document.documentElement.classList.add('actif');
          nav_etat = 'article';
        }
        headerMove.cancel();
        headerSize.cancel();
        introMove.cancel();
        navMove.cancel();
        nav1Move.cancel();
        nav2Move.cancel();
      });
    }

    else {
      if (nav.id == 'nav_accueil')
        nav_etat = 'accueil';
      else
        nav_etat = 'article';
      Params.articleOuvert = true;
    }

    // Si on navigue vers un article qui existe
    if (navs.indexOf(nav.id) != -1) {
      navs.forEach(e => {
        // 2.2 : On masque toutes les autres articles que celui demandé
        if (e != nav.id) {
          const articleID = e.replace('nav_', '');
          const article = document.getElementById(articleID);
          const navlink = document.getElementById(e);
          article.style.display = 'none';
          navlink.classList.remove('selected');

          // Recrée le href des liens des articles fermés
          if (navlink.dataset.href) {
            navlink.href = navlink.dataset.href;
            navlink.removeAttribute('data-href');
          }
          
          // On réinitialise le formulaire de contact
          if (articleID == 'contact') {
            champsContact.forEach(e => verifyForm(document.getElementById(e + '_mail')));
            const enveloppeSvg = document.getElementById('svg-email');
            enveloppeSvg.innerHTML = enveloppeSvg.innerHTML.replace('#email-open', '#email-closed');
          }
        }
      });

      nav.classList.add('selected');

      // Supprime le href du lien de l'article ouvert
      nav.dataset.href = nav.getAttribute('href');
      nav.removeAttribute('href');
      
      if (nav.id == 'nav_contact')
      {
        const enveloppeSvg = document.getElementById('svg-email');
        enveloppeSvg.innerHTML = enveloppeSvg.innerHTML.replace('#email-closed', '#email-open');
      }

      if (nav.id == 'nav_bio')
        anim_competences(false);
      
      // Animation de la propagation de la couleur de article choisi
      yield changeCouleur(event, nav);
    }

    else throw 'Navigation demandée vers un article inexistant: ' + nav.id;


    // ÉTAPE 3 : Affichage du nouvel article
    // Si une autre navigation a été déclenchée après celle encore en cours
    //if (lastNav != nav) throw 'expired';
    
    const article_id = nav.id.replace('nav_', '');
    const article = document.getElementById(article_id);
    article.style.display = 'grid';

    // Apparition de l'article demandé
    const isMotionReduced = Params.isMotionReduced();
    let article_animation = main.animate([
      { opacity: '0', transform: 'translate3D(0, 1rem, 0)' },
      { opacity: '1', transform: 'translate3D(0, 0, 0)' }
    ], {
        easing: Params.easingDecelerate,
        duration: start ? 0 : isMotionReduced ? 0 : 150,
        fill: 'forwards'
    });
    if (start) article_animation.play(); // Empêche Edge d'ignorer l'animation de durée 0

    yield new Promise(resolve => article_animation.onfinish = resolve);

    main.style.height = 'auto';
    document.documentElement.style.overflowY = 'auto';
    window.scrollTo(0, currentScroll);
    nav_actuelle = 'nav_' + article_id;
    navEnCours = false;
    if (start) document.body.removeAttribute('data-start');

    // Animations et derniers ajustements du contenu du nouvel article
    if (article_id == 'bio') {
      anim_competences();
      loadMaPhoto();
      document.getElementById('photosecret').classList.remove('nope');
    }
    else if (article_id == 'portfolio')
      loadProjetImages();
    else if (article_id == 'accueil')
      boutonLangage.classList.remove('off');
    
    if (article_id != 'portfolio' && document.getElementById('portfolio').querySelector('.actual-image') != null) {
      const listeProjets = Array.from(document.getElementsByClassName('projet-actual-image'));
      placeholderNoMore(false, listeProjets);
    }
    if (article_id != 'bio' && document.getElementById('bio').querySelector('.actual-image') != null) {
      const maPhoto = document.getElementById('photo');
      placeholderNoMore(false, [maPhoto]);
    }

    return;
  }
  
  catch(error) {
    /*if (error == 'expired')
    {
      console.log('Navigation expirée : navigation plus récente en cours');
      // Quand on voulait aller sur l'accueil mais qu'une navigation plus récente va ailleurs
      if (nav.id == 'nav_accueil' && nav_etat == 'article' && !document.documentElement.classList.contains('actif'))
        document.documentElement.classList.add('actif');
      // Quand on voulait aller ailleurs mais qu'une navigation plus récente va sur l'accueil (nav_actuelle n'est pas màj quand on throw vers ici)
      else if (nav.id != 'nav_accueil' && nav_etat == 'accueil' && document.documentElement.classList.contains('actif'))
        document.documentElement.classList.remove('actif');
    }
    else*/
      console.log(error);
  };
}

naviguer = cancelableAsync(naviguer);



/////////////////////////////////////
// Set / get la variable nav_actuelle
export function setNavActuelle(n) { nav_actuelle = n; }
export function getNavActuelle() { return nav_actuelle; }



///////////////////////////////////////
// On écoute les demandes de navigation
navs.forEach(e => document.getElementById(e).addEventListener('click', event => {
  const a = (event.target.tagName == 'A') ? event.target : event.target.parentElement;
  if (a.href && event.button == 0) {
    if (a.origin == document.location.origin)
      event.preventDefault();
  }
  naviguer(event, event.currentTarget);
}));