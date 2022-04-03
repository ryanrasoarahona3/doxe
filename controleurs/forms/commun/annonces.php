<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

// Si chargé depuis la page de détail on ne récupère pas le get, l'annonce est déjà connue
if(!isset($annonce)) $type = $_GET['action'];
else $type=$annonce->type;

// Initialisation des données
$form = new stdClass;
$form->section = $type.'_annonces';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->lien_annulation = 'action_annuler';

if(!isset($annonce)) $form->id = $_GET['id'];
else $form->id = $annonce->createur;
$form->type = $type;

// Édition depuis la page générale
if (isset($annonce)) {
	$form->lien_annulation = 'action_retour';
	
	if ($annonce->type=='personnes') {
		$personne = new personne($annonce->createur);
		$header = '<h1><span class="icon-personnes"></span><a href="/personnes/detail/'.$personne->id_personne.'">'.$personne->prenom.' '.$personne->nom.'</a> </h1><br><em><strong>N°Adhérent : '.$personne->id_personne.'</strong></em><br/><br/>';
		
	}
	if ($annonce->type=='associations') {
		$asso = new association($annonce->createur);
		$header = '<h1><span class="icon-associations"></span><a href="/associations/detail/'.$asso->id_association.'">'.$asso->nom.'</a> </h1><br><em><strong>N°Dossier : '.$asso->numero_dossier.'</strong></em><br/><br/>';
	}
}

// Ajout
if ((!isset($_GET['id_lien'])) && (!isset($annonce))) {	
	$form->label_validation = "Ajouter";
	$form->action = 'modifier'; // On laisse modifier pour rester sur la même page
	
	$menuValidation = getSelect('annonces_validation', array(3));
	
	$Activites = getSelect('activites');
}
// Récupération GET et contenu si modification
else   {
	$form->label_validation = "Modifier";
	$form->suppression = true;
	
	if (!isset($annonce)) {
		$annonce = new annonce($_GET['id_lien']);
		$form->id_lien = $_GET['id_lien'];
		$form->action = 'modifier';
	} else {
		$form->id_lien = $annonce->id_annonce;
		$form->action = 'ajouter';
	}
	
	$menuValidation = getSelect('annonces_validation', array($annonce->validation));

	$Activites = getSelect('activites' , $annonce->activites);
}

// Si ajout global
if ($isChoixType) {
	$form->action = 'ajouter';
	$form->section = 'annonces';
}

// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/commun/annonces.php');
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
	
	$( document.body ).on('click','#action_retour',function(event) {
		window.location.href='/annonces/?auto&retour';
	});
	
	$('#choix_association').autoCompleteSection('association',3,false,'createur');
	
	$('#choix_personne').autoCompleteSection('personne',3,false,'createur');
		
	// Choix Personne / Asso (pour l'ajout depuis la page Annonces)	
	<?php if (isGestion() && $isChoixType) : ?>
		$("#type").on('change', function (e) {
			var valueSelected = this.value;
			if (valueSelected== 'personnes') {
				$("#select_association").hide();
				$("#select_personne").show();
				$("#createur").val('');
			}
			if (valueSelected== 'associations') {
				$("#select_association").show();
				$("#select_personne").hide();
				$("#createur").val('');
			}
		});	
		// init
		$("#type").trigger("change");
	<?php endif; ?>
	
	
});
</script>