<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;
$form->destination_validation = "json/sauve.php";

//
// Modes
//
// Configuration du formulaire en fonction de l'endroit où il est appelé
// Utilisé en plus de isGestion
//
// gestion_ajouter 		Ajout depuis l'espace de gestion
// gestion_modifier 	Modification depuis l'espace de Gestion avec formulaire chargé en Ajax ($_GET)
// site_ajouter 		Ajout depuis le site (inscription avec la liste des membres du CA)
// site_modifier 		Modification depuis l'espace de gestion du site 

if (isGestion() && !isset($_GET['id'])) $mode = 'gestion_ajouter';
else if (isGestion() && isset($_GET['id'])) $mode = 'gestion_modifier';
else if (!isGestion() && !isset($assoc)) $mode = 'site_ajouter';
else if (!isGestion() && isset($assoc)) $mode = 'site_modifier';

// Inclusion dans le site
if (!isGestion()) { 

	// Site
	
	// Champs requis

	$requis['numero_dossier'] = 'data-parsley-group="block1" required';
	$requis['nom'] = 'data-parsley-group="block1" required';
	$requis['numero_convention'] = 'data-parsley-group="block1" required';
	$requis['date_declaration_jo'] = 'data-parsley-group="block1" required';
	$requis['numero_siret'] = 'data-parsley-group="block1" required';
	$requis['code_ape_naf'] = 'data-parsley-group="block1" required';
	$requis['ville'] = 'data-parsley-group="block1" required';
	$requis['courriel'] = 'data-parsley-group="block1" required';
	if ( $mode == 'site_ajouter') $requis['mdp'] = 'data-parsley-group="block1" required';

	$requis['nom_ca'] = 'data-parsley-group="block2" required';
	$requis['prenom_ca'] = 'data-parsley-group="block2" required';
	$requis['fonction_ca'] = 'data-parsley-group="block2" required';
	$requis['fonction_ca'] = 'data-parsley-group="block2" required';
	$requis['courriel_ca'] = 'data-parsley-group="block2" required';
	$requis['adresse_ca'] = 'data-parsley-group="block2" required';
	$requis['ville_ca'] = 'data-parsley-group="block2" required';
	
	//  Légendes des champs
	
	$legendes=array();
	$legendes['association_type'] = "Type d'association";
	$legendes['numero_dossier'] = "N°Dossier";
	$legendes['nom'] = "Nom de l'association ou de la collectivité";
	$legendes['sigle'] = "Sigle";
	$legendes['numero_adherent'] = "N°Adhérent";
	$legendes['numero_convention'] = "Numéro de convention";
	$legendes['association_activites'] = "Activités";
	$legendes['date_declaration_jo'] = "Date de déclaration de votre association au Journal Officiel";
	$legendes['numero_siret'] = "Numéro de SIRET";
	$legendes['association_activites'] = "Activités";
	$legendes['code_ape_naf'] = "Code APE/NAF";
	$legendes['adresse'] = "Adresse";
	$legendes['code_postal'] = "Code postal";
	$legendes['telephone_mobile'] = "Téléphone mobile";
	$legendes['telephone_fixe'] = "Téléphone fixe";
	$legendes['fax'] = "Fax";
	$legendes['courriel'] = "Courriel";
	$legendes['mdp_clair'] = "Mot de passe";
	$legendes['logo'] = "Logo";
	
	$commentaires=array();
	$commentaires['association_type'] = "";
	$commentaires['numero_dossier'] = "";
	$commentaires['nom'] = "";
	$commentaires['sigle'] = "";
	$commentaires['numero_convention'] = "";
	$commentaires['association_activites'] = "";
	$commentaires['date_declaration_jo'] = "";
	$commentaires['numero_siret'] = "";
	$commentaires['association_activites'] = "Sélectionnez les activités de votre association";
	$commentaires['code_ape_naf'] = "";
	$commentaires['adresse'] = "Adresse du siège de l'association";
	$commentaires['code_postal'] = "";
	$commentaires['telephone_mobile'] = "";
	$commentaires['telephone_fixe'] = "";
	$commentaires['fax'] = "";
	$commentaires['courriel'] = "";
	$commentaires['mdp_clair'] = "Ce mot de passe vous permettra de vous connecter à votre espace association.";
	$commentaires['logo'] = "Vous pouvez personnaliser votre espace personnel en y insérant votre logo (image format Jpeg). Vous pouvez également l'ajouter ultérieurement.";
	$commentaires['benevole'] ="Ce membre du Conseil d'Administration est également bénévole.";
} else { 

	// GESTION 

	// Champs requis
		
	$requis['numero_dossier'] = '';
	$requis['nom'] = 'required';
	$requis['numero_convention'] = '';
	$requis['date_declaration_jo'] = 'required';
	$requis['numero_siret'] = 'd';
	$requis['code_ape_naf'] = '';
	$requis['ville'] = 'required';
	$requis['courriel'] = 'required';
	
	
	//  Légendes des champs
	
	$legendes=array();
	$legendes['association_type'] = "Type d'association";
	$legendes['numero_dossier'] = "N°Dossier";
	$legendes['nom'] = "Nom";
	$legendes['sigle'] = "Sigle";
	$legendes['numero_convention'] = "numero_convention";
	$legendes['association_activites'] = "Activités";
	$legendes['date_declaration_jo'] = "Déclaration JO";
	$legendes['numero_siret'] = "N°SIRET";
	$legendes['association_activites'] = "Activités";
	$legendes['code_ape_naf'] = "Code APE/NAF";
	$legendes['adresse'] = "Adresse";
	$legendes['code_postal'] = "Code postal";
	$legendes['telephone_mobile'] = "Téléphone mobile";
	$legendes['telephone_fixe'] = "Téléphone fixe";
	$legendes['fax'] = "Fax";
	$legendes['courriel'] = "Courriel";
	$legendes['mdp_clair'] = "Mot de passe";
	$legendes['logo'] = "Logo";

	$commentaires=array();
}



// Ajout
if ($mode == 'gestion_ajouter') {
	$form->annulation = false;
	$form->label_validation = "Ajouter";
	$form->action = 'ajouter';
	$form->section = 'associations';
	
	$villeSelected =  '';
	$AssociationsTypes = getSelect('associations_types');
	
}
// Récupération GET et contenu si modification
if ($mode == 'gestion_modifier')  {
	$form->annulation = true;
	$form->label_validation = "Modifier";
	$form->id_element = $_GET['id'];
	$form->action = 'modifier';
	$form->section = 'associations';
	
	$scripts = "$('#code_postal').hide();";
	if (!empty($assoc->delegue_special)) $scripts .= "$('#delegue_special_select').hide();";
	
	$assoc = new association($_GET['id']);
	
	$villeSelected = '<li class="code_postal_'.$assoc->ville.'">'.$assoc->ville_label.'<a href="#" class="code_postal_remove ui-icon ui-icon-circle-close right"></a><input type="hidden" name="code_postal_tab_resultat[]" value="'.$assoc->ville.'" class="code_postal_'.$assoc->ville.'"></li>';
	$AssociationsTypes = getSelect('associations_types',array($assoc->association_type));
}

$Pays = getPays();
	
$selectProspect = isSelected($assoc->prospect,'checkbox');

// Récupération des contenus des menus
$Activites = getActivites(array($assoc->association_activites));


if ($mode == 'site_modifier') {
	$form->annulation = true;
	$form->action = $mode;
	$form->section = 'associations';
	
	$menuCivilite = menuCivilite();
	$Pays = getPays(ID_FRANCE);

	$AssociationsTypes = getSelect('associations_types');
	$fonctions = getSelect('cons_admin_fonctions');

}
if ($mode == 'site_ajouter') {
	$form->annulation = false;
	$form->action = $mode;
	$form->section = 'associations_inscription';
	
	$menuCivilite = menuCivilite();
	$Pays = getPays(ID_FRANCE);

	$AssociationsTypes = getSelect('associations_types');
	$fonctions = getSelect('cons_admin_fonctions');
	
}
	
// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/associations/ajouter.php');
?>
<script>

jQuery(function($) {

 	$("select[multiple]").asmSelect({
		addItemTarget : 'bottom',
		animate : false,
		highlight : false,
		sortable : false,
		listType : 'ul',
		hideWhenAdded : true,
		removeLabel : '',
		removeClass : 'asmHighlight ui-icon ui-icon-circle-close'
		
	});
	
	<?php if ($mode == 'site_ajouter') : ?>
		var sheepItForm = $('#ajoutCA').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 1,
			iniFormsCount: 1,
			data: [
			 <?php echo $affData?>
			],	
			afterAdd: function(source, newForm) {
				//ajoutCA_template0
            	var id = newForm[0].id.substring(16);
            	
            	// Sélection ville
            	$('#ajoutCA_'+id+'_code_postal').autoCompleteSection('cp_ville',2,false,'ajoutCA_'+id+'_ville');
            	
            	$( '#ajoutCA_'+id+'_code_postal' ).on( "selection", function( event ) {
					$('#ajoutCA_'+id+'_label_code_postal').html('Ville sélectionnée');
				});
				$( '#ajoutCA_'+id+'_code_postal' ).on( "suppression", function( event ) {
					$('#ajoutCA_'+id+'_label_code_postal').html("<?php echo $legendes['code_postal'] ?>");
				});	
				
				$( '#ajoutCA_'+id+'_date_naissance' ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1900:"+new Date().getFullYear()
				  });
	  
				// Changement Pays
				$('#ajoutCA_'+id+'_pays').on('change', function (e) {
					var valueSelected = this.value;
		
					if (valueSelected == "<?php echo ID_FRANCE ?>") {
						  $('#ajoutCA_'+id+'_zone_ville').show();
						  $('#ajoutCA_'+id+'_zone_ville').append('<input hidden="" type="text" name="ajoutCA_'+id+'_ville" id="ajoutCA_'+id+'_ville" data-parsley-trigger="change bind" value="" required>');  
						  $('#ajoutCA_'+id+'_code_postal').show();
						  $('#ajoutCA_'+id+'_zone_ville_pays').hide();
						  
						  $('#ajoutCA_'+id+'_ville').prop("disabled", false);
						  
					} else {
						  $('#ajoutCA_'+id+'_zone_ville').hide();
						  $('#ajoutCA_'+id+'_zone_ville #ville').remove();
						  $('#ajoutCA_'+id+'_code_postal').hide();
						  $('#ajoutCA_'+id+'_pays').show();
						  $('#ajoutCA_'+id+'_zone_ville_pays').show();
						  
						  $('#ajoutCA_'+id+'_ville').prop("disabled", true);
					}
				}); 
				$('#ajoutCA_'+id+'_pays').trigger('change');
				
          	},
		});
		
		
	$.fn.preValider = function () {
		
		// Pré-validation du numéro de dossier
		if (window.etapeInscription == 1) {
			if ($('#numero_dossier').val().length > 0) {
				$.ajax({
					url: '<?php echo GESTION ?>/json/validation.php',
					type: 'get',
					dataType: 'json',
					data: 'section=associations_unique&numero_dossier='+$('#numero_dossier').val(),
					success: function(data) {
					
						if (data.resultat > 0) {
							$("#erreur_numero_dossier").html('Cette association est déjà enregistrée. Vous pouvez vous connecter pour gérer vos bénévoles.')
							return false;
						}
						else {
							$("#erreur_numero_dossier").html('');
							return true;
						}
					 },
					 error: function(jqXHR, textStatus, errorThrown){
					
						alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
						/*
						console.log(errorThrown);
						console.log(textStatus);
						console.log(jqXHR);
						*/
					 }
			
				});
			}
		}
		
		// Pré-validation CAPTCHA
		if (window.etapeInscription == 3) {
		
				$.ajax({
					url: '<?php echo GESTION ?>json/validation.php',
					type: 'get',
					dataType: 'json',
					data: 'section=captcha&captcha='+$('#captcha').val(),
					success: function(data) {
						
						if (data.resultat == false) {
							$("#erreur_captcha").html('Le code de sécurité est faux.')
							return false;
						}
						else {
							$("#erreur_numero_dossier").html('');
							return true;
						}
					 },
					 error: function(jqXHR, textStatus, errorThrown){
					
						alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
						
						console.log(errorThrown);
						console.log(textStatus);
						console.log(jqXHR);
						
					 }
			
				});
			
		}
		
	}
	<?php endif; ?>
	
	
	$('#delegue_special_select').autoCompleteSection('delegue',2,false,'delegue_special');
	$('#code_postal').autoCompleteSection('cp_ville',2,false,'ville');
	
	<?php echo $scripts; ?>
	
	// Gestion des zones à afficher
	$("#association_type").on('change', function (e) {
		var valueSelected = this.value;
		
		if (valueSelected == 2)	{
			$(".zone_commune").show();
			$(".zone_association").hide();
			$('#numero_convention').prop("disabled", false);
			$('#numero_dossier').prop("disabled", true);
		}
		else {
			$(".zone_commune").hide();
			$(".zone_association").show();
			$('#numero_convention').prop("disabled", true);
			$('#numero_dossier').prop("disabled", false);
		}
		
	});
	
	$( "#code_postal" ).on( "selection", function( event ) {
  		$("#label_code_postal").html('Ville sélectionnée');
	});
	$( "#code_postal" ).on( "suppression", function( event ) {
  		$("#label_code_postal").html('Code postal');
	});	
	
	
	

	
	// Init 
	$("#association_type").trigger('change');
	
});
	
</script>