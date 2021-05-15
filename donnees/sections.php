<?php
require_once __DIR__.'/../modules/class_Couleur.php';

$sectionsData = array(
  'accueil' => array(
    'primary-hue' => 300
  ),
  'bio' => array(
    'primary-hue' => 160
  ),
  'projets' => array(
    'primary-hue' => 230
  ),
  'articles' => array(
    'primary-hue' => 10
  ),
  'contact' => array(
    'primary-hue' => 0
  )
);

class Section {
  public $primaryHue;
  public $accentHue;
  public $bgColor;
  public $secondaryBgColor;
  public $linkColor;
  public $linkUnderlineColor;
  public $linkBrightColor;
  public $secondaryTextColor;

  function __construct($sectionArray, $id) {
    $this->id = $id;
    $this->primaryHue = $sectionArray['primary-hue'];
    $this->accentHue = ($this->primaryHue + 180) % 360;
    $s = ($id == 'contact') ? '0%' : '25%';
    $this->bgColor = array(
      'dark' => (new Couleur("hsl($this->primaryHue, $s, 10%)"))->betterContrast('black', 1.1757, 1),
      'light' => (new Couleur("hsl($this->primaryHue, $s, 90%)"))->betterContrast('white', 1.1757, 1),
    );
    $this->secondaryBgColor = array(
      'dark' => $this->bgColor['dark']->change('l', '+5%')->change('s', '+10%'),
      'light' => $this->bgColor['light']->change('l', '-5%')->change('s', '+10%')
    );
    $s = ($id == 'contact') ? '0%' : '50%';
    $this->linkColor = array(
      'dark' => (new Couleur("hsl($this->accentHue, $s, 80%)"))->betterContrast($this->bgColor['dark'], 12.6, 1),
      'light' => (new Couleur("hsl($this->accentHue, $s, 20%)"))->betterContrast($this->bgColor['light'], 12.6, 1),
    );
    $this->linkUnderlineColor = array(
      'dark' => $this->linkColor['dark']->replace('a', .25),
      'light' => $this->linkColor['light']->replace('a', .25),
    );
    $s = ($id == 'contact') ? '0%' : '60%';
    $this->linkBrightColor = array(
      'dark' => (new Couleur("hsl($this->accentHue, $s, 75%)"))->betterContrast($this->bgColor['dark'], 11.973, 1),
      'light' => (new Couleur("hsl($this->accentHue, $s, 25%)"))->betterContrast($this->bgColor['light'], 11.973, 1),
    );
    $s = ($id == 'contact') ? '0%' : '25%';
    $this->secondaryTextColor = array(
      'dark' => (new Couleur("hsl($this->primaryHue, $s, 70%)"))->betterContrast($this->bgColor['dark'], 7.8147, 1),
      'light' => (new Couleur("hsl($this->primaryHue, $s, 30%)"))->betterContrast($this->bgColor['light'], 7.8147, 1),
    );
  }

  public function darkHSL() {
    $dark = new StdClass();
    $keys = array_keys(get_object_vars($this));
    forEach($keys as $key) {
      try {
        if ($key == 'id') throw new Error('osef');
        $dark->{$key} = $this->{$key}['dark']->hsl();
        if ($this->id == 'contact') {
          $dark->{$key} = $this->{$key}['dark']->replace('s', '0%')->hsl();
        }
      }
      catch (Error $error) {}
    }
    return $dark;
  }

  public function lightHSL() {
    $dark = new StdClass();
    $keys = array_keys(get_object_vars($this));
    forEach($keys as $key) {
      try {
        if ($key == 'id') throw new Error('osef');
        $dark->{$key} = $this->{$key}['light']->hsl();
        if ($this->id == 'contact') {
          $dark->{$key} = $this->{$key}['light']->replace('s', '0%')->hsl();
        }
      }
      catch (Error $error) {}
    }
    return $dark;
  }
}

$sections = array();
forEach($sectionsData as $id => $data) {
  $obj = new Section($data, $id);
  $sections[$id] = $obj;
}