﻿<?php

/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb
{
	private static $serveur = 'mysql:host=localhost';
	private static $bdd = 'dbname=gsb';
	private static $user = 'sio';
	private static $mdp = 'slam';
	private static $monPdo;
	private static $monPdoGsb = null;
	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la classe
	 */
	private function __construct()
	{
		PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct()
	{
		PdoGsb::$monPdo = null;
	}
	/**
	 * Fonction statique qui crée l'unique instance de la classe
 
	 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
	 * @return l'unique objet de la classe PdoGsb
	 */
	public  static function getPdoGsb()
	{
		if (PdoGsb::$monPdoGsb == null) {
			PdoGsb::$monPdoGsb = new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;
	}
	/**
	 * Retourne les informations d'un utilisateur
 
	 * @param $login 
	 * @param $mdp
	 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
	 */
	public function getInfosutilisateur($login, $mdp)
	{
		$req = "select utilisateur.id as id, utilisateur.nom as nom, utilisateur.prenom as prenom, utilisateur.role as role
		from utilisateur 
		where utilisateur.login='$login' and utilisateur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

	/**
	 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
	 * concernées par les deux arguments
 
	 * La boucle foreach ne peut être utilisée ici car on procède
	 * à une modification de la structure itérée - transformation du champ date-
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
	 */
	public function getLesFraisHorsForfait($idutilisateur, $mois)
	{
		$req = "select * from lignefraishorsforfait where lignefraishorsforfait.idutilisateur ='$idutilisateur' 
		and lignefraishorsforfait.mois = '$mois' ";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i = 0; $i < $nbLignes; $i++) {
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes;
	}
	/**
	 * Retourne le nombre de justificatif d'un utilisateur pour un mois donné
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @return le nombre entier de justificatifs 
	 */
	public function getNbjustificatifs($idutilisateur, $mois)
	{
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
	/**
	 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
	 * concernées par les deux arguments
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
	 */
	public function getLesFraisForfait($idutilisateur, $mois)
	{
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idutilisateur ='$idutilisateur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	/**
	 * Retourne tous les id de la table FraisForfait
 
	 * @return un tableau associatif 
	 */
	public function getLesIdFrais()
	{
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	/**
	 * Met à jour la table ligneFraisForfait
 
	 * Met à jour la table ligneFraisForfait pour un utilisateur et
	 * un mois donné en enregistrant les nouveaux montants
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
	 * @return un tableau associatif 
	 */
	public function majFraisForfait($idutilisateur, $mois, $lesFrais)
	{
		$lesCles = array_keys($lesFrais);
		foreach ($lesCles as $unIdFrais) {
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idutilisateur = '$idutilisateur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
	}
	/**
	 * met à jour le nombre de justificatifs de la table fichefrais
	 * pour le mois et le utilisateur concerné
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 */
	public function majNbJustificatifs($idutilisateur, $mois, $nbJustificatifs)
	{
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idutilisateur = '$idutilisateur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Teste si un utilisateur possède une fiche de frais pour le mois passé en argument
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @return vrai ou faux 
	 */
	public function estPremierFraisMois($idutilisateur, $mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idutilisateur = '$idutilisateur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if ($laLigne['nblignesfrais'] == 0) {
			$ok = true;
		}
		return $ok;
	}
	/**
	 * Retourne le dernier mois en cours d'un utilisateur
 
	 * @param $idutilisateur 
	 * @return le mois sous la forme aaaamm
	 */
	public function dernierMoisSaisi($idutilisateur)
	{
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idutilisateur = '$idutilisateur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}

	/**
	 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un utilisateur et un mois donnés
 
	 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idetat, crée une nouvelle fiche de frais
	 * avec un idetat à 'CR' et crée les lignes de frais forfait de quantités nulles 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 */
	public function creeNouvellesLignesFrais($idutilisateur, $mois)
	{
		$dernierMois = $this->dernierMoisSaisi($idutilisateur);
		$laDerniereFiche = $this->getLesInfosfichefrais($idutilisateur, $dernierMois);
		if (!empty($laDerniereFiche['idetat']) == 'CR') {
			$this->majEtatfichefrais($idutilisateur, $dernierMois, 'CL');
		} else {
		} // rajout
		$req = "insert into fichefrais(idutilisateur,mois,nbJustificatifs,montantValide,dateModif,idetat) 
		values('$idutilisateur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach ($lesIdFrais as $uneLigneIdFrais) {
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idutilisateur,mois,idFraisForfait,quantite) 
			values('$idutilisateur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		}
	}
	/**
	 * Crée un nouveau frais hors forfait pour un utilisateur un mois donné
	 * à partir des informations fournies en paramètre
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @param $libelle : le libelle du frais
	 * @param $date : la date du frais au format français jj//mm/aaaa
	 * @param $montant : le montant
	 */
	public function creeNouveauFraisHorsForfait($idutilisateur, $mois, $libelle, $date, $montant)
	{
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values(null,'$idutilisateur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Supprime le frais hors forfait dont l'id est passé en argument
 
	 * @param $idFrais 
	 */
	public function supprimerFraisHorsForfait($idFrais)
	{
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id = '$idFrais'";
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Retourne les mois pour lesquel un utilisateur a une fiche de frais
 
	 * @param $idutilisateur 
	 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
	 */
	public function getLesMoisDisponibles($idutilisateur)
	{
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idutilisateur ='$idutilisateur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois = array();
		$laLigne = $res->fetch();
		while ($laLigne != null) {
			$mois = $laLigne['mois'];
			$numAnnee = substr($mois, 0, 4);
			$numMois = substr($mois, 4, 2);
			$lesMois["$mois"] = array(
				"mois" => "$mois",
				"numAnnee"  => "$numAnnee",
				"numMois"  => "$numMois"
			);
			$laLigne = $res->fetch();
		}
		return $lesMois;
	}
	/**
	 * Retourne les informations d'une fiche de frais d'un utilisateur pour un mois donné
 
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
	 */
	public function getLesInfosfichefrais($idutilisateur, $mois)
	{
		$req = "select fichefrais.idetat as idetat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from fichefrais inner join etat on fichefrais.idetat = etat.id 
			where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
	/**
	 * Modifie l'état et la date de modification d'une fiche de frais
 
	 * Modifie le champ idetat et met la date de modif à aujourd'hui
	 * @param $idutilisateur 
	 * @param $mois sous la forme aaaamm
	 */

	public function majEtatfichefrais($idutilisateur, $mois, $etat)
	{
		$req = "update fichefrais set idetat = '$etat', dateModif = now() 
		where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

	/**
	 * Récupère les frais kilométriques
	 */
	public function getFraisKilometriques()
	{
		$req = "select * from fraiskilometriques";
		$res = PdoGsb::$monPdo->query($req);
		$unePuissance = $res->fetchAll();
		return $unePuissance;
	}

	/**
	 * Récupère le montant associé à la puissance du véhicule
	 */
	public function getMontant($libelle)
	{
		$req = "select montant from fraiskilometriques where libelle = '$libelle'";
		$res = PdoGsb::$monPdo->query($req);
		$unMontant = $res->fetch();
		return $unMontant;
	}

	public function getNom($idutilisateur)
	{
		$req = "select nom from utilisateur where id='$idutilisateur'";
		$res = PdoGsb::$monPdo->query($req);
		$unNom = $res->fetch();
		return $unNom;
	}

	public function ajouterJustificatif($idutilisateur, $mois)
	{
		$req = "update fichefrais set nbjustificatifs = nbjustificatifs + 1 where idutilisateur='$idutilisateur' and mois='$mois'";
		$res = PdoGsb::$monPdo->query($req);
	}

	public function supprimerJustificatif($idutilisateur, $mois)
	{
		$req = "update fichefrais set nbjustificatifs = nbjustificatifs - 1 where idutilisateur='$idutilisateur' and mois='$mois'";
		$res = PdoGsb::$monPdo->query($req);
	}

	public function getJustificatif($idutilisateur, $mois, $idfrais)
	{
		$req = "select justificatif from lignefraishorsforfait where idutilisateur='$idutilisateur' and mois='$mois' and id='$idfrais' and justificatif=1";
		$res = PdoGsb::$monPdo->query($req);
		$unJustificatif = $res->fetch();
		return $unJustificatif;
	}

	public function getInfosFichesValidees()
	{
		$req = "select distinct fichefrais.idutilisateur as id, utilisateur.nom as nom, fichefrais.datemodif as datemodif 
				from utilisateur, fichefrais
				where utilisateur.id = fichefrais.idutilisateur";
		$res = PdoGsb::$monPdo->query($req);
		$lesInfos = $res->fetchAll();
		return $lesInfos;
	}

	public function getDate($mois)
	{
		$req = "select * from lignefraishorsforfait where mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$lesInfos = $res->fetch();
		return $lesInfos;
	}

	/* renvoie la somme des prix des nuitées pour l'utilisateur en question */
	public function getPrixNuitee($idutilisateur)
	{
		$req = "select (fraisforfait.montant * lignefraisforfait.quantite) as montantNuitee
				from fraisforfait, lignefraisforfait
				WHERE fraisforfait.id = 'NUI'
				AND lignefraisforfait.idfraisforfait = 'NUI'
				AND lignefraisforfait.idutilisateur = '$idutilisateur';";
		$res = PdoGsb::$monPdo->query($req);
		$prixnuitee = $res->fetch();
		return $prixnuitee;
	}

	/* renvoie la somme des prix des repas du midi pour l'utilisateur en question */
	public function getPrixRepasMidi($idutilisateur)
	{
		$req = "select (fraisforfait.montant * lignefraisforfait.quantite) as montantRep
				from fraisforfait, lignefraisforfait
				WHERE fraisforfait.id = 'REP'
				AND lignefraisforfait.idfraisforfait = 'REP'
				AND lignefraisforfait.idutilisateur = '$idutilisateur';";
		$res = PdoGsb::$monPdo->query($req);
		$prixrepasmidi = $res->fetch();
		return $prixrepasmidi;
	}

	/* renvoie la somme des prix des étapes pour l'utilisateur en question */
	public function getPrixEtape($idutilisateur)
	{
		$req = "select (fraisforfait.montant * lignefraisforfait.quantite) as montantEtape
				from fraisforfait, lignefraisforfait
				WHERE fraisforfait.id = 'ETP'
				AND lignefraisforfait.idfraisforfait = 'ETP'
				AND lignefraisforfait.idutilisateur = '$idutilisateur';";
		$res = PdoGsb::$monPdo->query($req);
		$prixetape = $res->fetch();
		return $prixetape;
	}

	/* renvoie la somme des prix des kilomètres pour l'utilisateur en question */
	public function getLesKm($idutilisateur)
	{
		$req = "select quantite as montantKm
				from lignefraisforfait
				where idutilisateur = '$idutilisateur'
				AND idfraisforfait = 'KM';";
		$res = PdoGsb::$monPdo->query($req);
		$prixkm = $res->fetch();
		return $prixkm;
	}

	/* renvoie la somme des prix des kilomètres pour l'utilisateur en question */
	public function getLaPuissance($idutilisateur)
	{
		$req = "select round(quantite,2) as montantPuissance
					from lignefraisforfait
					where idutilisateur = '$idutilisateur'
					AND idfraisforfait = 'PUI';";
		$res = PdoGsb::$monPdo->query($req);
		$lapuissance = $res->fetch();
		return $lapuissance;
	}

	public function getEtat($idutilisateur, $mois)
	{
		$req = "select libelle
				from fichefrais f, etat e
				where f.idetat = e.id
				AND idutilisateur = '$idutilisateur'
				AND mois = '$mois';";
		$res = PdoGsb::$monPdo->query($req);
		$etat = $res->fetch();
		return $etat;
	}


	public function getValeurEtat($idutilisateur, $mois)
	{
		$req = "select idetat
				from fichefrais
				WHERE idutilisateur = '$idutilisateur'
				AND mois = '$mois';";
		$res = PdoGsb::$monPdo->query($req);
		$valeuretat = $res->fetch();
		return $valeuretat;
	}

	public function setEtat($idutilisateur, $valeuretat)
	{
		$req = "update fichefrais
				set idetat = 'VA'
				where idetat = '$valeuretat'
				and idutilisateur = '$idutilisateur';";
		PdoGsb::$monPdo->query($req);
	}

	public function getLesFiches()
	{
		$req = "select distinct (login) as login
				from utilisateur, fichefrais
				where utilisateur.id = fichefrais.idutilisateur
				and role= 'visiteur';";
		$res = PdoGsb::$monPdo->query($req);
		$lesfiches = $res->fetchAll();
		return $lesfiches;
	}


	public function getInfosFichesValidees2($idutilisateur)
	{
		$req = "select distinct fichefrais.idutilisateur as id, utilisateur.nom as nom, fichefrais.datemodif as datemodif 
				from utilisateur, fichefrais
				where utilisateur.id = fichefrais.idutilisateur
				and idutilisateur = '$idutilisateur'
				and role='visiteur';";
		$res = PdoGsb::$monPdo->query($req);
		$lesInfos = $res->fetchAll();
		return $lesInfos;
	}

	public function getIdVisiteur($login)
	{
		$req = "select id 
				from utilisateur 
				where login = '$login';";
		$res = PdoGsb::$monPdo->query($req);
		$idVisiteur = $res->fetch();
		return $idVisiteur;
	}

	public function getNomVisiteur($id)
	{
		$req = "select prenom, nom 
				from utilisateur 
				where id = '$id';";
		$res = PdoGsb::$monPdo->query($req);
		$idVisiteur = $res->fetch();
		return $idVisiteur;
	}

	public function setEtatRefuse($idutilisateur)
	{
		$req = "update fichefrais
				set idetat = 'RE'
				where idetat = 'CR'
				and idutilisateur = '$idutilisateur';";
		PdoGsb::$monPdo->query($req);
	}

	public function getLibelleRefuse($idutilisateur, $mois)
	{
		$req = "select libelle from lignefraishorsforfait
				where idutilisateur = '$idutilisateur'
				and mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$lesInfos = $res->fetchAll();
		return $lesInfos;
	}

	public function setEtatLibelleRefuse($libelle)
	{
		$req = "update lignefraishorsforfait
				set libelle = CONCAT('REFUSE','$libelle')
				where libelle = '$libelle'";
		PdoGsb::$monPdo->query($req);
	}

	public function setMontantValide($montantValide,$mois) {
		$req = "update fichefrais
		set montantvalide = $montantValide
		where mois = $mois";
		PdoGsb::$monPdo->query($req);
	}
}
