:root {
  /* Animation timing */
  --easing-standard: cubic-bezier(0.4, 0.0, 0.2, 1);
  --easing-decelerate: cubic-bezier(0.0, 0.0, 0.2, 1);
  --easing-accelerate: cubic-bezier(0.4, 0.0, 1, 1);

  /* Main gradient */
  --main-gradient-bands: 6;
  --main-gradient: repeating-linear-gradient(to right,
    hsl(0, 100%, 90%) 0,
    hsl(39, 100%, 90%) calc(1 * 100% / var(--main-gradient-bands)),
    hsl(60, 100%, 90%) calc(2 * 100% / var(--main-gradient-bands)),
    hsl(120, 100%, 90%) calc(3 * 100% / var(--main-gradient-bands)),
    hsl(240, 100%, 90%) calc(4 * 100% / var(--main-gradient-bands)),
    hsl(300, 100%, 90%) calc(5 * 100% / var(--main-gradient-bands)),
    hsl(0, 100%, 90%) calc(6 * 100% / var(--main-gradient-bands))
  );
  --vivid-gradient: repeating-linear-gradient(to right,
    hsl(0, 100%, 80%) 0,
    hsl(39, 100%, 80%) calc(1 * 100% / var(--main-gradient-bands)),
    hsl(60, 100%, 80%) calc(2 * 100% / var(--main-gradient-bands)),
    hsl(120, 100%, 80%) calc(3 * 100% / var(--main-gradient-bands)),
    hsl(240, 100%, 80%) calc(4 * 100% / var(--main-gradient-bands)),
    hsl(300, 100%, 80%) calc(5 * 100% / var(--main-gradient-bands)),
    hsl(0, 100%, 80%) calc(6 * 100% / var(--main-gradient-bands))
  );
}

/*<?php ob_start();?>*/
:root[data-theme="light"] {
  --text-color: black;
  --link-hover-color: black;
  --inverse-text-color: white;
}

:root[data-theme="dark"] {
  --text-color: white;
  --link-hover-color: white;
  --inverse-text-color: black;
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! ACCESSIBILITÉ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/* Enlève le fond bleu sur les éléments tappables */
* { -webkit-tap-highlight-color: rgba(0, 0, 0, 0); }

/* Sélection de texte */
::-moz-selection{
  color: var(--inverse-text-color);
  background:var(--text-color);
}
::selection {
  color: var(--inverse-text-color);
  background:var(--text-color);
}
input::selection {
  color: var(--text-color);
  background:var(--inverse-text-color);
}
textarea::selection {
  color: var(--text-color);
  background:var(--inverse-text-color);
}

/* Éléments focusables */
*:active, *:focus:not(:focus-visible) { outline: none; }
*::moz-focus-inner { border: none; }





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! TYPOGRAPHIE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/* roboto-regular - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local(''),
       url('/_common/fonts/roboto/roboto-v20-latin-regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/roboto/roboto-v20-latin-regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}
/* roboto-500 - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 500;
  src: local(''),
       url('/_common/fonts/roboto/roboto-v20-latin-500.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/roboto/roboto-v20-latin-500.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}
/* open-sans-regular - latin */
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local(''),
       url('/_common/fonts/open-sans/open-sans-v18-latin-regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/open-sans/open-sans-v18-latin-regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}
/* open-sans-600 - latin */
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 600;
  src: local(''),
       url('/_common/fonts/open-sans/open-sans-v18-latin-600.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/open-sans/open-sans-v18-latin-600.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}

/* Police fluide */
html {
  font-family: 'Open Sans', system-ui, sans-serif;
  --min-font: 1.1; /* rem */
  --max-font: 1.2; /* rem */
  --min-screen: 42; /* 960px si 1rem = 16px */
  --max-screen: 80; /* 1280px si 1rem = 320px */
  font-size: calc(1rem * var(--min-font));
  --mod-big: 1.58;
  --mod-small: 1.13;
  --mod: var(--mod-small);
  -webkit-text-size-adjust: none;
  -moz-text-size-adjust: none;
  text-size-adjust: none;
}
@media screen and (min-width: 42rem) {
  html {
    font-size: calc(1rem * var(--min-font) + (var(--max-font) - var(--min-font)) * ((100vw - 1rem * var(--min-screen)) / (var(--max-screen) - var(--min-screen))));
    --mod: var(--mod-big);
  }
}
@media screen and (min-width: 80rem) {
  html {
    font-size: calc(1rem * var(--max-font));
  }
}
@media screen and (min-width: 125rem) {
  html {
    font-size: calc(0.2rem + 1.8vh);
  }
}

/* Police des liens */
header, footer, .lien-interne {
  font-family: 'Roboto', system-ui, sans-serif;
}

/* Enlever le style des headings, pour les utiliser uniquement selon leur signification */
h1, h2, h3, h4, h5, h6 {
  display: inline;
  margin: 0;
  font: inherit;
}

/* Niveaux de taille de texte */
.s1 { font-size: calc(var(--mod) * var(--mod) * 1rem) }
.s2 { font-size: calc(var(--mod-small) * 1rem) }
.s5 { font-size: 1rem }
.s6 { font-size: calc(1rem / var(--mod-small)) }
.s7 { font-size: calc(1rem / var(--mod-small) / var(--mod-small)) }
.s8 { font-size: calc(1rem / var(--mod-small) / var(--mod-small) / var(--mod-small)) }
p { font-size: 1rem; line-height: 1.6rem; margin: 0; }



/* Dégradé arc-en-ciel animé */

.rainbow-text,
strong {
  font-weight: 600;
  color: var(--text-color);
}

.rainbow-bg {
  background-color: var(--text-color);
  background-image: var(--vivid-gradient);
  background-size: calc(var(--main-gradient-bands) * 50%) 100%;
  background-position: 0 0;
  background-repeat: repeat;
  animation: rainbow-text-animation 40s linear infinite;
}

@supports (background-clip: text) or (-webkit-background-clip: text) {
  .rainbow-text,
  strong {
    background-image: var(--main-gradient);
    background-size: calc(var(--main-gradient-bands) * 50%) 100%;
    background-position: 0 0;
    background-repeat: repeat;
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    animation: rainbow-text-animation 40s linear infinite;
  }
}

@keyframes rainbow-text-animation {
  0% { background-position: 0 0; }
  100% { background-position: calc(100% * var(--main-gradient-bands)) 0; }
}



/* Boutons */

button {
  border: none;
  background-color: transparent;
  padding: 0;
  margin: 0;
  font: inherit;
  line-height: inherit;
  text-transform: none;
  -webkit-appearance: button;
}



/* Liens */

.lien-interne,
a {
  padding: .25em 1px;
  margin: auto 0 0;
  font-weight: 500;
  text-decoration: none;
  color: var(--link-color);
  border-bottom: 1px solid var(--link-underline-color);
  transition: all .2s var(--easing-standard);
  cursor: pointer;
  white-space: nowrap;
}

.grand-apercu-projet-lien:hover .lien-interne,
.grand-apercu-projet-lien:focus .lien-interne,
.apercu-projet:hover .lien-interne,
.apercu-projet:focus .lien-interne,
.lien-interne:hover,
.lien-interne:focus,
a:hover,
a:focus {
  color: var(--text-color);
  border-bottom-color: var(--color, var(--link-color));
}

a:not(.lien-interne) {
  padding: .05em 1px;
}

a:not(.lien-interne):hover,
a:not(.lien-interne):focus {
  color: var(--bright-link-color);
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! LAYOUT GLOBAL !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

html {
  width: 100vw;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;

  --full-bleed-width: 117rem;
  --partial-bleed-width: 60rem;
  --text-zone-width: 70ch;
  --full-bleed-column: calc(0.5 * var(--full-bleed-width) - 0.5 * var(--partial-bleed-width));
  --partial-bleed-column: calc(0.5 * var(--partial-bleed-width) - 0.5 * var(--text-zone-width));
}

.full-bleed {
  grid-column: full-bleed-start / full-bleed-end;
}

.partial-bleed {
  grid-column: partial-bleed-start / partial-bleed-end;
}

.text-zone {
  grid-column: text-zone-start / text-zone-end;
}

body {
  width: 100%;
  min-height: 100%;
  --max-width: 117rem;
  max-width: var(--max-width);

  margin: 0 auto;
  padding: 0;
  position: relative;

  overflow-x: hidden;
  overflow-y: hidden;

  font-weight: 400;
  color: var(--text-color);

  display: grid;
  grid-template-rows: auto 1fr auto;
  grid-template-columns: 1fr [full-bleed-start] min(100%, 117rem) [full-bleed-end] 1fr;

  background-color: var(--bg-color);
}

header,
main,
article,
footer {
  grid-column: full-bleed-start / full-bleed-end;
  display: grid;
  grid-template-columns: 
    [full-bleed-start] 2.6rem
    [quasi-full-bleed-start] 1fr
    [partial-bleed-start] calc(0.5 * var(--partial-bleed-width) - 0.5 * var(--text-zone-width))
    [text-zone-start] var(--text-zone-width) [text-zone-end]
    calc(0.5 * var(--partial-bleed-width) - 0.5 * var(--text-zone-width)) [partial-bleed-end]
    1fr [quasi-full-bleed-end]
    2.6rem [full-bleed-end];
  z-index: 2;
}

@media (max-width: 80rem) {
  header,
  main,
  article,
  footer {
    grid-template-columns: 
      [full-bleed-start] 1.2rem [quasi-full-bleed-start partial-bleed-start] 1fr
      [text-zone-start] min(calc(100% - 2.4rem), var(--text-zone-width)) [text-zone-end]
      1fr [partial-bleed-end quasi-full-bleed-end] 1.2rem [full-bleed-end];
  }

  theme-selector>.selector {
    right: 0;
  }
}

header {
  grid-row: 1;
  grid-column: full-bleed-start / full-bleed-end;
  padding: .6rem 0;
}

main {
  grid-row: 2;
  grid-column: full-bleed-start / full-bleed-end;
}

footer {
  grid-row: 3;
  grid-column: full-bleed-start / full-bleed-end;
  padding: .4rem 0;
}



/* Transition colorée animée entre sections */

#couleur {
  width: 100vw;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  transform-origin: top right;
  transform: scaleX(0);
  z-index: 1;
}

/*@media (prefers-reduced-motion: reduce) {
  #couleur {
    transform: scaleX(1);
  }
}*/





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! HEADER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

nav {
  grid-column: partial-bleed-start / partial-bleed-end;
  grid-row: 1;
  /*justify-self: start;*/
  align-self: center;
  display: flex;
  flex-wrap: wrap;
  margin: auto 0;
}

.logo {
  font-size: 1.5rem;
  border-bottom: none;
  margin-left: 0;
}

.logo>strong::before {
  content: "remiscan";
}

nav>.lien-interne,
footer .lien-interne {
  padding-top: 0;
  margin: auto 0;
}

nav,
.liens-bottom {
  gap: 1.65em;
}

.lien-nav[data-section].on,
.lien-nav[data-section].on:hover,
.lien-nav[data-section]:active {
  color: var(--next-link-color, var(--link-color));
  border-bottom-color: var(--text-color);
}

.lien-nav[data-section].on {
  pointer-events: none;
}

.spacer {
  flex-grow: 1;
  margin: 0 calc(.5 * -1.65em);
}

/*nav>.spacer {
  display: none;
}*/

@media (max-width: 35rem) {
  .logo>strong::before {
    content: "rémi";
  }

  nav,
  .liens-bottom {
    gap: 1em;
    width: 100%;
    justify-content: flex-end;
  }

  nav>.spacer {
    display: block;
  }
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! MAIN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

article {
  grid-row: 1;
  grid-column: full-bleed-start / full-bleed-end;
  display: none;
  grid-template-rows: auto;
}

body[data-section="accueil"] #accueil,
body[data-section="bio"] #bio,
body[data-section="projets"] #projets,
body[data-section="articles"] #articles,
body[data-section="contact"] #contact {
  display: grid;
}

.article-titre {
  grid-column: text-zone-start / text-zone-end;
  display: flex;
  justify-content: center;
  position: relative;
  padding-bottom: .25em;
  color: var(--secondary-text-color);
  width: -moz-fit-content;
  width: fit-content;
  justify-self: center;
}

.article-titre::after {
  content: '';
  display: block;
  width: 100%;
  height: 1px;
  position: absolute;
  left: 0;
  bottom: 0;
}

/* PAGE D'ACCUEIL */

#accueil {
  grid-template-rows: minmax(1.2rem, 1fr) [moi] auto minmax(2.6rem, 1fr) [projets] auto minmax(1.2rem, 1fr);
  padding: 0;
}

.lien-fleche {
  --gap: .25ch;
  --arrow-width: 1.5ch;
  --arrow-color: var(--text-color);

  display: inline-grid;
  grid-template-columns: auto var(--arrow-width);
  gap: var(--gap);
  position: relative;
  margin-top: .5em;
  padding: .15em 1px;
}

.lien-fleche::after {
  --arrow-dark: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='1 -2 22 22'%3E%3Cpath d='M 9 6 L 15 12 L 9 18' stroke='white' stroke-linecap='round' stroke-width='4' fill='transparent'/%3E%3C/svg%3E");
  --arrow-light: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='1 -2 22 22'%3E%3Cpath d='M 9 6 L 15 12 L 9 18' stroke='black' stroke-linecap='round' stroke-width='4' fill='transparent'/%3E%3C/svg%3E");

  display: block;
  grid-row: 1;
  grid-column: -2;
  transition: transform .2s var(--easing-standard);
  transform: translateX(0);
}

/*<?php ob_start();?>*/
:root[data-theme="light"] .lien-fleche::after {
  content: var(--arrow-light);
}

:root[data-theme="dark"] .lien-fleche::after {
  content: var(--arrow-dark);
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/

.grand-apercu-projet-lien:hover .lien-fleche::after,
.grand-apercu-projet-lien:focus .lien-fleche::after,
.apercu-projet:hover .lien-fleche::after,
.apercu-projet:focus .lien-fleche::after,
.lien-fleche:hover::after,
.lien-fleche:focus::after {
  --arrow-color: var(--link-color);
}

@keyframes back-and-forth {
  0% { transform: translateX(0); }
  25% { transform: translateX(calc(1 * var(--step))); }
  50% { transform: translateX(0); }
  75% { transform: translateX(calc(-1 * var(--step))); }
  100% { transform: translateX(0); }
}

/* Mini-bio */

.accueil-moi {
  grid-row: moi;
  grid-column: partial-bleed-start / partial-bleed-end;
  display: flex;
  flex-direction: column;
  align-items: start;
  max-width: 25ch;
  color: var(--secondary-text-color);
  font-weight: 400;
}

/* Mini-articles */

.accueil-articles {
  grid-column: articles;
  grid-row: moi;
}

.section-titre {
  display: grid;
  grid-template-columns: 1fr auto 0;
  gap: 0 .2em;
  margin-bottom: .6rem;
}

.section-titre>h4 {
  display: block;
}

.section-titre>a {
  margin-top: auto;
}

/* Mini-projets */

.accueil-projets {
  grid-column: partial-bleed-start / partial-bleed-end;
  grid-row: projets;
}

.accueil-projets.trois {
  grid-column: quasi-full-bleed-start / quasi-full-bleed-end;
}

.accueil-projets.trois .apercu-projet:nth-child(3) {
  display: grid;
}

.accueil-conteneur-projets {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr));
  gap: 1.2rem;
}

.grand-apercu-projet-lien,
.apercu-projet {
  display: grid;
  --padding: 0;
  grid-template-rows: 1fr [infos] auto var(--padding);
  grid-template-columns: var(--padding) [infos] 1fr var(--padding);
  border: none;
  box-shadow: 0 0 0 1px var(--link-underline-color);
  position: relative;
  overflow: hidden;
}

.grand-apercu-projet-image::before,
.apercu-projet::before {
  grid-row: 1 / -1;
  grid-column: 1 / -1;
  content: '';
  display: block;
  width: 0;
  height: 0;
  padding-bottom: 50%;
}

.apercu-projet:hover,
.apercu-projet:focus {
  border: none;
  box-shadow: 0 0 0 1px var(--link-color);
}

.apercu-projet-image,
.grand-apercu-projet-image::after {
  background-size: cover;
  background-position: center;
  clip-path: inset(1px);
  transition: clip-path .2s var(--easing-standard);
}

.apercu-projet-image {
  grid-row: 1 / -1;
  grid-column: 1 / -1;
  position: relative;
  z-index: 1;
}
/*<?php ob_start();?>*/
:root[data-theme="light"] .apercu-projet-image {
  background-image: var(--image-light, var(--image));
}

:root[data-theme="dark"] .apercu-projet-image {
  background-image: var(--image-dark, var(--image));
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/
/*<?php ob_start();?>*/
:root[data-theme="light"] .grand-apercu-projet-image::after {
  background-image: var(--image-light, var(--image));
}

:root[data-theme="dark"] .grand-apercu-projet-image::after {
  background-image: var(--image-dark, var(--image));
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/

.grand-apercu-projet-lien:hover>.grand-apercu-projet-image::after,
.grand-apercu-projet-lien:focus>.grand-apercu-projet-image::after,
.apercu-projet:hover>.apercu-projet-image,
.apercu-projet:focus>.apercu-projet-image {
  clip-path: inset(.3rem);
}

.apercu-projet-infos {
  grid-row: infos;
  grid-column: infos;
  display: grid;
  grid-template-columns: auto 1fr auto;
  grid-template-rows: auto auto;
  gap: .3em .6em;
  padding: .2em .4em .1em;
  background-color: var(--bg-color);
  color: var(--text-color);
  position: relative;
  z-index: 3;
}

.apercu-projet-titre {
  grid-row: 1;
  grid-column: 1;
  align-self: baseline;
  color: var(--secondary-text-color);
}

.apercu-projet-description {
  grid-row: 1;
  grid-column: 2;
  align-self: baseline;
}

@media (max-width: 42rem) {
  .accueil-conteneur-projets {
    grid-template-columns: minmax(15rem, 1fr);
    max-width: 25rem;
    margin: 0 auto;
  }
}

@media (max-width: 60rem) {
  .apercu-projet-infos {
    padding: .2em .4em;
    gap: .2em;
  }
  .apercu-projet-infos {
    gap: 0 .6em;
  }
  .apercu-projet-lien {
    display: none;
  }
}

.apercu-projet-lien {
  grid-row: 1;
  grid-column: 3;
  margin-top: auto;
  align-self: baseline;
}

.apercu-projet-lien::before {
  content: '';
  display: block;
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}

/* Mobile */

@media (max-width: 80rem) {
  #accueil {
    grid-template-rows: minmax(1.2rem, 1fr) [moi] auto minmax(2.6rem, 1fr) [articles projets] auto minmax(1.2rem, 1fr);
  }

  .accueil-articles {
    grid-row: articles;
  }
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! BIO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

#bio {
  grid-template-rows: auto auto 1fr;
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! PROJETS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

#projets {
  grid-template-rows: minmax(1.2rem, 1fr) [projets] auto minmax(1.2rem, 1fr);
}

.liste-projets {
  grid-row: projets;
  grid-column: partial-bleed-start / partial-bleed-end;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2.4rem;
}

@media (max-width: 42rem) {
  .liste-projets {
    grid-template-columns: minmax(15rem, 1fr);
    max-width: 25rem;
    margin: 0 auto;
    gap: 2.4rem 1.2rem;
  }
}

.grand-apercu-projet {
  display: grid;
  grid-template-rows: auto 1fr;
  grid-template-columns: 100%;
  min-width: calc(50% - .6rem);
  gap: .6rem;
  box-shadow: none;
  border: none;
}

.grand-apercu-projet-lien {
  grid-row: 1;
  display: grid;
  grid-template-rows: auto auto;
  grid-template-columns: auto 1fr auto;
  gap: .6rem;
  box-shadow: none;
}

.grand-apercu-projet>.apercu-projet-titre {
  grid-row: 1;
  grid-column: 1;
  align-self: center;
}

.grand-apercu-projet-lien>.apercu-projet-lien {
  grid-row: 1;
  grid-column: -2;
  align-self: center;
}

@media (max-width: 60rem) {
  .grand-apercu-projet-lien>.apercu-projet-lien {
    display: grid;
  }
}

.grand-apercu-projet-image {
  grid-row: 2;
  grid-column: 1 / -1;
  box-shadow: 0 0 0 1px var(--link-underline-color);
  display: grid;
  transition: box-shadow .2s var(--easing-standard);
}

.grand-apercu-projet-image::after {
  content: '';
  grid-row: 1 / -1;
  grid-column: 1 / -1;
  display: block;
}

.grand-apercu-projet-lien:hover>.grand-apercu-projet-image,
.grand-apercu-projet-lien:focus>.grand-apercu-projet-image {
  box-shadow: 0 0 0 1px var(--link-color);
}

.grand-apercu-projet>.apercu-projet-description {
  grid-row: 2;
  grid-column: 1 / -1;
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! FOOTER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

.liens-bottom {
  grid-column: partial-bleed-start / partial-bleed-end;
  grid-row: 1;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}

.lien-social {
  display: inline-grid;
  align-items: end;
  grid-template-columns: 2.5ch auto;
  gap: .75ch;
  position: relative;
}

/*<?php ob_start();?>*/
:root[data-theme="light"] .lien-social {
  --color: var(--color-light);
}

:root[data-theme="dark"] .lien-social {
  --color: var(--color-dark);
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/

.lien-social>svg {
  grid-column: 1;
  fill: var(--text-color);
  transition: fill .2s var(--easing-standard);
  margin-top: -2.5ch; /* pour ne pas influencer la position du lien */
}

.lien-social:hover>svg,
.lien-social:focus>svg {
  fill: var(--color, var(--link-color));
}

.social-nom {
  grid-column: 2;
}

@media (max-width: 42rem) {
  .lien-social {
    gap: 0;
    grid-template-columns: 2.5ch 0;
    /*border: 0;*/
    padding: .6em .6em .25em;
    margin-bottom: -.6em;
  }

  .lien-social>svg {
    margin-top: 0;
  }

  .social-nom {
    display: none;
  }
}

.bouton-langage:disabled {
  opacity: .5;
  filter: grayscale(1);
  pointer-events: none;
  border-bottom: 1px solid transparent;
}

@media (max-width: 42rem) {
  .bouton-langage:disabled {
    display: none;
  }
}

theme-selector {
  width: 1.5rem;
  height: 1.5rem;
  --primary-color: var(--text-color);
  --secondary-color: var(--link-color);
}







/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! LAZY LOADING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

@keyframes background-loading {
  0% { background-color: rgba(0, 0, 0, .3); }
  50% { background-color: rgba(0, 0, 0, .3); }
  75% { background-color: rgba(0, 0, 0, .2); }
  100% { background-color: rgba(0, 0, 0, .3); }
}

.loading {
  animation: background-loading 2s ease-out calc(0.5s + (var(--n, 0) - var(--nmax, 0)) * 0.1s) infinite;
}

.loaded {
  animation: none;
}

.loaded .actual-image {
  background-image: var(--image);
  box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .1);
}