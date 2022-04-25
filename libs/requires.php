<?php
//ini_set("session.cookie_domain", ".amis.dev");

require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/constantes.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/fonctions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/phpexcel/PHPExcel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/phonetic.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/phpmailer/class.pop3.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/phpmailer/PHPMailerAutoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/Mailin.php'); // Send in blue


require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/association.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/personne.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/laf_personne.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/laf_association.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/annonce.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/distinction.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/document.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/produit.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/email.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/commande.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/commande_produit.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/commande_session.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/class/configuration.class.php');

#require_once($_SERVER['DOCUMENT_ROOT'].'/../www/sites/all/themes/amis/libs/commerce.php');




// Connexion à la base
require_once($_SERVER['DOCUMENT_ROOT'].'/../gestion/libs/connect.php');
?>
