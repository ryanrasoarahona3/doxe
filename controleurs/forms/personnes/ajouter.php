<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'personnes';
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

	$requis['nom'] = 'required';
	$requis['prenom'] = ' required';
	$requis['date_naissance'] = ' required';
	if ( $mode == 'site_ajouter') $requis['mpd'] = ' required';
	$requis['adresse'] = ' required';
	$requis['ville'] = ' required';
	$requis['courriel'] = ' required';
	
	$commentaires=array();
	$commentaires['mdp'] = "Ce mot de passe vous permettra de vous connecter à votre espace association.";
	$commentaires['newsletter'] = "Souhaitez-vous vous abonner à la lettre d'information du CNB ?.";
	 
} else { 

	// GESTION 

	// Champs requis
		
	$requis['nom'] = 'required';
	$requis['prenom'] = ' required';
	$requis['civilite'] = ' required';
	$requis['date_naissance'] = ' required';
	if ( $mode == 'site_ajouter') $requis['mpd'] = ' required';
	$requis['adresse'] = ' required';
	$requis['ville'] = ' required';
	$requis['courriel'] = ' required';

}


// Ajout
if ($mode == 'gestion_ajouter') {
	$form->annulation = false;
	$form->label_validation = "Ajouter";
	$form->action = 'ajouter';
	
	$villeSelected =  '';
	$Pays = getPays();
	$newsletterOui = ' checked ';
	
}
// Récupération GET et contenu si modification
else if ($mode == 'gestion_modifier')  {
	$form->annulation = true;
	$form->label_validation = "Modifier";
	$form->id_element = $_GET['id'];
	$form->action = 'modifier';
	
	$scripts = "$('#code_postal').hide();";
		
	$perso = new personne($_GET['id']);

	if ($perso->newsletter == 1) $newsletterOui = ' checked ';
	if ($perso->newsletter == 0) $newsletterNon = ' checked ';
	
	if ($perso->prospect == 1) $prospectOui = ' checked ';
	if ($perso->prospect == 0) $prospectNon = ' checked ';

				

	if (!empty($perso->ville)) $villeSelected = '<li class="code_postal_'.$perso->ville.'">'.$perso->ville_label.'<a href="#" class="code_postal_remove ui-icon ui-icon-circle-close right"></a><input type="hidden" name="code_postal_tab_resultat[]" value="'.$perso->ville.'" class="code_postal_'.$perso->ville.'"></li>';
	$Pays = getPays($perso->pays);

}

if ($mode == 'site_modifier') {
	$form->annulation = true;
	$form->action = $mode;
	$form->section = 'personnes';
	$form->label_validation = "Modifier";
	$Pays = getPays($perso->pays);
}
if ($mode == 'site_ajouter') {
	$form->annulation = false;
	$form->action = $mode;
	$form->section = 'personnes_inscription';
	$form->label_validation = "Valider";
	$Pays = getPays();
}

// Morceau de code ajouté afin d'éviter les messages d'avertissement sur /personnes/ajouter
if(!isset($perso))
    $perso = new personne();
if(!isset($commentaires))
    $commentaires = array();

// Il se peut qu'il y a un oubli d'inclusion (revoir la structure de l'application)
$attributs_commentaires = array("civilite", "nom", "prenom", "nom_jeune_fille", "date_naissance", "mdp", "adresse", "pays",
    "pays", "code_postal", "code_pays", "ville_pays", "telephone_fixe", "telephone_mobile", "courriel", "profession", "portrait",
    "presse", "elu");
// TODO: type utilisé pour ces variables
$requis_attributes = array("prenom", "nom_jeune_fille", "mdp");
foreach($attributs_commentaires as $attr)
    if(!isset($commentaires[$attr]))
        $commentaires[$attr] = "";
foreach($requis_attributes as $attr)
    if(!isset($requis[$attr]))
        $requis[$attr] = "";


// Newsletter OUI/NON
// TODO: comment ça fonctionne
if(!isset($newsletterOui))
    $newsletterOui = "";
if(!isset($newsletterNon))
    $newsletterNon = "";

// Prospect OUI/NON
if(!isset($prospectOui))
    $prospectOui = "";
if(!isset($prospectNon))
    $prospectNon = "";

$selectProspect = isSelected($perso->prospect,'checkbox');

// Menu civilité
$menuCivilite = menuCivilite($perso->civilite);


// Menu délégué statut
$menuDelegueStatut = '';
if ($perso->delegue_statut =='1') {
	$select1 = 'selected';
	$masqueDelegue="visible";
} else $select1='';
if ($perso->delegue_statut =='2') {
	$select2 = 'selected';
	$masqueDelegue="visible";
} else $select2='';
$menuDelegueStatut .= '<option value="0">Aucun</option>';
$menuDelegueStatut .= '<option value="1" '.$select1.'>Conseillé</option>';
$menuDelegueStatut .= '<option value="2" '.$select2.'>Délégué</option>';

// Menu délégué type
$menuDelegueType = '';
if ($perso->delegue_type =='1') {
	$select1 = 'selected';
	$masqueRegions="visible";
} else $select1='';
if ($perso->delegue_type =='2') {
	$select2 = 'selected'; 
	$masqueRegions="visible";
} else $select2='';
if ($perso->delegue_type =='3') {
	$select3 = 'selected'; 
	$masqueRegions="masque";
} else $select3='';
$menuDelegueType .= '<option value="1" '.$select1.'>Régional</option>';
$menuDelegueType .= '<option value="2" '.$select2.'>Départemental</option>';
$menuDelegueType .= '<option value="3" '.$select3.'>Circonscription</option>';

if ($perso->delegue_adjoint == 1) $selectAdjointOui = ' checked ';
if ($perso->delegue_adjoint == 0) $selectAdjointNon = ' checked ';

if ($perso->delegue_habilite == 1) $selectHabiliteOui = ' checked ';
if ($perso->delegue_habilite == 0) $selectHabiliteNon = ' checked ';

if ($perso->siege_habilite == 1) $selectSiegeHabiliteOui = ' checked ';
if ($perso->siege_habilite == 0) $selectSiegeHabiliteNon = ' checked ';




				
// Delegué spécial
if (is_array($perso->association_special_label)) {
	$delegueSpecial = '';
	foreach ($perso->association_special_label as $id=>$nom) {
		$delegueSpecial .= '<li class="association_special_select_'.$id.'">'.$nom.'';		
		$delegueSpecial .= '<a href="#" class="association_special_select_remove ui-icon ui-icon-circle-close right"></a>';		
		$delegueSpecial .= '<input type="hidden" name="association_special_select_tab_resultat[]" value="'.$id.'" class="association_special_select_'.$id.'">';		
		$delegueSpecial .= '</li>';		
	}
}
					
// Récupération des contenus des menus
$ElusFonctions = getSelect('elus_fonctions' , array($perso->elu), array('fonction'));

// Récupération des contenus des menus
$menuSiege = getSelect('siege' , array($perso->siege), array('nom'));

// Création Menus régions départements
$MenuRegionsDepartements = '';
$connect = connect();
$req = "SELECT * FROM regions order by nom ASC";
try {
	  $requete = $connect->query($req);
   
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		
		if (!empty($perso->delegue_regions)) {
			if (in_array($element->id, $perso->delegue_regions)) $checked = ' checked ';
			else $checked = '';
		} else $checked = '';
		$MenuRegionsDepartements .= '<fieldset class="col05">';
		$MenuRegionsDepartements .= '<label >'.$element->nom.' <input type="checkbox" name="delegue_regions[]"  value="'.$element->id.'" id="delegue_regions" '.$checked.'/></label>';
		
		$MenuRegionsDepartements .= '<select type="text" title="Choisissez"  multiple="multiple" name="delegue_departements[]" id="delegue_departements" >';
		
		// Departements
		$reqDep = "SELECT * FROM departements WHERE region = '".$element->id."' order by numero ASC";
		try {
			  $requete2 = $connect->query($reqDep);
   
			  // Traitement
			  while( $element2 = $requete2->fetch(PDO::FETCH_OBJ)){
			  		if (!empty($perso->delegue_departements)) {
			  			if (in_array($element2->id, $perso->delegue_departements)) $selected = ' selected="selected" ';
						else $selected = '';
			   		} else $selected = '';
			   		$MenuRegionsDepartements .= '<option value="'.$element2->id.'" '.$selected.'>'.$element2->numero.' - '.$element2->nom.'</option>';
			   }
				
  
			} catch( Exception $e ){
			  echo 'Erreur de lecture de la base : ', $e->getMessage();
			}
			$MenuRegionsDepartements .= '</select>';
				
		
		$MenuRegionsDepartements .= '</select>';
		$MenuRegionsDepartements .= '</fieldset>';
	  }
	
  
	} catch (Exception $e) {
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}



// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/personnes/ajouter.php');
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
	
	$( "#date_naissance" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
      	yearRange: "1900:"+new Date().getFullYear()
      });
      
	$('#association_special_select').autoCompleteSection('association',2,true,'');

	$('#code_postal').autoCompleteSection('cp_ville',2,false,'ville');
	
	// Afficher les délégués spéciaux
   $( "#affiche_selection_delegue" ).click(function() {
	  if ($( "#selection_delegue" ).css('display') == 'none') {
	  	$( "#selection_delegue" ).slideDown( "fast", function() {
			// Animation complete.
	  	});
	  } else {
	  	$( "#selection_delegue" ).slideUp( "fast", function() {
			// Animation complete.
	  	});
	  }
	});
	
	$('#delegue_type').on('change', function (e) {
    	var valueSelected = this.value;
    	
    	if (valueSelected == 3) {
    		  $("#delegue_habilite").prop('checked',false);
    		  $("fieldset.region").hide();
    		  $("fieldset.habilite").hide();
    	} else {
    		 $("fieldset.region").show();
    		 $("fieldset.habilite").show();
    	}
	}); 
	
	$('#pays').on('change', function (e) {
    	var valueSelected = this.value;
    	
    	if (valueSelected == "<?php echo ID_FRANCE ?>") {
    		  $("#zone_ville").show();
    		  $("#zone_ville").append('<input hidden="" type="text" name="ville" id="ville" data-parsley-trigger="change bind" value="" required="">');  
    		  $("#code_postal").show();
    		  $("#zone_ville_pays").hide();
    		  
    	} else {
    		  $("#zone_ville").hide();
    		  $('#zone_ville #ville').remove();
    		  $("#code_postal").hide();
    		  $("#ville_pays").show();
    		  $("#zone_ville_pays").show();
    		
    	}
	}); 
	
	<?php 
	if ($masqueDelegue=="visible") {
		 echo '$("#selection_delegue").show();';
	}
	if ($masqueRegions=="visible") {
		 echo '$("fieldset.region").show();';
	} else {
		echo '$("fieldset.region").hide();';
	}
	?>
	<?php echo $scripts; ?>
	
	
	<?php if ($mode == 'site_ajouter') : ?>
		$.fn.preValider = function () {
		
			if ($('#courriel').val().length > 0) {
				$.ajax({
					url: '<?php echo GESTION ?>/json/validation.php',
					type: 'get',
					dataType: 'json',
					data: 'section=courriel_unique&courriel='+$('#courriel').val(),
					success: function(data) {
				
						if (data.resultat > 0) {
							$("#erreur_courriel").html('Votre courriel est déjà enregistré. Vous devez vous connecter pour gérer vos informations personnelles.')
							return false;
						}
						else {
							$("#erreur_courriel").html('');
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
	<?php endif ; ?>
	
	<?php if (($mode == 'gestion_modifier') || ($mode == 'site_modifier')) : ?>
		//initialisation pour un fomulaire déjà remplit
		if ( $('#pays').val() == "<?php echo ID_FRANCE ?>" ) {
			 $("#zone_ville").show();
			 $("#code_postal").hide();
				  $("#zone_ville_pays").hide();
		} else {
			$("#zone_ville").hide();
			$('#zone_ville #ville').remove();
			$("#code_postal").hide();
			$("#ville_pays").show();
			$("#zone_ville_pays").show();
		}
	<?php else : ?>
		$('#pays').trigger('change');
	<?php endif ; ?>
	
	$('#file_upload').uploadify({
        'swf'      : '/css/uploadify.swf',
        'uploader' : '/json/uploadify.php',
        'multi'    : false,
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
            
            data = data.replace(".pngii", ".png");
            data = data.replace(".jpgii", ".jpg");
            data = data.replace(".jpegii", ".jpeg");
            data = data.replace(".PNGii", ".PNG");
            data = data.replace(".JPGii", ".JPG");
            data = data.replace(".JPEGii", ".JPEG");
            console.log('Nom du fichier : '+data);
            $('#'+file.id).prepend('<input type="hidden" id="portrait" name="portrait" value="'+data+'">');
        },
        'formData' : { 
        	'destination' : '/upload/portraits',
        	 'timestamp' : '<?php echo $timestamp;?>',
        	 'token'     : '<?php echo md5("unique_salt" . $timestamp);?>'
        }
        // Put your options here
    });
    

	
	<?php if (!empty($affDataDocuments)) : ?>
		$('#file_upload-queue').html('<?php echo $affDataDocuments ?>');
	<?php endif; ?>
	
});
	
</script>