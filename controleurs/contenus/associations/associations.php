<?php
$limite = 4;

// Récupération GET en cas d'inclusion AJAX
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$asso = new association($_GET['id']);
	
}

// Chargé par ajax
if (isset($_GET['plus'])){
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
				</div>';
}


// Compte le nombre de bénévoles
$asso->nbrBenevoles();


// CA
if ($asso->association_type == 1) 
	$president = new personne ($asso->presidents[ANNEE_COURANTE]['id_personne']);
else {
	if(!empty($asso->gestionnaire)) $president = new personne ($asso->gestionnaire);
}

// Délégué
if ($asso->delegue_special >0 ) {
	$delegue = new personne($asso->delegue_special);
} else {
	$delegue = getDelegues($asso->region_id,$asso->departement_id,$asso->pays,'associations');
}
$emailDelegue = $delegue->courriel;


$assuranceGratuite = '';

$total = count($asso->nbr_benevoles);


if (count($asso->nbr_benevoles) > 0 ) {
		if (!array_key_exists (ANNEE_COURANTE,$asso->nbr_benevoles )) {
			$assuranceGratuite .= '
			<tr>
			  <td class="alerte">'.ANNEE_COURANTE.'</td>
			  <td align="center" class="alerte" colspan="2" >Non renouvellés</td>
			  <td class="actions"></td>
			</tr>';
		}
		
		$i=0;
		
		
		
		foreach ($asso->nbr_benevoles as $annee=>$totalBenevoles) {
			if (!empty( $annee)) {
				$assuranceGratuite .= '
				<tr>
				  <td >'.$annee.'</td>
				  <td align="right">'.$totalBenevoles.'</td>
				  <td align="right">';
				$diff = $totalBenevoles - $asso->nbr_benevoles[$annee-1];
				if ($diff != 0) {
					if ($diff>0) $diff = '+ '.$diff;
					$assuranceGratuite .= $diff.'  bénévole(s)';
				}
				
				$assuranceGratuite .= '</td>
					  <td class="actions">
					    <button type="button" form-action="telecharger_multiple" form-element="'.$annee.'_'.$asso->id_association.'" form-type="'.$asso->association_type.'" form-date="'.$annee.'" class="action telecharger right" title="Télécharger les attestations"></button>
					    <button form-action="envoyer_multiple" form-element="'.$annee.'_'.$asso->id_association.'" form-type="'.$asso->association_type.'" form-date="'.$annee.'" class="right envoyer_fichier action" title="Envoyer les attestations"></button> 
					  	<button form-action="lien" form-element="'.$_SESSION['WEBROOT'].'personnes?auto&inscrit='.$annee.'&id_association='.$asso->id_association.'" class="right personnes action" title="Voir tous les membres"></button> 
					 </td>
					</tr>';
					$i++;
					if ($limite && ($i == $limite)) break;
			}
		}
		
		if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="associations" form-type="associations" form-id="'.$asso->id_association.'">> Voir '.($total-$limite).' plus</a>';
	  }


} else {
	$assuranceGratuite = '';
}
include_once($_SESSION['ROOT'].'/vues/contenus/associations/associations.php');
?>