<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'bienfaiteur';
$form->action = 'modifier';
$form->destination_validation = "json/sauve.php";
$form->lien_annulation = 'action_annuler';
$form->bienfaiteur = $_GET['id'];
$form->type_utilisateur = $_GET['type_utilisateur'];


if (@$commande->payement != 1) $typePaiements = getSelect('commerce_payement' , array(@$commande->payement) );
else $typePaiements = '<option value="1">Carte bleue</option>';

    $etatPaiements = getSelect('commerce_payement_etat' , array(@$commande->etat) );

// Inclusion affichage
include_once($_SESSION['ROOT'].'vues/forms/commun/bienfaiteur.php');

?>