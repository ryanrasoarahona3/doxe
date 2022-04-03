<?php
// Récupération de la commande
$commande = new commande($element);

$recap = recapCommande($commande->id_commande); 

if ($commande->type_utilisateur == 'P') {
	$data = new personne($commande->id_utilisateur);
	$recap =  '<h3><a href="/personnes/detail/'.$data->id_personne.'">'.$data->prenom.' '.$data->nom.' / N°'.$data->id_personne.' </a></h3><br>'.$recap;
	}
elseif ($commande->type_utilisateur == 'A') {
	$data = new association($commande->id_utilisateur);
	$recap =  '<h3><a href="/associations/detail/'.$data->id_association.'">'.$data->nom.' / N°'.$data->id_association.'</a><br></h3>'.$recap;
	}

if (($commande->etat == ETAT_PAYE) || ($commande->payement == ID_MANDAT)) {
	$alerte = "vert";
	$facture = '<button type="button" form-action="telecharger" form-element="FAC_'.$commande->id_commande.'" class="action telecharger " title="Télécharger la facture"></button> Télécharger la facture';
	
	foreach ($commande->produits as $id=>$produit) {
		if ( ($produit->id_source == 1) || ($produit->id_source == 2) || ($produit->id_source == 3) )  {
			$recu = '<br><button type="button" form-action="telecharger" form-element="DON_'.$commande->id_commande.'" class="action telecharger " title="Télécharger le reçu"></button> Télécharger le reçu';
		}
	}
	if ( (!empty($recu)) && (count($commande->produits) == 1) ) $facture ="";
	
} elseif ($commande->etat == ETAT_RECU)  {
	$alerte = "orange";
} elseif ($commande->etat == ETAT_ANNULE)  {
	$alerte = "";
} else {
	$alerte = "attention";
}

$livraison = formatLivraison($commande,$data); 

$modifier = '<button type="button" form-action="boutique" form-type="ajouter" form-id="'.$commande->id_commande.'" class="edit" title="Modifier"></button>';



//$_SESSION['utilisateur'] = 'utilisateur' ;

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>