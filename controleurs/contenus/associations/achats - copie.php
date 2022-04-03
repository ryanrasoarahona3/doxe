<?php
if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$asso = new association($_GET['id']);
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
				</div>';
} else {
	$limite = 5;
}


$req = "SELECT site_commerce_order.order_id AS id_commande, 
	site_commerce_payment_transaction.`changed` AS date_paiement, 
	site_commerce_payment_transaction.payment_method, 
	site_commerce_payment_transaction.message, 
	site_commerce_payment_transaction_revision.remote_status, 
	site_commerce_payment_transaction_revision.`status`, 
	site_field_data_commerce_order_total.commerce_order_total_amount AS montant
FROM site_commerce_order INNER JOIN site_commerce_payment_transaction ON site_commerce_order.order_id = site_commerce_payment_transaction.order_id
	 INNER JOIN site_commerce_payment_transaction_revision ON site_commerce_payment_transaction.revision_id = site_commerce_payment_transaction_revision.revision_id AND site_commerce_payment_transaction.transaction_id = site_commerce_payment_transaction_revision.transaction_id
	 INNER JOIN site_users ON site_commerce_order.uid = site_users.uid
	 INNER JOIN site_field_data_commerce_order_total ON site_commerce_order.revision_id = site_field_data_commerce_order_total.revision_id
WHERE site_users.`name` = 'associations_".$asso->id_association."'
ORDER BY date_paiement DESC";


try {
  	$requete = $connect->query($req);
	$total = $requete->rowCount();
	
	if ($total >0) {
		$totalAchats = '<em>('.$total.' achat'.s($total).')</em>';
		$i=0;
		$achats = '';
		// Traitement
		while( $element = $requete->fetch(PDO::FETCH_OBJ)){
			
			switch($element->status) {
				case 'success' :
					$affstatus = 'Payé';
					$alerte='';
				break;
				
				case 'pending' :
					$affstatus = 'Attente paiement';
					$alerte=' class="attention" ';
				break;
				
				default  :
					$affstatus = $element->status;
					$alerte='';
				break;
			}
				
			switch($element->payment_method) {
				case 'bank_transfer' :
					$affPayement = 'Virement';
				break;
				
				case 'commerce_payment_example' :
					$affPayement = 'Paiement de test';
				break;
				
				default  :
					$affPayement = $element->payment_method;
				break;
			}
							
				$achats .= '<tr '.$alerte.'>
						  
						  <td>'.affDate($element->date_paiement).'</td>
						  <td align="right">'.formateMontant($element->montant).'</td>
						   <td align="right">'.$affPayement.'</td>
						  <td align="right">'.$affstatus.'</td>
						  <td class="actions">';
						  if ($element->status == 'success') $achats .= '<a href="" title="Télécharger la facture"><span class="icon-telecharger"></span></a>';
						  if ($element->status == 'success')$achats .= '<a href="" title="Envoyer la facture"><span class="icon-envoyer_fichier"></span></a>';
						   $achats .= '<a href="'.SITE_ADMIN.'commerce/orders/'.$element->id_commande.'" ><span class="icon-edit"></span></a>
						  </td>
						</tr>';
		$i++;
		if ($limite && ($i == $limite)) break;
	  }
  
	  if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="achats" form-id="'.$asso->id_association.'">> Voir '.($total-$limite).' plus</a>';
	  }
  }
  
} catch( Exception $e ){
  echo 'Erreur de lecture de la base : ', $e->getMessage();
}





include_once($_SESSION['ROOT'].'/vues/contenus/associations/achats.php');
?>