<?php

$Departements = getSelect('departements' , '', array('numero','nom')) ;
$Regions = getSelect('regions' ) ;
$Pays = getPays();
	
$Activites = getSelect('activites' );
$AssociationsTypes = getSelect('associations_types' );

$nouvel_adherent = getAnnees('system',  'select', 'nouvel_adherent', $selection=array(0));
$non_inscrit = getAnnees('system',  'checkbox', 'non_inscrit[]', $selection=array(0));
$inscrit = getAnnees('system',  'checkbox', 'inscrit[]', $selection=array(0));
$adherent_annees = getAnnees('system',  'checkbox', 'adherent_annees', $selection=array(0));

$DepartementsP = getSelect('departements' , '', array('numero','nom')) ;
$RegionsP = getSelect('regions' ) ;
$laf_annee = getAnnees('system',  'select', 'laf_annee',array());


include_once(ROOT.'/vues/'.$controlleur.'.php');
?>

<script>
jQuery(function($) {
	$('#code_postal').autoCompleteSection('cp_ville',2,false,'ville');
	$('#code_postal_president').autoCompleteSection('cp_ville',2,false,'ville_president');
	$('#nom_association').autoCompleteSection('association',2,false,'id_association');
	$('#nom_president').autoCompleteSection('personne',2,false,'id_president');
	
	$('#pays_president').on('change', function (e) {
    	var valueSelected = this.value;
    	
    	if (valueSelected == "<?php echo ID_FRANCE ?>") {
    		  $("#zone_ville_president").show();
    		  $("#code_postal_president").show();
    		  $("#zone_ville_pays_president").hide();
    	} else {
    		  $("#zone_ville_president").hide();
    		  $("#code_postal_president").hide();
    		  $("#ville_pays_president").show();
    		  $("#zone_ville_pays_president").show();
    	}
	}); 
	
	$('#laf_annee').on('change', function (e) {
    	var valueSelected = this.value;
    	
    	if (valueSelected > 0) {
    		$("#adherent_annees[type=checkbox]").prop('disabled', true);
			$("#nouvel_adherent").prop('disabled', true);
			$("#non_inscrit_annee").prop('disabled', true);
			$("#renouvele_annee").prop('disabled', true);
			$("#non_renouvele_annee").prop('disabled', true);
			
			$("#date_paiement_debut").prop('disabled', false);
			$("#date_paiement_fin").prop('disabled', false);
			$("#etat_paiement").prop('disabled', false);
			$("#gmf").prop('disabled', false);
			$("#depasse_gmf").prop('disabled', false);
			$("#citizenplace").prop('disabled', false);
			$("#aide_citizenplace").prop('disabled', false);
			$("#groupama").prop('disabled', false);
			$("#banque_postale").prop('disabled', false);
    	} else {
    		$("#adherent_annees[type=checkbox]").prop('disabled', false);
			$("#nouvel_adherent").prop('disabled', false);
			$("#non_inscrit_annee").prop('disabled', false);
			$("#renouvele_annee").prop('disabled', false);
			$("#non_renouvele_annee").prop('disabled', false);
			
			$("#date_paiement_debut").prop('disabled', true);
			$("#date_paiement_fin").prop('disabled', true);
			$("#etat_paiement").prop('disabled', true);
			$("#gmf").prop('disabled', true);
			$("#depasse_gmf").prop('disabled', true);
			$("#citizenplace").prop('disabled', true);
			$("#aide_citizenplace").prop('disabled', true);
			$("#groupama").prop('disabled', true);
			$("#banque_postale").prop('disabled', true);
    	}
	}); 
	
	// Init
	$("#pays_president").trigger("change");
	$("#laf_annee").trigger("change");
});
</script>
