<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'personnes_associations';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->id_personne = $_GET['id'];
 
// Ajout
if (!isset($_GET['id_lien'])) {	
	$form->label_validation = "Ajouter";
	$form->action = 'modifier'; // On laisse modifier pour rester sur la même page
	$villeSelected =  '';
	
	$dateInscrition = date('d/m/Y');
	
	$menuAnnee = getAnnees('system',  'select', '', array(ANNEE_COURANTE));
	$menuEtat = getSelect('personnes_associations_etat');
	$menuCA = getSelect('cons_admin_fonctions');
	$benevole = " checked ";
}
// Récupération GET et contenu si modification
else if (isset($_GET['id_lien']))  {
	$form->label_validation = "Modifier";
	$form->suppression = true;
	$form->action = 'modifier';
	$form->id_lien = $_GET['id_lien'];
	
	$reqPersonneAssociation = $connect->prepare('SELECT * FROM personnes_associations INNER JOIN associations ON personnes_associations.association = associations.id WHERE personnes_associations.id =  :id ');	
	$reqPersonneAssociation->bindValue(':id', $form->id_lien, PDO::PARAM_INT);
	$reqPersonneAssociation->execute();
	$enregistrement = $reqPersonneAssociation->fetch(PDO::FETCH_OBJ);
	$dateInscrition=convertDate($enregistrement->date,'php');
	
	if ($enregistrement->benevole == 1) $benevole = " checked ";
	else $benevole = "  ";
	$selectAssociation = '<li class="choix_association_'.$enregistrement->association.'">'.$enregistrement->nom.'<a href="#" class="choix_association_remove ui-icon ui-icon-circle-close right"></a><input type="hidden" name="choix_association_tab_resultat[]" value="'.$enregistrement->association.'" class="choix_association_'.$enregistrement->association.'"></li>';
	
	$menuAnnee = getAnnees('system',  'select', '', array($enregistrement->annee));
	$menuEtat = getSelect('personnes_associations_etat',array($enregistrement->etat));
	$menuCA = getSelect('cons_admin_fonctions', array($enregistrement->cons_admin));
}


// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/personnes/associations.php');
?>
<script>

	// Gestion association 
	$('#choix_association').autoCompleteSection('association',2,false,'id_association');
	
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
		
		if (($("#id_association").val().length > 0) && ($("#id_lien").val()=='')) {
			$.ajax({
				url: '<?php echo $_SESSION["WEBROOT"]?>json/validation.php',
				type: 'post',
				dataType: 'json',
				data: $('#'+form).serialize(),
				success: function(data) {
				
					if (data.resultat == 0) {
						$( "#dialog-modal-personne" ).dialog({
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
	

	// Init
	$('#choix_date').trigger('change');

  /*    
    $('#ajouter_associations').parsley().addAsyncValidator('personne_association', function (xhr) {
    	console.log(xhr);
    	return xhr.status;
    	

  	}, '<?php echo $_SESSION["WEBROOT"] ?>/json/validation.php?section=personne_association&personne='+$('[name="id_personne"]').val()+'&annee='+$('[name="annee"]').val());
     */ 
</script>