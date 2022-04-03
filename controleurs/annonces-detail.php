<?php
// Récupération de l'association
$annonce = new annonce($element);

// Inclusion vue	
include_once(ROOT.'/controleurs/forms/'.$annonce->type.'/annonces.php');

?>