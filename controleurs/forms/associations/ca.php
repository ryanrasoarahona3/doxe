<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'associations_ca';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->id_association = $_GET['id'];
if($_GET['id_lien']) $form->annee =  $_GET['id_lien'];
$form->action = 'conseil_administration'; 



if ($form->annee) {
	$titre = 'Conseil d\'administration '.$form->annee;
	$ajouteCA = false;
	
	// Récupération du CA
	$asso = new association($form->id_association);
	$asso->conseilAdministration();
	
	$detailCA = '';
	if (count($asso->conseil_administration)>0) {
		
		foreach ($asso->conseil_administration[$form->annee] as $val) {
			$detailCA.='<tr>';
			$detailCA.='<td>'.$val['fonction_label'].'</td>';
			$detailCA.='<td>'.$val['personne'].'</td>';
			$detailCA.='<td>
					<button form-action="lien" form-element="/personnes/detail/'.$val['id_personne'].'" class="right personnes action"></button>
					<button form-action="mail" form-element="'.$val['courriel'].'" class="right envoyer action"></button>
					<button form-action="supprimer" form-element="section=personnes_associations&id='.$val['id_ca'].'&id_association='.$form->id_association.'&annee='.$form->annee.'" class="right supprimer action"></button>
				</td>';
			$detailCA.='</tr>';
		}
	}
	
	$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE) );
}
else {
	$titre = 'Ajout du Conseil d\'administration';
	$ajouteCA = true;

	// Vérification des années disponibles
	$req = 'SELECT DISTINCT personnes_associations.annee
	FROM personnes_associations
	WHERE personnes_associations.association = '.$form->id_association.' AND personnes_associations.cons_admin <> 0';
	$exclusion=array();

	try {
	  $requete = $connect->query($req);
	  $exclusion = array();
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
	
		$exclusion[] = $element->annee;
		$menuCopieAnnee .= '<option value="'.$element->annee.'">'.$element->annee.'</option>';
	  }

	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}
	
	// Possibilité de copier une année
	if (count($exclusion)==0) $copieAnnee = false;
	else $copieAnnee = true;
	
	// Années
	$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE), $exclusion );
}


$dateInscrition = date('d/m/Y');
$menuCA = getSelect('cons_admin_fonctions');



// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/ca.php');

?>


<script>
// Scripts
$(document).ready(function() {

	$('#identique_annee').on('change', function (e) {
    	var valueSelected = this.value;
    	if (valueSelected == 0) $("#zone_ajout_personne").show();
    	else $("#zone_ajout_personne").hide();
	}); 
	
	
	// Init
	$('#identique_annee').trigger('change');
	
	// Gestion de la personne
	
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

	$('#choix_date').on('change', function (e) {
    	var valueSelected = this.value;
    	if  ((valueSelected == 1) ||  (valueSelected == 2))$("#field_choix_date").hide();
    	if (valueSelected == 3) $("#field_choix_date").show();
	}); 
	
	$('#type_recherche').on('change', function (e) {
    	var valueSelected = this.value;

    	$( "#choix_personne" ).autocomplete( "destroy" );
    	if (valueSelected == 1) $('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
    	if (valueSelected == 2) $('#choix_personne').autoCompleteSection('personne_association&id_association=<?php echo $form->id_association ?>&annee='+$('#annee').val(),2,false,'id_personne');
	}); 
	
	// Init
	$('#choix_date').trigger('change');
	$('#choix_personne').autoCompleteSection('personne',2,false,'id_personne');
	
});
</script>