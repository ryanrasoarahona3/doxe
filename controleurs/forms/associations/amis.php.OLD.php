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
	
	$dateInscrition = date('d/m/Y');
	

	
	$typesPaiement  = getSelect('paiement_types', array(0), array('nom'),'code');
	$etatPaiement  = getSelect('paiement_etats', array(0), array('nom'),'code');
	
	$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE));
	
	// Récupération du contenu de la dernière année LAF
	$asso = new association($_GET['id']);
	$asso->lesAmis();
	if (is_array($perso->lesamis)) {
		$last_annee = array_keys($perso->lesamis)[0];
		if (!empty($last_annee)) {
			$alerte_chargement = '<h3 class="alerte">Les contenus du formulaire ont été préchargés avec les données de '.$last_annee.'</h3><br>'; 
			$laf = new laf_association();
			$laf = $asso->lesamis[$last_annee];
			$laf->paiement = 0;
			$laf->montant = '';
			$laf->date_paiement = '';
			$laf->type_paiement = '';
			$laf->etat_paiement = '';
		}
	}
}
// Récupération GET et contenu si modification
else if (isset($_GET['id_lien']))  {
	
	$laf = new laf_association($_GET['id_lien']);
	
	$form->label_validation = "Modifier";
	
	if (!empty($laf->commande) && empty($laf->etat_paiement) ) {		
		$form->suppression = true;
		$alerte_commande = '<span class="alerte">La commande associée à cette adhésion n\'existe pas ou n\'est pas associée à un moyen de paiement</span><br>';
	}
	else if (!empty($laf->commande) && !empty($laf->etat_paiement) ) {
		$form->suppression = false;
		$alerte_commande = '';
		$alerte_supprime='Cette adhésion est liée à une commande et ne peut-être supprimée.';
	}
	else {
		$form->suppression = true;
		$alerte_commande = '';
	}
	
	
	$typesPaiement  = getSelect('paiement_types', array($laf->type_paiement), array('nom'),'code');
	$etatPaiement  = getSelect('paiement_etats', array($laf->etat_paiement), array('nom'),'code');

}


// Traitement LAF

if (isset ($laf)) {	

	$form->action = 'modifier';
	$form->id_lien = $_GET['id_lien'];
	
		
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
		
	/*
	$("#nbr_salaries").$("#nbr_adherents").on('change paste keyup', function (e) {
		 var valueSelected = this.value;	 
		 if (valueSelected > <?php echo MAX_ADHERENTS_GMF ?> ) {
			$("#assurance_gmf").attr("disabled", true);
			$("#assurance_gmf").attr('checked', false);
		  } else {
			$("#assurance_gmf").removeAttr("disabled");
		  }	
	});
	
	$("#nbr_salaries").on('change paste keyup', function (e) {
		 var valueSelected = this.value;
		 
		 if (valueSelected > <?php echo MAX_SALARIES_GMF ?> ) {
				$("#assurance_gmf").attr("disabled", true);
				$("#assurance_gmf").attr('checked', false);
		  } else {
				$("#assurance_gmf").removeAttr("disabled");
		  }	
	});
	
	$("#budget_fonctionnement").on('change paste keyup', function (e) {
		 var valueSelected = this.value;
		 
		 if (valueSelected > <?php echo MAX_BUDGET_GMF ?> ) {
				$("#assurance_gmf").attr("disabled", true);
				$("#assurance_gmf").attr('checked', false);
		  } else {
				$("#assurance_gmf").removeAttr("disabled");
		  }	
	});
	*/
	
	// Init
	$("#paiement").trigger( "change" );
	/*
	$('#nbr_salaries').trigger( "keyup" );
	$('#nbr_adherents').trigger( "keyup" );
	$('#budget_fonctionnement').trigger( "keyup" );
	*/
});
</script>