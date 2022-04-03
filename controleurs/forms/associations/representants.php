<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'gestionnaire';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->id_association = $_GET['id'];
if($_GET['id_lien']) $form->annee =  $_GET['id_lien'];
$form->action = 'modifier'; 


	$titre = 'Modification du gestionnaire';


// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/representants.php');

?>


<script>
// Scripts
$(document).ready(function() {

		// Gestion de la personne
	
	$('#type_recherche').on('change', function (e) {
    	var valueSelected = this.value;

    	$( "#choix_personne" ).autocomplete( "destroy" );
    	if (valueSelected == 1) $('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
    	if (valueSelected == 2) $('#choix_personne').autoCompleteSection('personne_association&id_association=<?php echo $form->id_association ?>&annee='+$('#annee').val(),2,false,'id_personne');
	}); 
	
		$('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
	
});
</script>