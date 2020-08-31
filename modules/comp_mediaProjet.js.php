const templateImg = document.createElement('template');
templateImg.innerHTML = `
<div style="">
  <img alt="" loading="lazy">
</div>
`;

const templateVideo = document.createElement('template');
templateVideo.innerHTML = `
<div style="">
  <video loop muted controls>
    <source type="video/mp4">
  </video>
</div>
`;

class mediaProjet extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ['src', 'width', 'height', 'phone'];
  }

  update(attributes = mediaProjet.observedAttributes) {
    if (!this.ready) return;
    const div = this.querySelector('div');
    const img = this.querySelector('img') || this.querySelector('video');
    const source = this.querySelector('img') || this.querySelector('source');

    src: {
      if (!attributes.includes('src')) break src;
      const src = this.getAttribute('src');
      source.setAttribute('src', src);
    }

    width: {
      if (!attributes.includes('width')) break width;
      const width = this.getAttribute('width');
      div.style.setProperty('--w', width);
      img.setAttribute('width', width);
    }

    height: {
      if (!attributes.includes('height')) break height;
      const height = this.getAttribute('height');
      div.style.setProperty('--h', height);
      img.setAttribute('height', height);
    }

    phone: {
      if (!attributes.includes('phone')) break phone;
      const phone = this.getAttribute('phone') != null;
      if (phone) div.style.setProperty('--largeur-max', '20rem');
      else       div.style.removeProperty('--largeur-max');
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