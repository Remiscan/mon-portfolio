<?php
$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir.'/php/autochargeClasses.php';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/getStrings.php';
require_once $commonDir.'/php/version.php';

// Définition des variables de données, comme les couleurs
include __DIR__ . '/donnees.php';

// Calcule l'âge d'une date par rapport à aujourd'hui
function age($date = '1993-10-31')
{
  $origine = date_create($date);
  $aujourdhui = date_create(date('Y-m-d'));
  $age = date_diff($origine, $aujourdhui);
  $age = $age->format('%y');
  return $age;
}

// Calcule le temps depuis que je programme
function agepro()
{
  $mod = 2;
  $agepro = age('2006-10-31');
  $agepro = intdiv($agepro, $mod) * $mod;
  return $agepro;
}