<?php
require_once __DIR__.'/../modules/class_Couleur.php';

$PROJETS = array(
  'solaire' => array(
    'id' => 'solaire',
    'titre' => 'Solaire',
    'url' => 'https://remiscan.fr/solaire/',
    'featured' => true,
    'couleur' => new Couleur('#211216'),
  ),
  'colori' => array(
    'id' => 'colori',
    'titre' => 'Colori',
    'url' => 'https://remiscan.fr/colori/',
    'featured' => true,
    'couleur' => new Couleur('aquamarine'),
  ),
  'remidex' => array(
    'id' => 'remidex',
    'titre' => 'Rémidex',
    'url' => 'https://remiscan.fr/remidex/',
    'featured' => false,
    'couleur' => new Couleur('rgb(63, 81, 181)'),
  ),
  'csswitch' => array(
    'id' => 'csswitch',
    'titre' => 'CSSwitch',
    'url' => 'https://remiscan.fr/csswitch/',
    'featured' => false,
    'couleur' => new Couleur('hsl(200, 0%, 40%)'),
  )
);