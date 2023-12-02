const templateImg = document.createElement('template');
templateImg.innerHTML = `
<img alt="" loading="lazy">
`;

const templateVideo = document.createElement('template');
templateVideo.innerHTML = `
<video loop muted controls>
  <source type="video/mp4">
</video>
`;

class mediaProjet extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ['src', 'width', 'height', 'phone', 'alt-text'];
  }

  update(attributes = mediaProjet.observedAttributes) {
    if (!this.ready) return;
    const img = this.querySelector('img') || this.querySelector('video');
    const source = this.querySelector('img') || this.querySelector('source');

    src: {
      if (!attributes.includes('src')) break src;
      const src = this.getAttribute('src');
      source.setAttribute('src', src);
    }

    alt: {
      if (!attributes.includes('type') && !attributes.includes('alt-text')) break alt;
      const type = this.getAttribute('type');
      const altText = this.getAttribute('alt-text');
      if (type === 'image' && altText != null) {
        img.setAttribute('alt', altText);
      }
    }

    width: {
      if (!attributes.includes('width')) break width;
      const width = this.getAttribute('width');
      this.style.setProperty('--w', width);
      img.setAttribute('width', width);
    }

    height: {
      if (!attributes.includes('height')) break height;
      const height = this.getAttribute('height');
      this.style.setProperty('--h', height);
      img.setAttribute('height', height);
    }

    phone: {
      if (!attributes.includes('phone')) break phone;
      const phone = this.getAttribute('phone') != null;
      if (phone) this.style.setProperty('--largeur-max', '20rem');
      else       this.style.removeProperty('--largeur-max');
    }
  }

  connectedCallback() {
    const type = this.getAttribute('type');
    const template = (type == 'image' || type == 'img') ? templateImg : (type == 'video' || type == 'vid') ? templateVideo : null;
    if (template !== null) this.appendChild(template.content.cloneNode(true));
    this.ready = true;
    this.update();
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }
}
if (!customElements.get('media-projet')) customElements.define('media-projet', mediaProjet);