<?php

if (isset ($_GET['auto'])) {
	$auto = true;
	if (isset ($_GET['inscrit'])) $selection_inscrit=array($_GET['inscrit']);
	
	if (isset ($_GET['num_dossier'])) $num_dossier = strtoupper($_GET['num_dossier']);
	
	if (isset ($_GET['id_association'])) $id_association = $_GET['id_association'];

	
} else {
	$selection_inscrit=array(0);
}
 
$distinctions_types = getSelect('distinctions_types') ;

$nouvel_adherent = getAnnees('system',  'select', 'nouvel_adherent', $selection=array(0));
$non_inscrit = getAnnees('system',  'checkbox', 'non_inscrit[]', $selection=array(0));
$inscrit = getAnnees('system',  'checkbox', 'inscrit[]', $selection_inscrit);
$adherent_annees = getAnnees('system',  'radio', 'adherent_annees', $selection=array(0));
$non_adherent_annees = getAnnees('system',  'checkbox', 'non_adherent_annees[]', $selection=array(0));
$annee_distinction = getAnnees('system',  'select', 'annee_distinction', $selection=array(0));
$menuConnaissance = getSelect('connaissance');

$menuSiege = getSelect('siege' , '', array('nom'));
$menuCA = getSelect('cons_admin_fonctions' , '', array('nom'));
$menuActif = getSelect('personnes_associations_etat' , '');
$Departements = getSelect('departements' , '', array('numero','nom')) ;
$Regions = getSelect('regions' ) ;
if(	$auto ) $Pays = getPays(0,true);
else $Pays = getPays(ID_FRANCE,true);

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>


<script>
jQuery(function($) {
	$('#code_postal').autoCompleteSection('cp_ville',2,false,'ville');
	$('#nom_association').autoCompleteSection('association',2,false,'id_association');
	$('#nom').autoCompleteSection('personne',2,false,'id_personne');
	
	
	$('#pays').on('change', function (e) {
    	var valueSelected = this.value;
    	
    	if (valueSelected == "<?php echo ID_FRANCE ?>") {
    		  $("#zone_ville").show();
    		  $("#code_postal").show();
    		  $("#zone_ville_pays").hide();
    		  
    	} else {
    		  $("#zone_ville").hide();
    		  $("#code_postal").hide();
    		  $("#ville_pays").show();
    		  $("#zone_ville_pays").show();
    		
    	}
	}); 
	
	// Init
	$("#pays").trigger("change");
	
	<?php if ($auto) : ?>
			$("#action_recherche").trigger("click");
	<?php endif; ?>
});
</script>
