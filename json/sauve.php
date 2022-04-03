<?php
session_start();
require_once('../libs/requires.php');
require_once('../libs/constantes.php');

/*
// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Requête Ajax uniquement';
  trigger_error($user_error, E_USER_ERROR);
}
*/

$action = $_POST['action'];
$section = $_POST['section'];
 
$retour = array();
$retour['message']= '';
$retour['action']= $action;
$retour['section']= $section;
//$retour['id'] = 0;
/////////////////////
//
//	BOUTIQUE
//
/////////////////////

// Modification état d'une commande

if ($section == 'boutique') {
	
	switch($action) {
		case 'ajouter' : 
			$commande = new commande();
	
		break;
		
		case 'modifier' : 
			$commande = new commande($_POST['id_commande']);
		break;
	}
		
	foreach ($_POST as $cle=>$val) {
			if(property_exists('commande', $cle)) 
			{
				if (!is_array($val) )  $val = trim($val);
				$commande->{$cle} = $val;
			}			
	}	
	$retour['message'] = $commande->sauve();		
	$retour['id'] = $commande->id_commande;

}



/////////////////////
//
//	ASSOCIATIONS
//
/////////////////////

// Création gestion / modification

if ($section == 'associations') {
	$assoc = new association();
	
	switch($action) {
		case 'ajouter' : 
			
		break;
		
		case 'modifier' : 
			$assoc->id_association = $_POST['id_element'];
		break;
	}
		
	foreach ($_POST as $cle=>$val) {
			if(property_exists('association', $cle)) 
			{
				if (!is_array($val) )  $val = trim($val);
				$assoc->{$cle} = $val;
			}			
	}	
	$retour['message'] = $assoc->sauve();		
	$retour['id'] = $assoc->id_association;
}

// Inscription

if ($section == 'associations_inscription') {
	
	// Création association
	$assoc = new association();
	foreach ($_POST as $cle=>$val) {
			if(property_exists('association', $cle)) 
			{
				if (!is_array($val) )  $val = trim(traiteTexte($val));
				$assoc->{$cle} = $val;
			}			
	}	
	$assoc->sauve();		
	
	// Création membres CA
	foreach ($_POST['ajoutCA'] as $cle=>$val) {
		$perso = new personne();
		foreach ($val as $champ=>$contenu) {
				if(property_exists('personne', $champ)) 
				{
					if (!is_array($contenu) )  $contenu = trim(traiteTexte($contenu));
					$perso->{$champ} = $contenu;
				}		
		}	
		$perso->sauve();
		$pass[$perso->id_personne] = $perso->mdp_clair; // stocké pour envoi mdp
		$assoc->ajoutePersonne('',$perso->id_personne,ANNEE_COURANTE,date('d/m/Y'),$val['fonction'],$val['benevole'],0,'00/00/0000');
	}	
	
	if($_POST['informer_ca'] == 1) {
		// envoyer emails
	}
	
	$retour['action']='associations_inscription';
	
	if(!empty($_SESSION['destination'])) $retour['destination'] = $_SESSION['destination'];
	else $retour['destination'] = '/mon_association';
	
}

/////////////////////
//
//	PERSONNES
//
/////////////////////

if ($section == 'personnes') {

	if ($action == 'modifier' ) {
		$perso = new personne($_POST['id_element']);
		unset($perso->delegue_regions);
		unset($perso->delegue_departements);
	} else $perso = new personne();
	
	
	foreach ($_POST as $cle=>$val) {
		if (property_exists('personne', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$perso->{$cle} = $val;
		}			
	}	
	

	// Champs particuliers
	if(!empty($_POST['association_special_select_tab_resultat'])) $perso->association_special = $_POST['association_special_select_tab_resultat'];
	
	// Portrait
	foreach ($_POST as $cle=>$val) {
		if(substr($cle,0,12) == 'portrait' ) {
			//$tabCle = explode('_',$cle);
			
			//$perso->documents[$tabCle[2]][$tabCle[3]] = $val;
			$perso->portrait = $val;
			
		}			
	}	

	$retour['message'] = $perso->sauve();				
	$retour['id'] = $perso->id_personne;

}

if ($section == 'personnes_inscription') {
	$perso = new personne();
	
	foreach ($_POST as $cle=>$val) {
		if(property_exists('personne', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$perso->{$cle} = $val;
		}			
	}
	$perso->sauve();	
	//$perso->mdp_clair
	
	if(!empty($_SESSION['destination'])) $retour['destination'] = $_SESSION['destination'];
	else $retour['destination'] = '/mon_compte';	
}
//////////////////////////////////////
//
//	PERSONNES ASSOCIATIONS
//
//////////////////////////////////////
 
if (($section == 'personnes_associations') || ($section == 'associations_personnes') ) {
	if (!isset($_POST['benevole'])) $_POST['benevole']=0;
	if (empty($_POST['date_etat'])) $_POST['date_etat']='00/00/0000';
	
	if (isset($_POST['choix_date']) ) { 
		switch ($_POST['choix_date']) {
			case '1': // date du jour
				$date = date('d/m/Y');
			break;
		
			case '2': // Début de l'année
				$date = '01/01/'.date('Y');
			break;
		
			case '3': // Choix
				$date = $_POST['date'];
			break;
		}
	} else $date = $_POST['date'];
	
	
	//print_r($_POST);
	$asso = new association($_POST['id_association']);
	$retour['message'] =  $asso->ajoutePersonne($_POST['id_lien'],$_POST['id_personne'],$_POST['annee'],$date,$_POST['cons_admin'],$_POST['benevole'],$_POST['etat'],$_POST['date_etat']);
	
	if ($section == 'personnes_associations') {
		$retour['id'] = $_POST['id_personne'];
		$retour['select_annee'] = $_POST['annee'];
	} else {
		$retour['id'] = $_POST['id_association'];
	}
}

//////////////////////////////////////
//
//	CONSEIL D'ADMINISTRATION	
//
//////////////////////////////////////
 
if ($section == 'associations_ca')  {
	if (!isset($_POST['benevole'])) $_POST['benevole']=0;
	
	$asso = new association($_POST['id_association']);
	
	if ($_POST['identique_annee'] == 0) {
	
		$date = date('d/m/Y');
		$retour['message'] =  $asso->ajoutePersonneCa($_POST['id_personne'],$_POST['annee'],$date,$_POST['cons_admin'],$_POST['benevole']);
	
	} else {
		$retour['message'] =  $asso->copieCA($_POST['annee'],$_POST['identique_annee']);
	}
	
	$retour['id'] = $_POST['id_association'];
	$retour['annee'] = $_POST['annee'];
}

//////////////////////////////////////
//
//	REPRESENTANT
//
//////////////////////////////////////
 
if ($section == 'representant')  {
	
	
	$asso = new association($_POST['id_association']);
	$asso->representant = $_POST['id_personne'];
	$asso->sauveRepresentant();
	
	
	$retour['id'] = $_POST['id_association'];
	$retour['annee'] = $_POST['annee'];
}


//////////////////////////////////////
//
//	ANNONCES
//
//////////////////////////////////////
 
if (($section == 'personnes_annonces') || ($section == 'associations_annonces') || ($section == 'annonces')) {
	$annonce = new annonce();

	if (!empty($_POST['id_lien'])) {
		$annonce->id_annonce = $_POST['id_lien'];
	}

	foreach ($_POST as $cle=>$val) {
		if(property_exists('annonce', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$annonce->{$cle} = $val;
		}			
	}	
	
	$retour['message'] = $annonce->sauve();				
	$retour['id'] = $annonce->createur;
}

if ($section == 'annonces_refuser') {
	$annonce = new annonce($_POST['id_lien']);
	$annonce->refus = $_POST['refus'];
	$retour['message'] = $annonce->refuser();				
	$retour['id'] = $annonce->createur;
}

if ($section == 'annonces_valider') {
	$annonce = new annonce($_POST['id_lien']);
	$retour['message'] = $annonce->valider();				
	$retour['id'] = $annonce->createur;
}


//////////////////////////////////////
//
//	DISTINCTIONS
//
//////////////////////////////////////

if ($section == 'distinctions') {
	
	//d($_POST);

	if (!empty($_POST['id_lien'])) {
		$distinction = new distinction ($_POST['id_lien']);
	} else $distinction = new distinction ();
	
	foreach ($_POST as $cle=>$val) {
		if(property_exists('distinction', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$distinction->{$cle} = $val;
		}			
	}	
	$distinction->domaines = $_POST['domaines'];
	
	// Traitements spécifiques
	$distinction->parrains = $_POST['ajoutParrains_0_nom_tab_resultat'];

	foreach ($_POST as $cle=>$val) {
		if(substr($cle,0,12) == 'SWFUpload_0_' ) {
			$tabCle = explode('_',$cle);
			
			$distinction->documents[$tabCle[2]][$tabCle[3]] = $val;
		}			
	}	
	//d($distinction);

	$retour['message'] = $distinction->sauve();				
	$retour['id'] = $distinction->id_distinction;

}

//////////////////////////////////////
//
//	LAF ADHÉSION PERSONNES
//
//////////////////////////////////////

if ($section == 'personnes_amis') {

	// Création de l'adhésion

	// Enregistrement
	if (!empty($_POST['id_lien'])) {
		// Charge pour récupérer les infos précédentes
		$laf = new laf_personne($_POST['id_lien']);
	} else $laf = new laf_personne();
	
	
	foreach ($_POST as $cle=>$val) {
		if(property_exists('laf_personne', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$laf->{$cle} = $val;
		}			
	}	
	
	// Gestion du paiement
	
	// Création Paiement Commerce 
	if (empty($_POST['id_commande'])) {
		
		// On enregistre que dans le cas d'une création
		
		// Création de la commande
		$commande = new commande();
		$commande->id_utilisateur = $_POST['id_personne'];
		$commande->type_utilisateur = 'P';
		$commande->date_creation = $_POST['date_creation'];
		$commande->payement = $_POST['payement'];
		$commande->etat = $_POST['etat'];
		$commande->reference = $_POST['reference'];
		$commande->sauve();
		
		$commande_produit = new commande_produit();
		$commande_produit->copie(ID_ADHESION_PERSONNE);
		$commande_produit->nom = $commande_produit->nom .' '.$laf->annee;
		$commande_produit->id_commande = $commande->id_commande;
		$commande_produit->quantite = 1;
		$commande_produit->sauve();
		
		
		// Création des documents
			if ($commande->etat==ETAT_PAYE)  {
				$document = new document('FAC_'.$commande->id_commande);
				$document->creation();
			}
		
		$laf->id_commande = $commande->id_commande;
		$laf->montant = $commande_produit->prix;
	} 
		
	$retour['message'] = $laf->sauve();	
	$retour['id'] = $_POST['id_personne'];
	
}

//////////////////////////////////////
//
//	LAF ADHÉSION ASSOCIATIONS
//
//////////////////////////////////////

if ($section == 'associations_amis') {

	// Création de l'adhésion

	// Enregistrement
	if (!empty($_POST['id_lien'])) {
		// Charge pour récupérer les infos précédentes
		$laf = new laf_association($_POST['id_lien']);
	} else $laf = new laf_association();
	
	
	foreach ($_POST as $cle=>$val) {
		if(property_exists('laf_association', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$laf->{$cle} = $val;
		}			
	}	
	
	// Gestion du paiement
	
	// Création Paiement Commerce 
	if (empty($_POST['id_commande'])) {
		
		// On enregistre que dans le cas d'une création
		
		// Création de la commande
		$commande = new commande();
		$commande->id_utilisateur = $_POST['id_association'];
		$commande->type_utilisateur = 'A';
		$commande->date_creation = $_POST['date_creation'];
		$commande->payement = $_POST['payement'];
		$commande->etat = $_POST['etat'];
		$commande->reference = $_POST['reference'];
		$commande->sauve();
		
		$commande_produit = new commande_produit();
		$commande_produit->copie(ID_ADHESION_association);
		$commande_produit->nom = $commande_produit->nom .' '.$laf->annee;
		$commande_produit->id_commande = $commande->id_commande;
		$commande_produit->quantite = 1;
		$commande_produit->sauve();
		
		
		// Création des documents
			if ($commande->etat==ETAT_PAYE)  {
				$document = new document('FAC_'.$commande->id_commande);
				$document->creation();
			}
		
		$laf->id_commande = $commande->id_commande;
		$laf->montant = $commande_produit->prix;
	} 
		
	$retour['message'] = $laf->sauve();	
	$retour['id'] = $_POST['id_association'];
	
}

//////////////////////////////////////
//
//	DOCUMENTS	
//
//////////////////////////////////////
 
if ($section == 'documents')  {
	
	$docu = new document($_POST['code']);

	foreach ($_POST as $cle=>$val) {
		if(property_exists('document', $cle)) {
			if (!is_array($val) )  $val = trim($val);
			$docu->{$cle} = $val;
		
		}			
	}	
	
	$docu->sauve();
	$retour['id'] = $_POST['code'];
}



//////////////////////////////////////
//
//	BIENFAITEUR
//
//////////////////////////////////////
 
if ($section == 'bienfaiteur')  {
	
	
		// Sauvegarde commande
		$commande = new commande();
		$commande->id_utilisateur = $_POST['bienfaiteur'];
		$commande->type_utilisateur = $_POST['type_utilisateur'];
		//$commande->date_creation = time();
		$commande->payement = $_POST['type_payement'];
		$commande->etat = $_POST['etat'];
		$commande->reference = $_POST['reference'];
		
		/*
		$commande->livraison_nom = $_SESSION['adresse_livraison']['nom'];
		$commande->livraison_prenom = $_SESSION['adresse_livraison']['prenom'];
		$commande->livraison_adresse = $_SESSION['adresse_livraison']['adresse'];
		$commande->livraison_pays = $_SESSION['adresse_livraison']['pays'];
		
		
		if ($_SESSION['adresse_livraison']['pays'] == ID_FRANCE) {
			$commande->livraison_ville = $_SESSION['adresse_livraison']['ville'];
		} else {
			$commande->livraison_ville = $_SESSION['adresse_livraison']['code_pays'].' '.$_SESSION['adresse_livraison']['ville_pays'];
		}
		*/
		
		
		// SAUVEGARDE COMMANDE
		$commande->sauve();
		
		// Sauvegarde produits
		$commande_produit = new commande_produit();
		$commande_produit->copie (1);
		$commande_produit->id_commande = $commande->id_commande;
		$commande_produit->quantite = 1;
		$commande_produit->prix = $_POST['don'];
		
		$commande_produit->sauve();
		
		/*
		// Sauvegarde produits
		foreach ($_SESSION['panier']['produits'] as $num => $produit) {
			if (!empty($produit)) {	
		
				$commande_produit = new commande_produit();
				$commande_produit->copie( $produit['id']);
				$commande_produit->id_commande = $commande->id_commande;
				$commande_produit->quantite = $produit['quantite'];
				$commande_produit->prix = $produit['prix'];
				$commande_produit->tva = $produit['tva'];
				$commande_produit->taux_tva = $produit['taux_tva'];
				$commande_produit->poids = $produit['poids'];
				$commande_produit->livrable = $produit['livrable'];
				
				// Si adhésion, on sauvegarde l'adhésion
				
					if (($produit['id'] == ID_ADHESION_PERSONNE) &&  isProduitAdhesion ()  && isFormAdhesion ()) {
                       		$commande_produit->nom .= ' '.$_SESSION['adhesion']['annee'];
							$laf = new laf_personne ();
							foreach ($_SESSION['adhesion'] as $cle=>$val) {	
								if(property_exists('laf_personne', $cle)) $laf->{$cle} = $val;
							}
							$laf->id_commande = $commande->id_commande;
                            				$laf->id_personne = $personne->id_personne;
							//$resultat += $laf->sauve();
							$laf->sauve();
					}
					if (($produit['id'] == ID_ADHESION_ASSOCIATION) &&  isProduitAdhesion ()  && isFormAdhesion ()) {
							$commande_produit->nom .= ' '.$_SESSION['adhesion']['annee'];
							$laf = new laf_association ();
							foreach ($_SESSION['adhesion'] as $cle=>$val) {	
								if(property_exists('laf_association', $cle)) $laf->{$cle} = $val;
							}
							$laf->id_commande = $commande->id_commande;
                            				$laf->id_association = $association->id_association;
							//$resultat += $laf->sauve();	
							$laf->sauve();	
					}
					
				if (!empty($produit['personnalisation'])) $commande_produit->personnalisation = serialize($produit['personnalisation']);
				//$resultat += $commande_produit->sauve();
				$commande_produit->sauve();
			}
		}
		
		// Sauvegarde de la livraison 
		if ( isLivrable() ) {
			$livraison = calculeLivraison($commande->livraison_pays);
			$commande_produit = new commande_produit();
			$commande_produit->copie(LIVRAISON);
			$commande_produit->id_commande = $commande->id_commande;
			$commande_produit->quantite = 1;
			$commande_produit->prix = $livraison;
			$commande_produit->tva = 0;
			$commande_produit->taux_tva = 0;
			$commande_produit->poids = 0;
			$commande_produit->sauve();
			
		}
		// Finalisation message et envoi
        
        // chargement des produits
        $commande->charge_produits();
	
		// Finalisation paiement
	
		if ($_SESSION['type_payement'] > 1) $message.= "<p><strong>Finalisation du paiement</strong></p>";
		$message.= recapPayement($commande->payement,montantGlobal($commande));
		
		// Cas d'un paiement par mandat administratif
		if ($commande->payement == ID_MANDAT) {
			$doc = new document('DEV_'.$commande->id_commande);
			$doc->auth();
			echo '<br><a href="'.$_SESSION['ROOT_DRUPAL'].'/telecharger?filename=FAC_'.$commande->id_commande.'&auth='.$doc->auth.'" target="_new">Télécharger votre devis</a><br/>'; 
		}
	
		// Récap commande
		$message.= '<br>'.recapCommande($commande->id_commande);   	
		
		// Livraison
		if ($livraison>0) {
			// Rechargement de la commande pour récupérer les libellés pays
			$commande = new commande($commande->id_commande);
			if (isset($association)) $message.= '<br><p><h3>Adresse de livraison</h3>'.formatLivraison($commande,$association).'</p>';
			else if (isset($personne)) $message.= '<br><p><h3>Adresse de livraison</h3>'.formatLivraison($commande,$personne).'</p>';
		}
		*/
		
		
	
	$retour['section']= 'personnes';
	//$retour['action'] = 'gestion';
	$retour['id'] = $_POST['bienfaiteur'];
}



if (!empty($retour)) {
	$json = json_encode($retour, JSON_FORCE_OBJECT);
	print $json;
} else print json_encode('oups...');
?>