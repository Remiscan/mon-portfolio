const templateImg = document.createElement('template');
templateImg.innerHTML = `
<img alt="" loading="lazy">
`;

const templateVideo = document.createElement('template');
templateVideo.innerHTML = `
<video autoplay muted loop playsinline controls data-lazy="true">
  <source type="video/mp4">
</video>
`;

const videoObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const sources = entry.target.querySelectorAll('source');
      for (const source of sources) {
        source.src = source.getAttribute('data-src');
      }

      entry.target.load();
      entry.target.removeAttribute('data-lazy');
      videoObserver.unobserve(entry.target);
    }
  });
});

const sheet = new CSSStyleSheet();
sheet.replaceSync(/*css*/`
  :host {
    align-self: center;
    display: grid;
    width: calc(var(--w) * 1px);
    max-width: var(--largeur-max, 100%);
    aspect-ratio: calc(var(--w) / var(--h));
    background-color: rgba(0, 0, 0, .3);
  }

  :host([phone]) {
    --largeur-max: 420px;
  }

  img, video {
    grid-row: 1 / -1;
    grid-column: 1 / -1;
    object-fit: contain;
    width: 100%;
    height: auto;
  }

  @media screen and (max-width: 620px) {
    :host {
      max-width: 100%;
    }
  }
`);

class mediaProjet extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.shadow.adoptedStyleSheets = [sheet];

    const type = this.getAttribute('type');
    const template = type === 'video' ? templateVideo : templateImg;
    this.shadow.appendChild(template.content.cloneNode(true));
  }


  get aspectRatio() {
    const width = this.getAttribute('width');
    const height = this.getAttribute('height');
    if (!width || !height) return 1;
    return width / height;
  }


  connectedCallback() {
    const video = this.shadow.querySelector('video');
    if (video && video.getAttribute('data-lazy') === 'true') {
      videoObserver.observe(video);
    }
  }


  disconnectedCallback() {
    const video = this.shadow.querySelector('video');
    if (video) videoObserver.unobserve(video);
  }


  static get observedAttributes() {
    return ['src', 'width', 'height', 'alt-text'];
  }


  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;

    const type = this.getAttribute('type');
    const media = type === 'video' ? this.shadow.querySelector('video') : this.shadow.querySelector('img');

    switch (name) {
      case 'src': {
        const src = this.getAttribute('src');
        if (type === 'video') {
          const source = this.shadow.querySelector('source');
          source.setAttribute('data-src', src);
          videoObserver.observe(media);
        } else {
          media.setAttribute('src', src);
        }
      } break;

      case 'alt': {
        const type = this.getAttribute('type');
        const altText = this.getAttribute('alt-text');
        if (type === 'image' && altText != null) {
          media.setAttribute('alt', altText);
        }
      } break;

      case 'width': {
        this.style.setProperty('--w', newValue);
        media.setAttribute('width', newValue);
        if (this.aspectRatio < 5 / 6) this.setAttribute('phone', 'true');
        else                            this.removeAttribute('phone');
      } break;

      case 'height': {
        this.style.setProperty('--h', newValue);
        media.setAttribute('height', newValue);
        if (this.aspectRatio < 5 / 6) this.setAttribute('phone', 'true');
        else                            this.removeAttribute('phone');
      } break;
    }
  }
}
if (!customElements.get('media-projet')) customElements.define('media-projet', mediaProjet);