<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');



// Initialisation des données
$form = new stdClass;
$form->section = 'distinctions';
$form->destination_validation = "json/sauve.php";
$form->annulation = true;
$form->lien_annulation = 'action_annuler';
$form->personne = isset($_GET['id']) ? $_GET['id'] : null;
$dataParrains='';
$timestamp = (new DateTime())->getTimestamp();

$choixDomaines = '';

// Édition depuis la page générale
if (isset($distinction)) {
	$form->lien_annulation = 'action_retour';
	
	$personne = new personne($distinction->id_distinction);
	$header = '<a href="/personnes/detail/'.$personne->id_personne.'"><h2><span class="icon-personnes"> '.$personne->prenom.' '.$personne->nom.' ('.$personne->numero_adherent.')</h2></a>';

}

// Ajout
if ( (!isset($_GET['id_lien'])) && (!$isChoixPersonne) ) {	
	$form->label_validation = "Ajouter / Enregistrer";
	$form->action = 'modifier'; // On laisse modifier pour rester sur la même page
	
	$Activites = getSelect('activites');
	$menuAvis = getSelect('distinctions_avis');
	
	$menuAnnee = getAnnees('distinctions',  'select', '', array(ANNEE_COURANTE));
	$menuDistinctions = getSelect('distinctions_types');
	$menuDistinctionsDecision = getSelect('distinctions_types_decisions');
	
	$selectFonctions = getSelect('cons_admin_fonctions');
	$menuActivitesPasseesAnneeDebut = $menuActivitesPasseesAnneeFin = $menuActivitesAnneeDebut = $menuActivitesAnneeFin = $menuDistinctionsAnnee = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	
	$menuValidation = getSelect('distinctions_validation');
	
	$menuAnnuaire = '<option value="1" selected>Oui</option>';
	$menuAnnuaire .= '<option value="0">Non</option>';
	
	foreach ( getDistinctionsDomaines() as $id=>$val) {
		if ($val != 'Autres' ) $choixDomaines .= '<br/><input type="checkbox" value="'.$id.'"  id="domaines" 	name="domaines[]"> '.$val.' ';
	}
	$choixDomaines .= '<br/><input type="checkbox" value="9"  id="domaines" name="domaines[]"> Autres ';
	$choixDomaines .= '<input type="text" value="'.$distinction->domaines_autres.'" id="domaines_autres" name="domaines_autres" style="width:200px;">';
}
// Récupération GET et contenu si modification
else   {
	$form->label_validation = "Modifier";
	$form->suppression = true;
	$form->action = 'ajouter';
	
	if (!isset($distinction)) {
		$distinction = new distinction((isset($_GET['id_lien']) ? $_GET['id_lien'] : ''));
		$form->id_lien = (isset($_GET['id_lien']) ? $_GET['id_lien'] : '');
	} else {
		$form->id_lien = $distinction->id_annonce;
	}
	$selectPersonne = $distinction->personne;
	$Activites = getSelect('activites' , array($distinction->activites));
	$menuAvis = getSelect('distinctions_avis', array($distinction->avis));	
	$menuAnnee = getAnnees('distinctions',  'select', '', array($distinction->annee));
	
	$menuDistinctions = getSelect('distinctions_types',array($distinction->distinction_type));
	
	
	$menuDistinctionsDecision = getSelect('distinctions_types_decisions',array($distinction->distinction_type_decision));
	
	$selectFonctions = getSelect('cons_admin_fonctions');
	$menuActivitesAnneeDebut = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	$menuActivitesAnneeFin = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	$menuActivitesPasseesAnneeDebut = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	$menuActivitesPasseesAnneeFin = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	$menuDistinctionsAnnee = getAnnees('periode',  'select', '',array(ANNEE_COURANTE));
	
	//$menuValidation = getSelect('distinctions_validation', array($distinction->validation));
	
	if ($distinction->annuaire == 1) $selected = 'selected ';
	else $selected = ' ';
	$menuAnnuaire = '<option value="1" '.$selected.'>Oui</option>';
	
	if ($distinction->annuaire == 0) $selected = 'selected ';
	else $selected = ' ';
	$menuAnnuaire .= '<option value="0" '.$selected.'>Non</option>';
	
	
	// Parrains
	if (isset($distinction->parrains) && is_countable($distinction->parrains) && count($distinction->parrains)>0) {	
			$affDataParrains = "{   
					'#form#_#index#_nom': ''
				}";
			foreach ($distinction->parrains as $val) {
				$perso = new personne($val);
				$dataParrains .= '<li class="ajoutParrains_0_nom_'.$perso->id_personne.'">'.$perso->nom.' '.$perso->prenom.' ('.$perso->numero_adherent.')
				<a href="#" class="ajoutParrains_0_nom_remove ui-icon ui-icon-circle-close right"></a>
				<input type="hidden" name="ajoutParrains_0_nom_tab_resultat[]" value="'.$perso->id_personne.'" class="ajoutParrains_0_nom_'.$perso->id_personne.'">
				</li>';
				//$scripts .= '$("#ajoutParrains_'.$perso->id_personne.'_nom").autoCompleteSection("personne",3,true,"parrains");';
			}
			$dataParrains=preg_replace('/^\s+|\n|\r|\s+$/m', '', $dataParrains);
	}

	// Documents
	if (isset($distinction->documents) && is_countable($distinction->documents) && count($distinction->documents)>0) {	
			$affDataDocuments='';
			foreach ($distinction->documents as $cle=>$val) {
					$affDataDocuments.='
				<div id="SWFUpload_0_'.$cle.'" class="uploadify-queue-item">
					<label>Nom du fichier</label>
					<input type="hidden" id="SWFUpload_0_'.$cle.'_filename" name="SWFUpload_0_'.$cle.'_filename" value="'.$val['document'].'">
					<input type="text" id="SWFUpload_0_'.$cle.'_nom" name="SWFUpload_0_'.$cle.'_nom" value="'.$val['nom'].'">
					<br>					
					<div class="cancel">						
						<a href="#" class="suppr_fichier" form-fichier="SWFUpload_0_'.$cle.'">X</a>					
					</div>					
					<span class="fileName">V1-Juillet.jpg (506KB)</span>
					<span class="data"> - Complete</span>					
					<div class="uploadify-progress">						
						<div class="uploadify-progress-bar" style="width: 100%;">
						<!--Progress Bar-->
						</div>					
					</div>				
				</div>';
			}
			//$affDataDocuments='<div id="SWFUpload_0_'.$cle.'" class="uploadify-queue-item"></div>';
			$affDataDocuments=preg_replace('/^\s+|\n|\r|\s+$/m', '', $affDataDocuments);
	}
	
	// Activités en cours
	if (isset($distinction->activites) && is_countable($distinction->activites) && count($distinction->activites)>0) {	
		$affDataActivites='';	
			$data=array();
			foreach ($distinction->activites as $val) {
				$data[] = "{   
					'#form#_#index#_association': '".str_replace("'","\'",$val['association'])."',
					'#form#_#index#_fonction': '".html_entity_decode($val['fonction'])."',
					'#form#_#index#_fonction_autre': '".html_entity_decode($val['fonction_autre'])."',
					'#form#_#index#_annee_debut': '".$val['annee_debut']."',
					'#form#_#index#_annee_fin': ''
				}";
			}
			$affDataActivites = lister($data);
	}	
	
	
	// Activités passées
	if (isset($distinction->activites_passees) && is_countable($distinction->activites_passees) && count($distinction->activites_passees)>0) {	
		$data=array();
		$affDataActivitesPassees='';	
			foreach ($distinction->activites_passees as $val) {
				$data[] = "{   
					'#form#_#index#_association': '".str_replace("'","\'",$val['association'])."',
					'#form#_#index#_fonction': '".html_entity_decode($val['fonction'])."',
					'#form#_#index#_fonction_autre': '".html_entity_decode($val['fonction_autre'])."',
					'#form#_#index#_annee_debut': '".$val['annee_debut']."',
					'#form#_#index#_annee_fin': '".$val['annee_fin']."'
				}";
			}
			$affDataActivitesPassees = lister($data);
	}	
	// Activités présentées dans un seul tableau
	if ( !empty($affDataActivites) ) $affDataActivitesPassees = $affDataActivites.','.$affDataActivitesPassees;
	
	// Distinctions
	if (isset($distinction->distinctions) && is_countable($distinction->distinctions) && count($distinction->distinctions)>0) {	
		$data=array();
		$affDataDistinctions='';	
			foreach ($distinction->distinctions as $val) {
				$data[] = "{   
					'#form#_#index#_annee': '".$val['annee']."',
					'#form#_#index#_distinction': '".$val['distinction_passee']."'
				}";
			}
			$affDataDistinctions = lister($data);
	}	
	
	// Domaines
	
	foreach ( getDistinctionsDomaines() as $id=>$val) {
		
		
		if ($val != 'Autres' ) {
			$select="";
			if(isset($distinction->domaines) && (is_array($distinction->domaines) || is_object($distinction->domaines)))
				foreach ( $distinction->domaines as $id2=>$val2) {
					if ($val2 == $id) {
						$select="checked";
						break;
					}
				}
			$choixDomaines .= '<input type="checkbox" value="'.$id.'"  id="domaines" 	name="domaines[]" '.$select.'> '.$val.' | ';
		}
	}
	$choixDomaines .= ' <input type="checkbox" value="9"  id="domaines" name="domaines[]"> Autres ';
	$choixDomaines .= '<input type="text" value="'.$distinction->domaines_autres.'" id="domaines_autres" name="domaines_autres" style="width:200px;">';


}

// Si ajout global
if (isset($isChoixType) && $isChoixType) {
	$form->action = 'ajouter';
}

// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/personnes/distinctions.php');

?>
<script>

$(document).ready(function() {
     
   <?php if (isset($isChoixPersonne) && $isChoixPersonne) : ?>
  		$('#choix_personne').autoCompleteSection('personne',3,false,'personne');
   <?php endif;?>
  
  	//$('#choix_personne').autoCompleteSection('personne',3,false,'personne');
  /*
    var sheepItForm = $('#ajoutActivites').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo $affDataActivites?>
			],
			afterAdd: function(source, newForm) {
				//ajoutActivites_template0
            	var id = newForm[0].id.substring(23);
            	$('#ajoutActivites_'+id+'_association').autocomplete({
					source : '/json/autocomplete.php?section=nom_association',
					 minLength : 3
				});
        	},
	});
	*/
	
	 var sheepItForm = $('#ajoutActivitesPassees').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo (isset($affDataActivitesPassees) ? $affDataActivitesPassees : '') ?>
			],
			afterAdd: function(source, newForm) {
            	//ajoutActivitesPassees_template0
            	var id = newForm[0].id.substring(30);
            	$('#ajoutActivitesPassees_'+id+'_association').autocomplete({
					source : '/json/autocomplete.php?section=nom_association',
					 minLength : 3
				});
        	},
	});

 	var sheepItForm = $('#ajoutDocuments').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo (isset($affData) ? $affData : '')?>
			],	
	});
	
	var sheepItForm = $('#ajoutDistinctions').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo (isset($affDataDistinctions) ? $affDataDistinctions : '') ?>
			],	
	});

	<?php if(isGestion()) : ?>
	var sheepItForm = $('#ajoutParrains').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 1,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo (isset($affDataParrains) ? $affDataParrains : '') ?>
			],	
			afterAdd: function(source, newForm) {
				//alert(newForm[0].id);
            	//ajoutParrains_template0
            	var id = newForm[0].id.substring(22);
            	
            	$('#ajoutParrains_'+id+'_nom').autoCompleteSection('personne',3,true,'parrains');
        	},
	});
	
	<?php else : ?>
	var sheepItForm = $('#ajoutParrains').sheepIt({
			separator: '',
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: false,
			maxFormsCount: 100,
			minFormsCount: 0,
			iniFormsCount: 0,
			data: [
			 <?php echo $affData?>
			],	
			afterAdd: function(source, newForm) {
				//ajoutParrains_template0
            	var id = newForm[0].id.substring(22);
            	$('#ajoutParrains_'+id+'_code_postal').autoCompleteSection('cp_ville',2,false,'ajoutParrains_'+id+'_ville');
            	
            	//$('#ajoutParrains_'+id+'_nom').autoCompleteSection('personne',3,true,'parrains');
        	},
	});
	<?php endif; ?>

    $('#file_upload').uploadify({
        'swf'      : '/css/uploadify.swf',
        'uploader' : '/json/uploadify.php',
        'multi'    : false,
        'width'          :130,
		'height'		 : 30,
        'removeCompleted' : false,
        'buttonText' : 'Choisir le fichier',
        'auto'     : true,
        'translations': {
			'browseButton': 'Parcourir',
			'error': 'Une erreur s\'est produite',
			'completed': 'Terminé',
			'replaceFile': 'Voulez-vous remplacer le fichier',
			'unitKb': 'Ko',
			'unitMb': 'Mo'
		},
        'onUploadSuccess' : function(file, data, response) {
            console.log(data);
             $('#'+file.id).prepend('<label>Nom du fichier</label><input type="hidden" id="'+file.id+'_filename" name="'+file.id+'_filename" value="'+data+'"><input type="text" id="'+file.id+'_nom" name="'+file.id+'_nom"><br>');
        },
        'formData' : { 
        	'destination' : '/upload/distinctions',
        	 'timestamp' : '<?php echo $timestamp;?>',
        	 'token'     : '<?php echo md5("unique_salt" . $timestamp);?>'
        }
        // Put your options here
    });
    
    /*
  	CKEDITOR.replace( 'commentaire', {
    	language: 'fr',
	});
  	CKEDITOR.config.disableNativeSpellChecker = false;
  	CKEDITOR.config.extraPlugins = 'scayt';
    CKEDITOR.config.extraPlugins = 'menubutton';
    CKEDITOR.config.extraPlugins = 'dialog';
    CKEDITOR.config.scayt_autoStartup = true;
    CKEDITOR.config.scayt_sLang = 'fr_FR';
    CKEDITOR.config.removePlugins = 'elementspath';

	
    CKEDITOR.config.toolbar = [
    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste',  '-', 'Undo', 'Redo' ] },
      { name: 'basicstyles', items: [ 'Bold', 'Italic' ] } ,
      { name: 'editing', groups: [ 'spellchecker' ], items: [  'Scayt' ] },
	
	];
	*/
	
	<?php if (!empty($affDataDocuments)) : ?>
		$('#file_upload-queue').html('<?php echo $affDataDocuments ?>');
	<?php endif; ?>
	
	<?php if (!empty($dataParrains)) : ?>
		$('#ajoutParrains_0_nom_resultat').html('<?php echo $dataParrains ?>');
		<?php //echo $scripts ?>
	<?php endif; ?>

});
</script>