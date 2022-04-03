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
var_dump($controlleur);


// TODO: revoir le fonctionnement de ce script
// Revoir l'endroit approprié pour ranger ce script
if(!isset($nom_jeune_fille))
    $nom_jeune_fille = "";
if(!isset($prospect))
    $prospect = "";
if(!isset($elu))
    $elu = "";
if(!isset($presse))
    $presse = "";



//$_SESSION['utilisateur'] = 'utilisateur' ;

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>