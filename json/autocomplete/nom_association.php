<?php
require_once '../../libs/connexion.php';
 
 $term = $_GET['term'];
 $array = array(); 
 
$selectionPrepa = $connect->prepare('SELECT * FROM associations WHERE nom LIKE :term LIMIT 10');


try {
  $selectionPrepa->execute(array('term' => '%'.$term.'%'));
   
  // Traitement
  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
    $array[$enregistrement->id]['value'] = $enregistrement->nom;
    $array[$enregistrement->id]['id'] = $enregistrement->id;
  }
  
  echo json_encode($array);
  
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}

?>