<?php
// Récupération de l'association
$asso = new association($element);
$asso->conseilAdministration();

// Gestion Alerte
$modal=false;
if (isset($_SESSION['doublons'])) {
	if ($_SESSION['doublons'] == $asso->id_association) {
		$modal=true;
		$modalTitre = 'Alerte doublon';
		$modalTexte = 'L\'association que vous venez d\'enregistrer est probablement un doublon.';
	}
}

// Inclusion vue	
include_once(ROOT.'/vues/'.$controlleur.'.php');
?>