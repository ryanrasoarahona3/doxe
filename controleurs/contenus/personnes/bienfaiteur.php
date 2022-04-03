<?php


if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
				</div>';
} else {
	$limite = 3;
}


$perso->commandes();

foreach ($perso->commandes as $commande) {
	
	$detail_commande = new commande($commande->id_commande);
	// Cherche si l'achat contien un DON
	$don = 0;
	foreach ($detail_commande->produits as $id_produit=>$produit) {
  			
  			if ($produit->id_source == ID_DON) $don = $don + $produit->prix;
  			//print_r($detail_commande);
	}	
	
	
	if ($don!=0) {
	
		// Vérification du/des paiements
		
		if ($detail_commande->etat != ETAT_PAYE) $alerte = ' class="attention" ';
		else $alerte = ' ';
		
		$bienfaiteur .= '<tr '.$alerte.'>
			  
				  <!--<td>'.$commande->numero_commande.'</td>-->
				   <td>'.affDate($commande->date_creation).'</td>
				  <td align="right">'.formateMontant($don).'</td>
				  
				  <td class="actions">';
				  
				  if ($paye) $bienfaiteur .= '<button type="button" form-action="telecharger" form-element="DON_'.$commande->commande_number.'"  class="action telecharger right" title="Télécharger le reçu fiscal"></button>';
				  if ($paye) $bienfaiteur .= '<button form-action="envoyer_fichier" form-element="DON_'.$commande->commande_number.'" form-type="personnes_achat" class="right envoyer_fichier action" title="Envoyer le reçu"></button>';
					//$bienfaiteur .= '<button form-action="lien" form-element="'.SITE_ADMIN.'commerce/commandes/'.$commande->commande_id.'" class="right details action" title="Voir la comande"></button> 
					$bienfaiteur .= '<button form-action="lien" form-element="/boutique/detail/'.$commande->id_commande.'" class="right details action" title="Voir la comande"></button> 
				  </td>
				</tr>';
			$i++;
	
		if ($limite && ($i == $limite)) break;
	} 
}


if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="achats" form-id="'.$perso->id_personnes.'">> Voir '.($total-$limite).' plus</a>';
}





/*
$req = "SELECT site_field_data_commerce_unit_price.commerce_unit_price_amount AS montant, 
	site_field_data_commerce_unit_price.commerce_unit_price_data AS donnees, 
	site_commerce_commande.commande_id AS id_commande, 
	site_commerce_payment_transaction.`changed` AS date_paiement
FROM site_field_data_commerce_line_items INNER JOIN site_commerce_line_item ON site_field_data_commerce_line_items.commerce_line_items_line_item_id = site_commerce_line_item.line_item_id
	 INNER JOIN site_commerce_commande ON site_commerce_commande.commande_id = site_field_data_commerce_line_items.entity_id
	 INNER JOIN site_users ON site_users.uid = site_commerce_commande.uid
	 INNER JOIN site_field_data_commerce_unit_price ON site_field_data_commerce_line_items.commerce_line_items_line_item_id = site_field_data_commerce_unit_price.entity_id
	 INNER JOIN site_commerce_payment_transaction ON site_commerce_commande.commande_id = site_commerce_payment_transaction.commande_id
WHERE   site_commerce_payment_transaction.`status` = 'success' AND site_commerce_line_item.line_item_label LIKE 'DON-%' AND site_users.`name` = 'personnes_".$perso->id_personne."'";


try {
  	$requete = $connect->query($req);
	$total = $requete->rowCount();
	if ($total >1) {
		$totalDons = '<em>('.$total.' don'.s($total).')</em>';
		$i=0;
		$bienfaiteur = '';
		// Traitement
		while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		$bienfaiteur .= '<tr>
						  <td>'.affDate($element->date_paiement).'</td>
						  <td align="right">'.formateMontant($element->montant).'</td>
						  <td class="actions">
						  <a href="" title="Télécharger le reçu fiscal"><span class="icon-telecharger"></span></a>
						  <a href="" title="Envoyer le reçu fiscal"><span class="icon-envoyer_fichier"></span></a>
						  <a href="'.SITE_ADMIN.'commerce/commandes/'.$element->id_commande.'" ><span class="icon-edit"></span></a>
						  </td>
						</tr>';
		$i++;
		if ($limite && ($i == $limite)) break;
	  }
  
	  if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="bienfaiteur" form-id="'.$perso->id_personne.'">> Voir '.($total-$limite).' plus</a>';
	  }
  }
  
} catch( Exception $e ){
  echo 'Erreur de lecture de la base : ', $e->getMessage();
}
*/




include_once($_SESSION['ROOT'].'/vues/contenus/personnes/bienfaiteur.php');
?>