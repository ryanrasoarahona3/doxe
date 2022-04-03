<?php
$limite = 6;

if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
	
} 
// Chargé par ajax
if (isset($_GET['plus'])){
	$limite = false;
	$fermer = '
	<div id="zone_validation">
			<button type="button" id="action_annuler" class="annuler">X Fermer</button>
	</div>';
}

$perso->distinctions();
$total = count($perso->distinctions);
$i=0;
$amis = '';
$affDistinctions = '';
//d($perso->distinctions);
		// Traitement
		if (count($perso->distinctions)>0) {
			foreach ($perso->distinctions as $id=>$distinction) {	
				
				if ($distinction->distinction_type_decision == 0) {
					$alerte ='alerte';
					$distinctionLabel = 'Demande : '.$distinction->distinction_type_label;
				}
				else {
					$alerte ='';
					$distinctionLabel = $distinction->distinction_type_decision_label;
				}
				
				$affDistinctions .= '
					<div class="titre '.$alerte.'" style="height:auto;">
						<h3 class="left" style="width:80%;">'.$distinction->annee.' - '.$distinctionLabel.'</h3>  
						<button form-action="lien" form-element="/distinctions/detail/'.$distinction->id_distinction.'" class="left right details action"></button>
					</div>';
				
				if ($distinction->distinction_type_decision != 0) $affDistinctions .= '	
					<!--<p>Annuaire de l’amicale : OUI</p>
					<a href="#">> Envoyer email de félicitaton 2014</a><br/>
					<a href="#">> Envoyer adhésion Cercle</a><br/>-->';
				
				$i++;
				if ($limite && ($i == $limite)) break;
			}
			
		}
 if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="distinctions" form-id="'.$perso->id_personne.'">> Voir '.($total-$limite).' plus</a>';
	  }
	  
include_once($_SESSION['ROOT'].'/vues/contenus/personnes/distinctions.php');
?>