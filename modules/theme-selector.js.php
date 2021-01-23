// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction } from './traduction.js.php';
import Theme from './theme.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const css = `
:host {
  display: grid;
  place-items: center;
  --default-size: 24px;
  width: var(--button-width, var(--default-size));
  height: var(--button-height, var(--default-size));
  position: relative;
}

button {
  border: none;
  background-color: transparent;
  padding: 0;
  margin: 0;
  font: inherit;
  line-height: inherit;
  text-transform: none;
  -webkit-appearance: button;

  display: grid;
  width: 100%;
  height: 100%;
}

svg {
  width: 100%;
  height: 100%;
  fill: var(--fill, white);
}

.selector {
  display: none;
  position: absolute;
  bottom: 100%;
  width: var(--selector-width, 15ch);
  height: var(--selector-height, 15ch);
  border-radius: var(--selector-border-radius, 0);
}

.selector.on {
  display: grid;
}

/*input[type=radio] {
  height: 0;
	width: 0;
  opacity: 0;
  pointer-events: none;
  position: absolute;
}*/
`;

const html = `
<button data-label="theme-button">
  <svg viewBox="0 0 24 24">
    <defs>
      <g id="all">
      </g>

      <g id="light">
        <rect fill="none" height="24" width="24"/>
        <path d="M12,7c-2.76,0-5,2.24-5,5s2.24,5,5,5s5-2.24,5-5S14.76,7,12,7L12,7z M2,13l2,0c0.55,0,1-0.45,1-1s-0.45-1-1-1l-2,0 c-0.55,0-1,0.45-1,1S1.45,13,2,13z M20,13l2,0c0.55,0,1-0.45,1-1s-0.45-1-1-1l-2,0c-0.55,0-1,0.45-1,1S19.45,13,20,13z M11,2v2 c0,0.55,0.45,1,1,1s1-0.45,1-1V2c0-0.55-0.45-1-1-1S11,1.45,11,2z M11,20v2c0,0.55,0.45,1,1,1s1-0.45,1-1v-2c0-0.55-0.45-1-1-1 C11.45,19,11,19.45,11,20z M5.99,4.58c-0.39-0.39-1.03-0.39-1.41,0c-0.39,0.39-0.39,1.03,0,1.41l1.06,1.06 c0.39,0.39,1.03,0.39,1.41,0s0.39-1.03,0-1.41L5.99,4.58z M18.36,16.95c-0.39-0.39-1.03-0.39-1.41,0c-0.39,0.39-0.39,1.03,0,1.41 l1.06,1.06c0.39,0.39,1.03,0.39,1.41,0c0.39-0.39,0.39-1.03,0-1.41L18.36,16.95z M19.42,5.99c0.39-0.39,0.39-1.03,0-1.41 c-0.39-0.39-1.03-0.39-1.41,0l-1.06,1.06c-0.39,0.39-0.39,1.03,0,1.41s1.03,0.39,1.41,0L19.42,5.99z M7.05,18.36 c0.39-0.39,0.39-1.03,0-1.41c-0.39-0.39-1.03-0.39-1.41,0l-1.06,1.06c-0.39,0.39-0.39,1.03,0,1.41s1.03,0.39,1.41,0L7.05,18.36z"/>
      </g>

      <g id="dark">
        <rect fill="none" height="24" width="24"/>
        <path d="M11.01,3.05C6.51,3.54,3,7.36,3,12c0,4.97,4.03,9,9,9c4.63,0,8.45-3.5,8.95-8c0.09-0.79-0.78-1.42-1.54-0.95 c-0.84,0.54-1.84,0.85-2.91,0.85c-2.98,0-5.4-2.42-5.4-5.4c0-1.06,0.31-2.06,0.84-2.89C12.39,3.94,11.9,2.98,11.01,3.05z"/>
      </g>
    </defs>

    <use href="#auto"/>
  </svg>
</button>

<div class="selector">
  <input type="radio" name="theme" id="theme-auto" value="auto" checked>
  <label for="theme-auto" data-string="theme-auto"></label>

  <input type="radio" name="theme" id="theme-light" value="light">
  <label for="theme-auto" data-string="theme-light"></label>

  <input type="radio" name="theme" id="theme-dark" value="dark">
  <label for="theme-auto" data-string="theme-dark"></label>
</div>
`;



const template = document.createElement('template');
template.innerHTML = `<style>${css}</style>${html}`;



class ThemeSelector extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.appendChild(template.content.cloneNode(true));
  }

  connectedCallback() {
    const type = this.getAttribute('type');

    // Button displays the selector
    const button = this.shadowRoot.querySelector('button');
    const selector = this.shadowRoot.querySelector('.selector');
    const use = this.shadowRoot.querySelector('use');

    // If type 'icon', clicking on the button changes the theme
    if (type == 'icon') {
      const currentTheme = Theme.resolve(Theme.get());
      use.setAttribute('href', `#${currentTheme == 'dark' ? 'light' : 'dark'}`);
      button.addEventListener('click', () => {
        const currentTheme = Theme.resolve(Theme.get());
        const chosenTheme = currentTheme == 'dark' ? 'light' : 'dark';
        use.setAttribute('href', `#${currentTheme}`);
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: Theme.unresolve(chosenTheme) } }));
      })
    }
    

    // If type 'menu', clicking on the button opens a menu
    // and choosing an option in that menu changes the theme.
    else {
      use.setAttribute('href', `#all`);
      button.addEventListener('click', () => selector.classList.toggle('on'));
      for (const choice of [...selector.querySelectorAll('input')]) {
        choice.addEventListener('change', () => {
          const chosenTheme = choice.value;
          window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: chosenTheme } }));
        });
      }
    }

    // Initial translation
    Traduction.traduire(this.shadowRoot);

    // Listens to translate events
    window.addEventListener('translate', () => Traduction.traduire(this.shadowRoot));
  }
}

customElements.define("theme-selector", ThemeSelector);