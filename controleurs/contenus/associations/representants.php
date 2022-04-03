<?php

if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$asso = new association($_GET['id']);
	
	
} 

if (!empty($asso->gestionnaire)){
	 $gestionnaire = new personne($asso->gestionnaire);
	 $detailGestionnaire = '<strong>'.$gestionnaire->nom.' '.$gestionnaire->prenom.'</strong><br/>';
	 if(!empty($gestionnaire->telephone_fixe)) $detailGestionnaire .= '<em>Téléphone : </em>'.$gestionnaire->telephone_fixe.'<br/>';
	 if(!empty($gestionnaire->telephone_mobile)) $detailGestionnaire .= '<em>Téléphone mobile : </em>'. $gestionnaire->telephone_mobile.'<br/>';
	 $detailGestionnaire .= '<em>Courriel  : </em>'. vide($gestionnaire->courriel).'<br/>';
}
include_once($_SESSION['ROOT'].'/vues/contenus/associations/representants.php');
?>