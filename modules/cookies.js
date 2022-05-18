import DefCookie from 'default-cookies';



export default class Cookie extends DefCookie {
  constructor(name, value, maxAge = null) {
    super(name, value, '/', maxAge);
  }

  static delete(name) {
    super.delete('/', name);
  }
}



/*import DefCookieConsentMini from 'cookie-consent-mini';
import CookieMaker from 'cookie-maker';



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
  }
}

if (!customElements.get('cookie-consent-mini')) customElements.define('cookie-consent-mini', CookieConsentMini);*/