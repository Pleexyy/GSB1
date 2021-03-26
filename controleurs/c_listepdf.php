<?php
// on récupère le mois et l'année uniquement
$numAnnee = substr($datemodif, 0, 4);
$numMois = substr($datemodif, 5, 2);

// on stock le résultat dans une variable
$mois = $numAnnee . $numMois;

$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUser, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idUser, $mois);
$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUser, $mois);

$libEtat = $lesInfosFicheFrais['libEtat'];
$montantValide = $lesInfosFicheFrais['montantValide'];
$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

include("vues/v_listeMois.php");
include("vues/v_listepdf.php");
