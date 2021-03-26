<?php
include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idutilisateur = $_SESSION['idutilisateur'];
switch ($action) {
	case 'selectionnerMois': {
			$lesMois = $pdo->getLesMoisDisponibles($idutilisateur);
			// Afin de sélectionner par défaut le dernier mois dans la zone de liste
			// on demande toutes les clés, et on prend la première,
			// les mois étant triés décroissants
			$lesCles = array_keys($lesMois);
			$moisASelectionner = $lesCles[0];
			include("vues/v_listeMois.php");
			break;
		}
	case 'voirEtatFrais': {
			$leMois = $_REQUEST['lstMois'];
			$_SESSION['leMois'] = $leMois;
			$lesMois = $pdo->getLesMoisDisponibles($idutilisateur);
			$moisASelectionner = $leMois;
			include("vues/v_listeMois.php");
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idutilisateur, $leMois);
			$lesFraisForfait = $pdo->getLesFraisForfait($idutilisateur, $leMois);
			$puissance = $pdo->getFraisKilometriques();
			$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idutilisateur, $leMois);
			$numAnnee = substr($leMois, 0, 4);
			$numMois = substr($leMois, 4, 2);
			$libEtat = $lesInfosFicheFrais['libEtat'];
			$montantValide = $lesInfosFicheFrais['montantValide'];
			$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
			$dateModif =  $lesInfosFicheFrais['dateModif'];
			$dateModif =  dateAnglaisVersFrancais($dateModif);

			include("vues/v_etatFrais.php");
		}
}
