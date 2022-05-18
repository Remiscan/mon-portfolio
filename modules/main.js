import Cookie from 'cookies';
import Navigation from 'navigation';
import 'theme-selector';



////////////////////////////////
// Gère les changements de thème
window.addEventListener('themechange', event => {
  // Set meta theme-color here

  if (event.detail.theme != 'auto') Cookie.submit('theme', event.detail.theme);
  else                              Cookie.delete('theme');
});



////////////////////////////////////////////////////////////////////
// Gère les appuis sur les boutons précédent / suivant du navigateur
window.addEventListener('popstate', event => {
  const section = event.state.section;
  Navigation.go(section, false);
}, false);



////////////////////////////////////////////////
// Gère la mise en place du site à son ouverture
const section = document.body.dataset.section;
history.replaceState({ section }, '', Navigation.getUrl(section));

// Personnalisation du theme-selector
const themeSelectors = document.querySelectorAll('theme-selector');
for (const themeSelector of themeSelectors) {
  themeSelector.querySelector('button').dataset.tappable = '';
  themeSelector.querySelector('.selector-title').classList.add('s5');
  themeSelector.querySelector('.selector-cookie-notice').classList.add('s8');
  const arrow = document.createElement('div');
  arrow.classList.add('selector-arrow');
  themeSelector.querySelector('.selector').appendChild(arrow);
}

Navigation.init();