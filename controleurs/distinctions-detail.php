<?php
// Récupération de l'association
$distinction = new distinction($element);

$personne = new personne($distinction->personne);


// Inclusion vue	
include_once(ROOT.'/vues/distinctions-detail.php');

?>