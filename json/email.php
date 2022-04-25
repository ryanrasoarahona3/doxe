<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');
require_once($_SESSION['ROOT'].'libs/rajout_mail.php');
//session_destroy();

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Requête Ajax uniquement';
  trigger_error($user_error, E_USER_ERROR);
}
 
$action = isset($_POST['action']) ? $_POST['action'] : 'email';
 
// Envoyer email
if ( $action == "email") {

	$retour = envoyerUnMail($_POST['destinataire'], $_POST['sujet'], $_POST['message']);
	
}


// Envoyer plusieurs fichiers attestations
if ($action == "fichiers_attestations") {
	
	// Vérification des erreurs
	
	$erreur = false;
	$message_erreur ='';
	
	if (!isset($_POST['fichiers'])) {
		$erreur = true;
		$message_erreur .='Aucun fichier séléctionné.<br>';
	}
	
	if ((!empty($_POST['president'])) && (!filter_var($_POST['president'], FILTER_VALIDATE_EMAIL)) ) {
		$erreur = true;
		$message_erreur .= 'Courriel du président invalide.<br/>';
	}
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
		foreach ($_POST['fichiers'] as $filename) {
			$doc = new document($filename);
			$doc->auth();
			$liens_fichiers .= '<a href="'.$_SESSION['ROOT_DRUPAL'].'/telecharger?filename='.$filename.'&auth='.$doc->auth.'">'.$doc->nom.'</a><br/>';
		}
	
		$mail = new email(); 

		if( !DEBUG) {
			if ($_POST['president']) $mail->to ($_POST['president'],'');
			if ($_POST['delegue']) $mail->to ($_POST['delegue'],'');
			if (!empty($_POST['destinataire'])) {
				foreach ($destinataires as $val) {
					$mail->to ($val,'' );
				}	
			}
		} else 	$mail->to (EMAIL,'' ); 
	 
		$mail->sujet    = $_POST['sujet'];
		$mail->message = $_POST['message'].'<br><br>'.$liens_fichiers;

		$mail->envoyer();

		if(!$mail->resultat) {
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



// Envoyer un fichier attestation
if ($action == "fichier") {
	
	
	// Vérification des erreurs
	
	$erreur = false;
	$message_erreur ='';
	
	$type = explode('_',$_POST['type']);
	
	if ($type[0] =="personnes" && $type[1] != "achat" && $type[1] != "amis") {
		
		// Recherche de l'association
		if ($_POST['type'] == 'personnes_attestation') {
			if ($_POST['president']==1) {
		
			// Récupération de l'association
			
				$detail = explode('_',$_POST['fichier']);
				$asso = new association ($detail[2]);
				
			}
		} 
		// Amis, donc récupération de l'association
		// else if ($_POST['type'] == 'personnes_amis') {
		// 	$asso = new association (ID_LAF);
		// }
		
		$asso->conseilAdministration();
		$president = $asso->presidents[$detail[1]];
	
		/* Test du mail du président
		if (!filter_var($president['courriel'], FILTER_VALIDATE_EMAIL))  {
			$erreur = true;
			$message_erreur .= 'Courriel du président invalide.<br/>';
		} else $destinataires[] = $president['courriel'];
		*/
		
	} else if ($type[0] =="associations"){
		
		if ((!empty($_POST['president'])) && (!filter_var($_POST['president'], FILTER_VALIDATE_EMAIL)) ) {
			$erreur = true;
			$message_erreur .= 'Courriel du président invalide.<br/>';
		} else $destinataires[] = $president['courriel'];
	} else if ($type[0] =="personnes" && ($type[1] == "achat" || $type[1] == "amis" )) {
		$retour = envoyerUnMail($_POST['destinataire'], $_POST['sujet'], $_POST['message'], $_POST['fichier']);
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
	
	
	if ($type[0] =="personnes" && $type[1] =! "achat")
	if (!$erreur) {

		// Sélection des fichiers
		$liens_fichiers='<br/><br/>';
		$doc = new document($_POST['fichier']);
		$doc->auth();
		$liens_fichiers .= 'Vous devez être connecté à l\'espace de gestion pour télécharger ce fichier : <br><a href="http://gestion.cercle-benevoles.fr/telecharger?filename='.$_POST['fichier'].'&auth='.$doc->auth.'">'.$doc->nom.'</a><br/>';

	
		$address = $_POST['destinataire'];
	
		$mail = new PHPMailer(); 

		$body = file_get_contents($_SESSION['ROOT'].'/documents/email.html');
		$body = eregi_replace("[\]",'',$body);
		$body = str_replace('{logo}', 'http://gestion.cercledesbenevoles.fr/documents/images/logo.jpg',$body);
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

		$mail->MsgHTML( utf8_decode($body) );


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

  
if (!empty($retour)) {
	$json = json_encode($retour, JSON_FORCE_OBJECT);
	print $json;
} else print json_encode('oups...');
?>