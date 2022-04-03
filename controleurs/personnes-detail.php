<?php
// Récupération de la personne
$perso = new personne($element);

// Gestion Alerte
$modal=false;
if (isset($_SESSION['doublons'])) {
	if ($_SESSION['doublons'] == $asso->id_association) {
		$modal=true;
		$modalTitre = 'Alerte doublon';
		$modalTexte = 'L\'association que vous venez d\'enregistrer est probablement un doublon.';
	}
}


//$_SESSION['utilisateur'] = 'utilisateur' ;

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>