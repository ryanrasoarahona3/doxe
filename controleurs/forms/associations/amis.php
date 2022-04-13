<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'associations_amis';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->id_association = $_GET['id'];
 
// Ajout
if (!isset($_GET['id_lien'])) {	
	$form->label_validation = "Ajouter";
	$form->action = 'modifier'; // On laisse modifier pour rester sur la même page
	$villeSelected =  '';
	
	$dateInscrition = date('d/m/Y');
	
	$menuConnaissance = getSelect('connaissance');
	$typesPaiement  = getSelect('commerce_payement', array(0), array('nom'));
	$etatPaiement  = getSelect('commerce_payement_etat', array(0), array('nom'));
	
	$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE));
	
	
}
// Récupération GET et contenu si modification
else if (isset($_GET['id_lien']))  {
	
	$laf = new laf_association($_GET['id_lien']);
	$commande = new commande($laf->id_commande);
	
	$form->label_validation = "Modifier";
	
	$form->suppression = true;
	$alerte_commande = '';
		
	$typesPaiement  = getSelect('commerce_payement', array($commande->payement), array('nom'));
	$etatPaiement  = getSelect('commerce_payement_etat', array($commande->etat), array('nom'));

}


// Traitement LAF

if (isset ($laf)) {	

	$form->action = 'modifier';
	$form->id_lien = $_GET['id_lien'];
	
	// $menuConnaissance = getSelect('connaissance', array($laf->connaissance));
		
	$menuAnnee = getAnnees('system',  'select', '', array($laf->annee));
	
	
}


// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/amis.php');

?>
<script>

$(document).ready(function() {
     
     // Date
     
     $( "#date_paiement" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
        yearRange: "2010:"+new Date().getFullYear()
      });
     

    // Check doublon (adhésion existante pour l'année sélectionnée)
    
    $( "#contenu_formulaire" ).on('click','#action_pre_valider',function(event) {
		var form = $(this).closest("form").attr('id');
		
		if ($("#id_lien").val()=='') {
			$.ajax({
				url: '<?php echo $_SESSION["WEBROOT"]?>json/validation.php',
				type: 'post',
				dataType: 'json',
				data: $('#'+form).serialize(),
				success: function(data) {
					
					if (data.resultat == 0) {
						$( "#dialog-modal-amis" ).dialog({
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
	
	
	// Gestion des zones à afficher
	/*
	$("#paiement").on('change', function (e) {
		var valueSelected = this.value;
		if (valueSelected == 0)	$(".montant").show();
		else $(".montant").hide();
		
		if (valueSelected == 2)	{
			$(".commande").show();
			$(".ladate").hide();
			$(".moyen").hide();
			$(".etat").hide();
		}
		else {
			$(".commande").hide();
			$(".ladate").show();
			$(".moyen").show();
			$(".etat").show();
		}
		
	});
	*/
		
	$("input[name=delegue]:radio").on('change', function (e) {
		var valueSelected = this.value;
		if (valueSelected == 1)	$("#delegue_oui").show();
		else $("#delegue_oui").hide();
	});
	
	$('#distinction').on('change', function (e) {
		var valueSelected = this.value;
		if (valueSelected > 0)	$("#distinction_oui").show();
		else $("#distinction_oui").hide();
	});
	
	// Init
	$("#paiement").trigger( "change" );
	$('#distinction').trigger( "change" );

});
</script>