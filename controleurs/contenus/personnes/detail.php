<?php
// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
}

// Récupération délégué spécial

// Récupération délégué
$delegueDetail = $perso->detailDelegue('detail');

if ($perso->prospect == 1) $prospect = '<p><strong>Prospect</strong></p>';
if ($perso->elu >0) $elu = '<p><strong>'.$perso->elu_fonction.'</strong></p>';
if (!empty($perso->presse)) $presse = '<p><strong>Presse : '.$perso->presse.'</strong></p>';
if ((!empty($perso->siege_label)) && ($perso->siege_habilite==1)) $siege = '<p><strong>'.$perso->siege_label.' - Habilité</strong></p>';
else  $siege = '<p><strong>'.$perso->siege_label.'</strong></p>';

include_once($_SESSION['ROOT'].'/vues/contenus/personnes/detail.php');
?>