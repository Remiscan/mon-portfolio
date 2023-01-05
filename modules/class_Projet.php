<?php
class Projet {
  public $id;
  public $titre;
  public $lien;
  public $couleur;
  public $image_pc;
  public $image_phone;
  public $image_preview;
  public $version;
  public $featured;

  function __construct($id, $titre, $couleur, $lien = '', $featured = false) {
    $this->titre = $titre;
    $this->couleur = $couleur;

    if ($id == '') {
      $id = htmlentities($titre, ENT_NOQUOTES, 'utf-8');
      $id = strtolower($id);
      $id = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $id);
      $id = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $id);
      $id = preg_replace('#&[^;]+;#', '', $id);
      $id = str_replace(' ', '', $id);
    }
    $this->id = $id;

    $image_preview = 'projets/' . $this->id.'/preview.png';
    if (file_exists($image_preview)) $this->image_preview = '';
    else                             $this->image_preview = '-phone';

    $this->lien = $lien;
    $this->featured = $featured;
  }

  // Classe les projets en plaçant ceux ayant featured=true en dernier
  public static function sortByFeatured($a, $b) {
    if ($a->featured && !$b->featured)      return 1;
    else if (!$a->featured && $b->featured) return -1;
    else                                    return 0;
  }
}