<?php

if (isset ($_GET['auto'])) {
	$auto = true;
} 

if ( (isset($_GET['retour'])) && (!empty($_SESSION['recherche'][$recherche])) ) {
	$choixRecherche = $_SESSION['recherche'][$recherche];
	$menuDemande = getSelect('distinctions_types' ,$choixRecherche['demande']);
	$menuDecision = getSelect('distinctions_types_decisions',$choixRecherche['decision']);
	$menuValidation = getSelect('distinctions_validation',$choixRecherche['validation']);
	$menuAnnee = getAnnees('distinctions',  'select', '', array(ANNEE_COURANTE));
} else {
	$menuDemande = getSelect('distinctions_types');
	$menuDecision = getSelect('distinctions_types_decisions');
	$menuValidation = getSelect('distinctions_validation');
	$menuAnnee = getAnnees('distinctions',  'select', '', array(ANNEE_COURANTE));
}
$Regions = getSelect('regions' ) ;
include_once(ROOT.'/vues/'.$controlleur.'.php');
?>

<script>
jQuery(function($) {
	$('#choix_personne').autoCompleteSection('personne',3,false,'personne');
	
	$('#choix_num_demande').autoCompleteSection('num_demande',3,false,'num_demande');
	
	
	<?php if (isset($auto) && $auto) : ?>
			$("#action_recherche").trigger("click");
	<?php endif; ?>
});
</script>
