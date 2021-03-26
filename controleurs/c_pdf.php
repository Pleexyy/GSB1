<?php
$idutilisateur = $_SESSION['idutilisateur'];

$lesMois = $pdo->getLesMoisDisponibles($idutilisateur);
// Afin de sélectionner par défaut le dernier mois dans la zone de liste
// on demande toutes les clés, et on prend la première,
// les mois étant triés décroissants
$lesCles = array_keys($lesMois);
$moisASelectionner = $lesCles[0];

$leMois = $_SESSION['leMois'];

$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idutilisateur, $leMois);
$lesFraisForfait = $pdo->getLesFraisForfait($idutilisateur, $leMois);
$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idutilisateur, $leMois);
$numAnnee = substr($leMois, 0, 4);
$numMois = substr($leMois, 4, 2);
$libEtat = $lesInfosFicheFrais['libEtat'];
$montantValide = $lesInfosFicheFrais['montantValide'];
$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
$dateModif = $lesInfosFicheFrais['dateModif'];
$dateModif = dateAnglaisVersFrancais($dateModif);
include("vues/v_listeMois.php");
include("vues/v_pdf.php");
