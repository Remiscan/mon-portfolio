<html style="height: 100%; width: 100%;">
<head>
<meta name="theme-color" content="red">
</head>

<body style="width: 100%; height: 100%;">
<button style="width: 50%; height: 50%;" onclick="document.body.style.backgroundColor = 'hsl(' + Math.round(360 * Math.random()) + ', 50%, 90%)'">Touche !</button>

/*<?php ob_start();?>*/

import { traduire, getString, switchLangage, getLangage } from '../_common/js/test-support.js';
import { Params, recalcOnResize } from './mod_Params.js.php';
import { traduire, getString, switchLangage, getLangage } from '../_common/js/traduction.js';

/*<?php
  $imports = ob_get_clean();
  require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
  var_dump(versionizeImports($imports, __DIR__));
?>*/

<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir.'/php/getStrings.php';

$test = new Textes('mon-portfolio');
echo '<div>';
print_r($test->strings);
echo '<br>';
echo '<br>';
print_r($test->strings['fr']);
echo '<br>';
echo '<br>';
print_r($test->strings['fr']['me-contacter'] != '');echo ',';
print_r($test->strings['en']['me-contacter'] != '');echo ',';
print_r($test->strings['it']['me-contacter'] != '');
echo '<br>';
echo '<br>';
if ($test->strings['fr']['me-contacter'] != '') echo 'yes'; else echo 'no';
if ($test->strings['en']['me-contacter'] != '') echo 'yes'; else echo 'no';
if ($test->strings['it']['me-contacter'] != '') echo 'yes'; else echo 'no';
echo '<br>';
echo '<br>';
echo $test->getString('me-contacter');
echo '<br>';
echo $test->getString('projet-solaire-titre');
echo '<br>';
echo $test->getString('blouille');
echo '</div>';*/