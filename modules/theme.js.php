// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Cookie from './cookies.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const defaultTheme = 'light';
const supportedThemes = ['auto', 'light', 'dark'];



const Theme = {
  /////////////////////////////////////////
  // Applies the selected theme to the page
  set: (requestedTheme = Theme.get()) => {
    let theme = Theme.resolve(requestedTheme);

    const html = document.documentElement;
    html.classList.remove('light', 'dark');
    html.classList.add(theme);

    // Set meta theme-color here
  
    new Cookie('theme', requestedTheme);
    new Cookie('resolvedTheme', theme);
  },


  ////////////////////////////////////////////////////////////////////////////////
  // Determines which theme to apply — 'auto' if osTheme, 'light' or 'dark' if not
  unresolve: (theme) => {
    return (theme == Theme.osTheme()) ? 'auto' : (supportedThemes.includes(theme)) ? theme : 'auto';
  },


  ///////////////////////////////////////////////////////////////////
  // Determines which theme to save — osTheme is 'auto', theme if not
  resolve: (theme) => {
    return (theme == 'auto') ? Theme.osTheme() : (supportedThemes.includes(theme)) ? theme : defaultTheme;
  },


  /////////////////////////////////////////////////////
  // Determines the preferred theme according to the OS
  osTheme: () => {
    let osTheme;
    if (window.matchMedia('(prefers-color-scheme: dark)').matches)        osTheme = 'dark';
    else if (window.matchMedia('(prefers-color-scheme: light)').matches)  osTheme = 'light';
    return osTheme;
  },


  active: () => {
    return Theme.resolve(Theme.userTheme());
  },

  get: () => {
    // Does not resolve 'auto'
    const theme = Theme.userTheme();
    return (supportedThemes.includes(theme)) ? theme : 'auto';
  },

  userTheme: () => {
    return Cookie.get('theme');
  }
}

export default Theme;