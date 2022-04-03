<?php
// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$asso = new association($_GET['id']);
}
include_once($_SESSION['ROOT'].'/vues/contenus/associations/detail.php');
?>