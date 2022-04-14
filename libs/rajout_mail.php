<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once($_SESSION['ROOT'] . 'libs/mailer/src/Exception.php');
require_once($_SESSION['ROOT'] . 'libs/mailer/src/PHPMailer.php');
require_once($_SESSION['ROOT'] . 'libs/mailer/src/SMTP.php');

define('MAIL_USERNAME', 'chocapic1314@gmail.com');
define('MAIL_PASSWORD', 'seuwaqewxkfgrubc');

function envoyerUnMail($destinataires, $sujet, $corps, $fichier = null)
{
    if (!empty($destinataires) && !empty($sujet) && !empty($corps)) {
        $mail = new PHPMailer();
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = 1;

        $mail->SMTPSecure = 'ssl';               //Protocole de sécurisation des échanges avec le SMTP
        $mail->Username   =  MAIL_USERNAME;   //Adresse email à utiliser
        $mail->Password   =  MAIL_PASSWORD;     //Mot de passe de l'adresse email à utiliser

        $mail->CharSet = 'UTF-8'; //Format d'encodage à utiliser pour les caractère

        $mail->From       =  'contact@ovh.net';                //L'email à afficher pour l'envoi
        $mail->FromName   = 'Cercle des Bénévoles';             //L'alias à afficher pour l'envoi

        $mail->Subject    =  $sujet;                      //Le sujet du mail
        $mail->WordWrap   = 50;                                //Nombre de caracteres pour le retour a la ligne automatique


        //preparation du corps du mail
        $body = file_get_contents($_SESSION['ROOT'] . '/documents/email.html');
        $body = str_replace('{logo}', 'http://gestion.cercledesbenevoles.fr/documents/images/logo.jpg', $body);
        $body = str_replace('{contenu}', $corps, $body);
        $mail->Body = $body;

        //jointure du fichier s'il y en a
        if ($fichier != null) {
            $path = $_SESSION['ROOT'] . '../documents/';
            if ((!empty($fichier))) {
                $document = new document($fichier);
                // $document->telecharge();
                $document->creation();
                // echo $document->emplacement;
                $mail->addAttachment($document->emplacement, $document->filename);
            }
        }

        $mail->IsHTML(true);                               

        $list_emails_to = explode(",", $destinataires);
        foreach ($list_emails_to  as $key => $email) {
            $mail->AddAddress($email);
        }

        if (!$mail->send()) {
            return $mail->ErrorInfo;
        } else {
            return "message envoyé";
        }
    }
    return "informations manquantes";
}
