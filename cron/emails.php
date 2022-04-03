<?php

require_once('../libs/init.php');
require_once('../libs/requires.php');

 $connect = connect();
 
$mail = new PHPMailer(); 
$mail->isSMTP();
$mail->Host = SMTP;
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; 
$mail->Port = 25;
$mail->Username = SMTP_USER;
$mail->Password = SMTP_PASS;
//$mail->SMTPDebug  = 2;   


$mail->SetFrom(EMAIL_FROM);
$mail->AddReplyTo(EMAIL_REPLY);

$gabarit = utf8_decode(file_get_contents('../documents/email.html'));
$gabarit = str_replace('{logo}', 'http://gestion.cercledesbenevoles.fr/documents/images/logo.jpg',$gabarit);



try {
	$req = 'SELECT * FROM emails';
	$requete = $connect->query($req);

	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
			
			// Destinataires 
			$dest = unserialize($element->destinataires);
			$email_valide=false;
			
				foreach ($dest as $email) {
					if (!empty($email['to'])) {
						$mail->AddAddress($email['to'],$email['to_name']);	
						$email_valide=true;
					}
				}

				$mail->Subject = utf8_decode($element->sujet);
				$mail->AltBody = "Pour visualiser ce courriel, vous devez disposez d'un logiciel de lecture compatible HTML !"; // optional, comment out and test
			
				// Remplissage gabarit et message
				$message 	= str_replace('{contenu}', utf8_decode( $element->message) , $gabarit);
				$mail->MsgHTML($message);
			

				if ($email_valide) {
					if(!$mail->Send()) {
						$mail->resultat = false;
					} else {
						$req2 = "DELETE FROM  `emails`  WHERE id = ".$element->id;
						try {
							$requete2 = $connect->query($req2);
							} catch( Exception $e ){
							echo 'Erreur de lecture de la base : ', $e->getMessage();
						}
					}
				}
				
				$mail->ClearAddresses();
				$mail->ClearAttachments();
				$mail->ClearCCs();
				$mail->ClearBCCs();
			
				$mail->Subject='';
				$mail->MsgHTML('');
			
	  }
} catch( Exception $e ){
  	echo 'Erreur de lecture de la base : ', $e->getMessage();
}



?>