<pre><?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');
require_once($_SESSION['ROOT'].'libs/phpmailer/PHPMailerAutoload.php');
//session_destroy();


	$address = 'slebonnois@labo83.com';
	
	$mail = new PHPMailer(); 
	
	$mail->isSMTP();
	$mail->Host = 'mercury.caoba.fr';
	$mail->SMTPAuth = true;
	$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
	$mail->Port = 25;
	$mail->Username = 'ac67373';
	$mail->Password = 'fendurw9dekfyy';
	$mail->SMTPDebug  = 2;   

	$body = 'Contenu du message';
	
	$mail->SetFrom('ne-pas-repondre@caoba.fr');
	$mail->AddReplyTo('slebonnois@labo83.com');

	$mail->AddAddress($address,'' );
	$mail->AddAddress('slebonnois@me.com','' );

	$mail->Subject    ='sujet test';
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($body);

	if(!$mail->Send()) {
	  $retour['message'] = "Erreur d'envoi du message.";
	   $retour['etat'] = false;
	} else {
	  $retour['message'] = "Votre message à bien été envoyé.";
	  $retour['etat'] = true;
	}
print_r($retour);
?></pre>