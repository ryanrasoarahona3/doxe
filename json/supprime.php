<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Requête Ajax uniquement';
  trigger_error($user_error, E_USER_ERROR);
}

$action = (isset($_POST['action']) ? $_POST['action'] : '');
$section = (isset($_POST['section']) ? $_POST['section'] : '');
 
$retour = array();
$retour['message']= '';
$retour['action']= $action;
$retour['section']= $section;
//$retour['id'] = 0;

/////////////////////
//
//	ASSOCIATIONS
//
/////////////////////

if ($section == 'associations') {
	$assoc = new association($_POST['id']);
	$assoc->supprime();
	$retour['retour'] = 'associations';
}

/////////////////////
//
//	PERSONNES
//
/////////////////////

if ($section == 'personnes') {
	$perso = new personne($_POST['id']);
	$perso->supprime();
	$retour['retour'] = 'personnes';
}

//////////////////////////////////////
//
//	PERSONNES ASSOCIATIONS
//
//////////////////////////////////////

if ($section == 'personnes_associations') {
	
	$asso = new association(@$_POST['id_association']);
	$retour['message'] =  @$asso->supprimePersonne(@$_POST['id_lien']);
	$retour['id'] = @$_POST['id_personne'];
	$retour['select_annee'] = @$_POST['annee'];
	
}

//////////////////////////////////////
//
//	CONSEIL D'ADMINISTRATION
//
//////////////////////////////////////

if ($section == 'personnes_associations') {

	$asso = new association($_POST['id_association']);
	$retour['message'] = $asso->supprimePersonneCA($_POST['id']) ;
	$retour['id'] = $_POST['id_association'];
	$retour['annee'] = $_POST['annee'];
	$retour['retour'] = 'personnes_associations';
	
}

//////////////////////////////////////
//
//	AMIS PERSONNES
//
//////////////////////////////////////

if ($section == 'personnes_amis') {
	
	$laf = new laf_personne($_POST['id_lien']);
	$retour['message'] =  $laf->supprime();
	$retour['id'] = $_POST['id_personne'];
	
	
}


//////////////////////////////////////
//
//	AMIS ASSOCIATIONS
//
//////////////////////////////////////

if ($section == 'associations_amis') {
	
	$laf = new laf_association($_POST['id_lien']);
	$retour['message'] =  $laf->supprime();
	$retour['id'] = $_POST['id_personne'];
	
	
}


//////////////////////////////////////
//
//	Distinctions
//
//////////////////////////////////////

if ($section == 'distinctions') {
	
	$distinction = new distinction($_POST['id_lien']);
	$retour['message'] =  $distinction->supprime();
	$retour['id'] = $_POST['id_personne'];

}

//////////////////////////////////////
//
//	ANNONCES
//
//////////////////////////////////////

if ( ($section == 'personnes_annonces') || ($section == 'associations_annonces') ) {
	
	$annonce = new annonce($_POST['id_lien']);
	$retour['message'] =  $annonce->supprime();
	$retour['id'] = $_POST['createur'];
}



if (!empty($retour)) {
	$json = json_encode($retour, JSON_FORCE_OBJECT);
	print $json;
} else print json_encode('oups...');
?>