// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { Traduction, getString } from './traduction.js.php';
import Theme from './theme.js.php';
import { wait } from './Params.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const css = `
*:active,
*:focus:not(:focus-visible) {
  outline: none;
}
*::moz-focus-inner {
  border: none;
}

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
  cursor: pointer;

  display: grid;
  width: 100%;
  height: 100%;
}

svg {
  width: 100%;
  height: 100%;
  fill: var(--fill, white);
}

/* Animation */
svg * {
  transition: all .5s ease;
}
.ray {
  transition-delay: calc(.2s + 8 * 20ms - var(--n) * 20ms);
  transition-duration: .3s;
  transform: scale(1);
  opacity: 1;
}
#moon-hole>circle {
  transform: translate(40 40);
}
svg.dark circle {
  transform: scale(1);
}
svg.dark .ray {
  opacity: 0;
  transition-delay: calc(var(--n) * 30ms);
  transform: scale(0);
}
svg.dark #moon-hole>circle {
  transition-delay: .2s;
  transform: scale(1);
}
svg:not(.animate) * {
  transition: none !important;
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
<button>
  <svg viewBox="0 0 120 120" id="test">
    <defs>
      <mask id="moon-hole">
        <rect x="0" y="0" width="120" height="120" fill="white"/>
        <circle cx="90" cy="30" r="40" fill="black" transform-origin="120 0" transform="translate(40 -40)"/>
      </mask>
    </defs>

    <circle cx="60" cy="60" r="50" transform="scale(.5)" transform-origin="50% 50%" mask="url(#moon-hole)"/>
    <g id="sun-rays" transform-origin="50% 50%">
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 1">
        <path d="M 60 10 L 60 24" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 3">
        <path d="M 60 10 L 60 24" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(90 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 5">
        <path d="M 60 10 L 60 24" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(180 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 7">
        <path d="M 60 10 L 60 24" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(270 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 2;">
        <path d="M 60 13 L 60 19" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(45 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 4">
        <path d="M 60 13 L 60 19" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(135 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 6">
        <path d="M 60 13 L 60 19" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(225 60 60)"/>
      </g>
      <g class="ray" width="120" height="120" transform-origin="60 60" style="--n: 8">
        <path d="M 60 13 L 60 19" style="stroke: var(--link-color)" stroke-linecap="round" stroke-width="10" transform="rotate(315 60 60)"/>
      </g>
    </g>
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
    const svg = this.shadowRoot.querySelector('svg');

    const currentTheme = Theme.resolve(Theme.get());

    // If type 'icon', clicking on the button changes the theme
    if (type == 'icon') {
      button.dataset.label = `theme-button-${currentTheme == 'dark' ? 'light' : 'dark'}`;
      if (currentTheme == 'dark') svg.classList.remove('dark');
      else                        svg.classList.add('dark');

      button.addEventListener('click', async () => {
        button.disabled = true;
        svg.classList.add('animate');
        const currentTheme = Theme.resolve(Theme.get());
        const chosenTheme = currentTheme == 'dark' ? 'light' : 'dark';
        if (chosenTheme == 'dark') svg.classList.remove('dark');
        else                       svg.classList.add('dark');
        button.dataset.label = `theme-button-${currentTheme}`;
        button.setAttribute('aria-label', getString(button.dataset.label));
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: Theme.unresolve(chosenTheme) } }));
        await wait(700);
        svg.classList.remove('animate');
        button.disabled = false;
        button.focus();
      });
    }
    

    // If type 'menu', clicking on the button opens a menu
    // and choosing an option in that menu changes the theme.
    else {
      button.dataset.label = `theme-button`;
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