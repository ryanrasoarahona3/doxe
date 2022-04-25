<?php

$documents = '';
$config = '';

// Récupération documents
$req = "SELECT * FROM documents_contenus ";
try {
  $requete = $connect->query($req);
  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
	$documents .= '<li><a href="/gestion/document/'.$element->code.'">'.$element->nom.'</a></li>'."\n";
  }
} catch( Exception $e ) {
  	echo 'Erreur de lecture de la base : ', $e->getMessage();
}


// Récupération configs
$req = "SELECT * FROM configuration ";
try {
  $requete = $connect->query($req);
  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
	$config .= '<li><a href="/gestion/configuration/'.$element->code.'">'.$element->nom.'</a></li>'."\n";
  }
} catch( Exception $e ) {
  	echo 'Erreur de lecture de la base : ', $e->getMessage();
}

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>