<?php

$limite = 100;

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

		$asso->lesAmis();
	
		$total = count($asso->lesamis);
		$i=0;
		$amis = '';

		// Traitement
		if (count($asso->lesamis)>0) {
			foreach ($asso->lesamis as $annee=>$laf) {	
					
				if ($laf->annee >= 2015) {
					
					$commande = new commande($laf->id_commande);
					
					switch($commande->etat) {
						case ETAT_PAYE :
							$alerte='';
							$payd=true;
						break;
					
						default :
							$alerte=' attention ';
							$payd=false;
						break;
					}
					
					if ($commande->payement == ID_MANDAT) $payd = true;
				
				} else {
					$alerte='';
					$payd=true;
					$commande = new commande();
					$commande->etat_libelle='Encaissé';
				}
				
				$amis .= '<div >';
					$amis .= '<div class="titre '.$alerte.'">
						<h3 class="left">'.$laf->annee.'</h3>		<h3 class="left">'.$commande->etat_libelle.'</h3>  <em>'.affDate(strtotime($commande->date_creation), true).'</em>';
					if ( isSiege() ) 	$amis .= '<button type="button" form-action="associations" form-type="amis" form-id="'.$asso->id_association.'" form-id-lien="'.$laf->id_laf.'" class="edit action right"></button>';
					$amis .= '</div>';
				
					$amis .= '<div>';
					
						if ($payd && ($laf->annee >= 2015) ) {
							//LAF_TYPE_ANNEE_IDLAF
							$recu = 'CNB_A_'.$laf->id_laf;
							$amis .= '<div class="left detail_laf">
										<p>Attestation

										<button type="button" form-action="telecharger" form-element="ALI_'.$laf->annee.'_'.$asso->id_association.'"  class="action telecharger " title="Télécharger l\'attestation"></button>
										<button form-action="envoyer_fichier" form-element="ALI_'.$laf->annee.'_'.$asso->id_association.'" form-type="associations_amis" class=" envoyer_fichier action" title="Envoyer l\'attestation"></button>
										</p>
									</div>	
									<div class="left detail_laf">
										<p>Reçu
						
										<button type="button" form-action="telecharger" form-element="'.$recu.'"  class="action telecharger " title="Télécharger le reçu"></button>
										<button form-action="envoyer_fichier" form-element="'.$recu.'" form-type="associations_amis" class=" envoyer_fichier action" title="Envoyer le reçu"></button>
										</p>
									</div>
									<div class="left detail_laf">
										<p>Carte
						
										<button type="button" form-action="telecharger" form-element="CAR_A_'.$laf->id_laf.'"  class="action telecharger " title="Imprimer la carte"></button>
										</p>
									</div>
									
									<div class="left detail_laf">
										<p>Lettre
						
										<button type="button" form-action="telecharger" form-element="LET_A_'.$laf->id_laf.'"  class="action telecharger " title="Imprimer la Lettre"></button>
										</p>
									</div>';
						} else if ($laf->annee < 2015){
							$amis .= '<div class="left detail_laf">Les documents relatifs à l\'adhésion aux <strong>Amis de la Fondation</strong> ne sont pas gérés.</div>';
						}
				
						
				
						$amis .= '<div class="left detail_laf">';
							if (!empty($laf->connaissance_label)) $amis .= '<p>Origine Adhésion : <strong>'.$laf->connaissance_label.'</strong></p>';
						$amis .= '</div>';
					
					$amis .= '</div>';
					
			 $amis .= '</div>';
			 $amis .= '<br class="clear">';
			$i++;
			if ($limite && ($i == $limite)) break;
		  }
	  }
  		
  	 
  		
	  if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="associations" form-type="amis" form-id="'.$asso->id_association.'">> Voir '.($total-$limite).' plus</a>';
	  }
  

include_once($_SESSION['ROOT'].'/vues/contenus/associations/amis.php');
?>