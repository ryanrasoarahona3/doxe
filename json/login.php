<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');
//session_destroy();

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Requête Ajax uniquement';
  trigger_error($user_error, E_USER_ERROR);
}
 
$retour = array();
$retour['login']= '';
$retour['message']= '';

$reqLogin = $connect->prepare("SELECT * FROM personnes WHERE (delegue_habilite = 1 OR siege_habilite = 1  ) AND personnes.etat = 1 AND courriel = :courriel AND mdp = :mdp;");
$reqLogin->bindValue(':mdp',md5($_POST['mdp']), PDO::PARAM_STR);  
$reqLogin->bindValue(':courriel', $_POST['courriel'], PDO::PARAM_STR); 

if ( (!empty($_POST['mdp'])) && (!empty($_POST['courriel'])) ) {
	try {
		$reqLogin->execute();		
		if ($enregistrement = $reqLogin->fetch(PDO::FETCH_OBJ) ) {
			$retour['login'] = 1;
			
			$perso = new personne($enregistrement->id);
			
			$_SESSION['utilisateur']['id'] = $perso->id_personne;
			$_SESSION['utilisateur']['nom'] = $perso->nom;
			$_SESSION['utilisateur']['prenom'] = $perso->prenom;
			$_SESSION['utilisateur']['courriel'] = $perso->courriel;
			
			$_SESSION['utilisateur']['statut'] = $perso->delegue_statut;
			$_SESSION['utilisateur']['type'] = $perso->delegue_type;
			$_SESSION['utilisateur']['adjoint'] = $perso->delegue_adjoint;
			$_SESSION['utilisateur']['habilite'] = $perso->delegue_habilite;
			
			$_SESSION['utilisateur']['droit']  = 'gestion';
			
			if ($perso->siege_habilite > 0) {
				$_SESSION['utilisateur']['siege'] = 1;
			} else {
				$_SESSION['utilisateur']['siege'] = 0;
				$_SESSION['utilisateur']['departements'] = $perso->delegue_tous_departements;
				$_SESSION['utilisateur']['regions'] = $perso->delegue_regions;
			}
		
			
		} else {
			$retour['login'] = 0;
			$retour['message']="Erreur de connexion <br><br>";
		}
	} catch( Exception $e ) {
		$retour['message'] = "Erreur de connexion : ". $e->getMessage();
	}
}

  
if (!empty($retour)) {
	//$json = json_encode($retour, JSON_FORCE_OBJECT);
	$json = json_encode($retour); // OVH
	print $json;
} else print json_encode('oups...');
?>