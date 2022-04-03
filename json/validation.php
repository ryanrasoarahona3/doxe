<?php
require_once '../libs/fonctions.php';
require_once '../libs/connect.php';
 
if (isset($_POST['section'])) $section = $_POST['section'];
if (isset($_GET['section'])) $section = $_GET['section'];
$array = array(); 

switch ($section) {

	case 'personnes_associations' :
	case 'associations_personnes' :	
		$selectionPrepa = $connect->prepare('SELECT DISTINCT * FROM personnes_associations WHERE personne = :personne AND  association = :association  AND  annee = :annee ');

		try {
		  $selectionPrepa->bindValue(':personne', $_POST['id_personne'], PDO::PARAM_INT);
		  $selectionPrepa->bindValue(':association', $_POST['id_association'], PDO::PARAM_INT);
		  $selectionPrepa->bindValue(':annee', $_POST['annee'], PDO::PARAM_INT);
   		  $selectionPrepa->execute();
   		  $count = $selectionPrepa->rowCount();
   			
		  if ($count==0)  $array['resultat'] = 1;
		  else if ($count>0)  $array['resultat'] = 0;
  
		} catch( Exception $e ){
		  echo 'Erreur : ', $e->getMessage();
		}
	break;
	
	case 'personnes_amis' :
		$selectionPrepa = $connect->prepare('SELECT DISTINCT * FROM laf_adhesions_personnes WHERE personne = :personne AND annee = :annee ');

		try {
		  $selectionPrepa->bindValue(':personne', $_POST['id_personne'], PDO::PARAM_INT);
		  $selectionPrepa->bindValue(':annee', $_POST['annee'], PDO::PARAM_INT);
   		  $selectionPrepa->execute();
   		  $count = $selectionPrepa->rowCount();
   			
		  if ($count==0)  $array['resultat'] = 1;
		  else if ($count>0)  $array['resultat'] = 0;
  
		} catch( Exception $e ){
		  echo 'Erreur : ', $e->getMessage();
		}
	break;
	
	case 'associations_amis' :
		$selectionPrepa = $connect->prepare('SELECT DISTINCT * FROM laf_adhesions_associations WHERE association = :association AND annee = :annee ');

		try {
		  $selectionPrepa->bindValue(':association', $_POST['id_association'], PDO::PARAM_INT);
		  $selectionPrepa->bindValue(':annee', $_POST['annee'], PDO::PARAM_INT);
   		  $selectionPrepa->execute();
   		  $count = $selectionPrepa->rowCount();
   			
		  if ($count==0)  $array['resultat'] = 1;
		  else if ($count>0)  $array['resultat'] = 0;
  
		} catch( Exception $e ){
		  echo 'Erreur : ', $e->getMessage();
		}
	break;
	
	case 'associations_unique' :
		$selectionPrepa = $connect->prepare('SELECT DISTINCT * FROM associations WHERE LOWER(associations.numero_dossier)  = :numero_dossier ');

		try {
		  $selectionPrepa->bindValue(':numero_dossier',strtoupper( $_GET['numero_dossier']), PDO::PARAM_INT);
   		  $selectionPrepa->execute();
   		  $count = $selectionPrepa->rowCount();
   			
		  $array['resultat'] = $count;
  
		} catch( Exception $e ){
		  echo 'Erreur : ', $e->getMessage();
		}
	break;
	
	case 'courriel_unique' :
		$selectionPrepa = $connect->prepare('SELECT DISTINCT * FROM personnes WHERE LOWER(personnes.courriel)  = :courriel ');
		try {
		  $selectionPrepa->bindValue(':courriel',strtoupper( $_GET['courriel']), PDO::PARAM_INT);
   		  $selectionPrepa->execute();
   		  $count = $selectionPrepa->rowCount();
   			
		  $array['resultat'] = $count;
  
		} catch( Exception $e ){
		  echo 'Erreur : ', $e->getMessage();
		}
	break;
	
	case 'captcha' :
		if ( ( $_GET['captcha'] == $_SESSION['captcha']) && ( !empty($_GET['captcha']) ) ) $array['resultat'] = true;
		else $array['resultat'] = false;
		$array['resultat'] = true;
	break;
	
} 
 
echo json_encode($array);

?>