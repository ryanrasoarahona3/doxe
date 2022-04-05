<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 'On');
#See CERCLE_CONFIGURATION

if(!isset($controlleur))
	$controlleur="";

// Ouverture de session
session_start();

// Fonctions PHP et librairies
require_once('libs/init.php');
require_once('libs/requires.php');

// Récupération page
$params = explode('/',$_GET['p']);
if (!empty( $params[0]))
{
	$action = $params[0];
	$controlleur = $params[0];
}

// Redirections
if ($controlleur == 'deconnexion') {
	session_start();
	unset($_SESSION);
	session_destroy ();
	 header('Location: /');
}

if ($controlleur == 'redirection_boutique') require (ROOT.'controleurs/'.$controlleur.'.php');
if ($controlleur == 'telecharger') {
	require (ROOT.'controleurs/'.$controlleur.'.php');
	die();
}

if (!empty( $params[1])) $controlleur .= '-'.$params[1];
if (!empty( $params[2])) $element = $params[2];

if (!isset($_SESSION['utilisateur']))  $controlleur='login';

// Page

// Haut de page
require ('inc/header.php');
if ( $controlleur!='login') {
	require ('inc/nav.php');
	require ('inc/utilisateur.php');
}

// Sélection page
if (!empty($controlleur) && is_file(ROOT.'controleurs/'.$controlleur.'.php'))
{
	require (ROOT.'controleurs/'.$controlleur.'.php');
}
else
{
       // require (ROOT. 'controleurs/accueil.php');
}

// Pied de page
require ('inc/footer.php');
?>
<script>
	console.log("Bonjour");
</script>