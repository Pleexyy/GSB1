<?php
include("vues/v_sommaire.php");
$idutilisateur = $_SESSION['idutilisateur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr($mois,0,4);
$numMois =substr($mois,4,2);
$action = $_REQUEST['action'];
switch($action) {
	case 'saisirFrais':{
		if($pdo->estPremierFraisMois($idutilisateur,$mois)) {
			$pdo->creeNouvellesLignesFrais($idutilisateur,$mois);
		}
		break;
	}
	case 'validerMajFraisForfait':{
		$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idutilisateur,$mois,$lesFrais);
		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
			include("vues/v_erreurs.php");
		}
	  break;
	}
	case 'validerCreationFrais':{
		$dateFrais = $_REQUEST['dateFrais'];
		$libelle = $_REQUEST['libelle'];
		$montant = $_REQUEST['montant'];
		valideInfosFrais($dateFrais,$libelle,$montant);
		if (nbErreurs() != 0 ){
			include("vues/v_erreurs.php");
		}
		else{
			if($montant > 0){
				$pdo->creeNouveauFraisHorsForfait($idutilisateur,$mois,$libelle,$dateFrais,$montant);
			}
			else {
				ajouterErreur("Les valeurs des frais doivent être numériques");
				include("vues/v_erreurs.php");
			}
		}
		break;
	}
	case 'supprimerFrais':{
		$idFrais = $_REQUEST['idFrais'];
	    $pdo->supprimerFraisHorsForfait($idFrais);
		break;
	}
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idutilisateur,$mois);
$lesFraisForfait= $pdo->getLesFraisForfait($idutilisateur,$mois);
$puissance = $pdo->getFraisKilometriques();

include("vues/v_lf_comptable.php");
include("vues/v_listeFraisHorsForfait.php");
