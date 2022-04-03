<?php
session_start();

if (isset($_GET['filename'])) $fichier = $_GET['filename'];
else {
	$fichier = $params[1];
}

$path = $_SESSION['ROOT'].'../documents/';
if ( (!empty($fichier)) && ( isGestion() )) {
	
	/*
	if ( (substr($fichier,0,3) == 'FAC') && (file_exists ( $path. $fichier.'.pdf.php' )) ) {
		decode($fichier,$path);
	} else {
		$document = new document($fichier);
		return $document->telecharge();
	}
	*/
	//echo $fichier;
	$document = new document($fichier);
	return $document->telecharge();
}

?>