<?php

$limite = 100;

if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);

}
// Chargé par ajax
if (isset($_GET['plus'])){
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
	</div>';
}

		$perso->lesAmis();
	
		$total = count($perso->lesamis);
		$i=0;
		$amis = '';

		// Traitement
		if (count($perso->lesamis)>0) {
			foreach ($perso->lesamis as $annee=>$laf) {	
				
				$carteOK = '';
					
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
                    $commande->date_creation="";
				}
				
				$amis .= '<div >';
					$amis .= '<div class="titre '.$alerte.'">
						<h3 class="left">'.$laf->annee.'</h3>		<h3 class="left">'.$commande->etat_libelle.'</h3>  <em>'.affDate(strtotime($commande->date_creation), true).'</em>';
						if ( isSiege() ) $amis .= '<button type="button" form-action="personnes" form-type="amis" form-id="'.$perso->id_personne.'" form-id-lien="'.$laf->id_laf.'" class="edit action right"></button>';
					$amis .= '</div>';
				
					$amis .= '<div>';
					
						if ($payd && ($laf->annee >= 2015) ) {
							//LAF_TYPE_ANNEE_IDLAF
							if ($laf->carte==1) $carteOK = '&nbsp;<strong style="background-color:rgb(20, 183, 202);color:rgb(228, 228, 229);padding:5px;">OK</strong>';
							$carteClass = $laf->carte == 1 ? '' : 'carte';
							$recu = 'CNB_P_'.$laf->id_laf;
							$amis .= '
									<div class="left detail_laf">
										<p>Reçu
										<button type="button" form-action="telecharger" form-element="'.$recu.'"  class="action telecharger " title="Télécharger le reçu"></button>
										<button form-action="envoyer_fichier" form-element="'.$recu.'" form-type="personnes_amis" class=" envoyer_fichier action" title="Envoyer le reçu"></button>
										</p>
									</div>
									<div class="left detail_laf ">
										<p><span class="zone_carte id_'.$laf->id_laf.'">Carte '.$carteOK.'</span>
										<button type="button" form-action="telecharger" 
										form-id-laf="'.$laf->id_laf.'" form-element="CAR_P_'.$laf->id_laf.'"  class="action telecharger '. $carteClass . '" 
										title="Imprimer la carte"></button>
										</p>
									</div>
									<div class="left detail_laf">
										<p>Lettre
										<button type="button" form-action="telecharger" form-element="LET_P_'.$laf->id_laf.'"  class="action telecharger " title="Imprimer la Lettre"></button>
										</p>
									</div>';
						} else if ($laf->annee < 2015){
							$amis .= '<div class="left detail_laf">Les documents relatifs à l\'adhésion aux <strong>Amis de la Fondation '.$laf->annee.'</strong> ne sont pas gérés.</div>';
						}
				
						
				
						$amis .= '<div class="left detail_laf">
						<!--	<p>Information association banque postale : <strong>'.strtoupper(ouinon($laf->informations_bp)).'</strong></p>-->';
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
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="amis" form-id="'.$perso->id_personne.'">> Voir '.($total-$limite).' plus</a>';
	  }
  

include_once($_SESSION['ROOT'].'/vues/contenus/personnes/amis.php');
?>