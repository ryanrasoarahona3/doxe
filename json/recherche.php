<?php
session_start();
require_once '../libs/fonctions.php';
require_once '../libs/connect.php';
require_once '../libs/constantes.php';

// Interdire les accès direct on ajax
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Accès inderdit';
  trigger_error($user_error, E_USER_ERROR);
}


//print_r($_POST);

if (isset($_GET['recherche'])) $recherche = $_GET['recherche'];
if (isset($_GET['affiche_resultats']) && $_GET['affiche_resultats']==1) $affiche_resultats = true;

$sqlComplement = array();

$retour = array();
//$retour['message']= '';
//$retour['resultat'] = '';


// Récupération  de la recherche en cas de retour
if (isset($_GET['retour'])) {
	$_GET = $_SESSION['recherche'][$recherche];
	$_SESSION['recherche'][$recherche] = '';
}

// Stockage  de la recherche en cas de retour
$_SESSION['recherche'][$recherche] = $_GET;
	

//////////////////////////
//
//		DISTINCTIONS
//
//////////////////////////


if ($recherche == 'distinctions') {
		
		if(isset($_GET['personne']) && !empty($_GET['personne']) && intval($_GET['personne']) > 0)
			$sqlComplement[] = ' distinctions.personne = "'.$_GET['personne'].'" ';
		if (isset($_GET['annee']) && !empty($_GET['annee'])) 
			$sqlComplement[] = ' distinctions.annee = "'.$_GET['annee'].'" ';
			
		if (isset($_GET['demande']) && !empty($_GET['demande'])) 
			$sqlComplement[] = ' distinctions.distinction_type = "'.$_GET['demande'].'" ';
			
		if (isset($_GET['decision']) && (!empty($_GET['decision']) || $_GET['decision'] == '0')) 
			$sqlComplement[] = ' distinctions.distinction_type_decision = "'.$_GET['decision'].'" ';	
			
		if (isset($_GET['validation']) && !empty($_GET['validation'])) 
			$sqlComplement[] = ' distinctions.validation = "'.$_GET['validation'].'" ';	
			
		if (isset($_GET['couriel_vide']) && !empty($_GET['couriel_vide']) && ($_GET['couriel_vide']==1) ) 
			$sqlComplement[] = ' personnes.courriel IS NULL ';	
		
		if (isset($_GET['couriel_vide']) && !empty($_GET['couriel_vide']) && ($_GET['couriel_vide']==0) ) 
			$sqlComplement[] = ' personnes.courriel IS NOT NULL ';	
			
		if (isset($_GET['region']) && !empty($_GET['region']) )
			$sqlComplement[] = ' regions.id = '.$_GET['region'];	
		
		if (isset($_GET['documents_complets']) && !empty($_GET['documents_complets']) && ($_GET['documents_complets']==1)  && is_int($_GET['documents_complets'])) 
			$sqlComplement[] = ' distinctions.documents_complets = 1 ';	
		
		if (isset($_GET['documents_complets']) && !empty($_GET['documents_complets']) && ($_GET['documents_complets']==0)  && is_int($_GET['documents_complets'])) 
			$sqlComplement[] = ' distinctions.documents_complets = 0 ';	
			
			
		if (isset($_GET['choix_num_demande']) && !empty($_GET['choix_num_demande'])) 
			$sqlComplement[] = ' LOWER(distinctions.num_demande) LIKE "%'.traiteTexte($_GET['choix_num_demande']).'%" ';
		
		
		// LIMITE DE DELEGUES (pour la france uniquement)
		if (!empty($_SESSION['utilisateur']['regions'])) $sqlComplement[] = ' regions.id IN ('.lister($_SESSION['utilisateur']['regions']).') ';
		if (!empty($_SESSION['utilisateur']['departements'])) $sqlComplement[] = ' departements.id IN ('.lister($_SESSION['utilisateur']['departements']).') ';
	
		
$sql ='SELECT 
	distinctions.id, 
	personnes.civilite, 
	personnes.nom, 
	personnes.prenom,
	personnes.adresse,
	villes.nom AS ville,
	villes.code_postal AS code_postal,
	personnes.courriel, 
	personnes.id AS id_personne,
	distinctions.date_demande, 
	distinctions.annee, 
	distinctions.distinction_type, 
	distinctions_types.nom AS distinction_type_label, 
	distinctions.distinction_type_decision, 
	distinctions_types_decisions.nom AS distinction_type_decision_label, 
	CASE WHEN distinctions_types_decisions.nom IS NULL THEN "<span class=\"alerte\">En attente</span>" END AS distinction_type_decision_label_refuse,
	distinctions.nbr_annees, 
	distinctions.personne, 
	distinctions.demandeur, 
	distinctions.acceptation_conditions, 
	distinctions.commentaire,  
	distinctions.date_modification, 
	distinctions.modificateur, 
	distinctions.num_demande, 
	distinctions.domaines, 
	distinctions.domaines_autres, 
	departements.nom AS departement, 
	regions.nom AS region,
CASE 
	WHEN distinctions.documents_complets = 1 
	THEN "Complets" 
	ELSE "Non complets" 
	END AS documents

FROM distinctions 
	 LEFT OUTER JOIN personnes ON distinctions.personne = personnes.id
	 LEFT OUTER JOIN villes ON personnes.ville = villes.id
	 LEFT OUTER JOIN departements ON villes.departement = departements.id
	 LEFT OUTER JOIN regions ON villes.region = regions.id
	 LEFT OUTER JOIN distinctions_types ON distinctions.distinction_type = distinctions_types.id
	 LEFT OUTER JOIN distinctions_types_decisions ON distinctions.distinction_type_decision = distinctions_types_decisions.id
	 LEFT OUTER JOIN distinctions_validation ON distinctions.type_validation = distinctions_validation.id
WHERE personnes.etat = 1  AND ';
	
	$sql .= lister($sqlComplement, ' AND ');
		
		// SORTIE DE LA REQUETTE
		// echo $sql;
		
		
		$_SESSION['last']['sql'] = $sql;
		$_SESSION['last']['recherche'] = $_GET; 
			
			
		try {
		  $retour['nbr_resultats'] = $connect->query($sql)->rowCount();
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
	
	// Affichage des résultats
	if (isset($affiche_resultats)) {
			
		// Tri 
		if (isset($_GET['sorts'])) {
			$tempTri = array();
			foreach ($_GET['sorts'] as $cle=>$val) {
				if ($val == 1) $ordre = 'ASC';
				else  $ordre = 'DESC';
				$tempTri[] = $cle.' '.$ordre;
			}
			$sql.= ' ORDER  BY '.lister($tempTri); 
		}
		if (isset($_GET['perPage'])) $sql.= ' LIMIT '.$_GET['perPage']; 
		if (isset($_GET['offset'])) $sql.= ' OFFSET '.$_GET['offset']; 
		
		$i=0;
		// Récupération des données
		try {
		  $select = $connect->query($sql);
		  $select->setFetchMode(PDO::FETCH_OBJ);
		  while( $enregistrement = $select->fetch() )
		  {
			$retour['records'][$i]['num_demande'] = $enregistrement->num_demande;
			$retour['records'][$i]['demande'] = $enregistrement->distinction_type_label;
			if (!empty($enregistrement->distinction_type_decision_label_refuse)) $retour['records'][$i]['decision'] = $enregistrement->distinction_type_decision_label_refuse;
			else $retour['records'][$i]['decision'] = $enregistrement->distinction_type_decision_label;
			$retour['records'][$i]['nom'] = $enregistrement->nom.' '.$enregistrement->prenom .' ('.$enregistrement->id_personne.')';
			$retour['records'][$i]['nbr_annees'] = $enregistrement->nbr_annees;
			$retour['records'][$i]['documents'] = $enregistrement->documents;
			$retour['records'][$i]['region'] = $enregistrement->region;
			$retour['records'][$i]['departement'] = $enregistrement->departement;
			$retour['records'][$i]['validation'] = (isset($enregistrement->validation) ? $enregistrement->validation : '');
			$retour['records'][$i]['actions'] = (isset($retour['records'][$i]['actions']) ? $retour['records'][$i]['actions'] : '') . '<button form-action="envoyer" form-element="'.$enregistrement->courriel.'" class="right envoyer action"></button>';
			$retour['records'][$i]['actions'] .= '<button form-action="lien" form-element="/personnes/detail/'.$enregistrement->id_personne.'" class="right personnes action"></button>';
			$retour['records'][$i]['actions'] .= '<button form-action="lien" form-element="/distinctions/detail/'.$enregistrement->id.'" class="right details action"></button>';
			$i++;
		  }
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
		
		if(isset($_GET['perPage'])) $retour['queryRecordCount']= $retour['nbr_resultats'];
		$retour['totalRecordCount']= $retour['nbr_resultats'];
	}	
}




//////////////////////////
//
//		ANNONCES
//
//////////////////////////


if ($recherche == 'annonces') {

	

	// Traitement du formulaire
	$sqlPrepare ='
	SELECT DISTINCT
		annonces.date_validation, 
		villes.departement, 
		villes.region, 
		annonces.createur, 
		annonces.type, 
		annonces.id, 
		annonces.titre, 
		annonces.texte, 
		annonces.date_saisie AS ladate, 
		annonces.validation, 
		annonces.refus,
		annonces_validation.nom AS validation_nom,
		GROUP_CONCAT(activites.nom) AS activites
	FROM annonces ';


	$AssoJoin = ' 
	LEFT JOIN associations ON annonces.createur = associations.id 
	LEFT JOIN annonces_activites ON annonces.id = annonces_activites.annonce
	LEFT JOIN villes ON associations.ville = villes.id	 
	LEFT OUTER JOIN activites ON activites.id = annonces_activites.activite 
	LEFT OUTER JOIN annonces_validation ON annonces.validation = annonces_validation.id';
	$AssoWhere = ' annonces.type = "associations"  AND associations.etat = 1  AND ';

	$PersoJoin = ' 
	LEFT JOIN personnes ON annonces.createur = personnes.id 
	LEFT JOIN annonces_activites ON annonces.id = annonces_activites.annonce 
	LEFT JOIN villes ON personnes.ville = villes.id	 
	LEFT OUTER JOIN activites ON activites.id = annonces_activites.activite 
	LEFT OUTER JOIN annonces_validation ON annonces.validation = annonces_validation.id';
	$PersoWhere = ' annonces.type = "personnes" AND personnes.etat = 1 AND ';

	
	// Choix génériques
	if ( (!empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin'])) ) 
			$sqlComplement[] = ' (associations.date_creation BETWEEN \''.convertDate($_GET['saisie_debut']).' 00:00:00\' AND \''.convertDate($_GET['saisie_fin']).' 23:59:59\') ';
	else if ( (!empty($_GET['saisie_debut'])) && (empty($_GET['saisie_fin']))) 
			$sqlComplement[] = ' associations.date_creation >= \''.convertDate($_GET['saisie_debut']).' 00:00:00\'  ';
	else if ( (empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin']))) 
			$sqlComplement[] = ' associations.date_creation <= \''.convertDate($_GET['saisie_fin']).' 23:59:59\'  ';
			
			
	if (!empty($_GET['titre'])) 
			$sqlComplement[] = ' LOWER(annonces.titre) LIKE "%'.traiteTexte($_GET['titre']).'%"  ';
	
	if (!empty($_GET['texte'])) 
			$sqlComplement[] = ' LOWER(annonces.texte) LIKE "%'.traiteTexte($_GET['texte']).'%"  ';
	
	
	if (!empty($_GET['activites'])) 
			$sqlComplement[] = ' annonces_activites.activite IN ('.lister($_GET['activites']).') ';
	
	if (!empty($_GET['validation']))
			 $sqlComplement[] = ' annonces.validation = "'.$_GET['validation'].'" ';
	
		
	// LIMITE DE DELEGUES
	if (!empty($_SESSION['utilisateur']['regions'])) $sqlComplement[] = ' villes.region IN ('.lister($_SESSION['utilisateur']['regions']).') ';
	if (!empty($_SESSION['utilisateur']['departements'])) $sqlComplement[] = ' villes.departement IN ('.lister($_SESSION['utilisateur']['departements']).') ';

	
	
	// Aucun choix	
	if ( empty($_GET['association']) && empty($_GET['choix_association']) && empty($_GET['choix_personne']) && empty($_GET['personne']) ) {
		
		$sql = '('.$sqlPrepare.$AssoJoin.' WHERE '.$AssoWhere.lister($sqlComplement,'AND').' GROUP BY annonces.id) UNION ('.$sqlPrepare.$PersoJoin.' WHERE '.$PersoWhere.lister($sqlComplement,'AND').' GROUP BY annonces.id)  ';
		
	}
	
	// Choix d'une association
	 else if ( (!empty($_GET['association'])) || (!empty($_GET['choix_association'])) ) {
	 
	 	if (!empty($_GET['association'])) 
			$sqlComplement[] = ' associations.id = "'.$_GET['association'].'" ';
		else if (!empty($_GET['choix_association'])) 
			$sqlComplements[] = ' (LOWER(associations.nom) LIKE "%'.traiteTexte($_GET['choix_association']).'%"   OR  LOWER(numero_dossier) LIKE "%'.traiteTexte($_GET['choix_association']).'%" )';
	
		$sql = $sqlPrepare.$AssoJoin.' WHERE '.$AssoWhere.lister($sqlComplement,'AND').' GROUP BY annonces.id ';
	}	
		
	// Choix d'une personne
	else if ( (!empty($_GET['personne'])) || (!empty($_GET['choix_personne'])) ) {
	
		if (!empty($_GET['personne'])) 
			$sqlComplement[] = ' personne.id = "'.$_GET['personne'].'" ';
		else if (!empty($_GET['choix_personne'])) 
			$sqlComplements[] = ' ( LOWER(choix_personne.nom) LIKE "%'.traiteTexte($_GET['choix_personne']).'%"  OR  LOWER(choix_personne.numero_adherent) LIKE "%'.traiteTexte($_GET['choix_personne']).'%" ) ';
		
		$sql = $sqlPrepare.$PersoJoin.' WHERE '.$PersoWhere.lister($sqlComplement,'AND').' GROUP BY annonces.id ';
	}
	

	// Compte les résultats	
		
		//echo $sql;
		
			
		try {
		  $retour['nbr_resultats'] = $connect->query($sql)->rowCount();
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
	
	// Affichage des résultats
	if (isset($affiche_resultats)) {
			
		// Tri 
		if (isset($_GET['sorts'])) {
			$tempTri = array();
			foreach ($_GET['sorts'] as $cle=>$val) {
				if ($val == 1) $ordre = 'ASC';
				else  $ordre = 'DESC';
				$tempTri[] = $cle.' '.$ordre;
			}
			$sql.= ' ORDER  BY '.lister($tempTri); 
		}
		else $sql.= ' ORDER  BY ladate DESC ';
		 
		 $_SESSION['last']['sql'] = $sql;
		$_SESSION['last']['recherche'] = $_GET; 
		
		
		if (isset($_GET['perPage'])) $sql.= ' LIMIT '.$_GET['perPage']; 
		if (isset($_GET['offset'])) $sql.= ' OFFSET '.$_GET['offset']; 
		
		$i=0;
		// Récupération des données
		try {
		  $select = $connect->query($sql);
		  $select->setFetchMode(PDO::FETCH_OBJ);
		  while( $enregistrement = $select->fetch() )
		  {
			$retour['records'][$i]['titre'] = $enregistrement->titre;
			$retour['records'][$i]['activites'] = $enregistrement->activites;
			$retour['records'][$i]['validation_nom'] = $enregistrement->validation_nom;
			$retour['records'][$i]['ladate'] = convertDate($enregistrement->ladate,'php');
			if ($enregistrement->date_validation != '0000-00-00') $retour['records'][$i]['date_validation'] = convertDate($enregistrement->date_validation,'php');
			else $retour['records'][$i]['date_validation'] = '';
			$retour['records'][$i]['actions'] = '<a href="'.$_SESSION['WEBROOT'].'annonces/detail/'.$enregistrement->id.'"><span class="icon-edit"></span></a>';
			if ($enregistrement->validation == 3) {
				$retour['records'][$i]['actions'] .= '<button class="action_refuser" element-id="'.$enregistrement->id.'" >Refuser</button> ';
				$retour['records'][$i]['actions'] .= '<button class="action_valider" element-id="'.$enregistrement->id.'" >Valider</button> ';
			}
			$i++;
			
		  }
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
		
		if(isset($_GET['perPage'])) $retour['queryRecordCount']= $retour['nbr_resultats'];
		$retour['totalRecordCount']= $retour['nbr_resultats'];
	}	
}


//////////////////////////
//
//		BOUTIQUE
//
//////////////////////////


if ($recherche == 'boutique') {

	require_once '../class/commande.class.php';
	require_once '../class/produit.class.php';
	require_once '../class/commande_produit.class.php';
	
	// Traitement du formulaire
	$sqlPrepare ='
	
	SELECT commerce_commandes.id, 
	commerce_commandes.numero_commande, 
	commerce_commandes.type_utilisateur, 
	commerce_commandes.id_utilisateur, 
	commerce_commandes.date_creation, 
	commerce_commandes.date_modification, 
	commerce_payement_etat.nom AS etat, 
    commerce_commandes.etat AS id_etat, 
    
	commerce_payement.nom AS payement,
	ROUND( SUM(commerce_commandes_produits.prix * commerce_commandes_produits.quantite), 2 ) AS total  ';
	
	$from = ' FROM ( commerce_commandes, commerce_payement_etat, commerce_payement, commerce_commandes_produits ) ';
	
	$sqlPrepareFrom ='FROM commerce_commandes 
		LEFT JOIN commerce_payement_etat ON commerce_commandes.etat = commerce_payement_etat.id
		LEFT JOIN commerce_payement ON commerce_commandes.payement = commerce_payement.id
	 	LEFT JOIN commerce_commandes_produits ON commerce_commandes.id = commerce_commandes_produits.id_commande ';

	$AssoJoin = ' 
	LEFT JOIN associations ON commerce_commandes.id_utilisateur = associations.id';
	$AssoWhere = array(' commerce_commandes.type_utilisateur = "A"  ', ' associations.etat = 1 '); // La deuxième condition permet d'avoir uniquement les commandes dont l'utilisateur existe
	$AssoSelect = 'associations.nom AS nom, associations.id AS id_utilisateur ';
	
	$PersoJoin = ' 
	LEFT JOIN personnes ON commerce_commandes.id_utilisateur = personnes.id';
	$PersoWhere = array(' commerce_commandes.type_utilisateur = "P"  ', ' personnes.etat = 1 '); // La deuxième condition permet d'avoir uniquement les commandes dont l'utilisateur existe
	$PersoSelect = ' CONCAT_WS (" ", personnes.prenom, personnes.nom  ) AS nom, personnes.id AS id_utilisateur ';
	
	// Choix génériques
	if ( (!empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin'])) ) 
			$sqlComplement[] = '  (commerce_commandes.date_creation BETWEEN \''.convertDate($_GET['saisie_debut']).' 00:00:00\' AND \''.convertDate($_GET['saisie_fin']).' 23:59:59\') ';
	else if ( (!empty($_GET['saisie_debut'])) && (empty($_GET['saisie_fin']))) 
			$sqlComplement[] = '  commerce_commandes.date_creation >= \''.convertDate($_GET['saisie_debut']).' 00:00:00\'  ';
	else if ( (empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin']))) 
			$sqlComplement[] = '  commerce_commandes.date_creation <= \''.convertDate($_GET['saisie_fin']).' 23:59:59\'  ';
					
		
	if (!empty($_GET['etat']))
			 $sqlComplement[] = '  commerce_commandes.etat = "'.$_GET['etat'].'" ';
	
	// Aucun choix	
	if ( empty($_GET['association']) && empty($_GET['choix_association']) && empty($_GET['choix_personne']) && empty($_GET['personne']) ) {
		
		if (count( $sqlComplement) >0 ) $tempComplement = ' AND ';
		else  $tempComplement = ' ';
		$sql = '('.$sqlPrepare.','.$AssoSelect.$sqlPrepareFrom.$AssoJoin.' WHERE '.lister($AssoWhere,' AND ').$tempComplement.lister($sqlComplement,' AND ').' GROUP BY commerce_commandes.id) UNION ('.$sqlPrepare.','.$PersoSelect.$sqlPrepareFrom.$PersoJoin.' WHERE '.lister($PersoWhere,' AND ').$tempComplement.lister($sqlComplement,' AND ').' GROUP BY commerce_commandes.id)  ';	
		
		/*
		$sql = $sqlPrepare.$sqlPrepareFrom;
		if (count($sqlComplement)>0) $sql .= ' WHERE '.lister($sqlComplement,' AND ');
		$sql .= ' GROUP BY commerce_commandes.id  ';	
	*/
	
	}
	
	// Choix d'une association
	 else if ( (!empty($_GET['association'])) || (!empty($_GET['choix_association'])) ) {
	 
	 	if (!empty($_GET['association'])) 
			$sqlComplement[] = ' associations.id = "'.$_GET['association'].'" ';
		else if (!empty($_GET['choix_association'])) 
			$sqlComplements[] = ' (LOWER(associations.nom) LIKE "%'.traiteTexte($_GET['choix_association']).'%"   OR  LOWER(numero_dossier) LIKE "%'.traiteTexte($_GET['choix_association']).'%" )';
	
		$sql = $sqlPrepare.$AssoJoin.' WHERE '.$AssoWhere.lister($sqlComplement,'AND').' AND	commerce_commandes.etat = commerce_payement_etat.id
AND commerce_commandes.payement = commerce_payement.id
AND  commerce_commandes.id = commerce_commandes_produits.id_commande GROUP BY commerce_commandes.id ';
	}	
		
	// Choix d'une personne
	else if ( (!empty($_GET['personne'])) || (!empty($_GET['choix_personne'])) ) {
	
		if (!empty($_GET['personne'])) 
			$sqlComplement[] = ' personnes.id = "'.$_GET['personne'].'" ';
		else if (!empty($_GET['choix_personne'])) 
			$sqlComplements[] = ' ( LOWER(choix_personne.nom) LIKE "%'.traiteTexte($_GET['choix_personne']).'%"  OR  LOWER(choix_personne.numero_adherent) LIKE "%'.traiteTexte($_GET['choix_personne']).'%" ) ';
		
		$sql = $sqlPrepare.','.$PersoSelect.$from.$PersoJoin.' WHERE '.lister($PersoWhere,'AND').' AND '.lister($sqlComplement,'AND').' AND	commerce_commandes.etat = commerce_payement_etat.id
AND commerce_commandes.payement = commerce_payement.id
AND  commerce_commandes.id = commerce_commandes_produits.id_commande GROUP BY commerce_commandes.id ';
	}
	

	// Compte les résultats	
		
		//echo $sql;
		
		try {
		  $retour['nbr_resultats'] = $connect->query($sql)->rowCount();
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
	
	// Affichage des résultats
	if (isset($affiche_resultats)) {
			
		// Tri 
		if (isset($_GET['sorts'])) {
			$tempTri = array();
			foreach ($_GET['sorts'] as $cle=>$val) {
				if ($val == 1) $ordre = 'ASC';
				else  $ordre = 'DESC';
				$tempTri[] = $cle.' '.$ordre;
			}
			$sql.= ' ORDER  BY '.lister($tempTri); 
		}
		else $sql.= ' ORDER  BY id DESC ';
		 
		if (isset($_GET['perPage'])) $sql.= ' LIMIT '.$_GET['perPage']; 
		if (isset($_GET['offset'])) $sql.= ' OFFSET '.$_GET['offset']; 
		
		
		//echo $sql;
		$_SESSION['last']['sql'] = $sql;
			$_SESSION['last']['recherche'] = $_GET; 
		
		$i=0;
		// Récupération des données
		try {
		  $select = $connect->query($sql);
		 $select->setFetchMode(PDO::FETCH_OBJ);
		  	  
		  while( $enregistrement = $select->fetch() )
		  {
			//$commande = new commande ($enregistrement->id);
			
              switch ($enregistrement->id_etat) {
                  case 1:
                    $class="alerte";
                  break;
                  
                  case 2:
                    $class="orange";
                  break;
                  
                  case 3:
                    $class="vert";
                  break;
                  
                  default:
                    $class="";
                  break;
                  
              }
              
			$retour['records'][$i]['numero_commande'] = $enregistrement->numero_commande;
			if ($enregistrement->type_utilisateur == 'P') $retour['records'][$i]['nom'] = '<a href="/personnes/detail/'.$enregistrement->id_utilisateur.'">'.$enregistrement->nom.'</a>';
			if ($enregistrement->type_utilisateur == 'A') $retour['records'][$i]['nom'] = '<a href="/associations/detail/'.$enregistrement->id_utilisateur.'">'.$enregistrement->nom.'</a>';
			$retour['records'][$i]['date_creation'] = affDate(strtotime($enregistrement->date_creation), true);
			$retour['records'][$i]['payement'] = '<span class="'.$class.'">'.$enregistrement->payement.'</span>';
			$retour['records'][$i]['etat'] = '<span class="'.$class.'">'.$enregistrement->etat.'</span>';
			$retour['records'][$i]['total'] = '<span class="'.$class.'">'.$enregistrement->total.'</span>';
			
			$retour['records'][$i]['actions'] .= '<button form-action="lien" form-element="/boutique/detail/'.$enregistrement->id.'" class="left details action"></button>';
			//if ($commande->type_utilisateur == 'P') $retour['records'][$i]['actions'] .= '<button form-action="lien" form-element="/personnes/detail/'.$commande->id_utilisateur.'" class="left personnes action"></button>';
			if ($enregistrement->id_etat == ETAT_PAYE) $retour['records'][$i]['actions'] .= '<button type="button" form-action="telecharger" form-element="FAC_'.$enregistrement->id.'" class="left action telecharger" title="Télécharger la facture" ></button>';
			
			

			$i++;			
		  }
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
		
		if(isset($_GET['perPage'])) $retour['queryRecordCount']= $retour['nbr_resultats'];
		$retour['totalRecordCount']= $retour['nbr_resultats'];
	}	
}

//////////////////////////
//
//		ASSOCIATIONS
//
//////////////////////////


if ($recherche == 'associations') {
	
	// Traitement du formulaire
		if (!empty($_GET['numero_adherent'])) 
			$sqlComplement[] = ' associations.numero_adherent = "'.$_GET['numero_adherent'].'" ';
	
		if (!empty($_GET['numero_dossier']))
			 $sqlComplement[] = ' associations.numero_dossier = "'.$_GET['numero_dossier'].'" ';
		
		if (!empty($_GET['id_association']))
			 $sqlComplement[] = ' associations.id = "'.$_GET['id_association'].'" ';
		else if (!empty($_GET['nom_association'])) 
			$sqlComplement[] = ' LOWER(associations.nom) LIKE "%'.traiteTexte($_GET['nom_association']).'%" ';
	
		if (!empty($_GET['sigle'])) 
			$sqlComplement[] = ' LOWER(associations.sigle) LIKE "%'.traiteTexte($_GET['sigle']).'%" ';
	
		if (!empty($_GET['numero_siret'])) 
			$sqlComplement[] = ' associations.numero_siret = "'.$_GET['numero_siret'].'" ';
	
		if (!empty($_GET['date_declaration_jo'])) 
			$sqlComplement[] = ' associations.date_declaration_jo = "'.convertDate($_GET['date_declaration_jo']).'" ';
	
		if (!empty($_GET['activites'])) {
			$sqlFrom[] = ' INNER JOIN associations_activites ON associations.id = associations_activites.association ';
			$sqlComplement[] = ' associations_activites.activite IN ('.lister($_GET['activites']).') ';
		}
	
		if ( (!empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin']))) 
			$sqlComplement[] = ' (associations.date_creation BETWEEN \''.convertDate($_GET['saisie_debut']).' 00:00:00\' AND \''.convertDate($_GET['saisie_fin']).' 23:59:59\') ';
		else if ( (!empty($_GET['saisie_debut'])) && (empty($_GET['saisie_fin']))) 
			$sqlComplement[] = ' associations.date_creation >= \''.convertDate($_GET['saisie_debut']).' 00:00:00\'  ';
		else if ( (empty($_GET['saisie_debut'])) && (!empty($_GET['saisie_fin']))) 
			$sqlComplement[] = ' associations.date_creation <= \''.convertDate($_GET['saisie_fin']).' 23:59:59\'  ';
	
		if (!empty($_GET['ville'])) 
			$sqlComplement[] = ' associations.ville = "'.$_GET['ville'].'" ';
		
		if (!empty($_GET['region'])) 
			$sqlComplement[] = ' regions.id IN ('.lister($_GET['region']).') ';
	
		if (!empty($_GET['departement'])) 
			$sqlComplement[] = ' departements.id IN ('.lister($_GET['departement']).') ';
			
		if (!empty($_GET['telephone_fixe'])) 
			$sqlComplement[] = ' REPLACE(REPLACE(associations.telephone_fixe, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_fixe']).'%" ';
			
		if (!empty($_GET['telephone_mobile'])) 
			$sqlComplement[] = ' REPLACE(REPLACE(associations.telephone_mobile, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_mobile']).'%" ';
			
		if (!empty($_GET['courriel'])) 
			$sqlComplement[] = ' LOWER(associations.courriel) LIKE "%'.strtolower($_GET['courriel']).'%" ';
			
		if (isset($_GET['prospect']) && $_GET['prospect'] == 1) 
			$sqlComplement[] = ' associations.prospect =  "1" ';
			
		if ($_GET['association_type']>0) 
			$sqlComplement[] = ' associations.association_type = "'.$_GET['association_type'].'" ';
	
		
		// Assurance gratuite
			
		if (!empty($_GET['inscrit'])) {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlComplement[] = ' personnes_associations.annee IN ( '.lister($_GET['inscrit']).') ';
		}
		// Problème de temps d'exécussion
		if (!empty($_GET['non_inscrit']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlComplement[] = ' personnes_associations.annee NOT IN ( '.lister($_GET['non_inscrit']).')';
		}
		
		// Président 
		if ( (!empty($_GET['nom_president'])) || (!empty($_GET['id_president']))) {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			if (!empty($_GET['id_president']))
				$sqlComplement[] = ' personnes.id = "'.$_GET['id_president'].'" ';
			else if (!empty($_GET['nom_president'])) 
				$sqlComplement[] = ' LOWER(personnes.nom) LIKE "%'.traiteTexte($_GET['nom_president']).'%" ';
		}
		
		if (!empty($_GET['prenom_president']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' LOWER(personnes.prenom) LIKE "%'.traiteTexte($_GET['prenom_president']).'%" ';
		}
		
		if (!empty($_GET['num_adherent']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' LOWER(personnes.id) LIKE "%'.traiteTexte($_GET['num_adherent']).'%" ';
		}
		
		if ( (!empty($_GET['naissance_debut'])) || (!empty($_GET['date_paiement_fin']))) {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
		}

		if ( (!empty($_GET['naissance_debut'])) && (!empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' (personnes.date_naissance BETWEEN \''.convertDate($_GET['naissance_debut']).' 00:00:00\' AND \''.convertDate($_GET['date_paiement_fin']).' 23:59:59\') ';
		else if ( (!empty($_GET['naissance_debut'])) && (empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' personnes.date_naissance >= \''.convertDate($_GET['naissance_debut']).' 00:00:00\'  ';
		else if ( (empty($_GET['naissance_debut'])) && (!empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' personnes.date_naissance <= \''.convertDate($_GET['naissance_fin']).' 23:59:59\'  ';	

		if (!empty($_GET['nom_jeune_fille']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' LOWER(personnes.nom_jeune_fille) LIKE "%'.traiteTexte($_GET['nom_jeune_fille']).'%" ';
		}
		
		if (!empty($_GET['courriel_president']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' LOWER(personnes.courriel) LIKE "%'.traiteTexte($_GET['courriel_president']).'%" ';
		}
		
		if (!empty($_GET['telephone_mobile_president']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' REPLACE(REPLACE(personnes.telephone_mobile, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_mobile_president']).'%" ';
		}
		
		if (!empty($_GET['telephone_fixe_president']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' REPLACE(REPLACE(personnes.telephone_fixe, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_fixe_president']).'%" ';
		}
		
		if (!empty($_GET['adresse_president']))  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' LOWER(personnes.adresse) LIKE "%'.traiteTexte($_GET['adresse_president']).'%" ';
		}
		

		/*
		if ($_GET['pays_president'] > 0)  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' personnes.pays = "'.$_GET['pays_president'].'" ';
		}
		*/
		
	
		if (isset($_GET['ville_president']) && $_GET['ville_president'] > 0)  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' personnes.ville = "'.$_GET['ville_president'].'" ';
		}
		
		if (isset($_GET['departement_president']) && $_GET['departement_president'] > 0)  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlFrom[] = ' INNER JOIN villes AS villes_p ON personnes.ville = villes_p.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' villes_p.departement = "'.$_GET['departement_president'].'" ';
		}
		
		if (isset($_GET['region_president']) && $_GET['region_president'] > 0)  {
			$sqlFrom[] = ' INNER JOIN personnes_associations ON associations.id = personnes_associations.association ';
			$sqlFrom[] = ' INNER JOIN personnes ON personnes_associations.personne = personnes.id ';
			$sqlFrom[] = ' INNER JOIN villes AS villes_p ON personnes.ville = villes_p.id ';
			$sqlComplement[] = ' personnes_associations.annee = "'.ANNEE_COURANTE.'" ';
			$sqlComplement[] = ' personnes_associations.cons_admin = 1 ';
			
			$sqlComplement[] = ' villes_p.region = "'.$_GET['region_president'].'" ';
		}
		
		
		// Amis
				
		if ($_GET['laf_annee'] > 0)  {
				$sqlFrom[] = ' INNER JOIN laf_adhesions_associations AS laf ON associations.id = laf.association ';
				$sqlComplement[] = ' laf.annee = "'.$_GET['laf_annee'].'" ';
			
			
			if ( (!empty($_GET['date_paiement_debut'])) && (!empty($_GET['date_paiement_fin']))) 
					$sqlComplement[] = ' (laf.date_paiement BETWEEN \''.convertDate($_GET['date_paiement_debut']).' 00:00:00\' AND \''.convertDate($_GET['date_paiement_fin']).' 23:59:59\') ';
			else if ( (!empty($_GET['date_paiement_debut'])) && (empty($_GET['date_paiement_fin']))) 
					$sqlComplement[] = ' laf.date_paiement >= \''.convertDate($_GET['date_paiement_debut']).' 00:00:00\'  ';
			else if ( (empty($_GET['date_paiement_debut'])) && (!empty($_GET['date_paiement_fin']))) 
					$sqlComplement[] = ' laf.date_paiement <= \''.convertDate($_GET['date_paiement_fin']).' 23:59:59\'  ';	
		
			if ($_GET['etat_paiement'] == 1) 
					$sqlComplement[] = ' laf.etat_paiement = "success" ';	
			
			
			if ($_GET['etat_paiement'] == 2)
					$sqlComplement[] = ' laf.etat_paiement <> "success" ';		
			
		
			if ($_GET['gmf'] < 2)
				$sqlComplement[] = ' laf.assurance_gmf = "'.$_GET['gmf'].'" ';		
			
		
			if ($_GET['depasse_gmf'] < 2) {			
					if ($_GET['depasse_gmf'] == 1) {
						$sqlComplement[] = ' laf.budget_fonctionnement  > '.MAX_BUDGET_GMF.' ';	
						$sqlComplement[] = ' laf.nbr_adherents  > '.MAX_ADHERENTS_GMF.' ';	
						$sqlComplement[] = ' laf.nbr_salaries  > '.MAX_SALARIES_GMF.' ';	
					}	else if ($_GET['depasse_gmf'] == 0) {
						$sqlComplement[] = ' laf.budget_fonctionnement  <= '.MAX_BUDGET_GMF.' ';	
						$sqlComplement[] = ' laf.nbr_adherents  <= '.MAX_ADHERENTS_GMF.' ';	
						$sqlComplement[] = ' laf.nbr_salaries  <= '.MAX_SALARIES_GMF.' ';	
					}	
			}
		
			if ($_GET['citizenplace'] < 2) 
					$sqlComplement[] = ' laf.logiciel_citizenplace = "'.$_GET['logiciel_citizenplace'].'" ';		
			
		
			if ($_GET['aide_citizenplace'] < 2) 
					$sqlComplement[] = ' laf.aide_citizenplace = "'.$_GET['aide_citizenplace'].'" ';		
			
		
			if ($_GET['groupama'] < 2) 
					$sqlComplement[] = ' laf.assurance_groupama = "'.$_GET['groupama'].'" ';		
			
		
			if ($_GET['banque_postale'] < 2) 
					$sqlComplement[] = ' laf.acces_info_banque_postale = "'.$_GET['banque_postale'].'" ';		
				
			if ($_GET['banque_postale'] < 2) 
					$sqlComplement[] = ' laf.acces_info_banque_postale = "'.$_GET['banque_postale'].'" ';				
		} 
					
		
		if (isset($_GET['adherent_annees']) && $_GET['adherent_annees'] > 0)  {
			$sqlComplement[] = ' laf_adhesions_associations.annee IN ( '.lister($_GET['adherent_annees']).') ';
			$sqlFrom[] = ' INNER JOIN laf_adhesions_associations AS laf ON associations.id = laf.association ';
		}
		
		if ($_GET['nouvel_adherent'] >0) {
			$sqlComplement[] = ' '.$_GET['nouvel_adherent'].' = (select annee from laf_adhesions_personnes where laf_adhesions_personnes.personne = laf.personne ORDER BY laf_adhesions_personnes.annee asc limit 1) ';
			$sqlFrom[] = ' INNER JOIN laf_adhesions_associations AS laf ON associations.id = laf.association ';
		}
		
		
		
		
		
		// LIMITE DE DELEGUES
		if (!empty($_SESSION['utilisateur']['regions'])) $sqlComplement[] = ' regions.id IN ('.lister($_SESSION['utilisateur']['regions']).') ';
		if (!empty($_SESSION['utilisateur']['departements'])) $sqlComplement[] = ' departements.id IN ('.lister($_SESSION['utilisateur']['departements']).') ';

	
	if (count($sqlComplement)==0) {
		$retour['erreur'] = 'Aucun critère sélectionné';
	} else {
	
		// Dédoublonne les requetes	
		if (isset($sqlFrom) && is_array($sqlFrom)) $sqlFrom = array_unique($sqlFrom);
		if (is_array($sqlComplement)) $sqlComplement = array_unique($sqlComplement);
	
		// Compte les résultats	
			$sql ='
			SELECT DISTINCT  
			associations.id, 
			associations.nom, 
			associations.numero_dossier, 
			associations.telephone_fixe, 
			associations.telephone_mobile, 
			associations.courriel,
			departements.nom AS departement, 
			regions.nom AS region, 
			departements.numero AS code_departement
			FROM associations 
			 LEFT JOIN villes ON associations.ville = villes.id
			 LEFT JOIN departements ON villes.departement = departements.id
			 LEFT JOIN regions ON villes.region = regions.id 
			  '.(isset($sqlFrom) && is_array($sqlFrom) ? lister($sqlFrom, '  '):'').'
			WHERE  associations.etat = 1  AND ';
			$sql .= lister($sqlComplement, ' AND ');
	
			// Sortie SQL
			//echo $sql;
	
			$_SESSION['last']['sql'] = $sql;
			$_SESSION['last']['recherche'] = $_GET; 
			
			try {
			  $retour['nbr_resultats'] = $connect->query($sql)->rowCount();
			} catch ( Exception $e ) {
			  echo "Une erreur est survenue lors de la récupération des créateurs";
			}
	
		// Affichage des résultats
		if (isset($affiche_resultats)) {
			
			// Tri 
			if (isset($_GET['sorts'])) {
				$tempTri = array();
				foreach ($_GET['sorts'] as $cle=>$val) {
					if ($val == 1) $ordre = 'ASC';
					else  $ordre = 'DESC';
					$tempTri[] = $cle.' '.$ordre;
				}
				$sql.= ' ORDER  BY '.lister($tempTri); 
			}
			if (isset($_GET['perPage'])) $sql.= ' LIMIT '.$_GET['perPage']; 
			if (isset($_GET['offset'])) $sql.= ' OFFSET '.$_GET['offset']; 
		
			$i=0;
			// Récupération des données
			try {
			  $select = $connect->query($sql);
			  $select->setFetchMode(PDO::FETCH_OBJ);
			  while( $enregistrement = $select->fetch() )
			  {
				$retour['records'][$i]['nom'] = $enregistrement->nom;
				$retour['records'][$i]['numero_dossier'] = $enregistrement->numero_dossier;
				$retour['records'][$i]['region'] = $enregistrement->region;
				$retour['records'][$i]['departement'] = $enregistrement->code_departement.' - '. $enregistrement->departement;
				$retour['records'][$i]['telephone_fixe'] = vide($enregistrement->telephone_fixe);
				$retour['records'][$i]['telephone_mobile'] = vide($enregistrement->telephone_mobile);
				$retour['records'][$i]['courriel'] = '<a href="mailto:'.$enregistrement->courriel.'">'.vide($enregistrement->courriel).'</a>';
				$retour['records'][$i]['actions'] = '<a href="'.$_SESSION['WEBROOT'].'associations/detail/'.$enregistrement->id.'"><span class="icon-eye"></span></a>';
				$i++;
			  }
			} catch ( Exception $e ) {
			  echo "Une erreur est survenue lors de la récupération des créateurs";
			}
		
			if(isset($_GET['perPage'])) $retour['queryRecordCount']= $retour['nbr_resultats'];
			$retour['totalRecordCount']= $retour['nbr_resultats'];
		}	
	}
}


//////////////////////////
//
//		PERSONNES
//
//////////////////////////


if ($recherche == 'personnes') {
	// Traitement du formulaire
		
		// Seulement les personnes actives (sauf si recherche spécifique)
		if (empty($_GET['inactive']))  
			$sqlComplement[] = ' personnes.etat = 1 ';
		
		if (!empty($_GET['id_personne'])) 
			$sqlComplement[] = ' personnes.id = "'.$_GET['id_personne'].'" ';
		elseif (!empty($_GET['nom'])) 
			$sqlComplement[] = ' LOWER(personnes.nom) LIKE "%'.traiteTexte(traiteCherche($_GET['nom'])).'%" ';
		
		if (!empty($_GET['num_adherent'])) 
			$sqlComplement[] = ' personnes.id = "'.$_GET['num_adherent'].'" ';
			
		if (!empty($_GET['civilite'])) 
			$sqlComplement[] = ' personnes.civilite = "'.$_GET['civilite'].'" ';
		
		if (!empty($_GET['prenom'])) 
			$sqlComplement[] = ' LOWER(personnes.prenom) LIKE "'.traiteTexte($_GET['prenom']).'%" ';
			
		if (!empty($_GET['nom_jeune_fille'])) 
			$sqlComplement[] = ' LOWER(personnes.nom_jeune_fille) LIKE "%'.traiteTexte($_GET['nom_jeune_fille']).'%" ';
			
		if (!empty($_GET['nom_jeune_fille'])) 
			$sqlComplement[] = ' LOWER(personnes.nom_jeune_fille) LIKE "%'.traiteTexte($_GET['nom_jeune_fille']).'%" ';
			
		if ( (!empty($_GET['naissance_debut'])) && (!empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' (personnes.date_naissance BETWEEN \''.convertDate($_GET['naissance_debut']).' 00:00:00\' AND \''.convertDate($_GET['naissance_fin']).' 23:59:59\') ';
		else if ( (!empty($_GET['naissance_debut'])) && (empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' personnes.date_naissance >= \''.convertDate($_GET['naissance_debut']).' 00:00:00\'  ';
		else if ( (empty($_GET['naissance_debut'])) && (!empty($_GET['naissance_fin']))) 
			$sqlComplement[] = ' personnes.date_naissance <= \''.convertDate($_GET['naissance_fin']).' 23:59:59\'  ';	
		
		
			
		if (!empty($_GET['telephone_fixe'])) 
			$sqlComplement[] = ' REPLACE(REPLACE(personnes.telephone_fixe, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_fixe']).'%" ';
			
		if (!empty($_GET['telephone_mobile'])) 
			$sqlComplement[] = ' REPLACE(REPLACE(personnes.telephone_mobile, ".", "") , " ", "")   LIKE "%'.traiteTel($_GET['telephone_mobile']).'%" ';

			
		if (!empty($_GET['courriel'])) 
			$sqlComplement[] = ' LOWER(personnes.courriel) LIKE "%'.strtolower($_GET['courriel']).'%" ';
		
		if (!empty($_GET['non_courriel'])) 
			$sqlComplement[] = ' personnes.courriel = "" ';
						
		if (isset($_GET['region']) && count($_GET['region'])>0) 
			$sqlComplement[] = ' regions.id IN ('.lister($_GET['region']).') ';
		
		if (isset($_GET['departement']) && count($_GET['departement'])>0) 
			$sqlComplement[] = ' departements.id IN ('.lister($_GET['departement']).') ';
	
		if (isset($_GET['adresse']) && !empty($_GET['adresse'])) 
				$sqlComplement[] = ' lower( personnes.adresse) LIKE "%'.traiteTexte($_GET['adresse']).'%" ';
				
		if ( (isset($_GET['pays']) && !empty($_GET['pays'])) && ($_GET['pays'] != 0) )
				$sqlComplement[] =  ' personnes.pays =  "'.$_GET['pays'].'" ';
				
		// Association		
		if (!empty($_GET['num_dossier'])) {
			$sqlComplement[] = ' UPPER( associations.numero_dossier ) =  "'.strtoupper($_GET['num_dossier']).'" ';
			$sqlFrom[] = ' INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne ';
			$sqlFrom[] = ' INNER JOIN associations ON personnes_associations.association = associations.id ';
		}
		if (!empty($_GET['id_association'])) {
			$sqlComplement[] = ' personnes_associations.association = '.$_GET['id_association'].' ';
			$sqlFrom[] = ' INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne ';
		}
		else if (!empty($_GET['nom_association'])) {
			$sqlComplement[] = ' lower( associations.nom ) LIKE "%'.traiteTexte(traiteCherche($_GET['nom_association'])).'%" ';
			$sqlFrom[] = ' INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne ';
			$sqlFrom[] = ' INNER JOIN associations ON personnes_associations.association = associations.id ';
		}
		
		// Particularités
		
		if (isset($_GET['membre_ca']) && $_GET['membre_ca'] > 0) {
			$sqlComplement[] = ' personnes_associations.cons_admin =  "'.$_GET['membre_ca'].'" ';
			$sqlFrom[] = ' INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne ';
		}
		
		if (isset($_GET['siege']) && $_GET['siege'] > 0) {
			$sqlComplement[] = ' personnes.siege =  "'.$_GET['siege'].'" ';
		}
		
		if (isset($_GET['delegue_statut']) && $_GET['delegue_statut'] > 0) {
			$sqlComplement[] = ' personnes.delegue_statut =  "'.$_GET['delegue_statut'].'" ';
		}		
		
		if (isset($_GET['delegue_type']) && $_GET['delegue_type'] > 0) {
			$sqlComplement[] = ' personnes.delegue_type =  "'.$_GET['delegue_type'].'" ';
		}	
		
		if (isset($_GET['bienfaiteur']) && $_GET['bienfaiteur'] == 1) {
			$sqlFrom[] = ' INNER JOIN bienfaiteurs ON personnes.id = bienfaiteurs.bienfaiteur ';
		}
		
		if (isset($_GET['presse']) && $_GET['presse'] == 1) {
			$sqlComplement[] = ' personnes.presse <>  "" ';
		}
		
		if (isset($_GET['elu']) && $_GET['elu'] == 1) {
			$sqlComplement[] = ' personnes.elu > "0" ';
		}
		
		if (isset($_GET['prospect']) && $_GET['prospect'] == 1) {
			$sqlComplement[] = ' personnes.prospect =  "1" ';
		}
		
		// Assurance gratuite
		
		if (isset($_GET['assure']) && $_GET['assure'] == 1) {
			$sqlFrom[] = ' INNER JOIN personnes_associations AS asso ON personnes.id = asso.personne ';
		
			if (!empty($_GET['actif'])) 
					$sqlComplement[] = ' asso.etat = "'.$_GET['actif'].'" ';
			
			if (!empty($_GET['inscrit'])) 
				$sqlComplement[] = ' asso.annee IN ( '.lister($_GET['inscrit']).') ';
		
			if (!empty($_GET['non_inscrit'])) 
				$sqlComplement[] = ' NOT EXISTS ( SELECT personnes_associations.annee,personnes_associations.personne FROM personnes_associations WHERE personnes_associations.annee IN ('.lister($_GET['non_inscrit']).') AND personnes_associations.personne = asso.personne ) ';
				
		}
		
		// Amis
		
		if (isset($_GET['amis']) && $_GET['amis'] == 1) {
			$sqlFrom[] = ' INNER JOIN laf_adhesions_personnes AS laf ON personnes.id = laf.personne ';
			
			
			if ($_GET['adherent_annees'] > 2014 ) {
				$sqlFrom[] = ' INNER JOIN `commerce_commandes` AS commande ON commande.id = laf.id_commande  ';
			
				if ($_GET['etat_paiement']>0) $sqlComplement[] = ' commande.etat = "'.$_GET['etat_paiement'].'" ';	
			}
			
				$sqlComplement[] = ' laf.annee = "'.$_GET['adherent_annees'].'" ';
				$sqlChamps[] = ' laf.annee AS annee, ';
			/*
			// NE MARCHE PAS
			if ($_GET['non_inscrit_annee'] == 1) 
				$sqlComplement[] = ' laf.annee <> "'.ANNEE_COURANTE.'" ';
			
			if ($_GET['renouvele_annee'] == 1) {
				$sqlComplement[] = ' laf.annee = "'.ANNEE_COURANTE.'" ';
				$sqlChamps[] = ' laf.annee AS annee, ';
				}
			// NE MARCHE PAS	
			if ($_GET['non_renouvele_annee'] == 1) 
				$sqlComplement[] = ' laf.annee = "'.ANNEE_COURANTE.'" ';
			*/	
				
			
			if ( (!empty($_GET['date_paiement_debut'])) && (!empty($_GET['date_paiement_fin']))) 
				$sqlComplement[] = ' (commande.date_creation BETWEEN \''.convertDate($_GET['date_paiement_debut']).' 00:00:00\' AND \''.convertDate($_GET['date_paiement_fin']).' 23:59:59\') ';
			else if ( (!empty($_GET['date_paiement_debut'])) && (empty($_GET['date_paiement_fin']))) 
				$sqlComplement[] = ' commande.date_creation >= \''.convertDate($_GET['date_paiement_debut']).' 00:00:00\'  ';
			else if ( (empty($_GET['date_paiement_debut'])) && (!empty($_GET['date_paiement_fin']))) 
				$sqlComplement[] = ' commande.date_creation <= \''.convertDate($_GET['date_paiement_fin']).' 23:59:59\'  ';	
	
			if ($_GET['origine_adhesion'] >0) 
				$sqlComplement[] = ' laf.connaissance = "'.$_GET['origine_adhesion'].'" ';
				
			if ($_GET['nouvel_adherent'] >0) 
				$sqlComplement[] = ' '.$_GET['nouvel_adherent'].' = (select annee from laf_adhesions_personnes where laf_adhesions_personnes.personne = laf.personne ORDER BY laf_adhesions_personnes.annee asc limit 1) ';
			
		}
		
		// Distinctions
		// Ne recherche que dans les distinctions acceptées
		
		if ($_GET['type_distinction'] > 0) {
			$sqlFrom[] = ' INNER JOIN distinctions ON personnes.id = distinctions.personne ';
	 		$sqlComplement[] = ' distinctions.distinction_type_decision = "'.$_GET['type_distinction'].'" ';
		}
		if ($_GET['annee_distinction'] > 0) {
			$sqlFrom[] = ' INNER JOIN distinctions ON personnes.id = distinctions.personne ';
	 		$sqlComplement[] = ' distinctions.distinction_type_decision <> 0 '; 			// il y a une décision
	 		$sqlComplement[] = ' distinctions.distinction_type_decision <> 5 '; 			// la décision n'est pas refusée
	 		$sqlComplement[] = ' distinctions.annee = "'.$_GET['annee_distinction'].'" ';
		}
		
		if ($_GET['annuaire'] > 0) {
			$sqlFrom[] = ' INNER JOIN distinctions ON personnes.id = distinctions.personne ';
	 		$sqlComplement[] = ' distinctions.distinction_type_decision <> 0 '; 			// il y a une décision
	 		$sqlComplement[] = ' distinctions.distinction_type_decision <> 5 '; 			// la décision n'est pas refusée
	 		$sqlComplement[] = ' distinctions.annuaire = "'.$_GET['annuaire'].'" ';
		}
		
		
		// SI FRANCE GESTION DES RÉGIONS / 	DÉPARTEMENTS / VILLE
		if ($_GET['pays'] == ID_FRANCE) {				
			
			if (!empty($_GET['ville'])) 
				$sqlComplement[] = ' personnes.ville = "'.$_GET['ville'].'" ';
		
			if (!empty($_GET['region'])) 
				$sqlComplement[] = ' regions.id IN ('.lister($_GET['region']).') ';
	
			if (!empty($_GET['departement'])) 
				$sqlComplement[] = ' departements.id IN ('.lister($_GET['departement']).') ';
			
			// LIMITE DE DELEGUES (pour la france uniquement)
			if (!empty($_SESSION['utilisateur']['regions'])) $sqlComplement[] = ' regions.id IN ('.lister($_SESSION['utilisateur']['regions']).') ';
			if (!empty($_SESSION['utilisateur']['departements'])) $sqlComplement[] = ' departements.id IN ('.lister($_SESSION['utilisateur']['departements']).') ';
		} 
		else if ($_GET['pays'] != ID_FRANCE) {	
			
			if (!empty($_GET['ville_pays'])) 
				$sqlComplement[] = ' lower( personnes.adresse) LIKE "%'.traiteTexte($_GET['ville_pays']).'%" ';
			
			if (!empty($_GET['code_pays'])) 
				$sqlComplement[] = ' lower( personnes.adresse) LIKE "%'.traiteTexte($_GET['code_pays']).'%" ';	
		}
		
	// Dé-doublonne les requetes	
	if (isset($sqlFrom) && is_array($sqlFrom)) $sqlFrom = array_unique($sqlFrom);
	if (is_array($sqlComplement)) $sqlComplement = array_unique($sqlComplement);
	
	// 

	 $sql ='SELECT DISTINCT 
	personnes.id, 
	personnes.nom, 
	personnes.prenom, 
	personnes.modificateur, 
	personnes.date_modification, 
	personnes.date_creation, 
	personnes.delegue_habilite, 
	personnes.delegue_adjoint, 
	personnes.delegue_type, 
	personnes.delegue_statut, 
	personnes.portrait, 
	personnes.presse, 
	personnes.elu, 
	personnes.prospect, 
	personnes.profession, 
	personnes.telephone_mobile, 
	personnes.telephone_fixe, 
	personnes.ville, 
	personnes.adresse, 
	personnes.mdp, 
	personnes.courriel, 
	personnes.ddn, 
	personnes.date_naissance, 
	personnes.nom_jeune_fille, 
	personnes.prenom_soundex, 
	personnes.nom_soundex,
	personnes.civilite, 
	personnes.numero_adherent, '.(isset($sqlChamps)?lister($sqlChamps, ' '):'').'	
	villes.code_postal, 
	villes.nom AS ville_label, 
	regions.nom AS region, 
	departements.numero AS code_departement, 
	departements.nom AS departement 
 FROM personnes 
	 LEFT OUTER  JOIN villes ON personnes.ville = villes.id
	 LEFT OUTER  JOIN departements ON villes.departement = departements.id 
	 LEFT OUTER JOIN regions ON villes.region = regions.id 
	  
	 '.(isset($sqlFrom)?lister($sqlFrom, '  '):'');

	 if (!is_array($sqlComplement) && strlen($sqlComplement)>0) $sql.= ' WHERE '. lister($sqlComplement, ' AND ');

 else if (is_array($sqlComplement)) $sql.= ' WHERE '. lister($sqlComplement, ' AND ');		
		// SORTIE DE LA REQUETTE
		//echo $sql;
		//die();
		
		$_SESSION['last']['sql'] = $sql;
		$_SESSION['last']['recherche'] = $_GET; 
		
		try {
		  $retour['nbr_resultats'] = $connect->query($sql)->rowCount();
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
	
	// Affichage des résultats
	if (isset($affiche_resultats)) {
			
		// Tri 
		if (isset($_GET['sorts'])) {
			$tempTri = array();
			foreach ($_GET['sorts'] as $cle=>$val) {
				if ($val == 1) $ordre = 'ASC';
				else  $ordre = 'DESC';
				$tempTri[] = $cle.' '.$ordre;
			}
			$sql.= ' ORDER  BY '.lister($tempTri); 
		}
		if (isset($_GET['perPage'])) $sql.= ' LIMIT '.$_GET['perPage']; 
		if (isset($_GET['offset'])) $sql.= ' OFFSET '.$_GET['offset']; 
		
		$i=0;
		// Récupération des données
		try {
		  $select = $connect->query($sql);
		  $select->setFetchMode(PDO::FETCH_OBJ);
		  while( $enregistrement = $select->fetch() )
		  {
			$retour['records'][$i]['nom'] = $enregistrement->nom;
			$retour['records'][$i]['prenom'] = $enregistrement->prenom;
			$retour['records'][$i]['region'] = $enregistrement->region;
			$retour['records'][$i]['departement'] = $enregistrement->code_departement.' - '. $enregistrement->departement;
			$retour['records'][$i]['telephone_fixe'] = vide(phone($enregistrement->telephone_fixe));
			$retour['records'][$i]['telephone_mobile'] = vide(phone($enregistrement->telephone_mobile));
			if(!empty($enregistrement->courriel)) $retour['records'][$i]['courriel'] = '<a href="mailto:'.$enregistrement->courriel.'">'.$enregistrement->courriel.'</a>';
			else $retour['records'][$i]['courriel'] = vide('');
			$retour['records'][$i]['details'] = $enregistrement->presse;
			$retour['records'][$i]['actions'] = '<a href="'.$_SESSION['WEBROOT'].'personnes/detail/'.$enregistrement->id.'"><span class="icon-eye"></span></a> ';
			if (isSiege()) $retour['records'][$i]['actions'] .= '<button  type="button" form-action="supprimer" form-element="section=personnes&id='.$enregistrement->id.'" class="supprimer right action"></button>
			';
			$i++;
		  }
		} catch ( Exception $e ) {
		  echo "Une erreur est survenue lors de la récupération des créateurs";
		}
		
		if(isset($_GET['perPage'])) $retour['queryRecordCount'] = $retour['nbr_resultats'];
		$retour['totalRecordCount']= $retour['nbr_resultats'];
        //print_r($retour);
	}	


}





if (!empty($retour)) {
	$json = json_encode($retour);
	print $json;
} else print json_encode('oups...');
?>