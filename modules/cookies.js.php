// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import CookieMaker from '/_common/js/cookie-maker.js.php';
import DefCookieConsentMini from '/_common/components/cookie-consent-mini/cookie-consent-mini.js.php';
import { Traduction } from './traduction.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const Cookie = new CookieMaker('/');
export default Cookie;

class CookieConsentMini extends DefCookieConsentMini {
  constructor() {
    super();
  }

  connectedCallback() {
    super.connectedCallback();

    this.querySelector('.cookie-consent-mini-question').classList.add('s7');
    this.querySelector('.cookie-consent-mini-button-yes').classList.add('s7');
    this.querySelector('.cookie-consent-mini-button-no').classList.add('s7');
    this.querySelector('.cookie-consent-mini-info').classList.add('s8');
    Traduction.traduire(this);
  }
}

if (!customElements.get('cookie-consent-mini')) customElements.define('cookie-consent-mini', CookieConsentMini);