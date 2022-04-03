<?php
// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
	$annee = $_GET['select_annee'];
	
} 
// Chargé par ajax
if (isset($_GET['plus'])){
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
				</div>';
}


$perso->assuranceGratuite();
//$perso->assurance_gratuite;
$affAssurance='';

$delegue = getDelegues($perso->region_id,$perso->departement_id,$perso->pays,'personnes');
$emailDelegue = $delegue->courriel;

if (isset($perso->assurance_gratuite)) {
	
	// Menu des années
	$menuAnnee = '';
	foreach ($perso->assurance_gratuite as $key=>$val) {
		// On sauve la première année si elle n'existe pas
		if (empty($annee)) $annee = $key;
	
		if ($annee == $key) $select = ' selected="selected" ';
		else $select = '';
		$menuAnnee .= '<option value="'.$key.'" '.$select.'>'.$key.'</option>';
	}


	// Recherche des associations non renouvelées pour l'année en cours
	if (!empty($perso->assurance_gratuite[$annee-1]))  {
		$array1 = $perso->assurance_gratuite[$annee-1] ;
		$array2 = $perso->assurance_gratuite[$annee] ;
		$nonRenouvelles = array_diff_key($array1, $array2);

		foreach ($nonRenouvelles as $key=>$val) {
	
			if ($val->benevole==1) $benevole='Bénévole';
			else $benevole='';
	
			$affAssurance .= '<tr>';
			$affAssurance .= '	<td>';
			$affAssurance .= '		<strong><a href="'.$_SESSION['WEBROOT'].'associations/detail/'.$val->id_association.'">'.$val->association.'</a></strong>';
			$affAssurance .= '		<p>'.$benevole.' '.$val->fonction.'</p>';
			$affAssurance .= '	</td>';
			$affAssurance .= '	<td class="alerte">';
			$affAssurance .= '	 Non renouvellé pour '.$annee;
			$affAssurance .= '	</td>';
			$affAssurance .= '	<td class="actions">';
				$affAssurance .= '	</td>';
			$affAssurance .= '</tr>';
		}
	}

	foreach ($perso->assurance_gratuite[$annee] as $key=>$val) {
	
		if ($val->benevole==1) $benevole='Bénévole';
		else $benevole='';
		
		$fichier = 'AIN_'.$annee.'_'.$val->id_association.'_'.$perso->id_personne;
		
		$affAssurance .= '<tr>';
		$affAssurance .= '	<td>';
		$affAssurance .= '		<strong><a href="'.$_SESSION['WEBROOT'].'associations/detail/'.$val->id_association.'">'.$val->association.'</a></strong>';
		$affAssurance .= '		<p>'.$benevole.' '.$val->fonction.'</p>';
		$affAssurance .= '	</td>';
		$affAssurance .= '	<td>';
		$affAssurance .= '		'.$val->etat.'';
		if ($val->id_etat > 0 ) $affAssurance .= ' <em>('.convertDate($val->date_etat,'php').')</em>';
		if ($val->id_etat == 0 ) $affAssurance .= ' <em>('.convertDate($val->date,'php').')</em>';
		$affAssurance .= '	</td>';
		$affAssurance .= '	<td class="actions">';
		$affAssurance .= '		<button type="button" form-action="telecharger" form-element="'.$fichier.'"  class="action telecharger right"></button>';
		$affAssurance .= '		<button form-action="envoyer_fichier" form-element="'.$fichier.'" form-type="personnes_attestation" class="right envoyer_fichier action" title="Envoyer le fichier"></button> ';
		$affAssurance .= '		<button type="button" form-action="personnes" form-type="associations" form-id="'.$perso->id_personne.'" form-id-lien="'.$val->id_association.'" class="edit action right"></button>
		';
		$affAssurance .= '	</td>';
		$affAssurance .= '</tr>';
	}

}

include_once($_SESSION['ROOT'].'/vues/contenus/personnes/associations.php');
?>
<script>
	$('#select_annee').on('change', function (e) {
    	var valueSelected = this.value;
    	$("#contenu_associations").load('/controleurs/contenus/personnes/associations.php?id=<?php echo $perso->id_personne?>&select_annee='+this.value);
	}); 
</script>