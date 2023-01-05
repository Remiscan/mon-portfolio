<?php
class Competence {
  public $nom;
  public $annee;
  public $couleur;
  public $colonne;
  public $exemples = array();
  public $mini = false;

  function __construct($nom, $annee, $couleur = 'rgb(255, 255, 255)', $colonne = 1, $exemples = '') {
    $this->nom = $nom;
    $this->annee = $annee;
    $this->colonne = $colonne;

    if (is_a($couleur, 'Couleur')) $this->couleur = $couleur;
    else                           $this->couleur = new Couleur($couleur);

    if ($exemples != '') $this->exemples = explode(',', $exemples);
    else                 $this->mini = true;
  }
}