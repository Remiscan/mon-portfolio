<?php
require_once __DIR__.'/../donnees/sections.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';

function generatePagesCSS($sections) {
  $css = "";
  // :root[data-theme="light"]
  $css .= ":root[data-theme=\"light\"] {\n";
    forEach($sections as $s) {
      $css .= "  /* Page $s->id */\n";
      $css .= "  --$s->id-primary-hue: $s->primaryHue;\n";
      $css .= "  --$s->id-accent-hue: $s->accentHue;\n";
      $vals = get_object_vars($s->lightHSL());
      forEach($vals as $key => $val) {
        $correctKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
        $css .= "  --$s->id-$correctKey: $val;\n";
      }
      $css .= "\n";
    }
    $css .= "}\n";
  $css .= "\n";
  // :root[data-theme="dark"]
  $css .= ":root[data-theme=\"dark\"] {\n";
  forEach($sections as $s) {
    $css .= "  /* Page $s->id */\n";
    $css .= "  --$s->id-primary-hue: $s->primaryHue;\n";
    $css .= "  --$s->id-accent-hue: $s->accentHue;\n";
    $vals = get_object_vars($s->darkHSL());
    forEach($vals as $key => $val) {
      $correctKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
      $css .= "  --$s->id-$correctKey: $val;\n";
    }
    $css .= "\n";
  }
  $css .= "}\n";

  $css = "/*" . buildThemesStylesheet($css) . "*/\n\n";
  forEach($sections as $s) {
    $css .= "/* Page $s->id */\n";
    $css .= "body[data-section=\"$s->id\"] {\n";
    $css .= "  --primary-hue: var(--$s->id-primary-hue);\n";
    $css .= "  --accent-hue: var(--$s->id-accent-hue);\n";
    forEach($vals as $key => $val) {
      $correctKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
      $css .= "  --$correctKey: var(--$s->id-$correctKey);\n";
    }
    $css .= "}\n";
    $css .= "\n";
  }
  return $css;
}

file_put_contents(__DIR__.'/../pages/variables-style.css.php', generatePagesCSS($sections));