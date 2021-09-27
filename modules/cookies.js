import CookieMaker from 'cookie-maker';
import DefCookieConsentMini from 'cookie-consent-mini';
import { Traduction } from 'traduction';



const Cookie = new CookieMaker('/', ['lang', 'theme']);
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