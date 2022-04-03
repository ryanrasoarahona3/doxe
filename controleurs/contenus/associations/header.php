<?php
// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$asso = new association($_GET['id']);
}
$asso->nbrBenevoles(ANNEE_COURANTE);
if ( empty($asso->nbr_benevoles[ANNEE_COURANTE]) )   $asso->nbr_benevoles[ANNEE_COURANTE] = 0;
$id = $asso->id_association;
$form_action = 'associations';


include_once($_SESSION['ROOT'].'/vues/contenus/associations/header.php');
?>