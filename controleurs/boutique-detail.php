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

// Declarations

function recapCommande($id) {
    $commande = new commande($id);

    $message = "<h3>Récapitulatif commande N° ".$commande->numero_commande."</h3><strong> ".affDate(strtotime($commande->date_creation), true)."</strong></p>";
    $message .='<div id="panier"><table border="0" width="600">
			<thead>
			<tr><th>Produit</th><th>Quantité</th><th>Prix</th><th>Total</th></tr>
			</thead>
			';
	
    foreach ($commande->produits as $id=>$produit) {

        $message .= '<tr>';
        $message .= '<td>'.$produit->nom;
        if (!empty($produit->personnalisation)) $message .= '<br><strong>'.implode(' ',unserialize($produit->personnalisation)).'</strong>';
        $message .='</td>';
       
        $message .= '<td>'.$produit->quantite.'</td>';
        $message .= '<td>'.formateMontant($produit->prix).'</td>';
        $message .= '<td><strong>'.formateMontant($produit->prix*$produit->quantite).'</strong></td>';
        $message .= '</tr>';
    }

    $message .='</table>';

    $message .='<table border="0" width="600">
		<tr><td align="right"><strong>Total '. formateMontant(montantGlobal($commande)) .' TTC</strong></td></tr>
		</table></div>';
    return $message;		
}

function montantGlobal($commande=false) {
    $total=0;
    if (!$commande) {
        $produits = $_SESSION['panier']['produits'];
        foreach ($produits as $id => $prod) {
            $total += $prod['prix'] * $prod['quantite'];
        }
        if (isLivrable()) {
       		if (isset($_SESSION['adresse_livraison']['pays'])) $total += calculeLivraison($_SESSION['adresse_livraison']['pays']);
        	else $total += calculeLivraison(ID_FRANCE);
        }
        
    } else {
        $produits = $commande->produits;
        foreach ($produits as $id => $prod) {
            $total += $prod->prix * $prod->quantite;
        }
       
    }

   return $total;
}
?>