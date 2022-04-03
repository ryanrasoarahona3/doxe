<?php
session_start();
$form = new stdClass;

$form->action = "associations";
$form->destination_validation = $_SESSION['WEBROOT']."json/sauve_personnes.php";

if (!isset($_GET['id'])) {
// Ajout	
	$form->destination_annulation = "";
	$form->suppression = false;
	$form->annulation = true;
	$form->label_validation = "Ajouter";
}
else if (isset($_GET['id']))  {
// Récupération GET et contenu si modification
	$form->destination_annulation = "";
	$form->suppression = true;
	$form->annulation = true;
	$form->label_validation = "Modifier";
	$form->id = $_GET['id'];
	$form->idpersonne = $_GET['idpersonne'];
}
// Récupération des contenus des menus

	// Civilité
	
	// Pays
	
	// Departement
	
	// Region
	
	// Fonction elu local
	
	// Statut
	
	// Type de resonsabilité
	
	// Régions délégations
	
	// Départements délégations

// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/president.php');
?>
<script>

	// Gestion association 
	$('#association').autoCompleteSection('association',2,false);

</script>