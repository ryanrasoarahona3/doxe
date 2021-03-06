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
 
$action = $_POST['action'];


// Envoyer plusieurs fichiers attestations
if ($action == "password") {
	
	
	$erreur = false;
	$message_erreur ='';
	
	
	if (!isset($_POST['type'])) {
		$erreur = true;
		$message_erreur .='Erreur, formulaire incomplet.<br>';
	}
	
	if (!isset($_POST['id'])) {
		$erreur = true;
		$message_erreur .='Erreur, formulaire incomplet.<br>';
	}
	
	
	
	if (!$erreur) {

		if ($_POST['type'] == 'personnes') {
			$data = new personne ($_POST['id']);
		} else if ($_POST['type'] == 'associations') {
			$data = new personne ($_POST['id']);
		}
		$mdp = genererMdp(6);
		$data->mdp = $mdp;
		$data->sauveMdp();
		
		$email = new email();
		$email->to ( $data->courriel , $data->prenom,' ',$data->nom );
		$email->sujet = 'Cercle National des Bénévoles - Nouveau mot de passe';
		$email->message = $_POST['message'];
		$email->message .=  '<br><br>Votre nouveau mot de passe est : <strong>'.$mdp.'</strong><br>
		<br>Vous pouvez vous connecter sur votre compte à cette adresse : <a href="http://www.cercle-benevoles.fr/mon_compte">http://www.cercle-benevoles.fr/mon_compte</a><br>
		Le Conseil d’administration.';
		$email->envoyer();
							
	   	$retour['message'] = "Votre message à bien été envoyé.";
	   	$retour['etat'] = true;
	
	} else {
		 $retour['message'] = $message_erreur;
		 $retour['etat'] = false;
	}
	
}

/*

// Envoyer un fichier attestation
if ($action == "fichier") {
	
	
	// Vérification des erreurs
	
	$erreur = false;
	$message_erreur ='';
	
	$type = explode('_',$_POST['type']);
	
	if ($type[0] =="personnes") {
		
		// Recherche de l'association
		if ($_POST['type'] == 'personnes_attestation') {
			if ($_POST['president']==1) {
		
			// Récupération de l'association
			
				$detail = explode('_',$_POST['fichier']);
				$asso = new association ($detail[2]);
				
			}
		} 
		// Amis, donc récupération de l'association
		else if ($_POST['type'] == 'personnes_amis') {
			$asso = new association (ID_LAF);
		}
		
		$asso->conseilAdministration();
		$president = $asso->presidents[$detail[1]];

		if (!filter_var($president['courriel'], FILTER_VALIDATE_EMAIL))  {
			$erreur = true;
			$message_erreur .= 'Courriel du président invalide.<br/>';
		} else $destinataires[] = $president['courriel'];
		
	} else if ($type[0] =="associations"){
		
		if ((!empty($_POST['president'])) && (!filter_var($_POST['president'], FILTER_VALIDATE_EMAIL)) ) {
			$erreur = true;
			$message_erreur .= 'Courriel du président invalide.<br/>';
		} else $destinataires[] = $president['courriel'];
	}
	
	// Autre
	
	if ((!empty($_POST['delegue'])) && (!filter_var($_POST['delegue'], FILTER_VALIDATE_EMAIL)) ) {
		$erreur = true;
		$message_erreur .= 'Courriel du délégué invalide.<br/>';
	}

	
	if (!empty($_POST['destinataire'])) {
		$destinataires = explode(',',$_POST['destinataire']);
	
		foreach ($destinataires as $val) {
			if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
				$erreur = true;
				$message_erreur .= 'Courriel du destinataire invalide.<br/>';
			}
		}
		
	}
	
	
	
	if (!$erreur) {

		// Sélection des fichiers
		$liens_fichiers='<br/><br/>';
		$doc = new document($_POST['fichier']);
		$doc->auth();
		$liens_fichiers .= '<a href="'.$_SESSION['ROOT_DRUPAL'].'/telecharger?filename='.$_POST['fichier'].'&auth='.$doc->auth.'">'.$doc->nom.'</a><br/>';

	
		$address = $_POST['destinataire'];
	
		$mail = new PHPMailer(); 

		$body = file_get_contents($_SESSION['ROOT'].'/documents/email.html');
		$body = eregi_replace("[\]",'',$body);
		$body = str_replace('{logo}', $_SESSION['WEBROOT'].'/documents/images/logo-fondation-petit.jpg',$body);
		$body = str_replace('{contenu}', $_POST['message'].$liens_fichiers ,$body);

		$mail->AddReplyTo($_SESSION['utilisateur']['courriel'] ,$_SESSION['utilisateur']['prenom'].' '.$_SESSION['utilisateur']['nom']);
		$mail->SetFrom($_SESSION['utilisateur']['courriel'] , $_SESSION['utilisateur']['prenom'].' '.$_SESSION['utilisateur']['nom']);
		$mail->AddReplyTo($_SESSION['utilisateur']['courriel'] ,$_SESSION['utilisateur']['prenom'].' '.$_SESSION['utilisateur']['nom']);

		if( !DEBUG) {
			if ($_POST['benevole']) $mail->AddAddress($_POST['benevole'],'' );
			if ($_POST['delegue']) $mail->AddAddress($_POST['delegue'],'' );
			if (!empty($_POST['destinataire'])) {
				foreach ($destinataires as $val) {
					$mail->AddAddress($val,'' );
				}	
			}
		} else 	$mail->AddAddress(EMAIL,'' ); 
	 
		$mail->Subject    = $_POST['sujet'];
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($body);


		if(!$mail->Send()) {
		  $retour['message'] = "Erreur d'envoi du message.";
		   $retour['etat'] = false;
		} else {
		  $retour['message'] = "Votre message à bien été envoyé.";
		  $retour['etat'] = true;
		}
	} else {
		 $retour['message'] = $message_erreur;
		 $retour['etat'] = false;
	}
	
}
*/
  
if (!empty($retour)) {
	$json = json_encode($retour, JSON_FORCE_OBJECT);
	print $json;
} else print json_encode('oups...');
?>