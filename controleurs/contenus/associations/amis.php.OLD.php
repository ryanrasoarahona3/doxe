<?php

$limite = 2;

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
		$total = 0;
		$total = count($asso->lesamis);
		$i=0;
		$amis = '';

		// Traitement
		if (count($asso->lesamis)>0) {
			foreach ($asso->lesamis as $annee=>$laf) {			
				
				switch($laf->etat_paiement) {
					case 'success' :
						$alerte='';
						$payd=true;
					break;
					case 'pending' :
						$alerte=' attention ';
						$payd=false;
					break;			
					default:
						$alerte=' attention ';
						$payd=false;
						$laf->etat_label = 'PROBLÈME COMMANDE';
					break;
			}
				
					$amis .= '<div class="titre '.$alerte.'">
						<h3 class="left">'.$laf->annee.'</h3>		<h3 class="left">'.$laf->etat_label.'</h3>  <em>'.$laf->date_paiement.'</em>
						<button type="button" form-action="associations" form-type="amis" form-id="'.$asso->id_association.'" form-id-lien="'.$laf->id_laf.'" class="edit action"></button>
					</div>';
				
				
					if ($payd) {
						//LAF_TYPE_ANNEE_IDLAF
						$recu = 'CNB_A_'.$laf->id_laf;
						$amis .= '<div class="left">
									<p>Attestation

									<button type="button" form-action="telecharger" form-element="ALA_'.$laf->annee.'_'.$perso->id_personne.'"  class="action telecharger " title="Télécharger l\'attestation"></button>
									<button form-action="envoyer_fichier" form-element="ALA_'.$laf->annee.'_'.$perso->id_personne.'" form-type="personnes_amis" class=" envoyer_fichier action" title="Envoyer l\'attestation"></button>
									</p>
								</div>	
								<div class="left">
									<p>Reçu
						
									<button type="button" form-action="telecharger" form-element="'.$recu.'"  class="action telecharger " title="Télécharger le reçu"></button>
									<button form-action="envoyer_fichier" form-element="'.$recu.'" form-type="personnes_amis" class=" envoyer_fichier action" title="Envoyer le reçu"></button>
									</p>
								</div>';
					}
				
					if(!empty($laf->commande)) {
						$temp = explode('_',$laf->commande);
						$amis .= '<button form-action="lien" form-element="'.SITE_ADMIN.'commerce/orders/'.$temp[2].'" class="right details action" title="Voir la comande"></button> ';
					}	
					
					$amis .= '<div class="left">';
						// Désactivé if ($laf->assurance_gmf == 1) $amis .= '<p>Assurance GMF : <strong>'.strtoupper(ouinon($laf->assurance_gmf)).'</strong></p>';
						if ($laf->logiciel_citizenplace == 1) $amis .= '<p>Logiciel CitizenPlace : <strong>'.strtoupper(ouinon($laf->logiciel_citizenplace)).'</strong></p>';
						if ($laf->aide_citizenplace == 1) $amis .= '<p>Aide CitizenPlace : <strong>'.strtoupper(ouinon($laf->aide_citizenplace)).'</strong></p>';
						if ($laf->assurance_groupama == 1) $amis .= '<p>Assurance Groupama : <strong>'.strtoupper(ouinon($laf->assurance_groupama)).'</strong></p>';
						if ($laf->acces_info_banque_postale == 1) $amis .= '<p>information Banque Postale : <strong>'.strtoupper(ouinon($laf->acces_info_banque_postale)).'</strong></p>';
					$amis .= '</div>';
					$amis .= '<div class="left">';		
						$amis .= '<p>Nombre d\'adhérents : <strong>'.$laf->nbr_adherents.'</strong></p>
						<p>Nombre de salariés : <strong>'.$laf->nbr_salaries.'</strong></p>
						<p>Budget de fonctionnement : <strong>'.formateMontant($laf->budget_fonctionnement,false).' </strong></p>';
					$amis .= '</div>';
			
			$i++;
			if ($limite && ($i == $limite)) break;
		  }
	  }

	  if ( ($total > $i) && ($total>0)) {
		$plus='<a href="#" class="right plus" form-action="associations" form-type="amis" form-id="'.$perso->id_personne.'">> Voir '.($total-$limite).' plus</a>';
	  }
  

include_once($_SESSION['ROOT'].'/vues/contenus/associations/amis.php');
?>