<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'associations_personnes';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->id_association = $_GET['id'];
 
// Ajout
$form->label_validation = "Ajouter";
$form->action = 'modifier'; // On laisse modifier pour rester sur la même page
	
$dateInscrition = date('d/m/Y');
	
$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE));
$menuEtat = getSelect('personnes_associations_etat');
$menuCA = getSelect('cons_admin_fonctions');
$benevole = " checked ";


// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/personnes.php');
?>


<script>
// Scripts

	
	$( "#date" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
        yearRange: "2010:"+new Date().getFullYear()
      });
    
    $( "#date_etat" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
        yearRange: "2010:"+new Date().getFullYear()
      });
    
    $( "#contenu_formulaire" ).on('click','#action_pre_valider',function(event) {
		var form = $(this).closest("form").attr('id');
		
		if ($("#id_personne").val().length > 0)  {
			$.ajax({
				url: '<?php echo $_SESSION["WEBROOT"]?>json/validation.php',
				type: 'post',
				dataType: 'json',
				data: $('#'+form).serialize(),
				success: function(data) {
				
					if (data.resultat == 0) {
						$( "#dialog-modal" ).dialog({
						modal: true,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
					}
					
					if (data.resultat == 1) $('#action_valider').trigger( "click" );
				 },
				 error: function(jqXHR, textStatus, errorThrown){
					alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
				 }
			
			});
    	} else {
    		// On vérifie le formulaire pour afficher l'erreur
    		$('#action_valider').trigger( "click" );
    	}
	});

	$('#choix_date').on('change', function (e) {
    	var valueSelected = this.value;
    	if  ((valueSelected == 1) ||  (valueSelected == 2))$("#field_choix_date").hide();
    	if (valueSelected == 3) $("#field_choix_date").show();
	}); 
	
	$('#type_recherche').on('change', function (e) {
    	var valueSelected = this.value;
    
    	$( "#choix_personne" ).autocomplete( "destroy" );
    	if (valueSelected == 1) $('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
    	if (valueSelected == 2) $('#choix_personne').autoCompleteSection('personne_association_annee&id_association=<?php echo $form->id_association ?>&annee='+$('#annee').val(),2,false,'id_personne');
	}); 
	
	// Init
	$('#choix_date').trigger('change');
	$('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
	
	
</script>