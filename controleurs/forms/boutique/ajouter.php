<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');
require_once($_SESSION['ROOT'].'amis/commerce.php');

$form = new stdClass;

$form->section = 'boutique';
$form->action = 'modifier';
$form->destination_validation = "json/sauve.php";
$form->lien_annulation = 'action_annuler';

$commande = new commande($_GET['id']);

if ($commande->payement != 1) $typePaiements = getSelect('commerce_payement' , array($commande->payement) );
else $typePaiements = '<option value="1">Carte bleue</option>';

$etatPaiements = getSelect('commerce_payement_etat' , array($commande->etat) );

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
} elseif ($commande->etat == ETAT_ANNULE)  {
	$alerte = "";
} else {
	$alerte = "attention";
}

$livraison = formatLivraison($commande,$data); 

// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/boutique/ajouter.php');

?>