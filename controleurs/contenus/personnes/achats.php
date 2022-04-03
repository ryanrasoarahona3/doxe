<?php
$plus='';

if (isset($_GET['id'])) {
	session_start();
	
	require_once($_SESSION['ROOT'].'libs/requires.php');
	$perso = new personne($_GET['id']);
	$limite = false;
	$fermer ='<div id="zone_validation">
						<button type="button" id="action_annuler" class="annuler">X Fermer</button>
				</div>';
} else {
	$limite = 5;
}

$perso->commandes();

if (!empty($perso->commandes)) {
	foreach ($perso->commandes as $order) {
		if ($order->id_etat != ETAT_PAYE) $alerte = ' class="attention" ';
		else $alerte = ' ';
		
		$achats .= '<tr '.$alerte.'>
			  
				  <td>'.$order->numero_commande.'</td>
				   <td>'.affDate(strtotime($order->date_creation), true).'</td>
				  <td align="right">'.formateMontant($order->total).'</td>
				   <td align="right">'.$order->payement_libelle.'</td>
				  <td align="right">'.$order->etat_libelle.'</td>
				  <td class="actions">';
				   if (($order->etat == ETAT_PAYE) || ($order->id_payement == ID_MANDAT)) $achats .= '<button type="button" form-action="telecharger" form-element="FAC_'.$order->id_commande.'"  class="action telecharger right" title="Télécharger la facture"></button>';
				    if (($order->etat == ETAT_PAYE) || ($order->id_payement == ID_MANDAT)) $achats .= '<button form-action="envoyer_fichier" form-element="FAC_'.$order->numero_commande.'" form-type="personnes_achat" class="right envoyer_fichier action" title="Envoyer le fichier"></button>';
					$achats .= '<button form-action="lien" form-element="/boutique/detail/'.$order->id_commande.'" class="right details action" title="Voir la comande"></button> ';
				  $achats .= '</td>
				</tr>';
			$i++;
	
		if ($limite && ($i == $limite)) break;
	  
	}
}


if (count($perso->commandes) > $i) {
		$plus='<a href="#" class="right plus" form-action="personnes" form-type="achats" form-id="'.$perso->id_personne.'">> Voir '.(count($perso->commandes)-$limite).' plus</a>';
} 




include_once($_SESSION['ROOT'].'/vues/contenus/personnes/achats.php');
?>