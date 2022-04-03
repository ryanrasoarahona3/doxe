<?php
if (empty($params[1])) {
	header("HTTP/1.0 404 Not Found");
	die();
}
$req = "SELECT * FROM site_users WHERE  uid='".$params[1]."'";

try {
  	$requete = $connect->query($req);
	$retour = $requete->fetch(PDO::FETCH_OBJ);
	if (empty($retour)) {
		header("HTTP/1.0 404 Not Found");
		die();
	}
	$url = '/'.str_replace('_','/detail/',$retour->name);
	header('Location: '.$url); 
  
} catch( Exception $e ){
  echo 'Erreur de lecture de la base : ', $e->getMessage();
}

?>