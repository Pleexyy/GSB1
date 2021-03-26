<?php
if (!isset($_REQUEST['action'])) {
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch ($action) {
	case 'demandeConnexion': {
			include("vues/v_connexion.php");
			break;
		}
	case 'valideConnexion': {
			$login = $_REQUEST['login'];
			$mdp = $_REQUEST['mdp'];
			$utilisateur = $pdo->getInfosutilisateur($login, hashPassword($mdp));
			if (!is_array($utilisateur)) {
				ajouterErreur("Login ou mot de passe incorrect");
				include("vues/v_erreurs.php");
				include("vues/v_connexion.php");
			} else {
				$id = $utilisateur['id'];
				$nom =  $utilisateur['nom'];
				$prenom = $utilisateur['prenom'];
				$role = $utilisateur['role'];
				connecter($id, $nom, $prenom, $role); ?>
				<div class="alert alert-success" role="alert">
					<strong>Connecté !</strong> Vous etes connecté sous le compte <?php echo $login; ?>
				</div> <?php
						include("vues/v_sommaire.php");
					}
					break;
				}
			case 'deconnexion': {
					deconnecter();
				}
			default: {
					include("vues/v_connexion.php");
					break;
				}
		}
