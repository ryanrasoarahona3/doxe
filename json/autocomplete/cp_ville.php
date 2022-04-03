<?php
require_once '../../libs/connexion.php';
 
 $term = $_GET['term'];
 $array = array(); 
 
$selectionCPVILLE = $connect->prepare('SELECT * FROM villes WHERE code_postal LIKE :term ORDER BY nom ASC LIMIT 100');


try {
  $selectionCPVILLE->execute(array('term' => '%'.$term.'%'));
   
  // Traitement
  while( $enregistrement = $selectionCPVILLE->fetch(PDO::FETCH_OBJ)){
    $array[$enregistrement->id]['value'] = $enregistrement->nom;
    $array[$enregistrement->id]['id'] = $enregistrement->id;
  }
  
  echo json_encode($array);
  
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}

?>