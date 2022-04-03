<?php 
// A activer sur le serveur distant
header('Access-Control-Allow-Origin: *'); 

require_once '../libs/fonctions.php';
require_once '../libs/connect.php';
require_once '../libs/constantes.php';


/*
// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Requête Ajax uniquement';
  trigger_error($user_error, E_USER_ERROR);
}
*/
session_start();
$connect = connect();
$term = $_GET['term'];
$section = $_GET['section'];
$array = array(); 
 
switch ($section) {

	case 'presse' :
		$selectionPrepa = $connect->prepare('SELECT DISTINCT presse FROM personnes WHERE LOWER(presse) LIKE :term AND presse IS NOT NULL ');

		try {
		  $selectionPrepa->execute(array('term' => '%'.$term.'%'));
   
		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->presse;
		  }
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'nom_association' :
		$selectionPrepa = $connect->prepare('SELECT associations.id, associations.nom, associations.numero_dossier FROM associations WHERE LOWER(nom) LIKE :term LIMIT 10');

		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%'));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom;
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'association' :
		if ($_SESSION['utilisateur']['siege'] == 1)  $selectionPrepa = $connect->prepare('SELECT associations.id, associations.nom, associations.numero_dossier FROM associations WHERE ( LOWER(nom) LIKE :term OR LOWER(numero_dossier) LIKE :term) AND associations.etat=1 LIMIT 10');
		
		else {	
			$sql = '
			SELECT associations.id, associations.nom, associations.numero_dossier FROM associations
				INNER  JOIN villes ON personnes.ville = villes.id
	 			INNER  JOIN departements ON villes.departement = departements.id 
	 			INNER JOIN regions ON villes.region = regions.id  
	 		WHERE ( LOWER(associations.nom) LIKE :term OR LOWER(associations.numero_dossier) LIKE :term )  AND associations.etat=1  ';
			
			if (!empty($_SESSION['utilisateur']['regions'])) 
				$sql .= '  AND regions.id IN ('.lister($_SESSION['utilisateur']['regions']).') ';
		
			if (!empty($_SESSION['utilisateur']['departements']))
				$sql .= ' AND departements.id IN ('.lister($_SESSION['utilisateur']['departements']).') ';
				
				$sql .= ' LIMIT 10';
			$selectionPrepa = $connect->prepare($sql);
		}
		
		
		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%'));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom .' ('.$enregistrement->numero_dossier.')';
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  		
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'personne' :
		
		if ($_SESSION['utilisateur']['siege'] == 1) $selectionPrepa = $connect->prepare('SELECT id, nom, prenom FROM personnes WHERE ( LOWER(nom) LIKE :term OR LOWER(id) LIKE :term ) AND personnes.etat=1 LIMIT 100');
		else {	
			$sql = '
			SELECT personnes.id, personnes.nom, personnes.prenom FROM personnes 
				INNER  JOIN villes ON personnes.ville = villes.id
	 			INNER  JOIN departements ON villes.departement = departements.id 
	 			INNER JOIN regions ON villes.region = regions.id  
	 		WHERE ( LOWER(personnes.nom) LIKE :term OR LOWER(personnes.id) LIKE :term ) AND personnes.etat=1 ';
			
			if (!empty($_SESSION['utilisateur']['regions'])) 
				$sql .= '  AND regions.id IN ('.lister($_SESSION['utilisateur']['regions']).') ';
		
			if (!empty($_SESSION['utilisateur']['departements']))
				$sql .= ' AND departements.id IN ('.lister($_SESSION['utilisateur']['departements']).') ';
				
				$sql .= ' LIMIT 10';
			$selectionPrepa = $connect->prepare($sql);
		}
		
		
		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%'));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom.' '.$enregistrement->prenom.' ('.$enregistrement->id.')';
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	
	
	
	case 'delegue' :
		$selectionPrepa = $connect->prepare('SELECT id, nom, prenom, numero_adherent FROM personnes WHERE ( LOWER(nom) LIKE :term OR LOWER(numero_adherent) LIKE :term ) AND (siege > 0 OR delegue_statut > 0)  AND personnes.etat=1  LIMIT 10');

		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%'));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom.' '.$enregistrement->prenom.' ('.$enregistrement->numero_adherent.')';
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'personne_association_annee' :
		
		$selectionPrepa = $connect->prepare('
SELECT DISTINCT
	personnes.id AS id, 
	personnes.nom AS nom, 
	personnes.prenom AS prenom, 
	personnes.numero_adherent AS numero_adherent
FROM personnes_associations INNER JOIN personnes ON personnes_associations.personne = personnes.id
WHERE (LOWER(personnes.nom) LIKE :term OR LOWER(personnes.numero_adherent) LIKE :term ) AND personnes_associations.association = :id_association AND personnes_associations.annee <> :annee 
AND personnes.id  NOT IN (SELECT personne FROM personnes_associations WHERE personnes_associations.association = :id_association AND  personnes_associations.annee = :annee)
LIMIT 10');

		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%', 'id_association' => $_GET['id_association'], 'annee' => $_GET['annee']));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom.' '.$enregistrement->prenom.' ('.$enregistrement->numero_adherent.')';
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'personne_association' :
		
		$selectionPrepa = $connect->prepare('
SELECT DISTINCT
	personnes.id AS id, 
	personnes.nom AS nom, 
	personnes.prenom AS prenom, 
	personnes.numero_adherent AS numero_adherent
FROM personnes_associations INNER JOIN personnes ON personnes_associations.personne = personnes.id
WHERE (LOWER(personnes.nom) LIKE :term OR LOWER(personnes.numero_adherent) LIKE :term ) AND personnes_associations.association = :id_association AND personnes_associations.annee <> :annee 
LIMIT 10');

		try {
		  $selectionPrepa->execute(array('term' => '%'.traiteTexte($term).'%', 'id_association' => $_GET['id_association'], 'annee' => $_GET['annee']));
 		  // Traitement
		  while( $enregistrement = $selectionPrepa->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom.' '.$enregistrement->prenom.' ('.$enregistrement->numero_adherent.')';
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;
	
	case 'cp_ville' :
		$selectionCPVILLE = $connect->prepare('SELECT * FROM villes WHERE code_postal LIKE :term ORDER BY nom ASC LIMIT 100');

		try {
		  $selectionCPVILLE->execute(array('term' => $term.'%'));
   
		  // Traitement
		  while( $enregistrement = $selectionCPVILLE->fetch(PDO::FETCH_OBJ)){
			$array[$enregistrement->id]['value'] = $enregistrement->nom;
			$array[$enregistrement->id]['id'] = $enregistrement->id;
		  }
  
		  echo json_encode($array);
  
		} catch( Exception $e ){
		  echo 'Erreur de recherche : ', $e->getMessage();
		}
	break;

} 
 


?>