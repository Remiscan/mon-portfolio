<?php
$source = $_POST['source_mail'];
$expediteur = filter_var($_POST['adresse_mail'], FILTER_VALIDATE_EMAIL);
$destinataire = base64_decode('cmVtaS5zY2FuZGVsbGErcG9pbnRmckBnbWFpbC5jb20=');
$objet = mb_encode_mimeheader('Contact via remiscan.fr', 'UTF-8');
$message = $_POST['message_mail'];
$headers = array(
  'From' => $expediteur,
  'Reply-To' => $expediteur,
  'X-Mailer' => 'PHP/' . phpversion(),
  'Content-Type' => 'text/plain; charset=utf-8'
);

if ($expediteur && $source === '') $envoi_reussi = mail($destinataire, $objet, $message, $headers);
else                               $envoi_reussi = false;

////////////////////////////////////
// On passe le résultat à javascript
header('Content-Type: application/json');
echo json_encode(array(
  'envoi_reussi' => $envoi_reussi,
), JSON_PRETTY_PRINT);