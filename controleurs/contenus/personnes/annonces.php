<?php
$limite = 5;
$plus='';

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

$req = "SELECT 
	annonces_validation.nom AS validation_label, 
	annonces.validation, 
	annonces.refus, 
	annonces.date_validation, 
	annonces.date_saisie, 
	annonces.texte, 
	annonces.titre, 
	annonces.type, 
	annonces.createur, 
	annonces.id
FROM annonces INNER JOIN annonces_validation ON annonces.validation = annonces_validation.id
WHERE annonces.createur = ".$perso->id_personne." AND annonces.type= 'personnes'
ORDER BY annonces.date_saisie DESC, annonces.date_validation DESC";

$connect = connect();
try {
  	$requete = $connect->query($req);
	$total = $requete->rowCount();
	if ($total > 0 ) {
		$totalAnnonces = '<em>('.$total.' achat'.s($total).')</em>';
		$i=0;
		$annonces = '';
		// Traitement
		while( $element = $requete->fetch(PDO::FETCH_OBJ)){
			
			/*
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
			*/				
				if ($element->validation==3) $alerte = 'alerte';
				else  $alerte = '';
				$annonces .= '<tr class="'.$alerte.'">
					  <td>'.$element->titre.'</td>
					  <td>'.$element->validation_label.'</td>
					  
					    <td class="actions"><button type="button" form-action="personnes" form-type="annonces" form-id="'.$element->createur.'" form-id-lien="'.$element->id.'" class="edit action"></button> </td>
					</tr>
					';
		$i++;
		if ($limite && ($i == $limite)) break;
	  }
  
	  if ($total > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="annonces" form-id="'.$element->createur.'">> Voir '.($total-$limite).' plus</a>';
	  } 
  }
  
} catch( Exception $e ){
  echo 'Erreur de lecture de la base : ', $e->getMessage();
}



					

include_once($_SESSION['ROOT'].'/vues/contenus/personnes/annonces.php');
?>