<?php
$limite = 4;

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

$asso->conseilAdministration();
		
		$total = count($asso->conseil_administration);
		$j=0;
		$ca = '';
		
		// Traitement
		if (count($asso->conseil_administration)>0) {
			// Récupération première année
			ksort($asso->conseil_administration);
			$annee0 = key($asso->conseil_administration);
			$total = (date('Y')) - $annee0 + 1;
			
			// Parcours les années depuis la première pour voir s'il y a des présidents
			for ($i=date('Y'); $i>=$annee0; $i--) {
				$president = (isset($asso->presidents[$i]) ? $asso->presidents[$i] : null);
				
				$ca .= '<tr>';
					  
				if ($president == NULL)  {
					$ca.= ' <td><span class="alerte">'.$i.'</span></td>';
					$ca .= '<td ><span class="alerte">Pas de président</span></td>';
					$ca .= '<td ><span class="actions">
					<button type="button" form-action="associations" form-type="ca" form-id="'.$asso->id_association.'" form-id-lien="'.$i.'" class="right details action"></button>
					
					</span></td>';
				}
				else {
					$ca.= ' <td>'.$i.'</td>';
					$ca .= '<td >'.$president['personne'].'</td>';
					$ca .= '<td ><span class="actions">
					<button type="button" form-action="associations" form-type="ca" form-id="'.$asso->id_association.'" form-id-lien="'.$i.'" class="right details action"></button>
					<button form-action="mail" form-element="'.$president['courriel'].'" class="right envoyer action" title="Contacter le président"></button>
					</span></td>';
				}
				$ca .= '</tr>';
				
				$j++;
				if ($limite && ($j == $limite)) break;
			}
		} 
		
		if ($total==0){
			 $ca.= ' <tr><td><span class="alerte">'.ANNEE_COURANTE.'</span></td>';
					$ca .= '<td ><span class="alerte">CA non renseigné.</span></td>';
					$ca .= '<td ><span class="actions">
					<button type="button" form-action="associations" form-type="ca" form-id="'.$asso->id_association.'" class="right details action"></button>
					</span></td></tr>';
				}
	if ($j >= $limite) {
		$plusCa='<a href="#" class="right plus" form-action="associations" form-type="conseil_administration" form-id="'.$asso->id_association.'">> Voir '.($total-$j).' plus</a>';
	  }

include_once($_SESSION['ROOT'].'/vues/contenus/associations/conseil_administration.php');
?>