/////////////////////////////////////////
// Constantes et paramètres de la fenêtre
export const Params = {
  breakpointMobile: 620,
  breakpointWide: 1200,

  easingStandard: 'cubic-bezier(0.4, 0.0, 0.2, 1)',
  easingDecelerate: 'cubic-bezier(0.0, 0.0, 0.2, 1)',
  easingAccelerate: 'cubic-bezier(0.4, 0.0, 1, 1)',

  reducedMotion: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
}



//////////////////////
// Promesse setTimeout
export function wait(time) { 
  if (time instanceof Animation)
    return new Promise(resolve => time.addEventListener('finish', resolve));
  else if (typeof time === 'number')
    return new Promise(resolve => setTimeout(resolve, time));
  else return Promise.resolve();
}



/////////////////////////////////
// Simule un click sur un élément
export function simulateClick(elem, x = -1, y = -1) {
	const event = new MouseEvent('click', {
		bubbles: true,
		cancelable: true,
    view: window,
    clientX: x,
    clientY: y
  });
  elem.dispatchEvent(event);
};