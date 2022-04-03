<?php
// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
}

$id = $perso->id_personne;
$form_action = 'personnes';

if(!empty($perso->nom_jeune_fille)) $nom_jeune_fille = '<em>('.$perso->nom_jeune_fille.')</em>';
if ($perso->prospect == 1) $prospect = '<em><h3>Prospect</h3></em>';
if ($perso->elu >0) $elu = '<em><h3>'.$perso->elu_fonction.'</h3></em>';
if (!empty($perso->presse)) $presse = '<em><h3>Presse : '.$perso->presse.'</h3></em>';
if ((!empty($perso->siege_label)) && ($perso->siege_habilite==1)) $siege = '<h3>'.$perso->siege_label.' - Habilité</h3>';
else  $siege = '<h3>'.$perso->siege_label.'</h3>';


// Récupération délégué
$delegueHeader = $perso->detailDelegue('header');



include_once($_SESSION['ROOT'].'/vues/contenus/personnes/header.php');
?>