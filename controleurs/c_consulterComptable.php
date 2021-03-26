<?php
include("vues/v_sommaire.php");
$idutilisateur = $_SESSION['idutilisateur'];
$mois = getMois(date("d/m/Y"));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$leMois = $numAnnee . $numMois;
$action = $_REQUEST['action'];

if (isset($_POST["value"])) { // Si le formulaire de suivi de remboursement des frais est rempli
	$idutilisateurrecherche = $_POST["value"]; // variable de session contenant id du visiteur choisi
}

switch ($action) {
	case 'saisirUtilisateur': {
			$infos = $pdo->getInfosFichesValidees();
			include("vues/v_consult_comptable.php");
			break;
		}
	case 'validationFrais': {
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idutilisateurrecherche, $mois);
			$prixnuitee = $pdo->getPrixNuitee($idutilisateurrecherche);
			$prixrepasmidi = $pdo->getPrixRepasMidi($idutilisateurrecherche);
			$prixetape = $pdo->getPrixEtape($idutilisateurrecherche);
			$prixkm = $pdo->getLesKm($idutilisateurrecherche);
			$lapuissance = $pdo->getLaPuissance($idutilisateurrecherche);
			$montantValide = $prixrepasmidi[0] + $prixnuitee[0] + $prixetape[0] + $prixkm[0] * $lapuissance[0];
			foreach($lesFraisHorsForfait as $unFraisHorsForfait) {
				$montantValide += $unFraisHorsForfait['montant'];
			}
			$etat = $pdo->getEtat($idutilisateurrecherche, $leMois); // état de la fiche de frais du mois choisi
			$_SESSION['idutilisateurrecherche'] = $idutilisateurrecherche; // variable de session du visiteur choisi pour y accéder lors de la validation de la fiche
			$_SESSION['montantvalide'] = $montantValide; // passage également du montant total de la fiche
			include("vues/v_validationFrais.php");
			break;
		}
	case 'validerEtat': {
			$valeuretat = $pdo->getValeurEtat($_SESSION['idutilisateurrecherche'], $mois); // id de la valeur actuelle de la fiche
			if ($valeuretat[0] != "RE" && $valeuretat[0] != "VA") { // si la fiche n'est pas validé ou refusé
				$etatFiche = "valider"; 
				$etatFrais = $_POST['choix']; // validé ou refusé
				if ($etatFrais == "refuser") {
					$etatFiche = "refuser";
				}
				for ($i = 0; $i < $_SESSION['compteur']; $i++) { // parocurs les frais hors forfaits
					$etatFrais = $_POST['etat' . $i]; // validé ou refusé
					if ($etatFrais == "refuser") {
						$etatFiche = "refuser";
						$libelleARefuse = $pdo->getLibelleRefuse($_SESSION['idutilisateurrecherche'], $leMois); // récupère le libelle à modifier
						$pdo->setEtatLibelleRefuse($libelleARefuse[$i][0]); // insère REFUSE devant chaque libelle des frais hors forfait refusés
					} else {
						$pdo->ajouterJustificatif($_SESSION['idutilisateurrecherche'], $leMois); // si le frais hors forfait est validé le nb de justificatif est incrémenté
					}
				}
				if ($etatFiche == "refuser") { // si la fiche est refusé
					$pdo->setEtatRefuse($_SESSION['idutilisateurrecherche']); // l'état de la fiche devient refusé 
				} else { // sinon
					$setetat = $pdo->setEtat($_SESSION['idutilisateurrecherche'], $valeuretat[0]); // l'état de la fiche devient validé
					$pdo->setMontantValide($_SESSION['montantvalide'],$leMois); // ajoute le montant validé de toute la ligne
				}
				$infos = $pdo->getInfosFichesValidees();
				include("vues/v_consult_comptable.php");
			} else {
				echo "<script>alert(\"Fiche déjà validée\")</script>";
			}
			break;
		}
	case 'suivreFiche': {
			$fiche = $pdo->getLesFiches();
			include("vues/v_suivreFrais.php");
			break;
		}
	case 'listeFiches': {
			$idutilisateurrecherche2 = $pdo->getIdVisiteur($_POST['values']); // récupère l'id du visiteur sélectionné
			$lesInfos = $pdo->getInfosFichesValidees2($idutilisateurrecherche2[0]);
			include("vues/v_listeFiches.php");
			break;
		}
	case 'voirPdf': {
			$datemodif = $_POST['infoDate'];
			$idUser = $_POST['infoId'];
			$nom = $pdo->getNomVisiteur($idUser);
			include('listePdf.php');
			break;
		}
}
