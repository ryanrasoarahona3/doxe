<?php

function cheminBoutique() {
    
    $etape = 0;
    $chemin = explode('/', $_SERVER['REQUEST_URI'] );
    if ($chemin[2] == 'inscription') $etape = 1;
    if ($chemin[2] == 'payement') $etape = 2;
    if ($chemin[2] == 'validation') $etape = 3;
 	if ($_SESSION['payement_finalise'] == true) $etape = 3; // la payement à aboutit
 	
    if (isLivrable()) {
		if ($etape==1) {
			$retour = '<ul class="menu_boutique">';
				$retour .= '<li class="actif">1 - Vos informations - livraison</li>';
				$retour .= '<li >2 - Paiement</li>';
				$retour .= '<li >3 - Finalisation</li>';
			$retour .= '</ul>';
		}
	
		 if ($etape==2) {
			$retour = '<ul class="menu_boutique">';
				if (!isset($_SESSION['payement'])) $retour .= '<li ><a href="/boutique/inscription">1 - Vos informations - livraison</a></li>';
				else if ($_SESSION['payement']==1) $retour .= '<li >1 - Vos informations - livraison</li>';
				$retour .= '<li class="actif">2 - Paiement</li>';
				$retour .= '<li >3 - Finalisation</li>';
			$retour .= '</ul>';
		}
	
		if ($etape==3) {
			$retour = '<ul class="menu_boutique">';
				 $retour .= '<li >1 - Vos informations - livraison</li>';
				$retour .= '<li >2 - Paiement</li>';
				$retour .= '<li class="actif">3 - Finalisation</li>';
			$retour .= '</ul>';
		}
 
		if ($etape==0) {
			$retour = '<ul class="menu_boutique">';
				$retour .= '<li >1 - Vos informations - livraison</li>';
				$retour .= '<li >2 - Paiement</li>';
				$retour .= '<li >3 - Finalisation</li>';
			$retour .= '</ul>';
		}
    } else {
		if ($etape==1) {
			$retour = '<ul class="menu_boutique">';
				
				$retour .= '<li >1 - Paiement</li>';
				$retour .= '<li >2 - Finalisation</li>';
			$retour .= '</ul>';
		}
	
		 if ($etape==2) {
			$retour = '<ul class="menu_boutique">';
				
				$retour .= '<li class="actif">1 - Paiement</li>';
				$retour .= '<li >2 - Finalisation</li>';
			$retour .= '</ul>';
		}
	
		if ($etape==3) {
			$retour = '<ul class="menu_boutique">';
				
				$retour .= '<li >1 - Paiement</li>';
				$retour .= '<li class="actif">2 - Finalisation</li>';
			$retour .= '</ul>';
		}
 
		if ($etape==0) {
			$retour = '<ul class="menu_boutique">';
				
				$retour .= '<li >1 - Paiement</li>';
				$retour .= '<li >2 - Finalisation</li>';
			$retour .= '</ul>';
		}
    }
    return $retour;
}

function creationPanier(){
    if ( (!isset($_SESSION['panier'])) || (!isset($_SESSION['panier']['produits'])) ) {
        $_SESSION['panier']=array();	
        $_SESSION['panier']['produits'] = array();
        $_SESSION['panier']['verrou'] = false;
    }
    return true;
}

function ajouterArticle($idProduit,$qteProduit=1,$prixProduit=0, $personnalisation='') {

    $ajoute = false;

    //Si le panier existe
    if (creationPanier() && !isVerrouille()) {
        // Connexion base de donnée
        $connect = connect();
        
        // Récupération du produit
        $reqCharge = $connect->prepare('
		SELECT commerce_produits.id AS id, 
			commerce_produits.nom, 
			commerce_produits.prix, 
			commerce_produits.livrable,
			commerce_produits.unique, 
			commerce_produits.type, 
			commerce_produits.personnalisable, 
            		commerce_produits.prix_personnalisation, 
            		commerce_produits.personnalisation, 
			commerce_produits.tva,
			commerce_produits.poids,
			commerce_produits.livrable,
			commerce_tva.montant AS taux_tva
		FROM commerce_produits 
			LEFT JOIN commerce_tva 
			ON commerce_produits.tva = commerce_tva.id
		WHERE commerce_produits.id = :id_produit
				');
        $reqCharge->bindParam(':id_produit', $idProduit, PDO::PARAM_INT, 11);

        try {
            $reqCharge->execute();
            $produit = $reqCharge->fetch(PDO::FETCH_ASSOC);

            // Modification du produit

            $produit['quantite'] = $qteProduit;
            if ($produit['unique'] == 1) $produit['quantite'] = 1;
            if ($produit['type'] == 1) $produit['prix'] = $prixProduit;
            
            $testePersonnalisation = trim(implode('', $personnalisation));
            if (strlen($testePersonnalisation) > 0)    {
                if ($produit['personnalisable'] == 1) $produit['personnalisation'] = $personnalisation;
                
            }

            // Si le produit est unique, on supprime le produit précédent et on ajoute
            if ($produit['unique'] == 1) {
                foreach ($_SESSION['panier']['produits'] as $id => $prod) {
                    if ($prod['id'] == $idProduit) unset($_SESSION['panier']['produits'][$id]);
                }
                if ( ($produit['prix']>0) && ($produit['quantite']>0) ) $ajoute = true;
            } 

            // Si le produit est unique et personnalisable (médailles) on permet l'ajout d'un autre produit
            else if ($produit['unique'] == 2) {
                if ($produit['prix']>0 && $produit['quantite']>0) $ajoute = true;
                if (strlen($testePersonnalisation) > 0) {
                    $produit['prix'] += $produit['prix_personnalisation'];
                }
            } 

            // Sinon, on test si il existe un produit précédent et on ajoute la quantité
            else if ($produit['unique'] == 0) {
                $existe = false;
                foreach ($_SESSION['panier']['produits'] as $id => $prod) {
                    if ($prod['id'] == $idProduit) {
                        $_SESSION['panier']['produits'][$id]['quantite'] = $_SESSION['panier']['produits'][$id]['quantite'] + $qteProduit;
                        $existe = true;
                    }
                }
                if (!$existe)  $ajoute = true;
            } 


            if ($ajoute == true) {
                $_SESSION['ajout_panier'] = true;
                array_push( $_SESSION['panier']['produits'], $produit);
            }


        } catch( Exception $e ) {
            echo "Erreur d'enregistrement : ", $e->getMessage();
        }  
    }
}


function supprimerArticle($idDemande){
    //Si le panier existe
    if (creationPanier() && !isVerrouille())
    {
        foreach ($_SESSION['panier']['produits'] as $id => $prod) {
            if ($id == $idDemande) {
                unset($_SESSION['panier']['produits'][$id]);
                return true;
            }
        }
        return false;
    }
    return false;
}


function modifierQTeArticle($idDemande,$qteProduit){

    //Si le panier éxiste
    if (creationPanier() && !isVerrouille())
    {
        //Si la quantité est positive on modifie sinon on supprime l'article
        if ($qteProduit > 0)
        {
            //Recharche du produit dans le panier
            foreach ($_SESSION['panier']['produits'] as $id => $prod) {
                if ($id == $idDemande) $_SESSION['panier']['produits'][$id]['quantite'] = $qteProduit;
            }
        }
        else
            supprimerArticle($idDemande);
    }

}

function isLivrable() {
   
  	 $produits = $_SESSION['panier']['produits'];
        foreach ($produits as $id => $prod) {
            if($prod['livrable'] == 1) return true;
        }

    return false ;
}

function montantGlobal($commande=false) {
    $total=0;
    if (!$commande) {
        $produits = $_SESSION['panier']['produits'];
        foreach ($produits as $id => $prod) {
            $total += $prod['prix'] * $prod['quantite'];
        }
        if (isLivrable()) {
       		if (isset($_SESSION['adresse_livraison']['pays'])) $total += calculeLivraison($_SESSION['adresse_livraison']['pays']);
        	else $total += calculeLivraison(ID_FRANCE);
        }
        
    } else {
        $produits = $commande->produits;
        foreach ($produits as $id => $prod) {
            $total += $prod->prix * $prod->quantite;
        }
       
    }

   return $total;
}

function montantTva($commande=false){
    $total=0;
    if (!$commande) {
        $produits = $_SESSION['panier']['produits'];
        foreach ($produits as $id => $prod) {
            if ($prod['taux_tva']>0) $total += ($prod['prix'] * $prod['quantite'])  - ( ($prod['prix'] * $produits[$id]['quantite']) * (100/($prod['taux_tva'] + 100)) );
        }
    } else {
        $produits = $commande->produits;
        foreach ($produits as $id => $prod) {
            if ($prod->taux_tva>0) $total += ($prod->prix * $prod->quantite)  - ( ($prod->prix * $prod->quantite) * (100/($prod->taux_tva + 100)) );
        }
    }

    return number_format ( $total, 2 , ',', ' ' ) ;
}

function isVerrouille(){
    if (isset($_SESSION['panier']) && $_SESSION['panier']['verrou'])
        return true;
    else
        return false;
}

function compterArticles()
{
    /*
   if (isset($_SESSION['panier']))
   return count($_SESSION['panier']['produits']);
   else
   return 0;
   */

    if (isset($_SESSION['panier'])) {
        $total = 0;
        foreach ($_SESSION['panier']['produits'] as $id=>$prod) {
            $total += $_SESSION['panier']['produits'][$id]['quantite'];
        }
        return $total;
    } else return 0;


}

function recapPayement ($type_payement,$montant) {
	$adresse = config('adresse');
	$rib = config('rib');
	$message = config('payement_'.$type_payement);
	
	$message = str_replace ('{montant}',$montant,$message);
	$message = str_replace ('{adresse}',$adresse,$message);
	$message = str_replace ('{rib}','<br>'.$rib,$message);
	
	return $message;
}

function recapCommande($id) {
    $commande = new commande($id);

    $message = "<h3>Récapitulatif commande N° ".$commande->numero_commande."</h3><strong> ".affDate(strtotime($commande->date_creation), true)."</strong></p>";
    $message .='<div id="panier"><table border="0" width="600">
			<thead>
			<tr><th>Produit</th><th>Quantité</th><th>Prix</th><th>Total</th></tr>
			</thead>
			';
	
    foreach ($commande->produits as $id=>$produit) {

        $message .= '<tr>';
        $message .= '<td>'.$produit->nom;
        if (!empty($produit->personnalisation)) $message .= '<br><strong>'.implode(' ',unserialize($produit->personnalisation)).'</strong>';
        $message .='</td>';
       
        $message .= '<td>'.$produit->quantite.'</td>';
        $message .= '<td>'.formateMontant($produit->prix).'</td>';
        $message .= '<td><strong>'.formateMontant($produit->prix*$produit->quantite).'</strong></td>';
        $tab .= '</tr>';
    }

    $message .='</table>';

    $message .='<table border="0" width="600">
		<tr><td align="right"><strong>Total '. formateMontant(montantGlobal($commande)) .' TTC</strong></td></tr>
		</table></div>';
    return $message;		
}

function supprimePanier(){
    unset($_SESSION['panier']);
}

function panier () {
    if (compterArticles() > 0) echo compterArticles().' article'.pluriel(compterArticles()).' - '.formateMontant(montantGlobal()).'';
    else echo 'Votre panier est vide';
}

function isProduitAdhesion () {
    foreach ($_SESSION['panier']['produits'] as $id => $prod) {
        if ( ($_SESSION['panier']['produits'][$id]['id'] == 2) || ($_SESSION['panier']['produits'][$id]['id'] == 3)) return $_SESSION['panier']['produits'][$id]['id'];
    }
    return false;
}

function isFormAdhesion () {
    if (isset($_SESSION['adhesion']['type_adhesion'] )) return $_SESSION['adhesion']['type_adhesion'] ;
    else return false;
}

function isFormInscription () {
    if (isset($_SESSION['inscription'])) return $_SESSION['inscription'];
    else return false;
}

function accesLivraison() {
    redirection('/boutique/inscription');
}

function accesPayement() {
    // Vérifications avant accès au paiement

    // Si au moins un produit 
    if (compterArticles() > 0) {

        // Utilisateur  NON connecté
        if (!isConnect()) {
		
			// Si form adhésion, test si produit adhésion et ajout si besoin (par sécurité)
			if ( !isProduitAdhesion() && isFormAdhesion()) {
				if (isFormAdhesion() == 'personnes') ajouterArticle(ID_ADHESION_PERSONNE);
				if (isFormAdhesion() == 'associations') ajouterArticle(ID_ADHESION_ASSOCIATION);
			}
			
			// Si produit Adhésion 
			if ( isProduitAdhesion()  ) {

				// Si formulaire adhésion remplit et pas de produit livrable, accès au payement
				if (isFormAdhesion() && !isLivrable() ) redirection('/boutique/payement');
			
				// Si  formulaire adhésion remplit et produit livrable, accès à la livraison
				else if (isFormAdhesion() && isLivrable() ) redirection('/boutique/inscription');
			
				// Sinon => redirection form adhésion
				else {
						if (isProduitAdhesion() == ID_ADHESION_PERSONNE) redirection('/adherez/adhesion_individuelle');
						else if (isProduitAdhesion() == ID_ADHESION_ASSOCIATION) redirection('/adherez/adhesion_association');
				}
		
		
				}
	
            // Si pas adhésion (achat normal)
            if ( !isProduitAdhesion() && !isFormAdhesion()) {

                // Si pas d'inscription => redirecton sur formulaire d'inscription
                if (!isFormInscription()) redirection('/boutique/inscription');

                // Si inscription complète => redirection sur page payement
                else if (isFormInscription()) {
                   redirection('/boutique/payement');
                   
                }
            }

        }	
        
         // Utilisateur  connecté
        else if (isConnect()) {
        	//if (!isLivrable() || isset($_SESSION['adresse_livraison']['pays']) ) redirection('/boutique/payement');
        	if ( !isLivrable() ) redirection('/boutique/payement');
        	else if ( isLivrable() ) redirection('/boutique/inscription');
        }
    }	else {

        // Si form adhésion
        if (isFormAdhesion()) {
            if (isFormAdhesion() == 'personnes') ajouterArticle(ID_ADHESION_PERSONNE);
            if (isFormAdhesion() == 'associations') ajouterArticle(ID_ADHESION_ASSOCIATION);
            redirection('/boutique/payement');
        } else {
            redirection('/boutique/panier');
        }
    }


}

function adresseLivraison($adresse) {
  
	// Adresse de livraison identique
	if ( isset($adresse['identique']) && $adresse['identique'] == 1) {
		$_SESSION['adresse_livraison']['identique'] = 1;
		unset($_SESSION['adresse_livraison']['nom']);
		unset($_SESSION['adresse_livraison']['prenom'] );
		unset($_SESSION['adresse_livraison']['adresse'] );
		unset($_SESSION['adresse_livraison']['ville']);
		if ( isConnect() ) $_SESSION['adresse_livraison']['pays'] =$_SESSION['utilisateur']['pays'];
		else if (isFormAdhesion() ) $_SESSION['adresse_livraison']['pays'] = $_SESSION['adhesion']['pays'];
		else if (isFormInscription() ) $_SESSION['adresse_livraison']['pays'] = $_SESSION['inscription']['pays'];
	}
	// Adresse livraison différente
	else {
		$_SESSION['adresse_livraison']['identique'] = 0;
		$_SESSION['adresse_livraison']['pays'] = $adresse['livraison_pays'];
		$_SESSION['adresse_livraison']['nom'] = $adresse['livraison_nom'];
		$_SESSION['adresse_livraison']['prenom'] = $adresse['livraison_prenom'];
		$_SESSION['adresse_livraison']['adresse'] = $adresse['livraison_adresse'];
		$_SESSION['adresse_livraison']['ville'] = $adresse['livraison_ville'];
		$_SESSION['adresse_livraison']['ville_pays'] = $adresse['livraison_ville_pays'];
		$_SESSION['adresse_livraison']['code_pays'] = $adresse['livraison_code_pays'];
	}

}


function calculeLivraison($id_pays = ID_FRANCE, $type_livraison = '1') {
    $produits = $_SESSION['panier']['produits'];
	foreach ($produits as $id => $prod) {
		$poids += $prod['poids'] * $prod['quantite'];
	}
	
	$connect = connect();
	$sql = 'SELECT commerce_livraison_montant.prix
			FROM commerce_livraison INNER JOIN commerce_livraison_montant ON commerce_livraison.id = commerce_livraison_montant.id_livraison
				 INNER JOIN commerce_livraison_zone ON commerce_livraison_zone.zone = commerce_livraison_montant.zone
			WHERE commerce_livraison_zone.id_pays = '.$id_pays.' 
			AND '.$poids.' >= commerce_livraison_montant.poid_min 
			AND '.$poids.' <= commerce_livraison_montant.poid_max
			AND commerce_livraison.id = 1 ';

	$reqLivraison = $connect->prepare($sql);

    try {
            $reqLivraison->execute();
            $prix = $reqLivraison->fetch(PDO::FETCH_ASSOC);
            return $prix['prix'];
      } catch( Exception $e ) {
            echo "Erreur d'enregistrement : ", $e->getMessage();
    }       
} 


//////////////////////////////////////////////////////////////////
//
//          ENREGISTREMENT DE LA COMMANDE
//
//////////////////////////////////////////////////////////////////

function enregistreCommande ($result=0) {
		
		//////////////////////////////////////////////////////////////////
		//
		//          ADHÉSION PUIS CONNEXION AUTOMATIQUE
		//
		//////////////////////////////////////////////////////////////////
                       
		if ( isProduitAdhesion ()  && isFormAdhesion () && !isPersonne()  ) {
			
			/////////////////////////////////
            //
            //          PERSONNES
            //
            /////////////////////////////////
            
			if ($_SESSION['adhesion']['type_adhesion'] == 'personnes') {
				
				$resultat = 0;
				
				// Sauvegarde personne
				$personne = new personne();
				foreach ($_SESSION['adhesion'] as $cle=>$val) {	
					if(property_exists('personne', $cle))  $personne->{$cle} = $val;
				}
				$personne->sauve();
				connexion($personne->id_personne,'P');
				
				$message = "
					Bonjour,
					<br>
					<br>
					Votre adhésion au Cercle National des Bénévoles à bien été pris en compte et sera validée dès réception de votre paiement.<br>
					";
				
				}
				
		}
        
            /////////////////////////////////
            //
            //          ASSOCIATIONS
            //
            /////////////////////////////////
// VERIFIER
			/*
			if ($_SESSION['adhesion']['type_adhesion'] == 'associations') {
				
				$resultat = 0;
				
				// Sauvegarde association
				$association = new association();
				foreach ($_SESSION['adhesion'] as $cle=>$val) {	
					if(property_exists('association', $cle))  $association->{$cle} = $val;
				}
				$association->sauve();
				connexion($association->id_association, 'A');
                
                // Préparation personne  
                $personne = new personne();
				foreach ($_SESSION['adhesion'] as $cle=>$val) {	
					if (substr($cle,0,2) == 'r_') {
                       
                        if (property_exists('personne', substr($cle,2))) {
                            if (!is_array($val) )  $val = trim($val);
                            $personne->{substr($cle,2)} = $val;
                        }	
                    }
				}
                
				$personne->sauve();
				$id_personne = $personne->id_personne;
				
				// Sauvegarde du lien avec l'association
				$association->ajoutePersonneCA($id_personne ,$_SESSION['adhesion']['annee'],time(),$_SESSION['adhesion']['r_qualite'],0);
				$association->gestionnaire($id_personne);
				
				$message = "
				Bonjour,
				<br>
				<br>
				Votre adhésion au Cercle National des Bénévoles à bien été pris en compte et sera validée dès réception de votre paiement.<br>
				";

			}
			*/
		
		// Cas d'un achat sans Adhésion
		if((montantGlobal()>0) && (compterArticles()>0)) {
			
			// Si inscription : enregistrer l'inscription et connexion
			if (isFormInscription ()) {
			
				// Inscription personnes 
				if ($_SESSION['inscription']['type_inscription'] == 'personnes') {
					$personne = new personne();
					foreach ($_SESSION['inscription'] as $cle=>$val) {	
						if(property_exists('personne', $cle)) $personne->{$cle} = $val;
					}
					$personne->sauve();
					connexion($personne->id_personne, $type='P');	
				} 
				// Inscription associations
				else if ($_SESSION['inscription']['type_inscription'] == 'associations') {
					$association = new association();
					foreach ($_SESSION['inscription'] as $cle=>$val) {	
						if(property_exists('association', $cle)) $personne->{$cle} = $val;
					}
					$association->sauve();
					connexion($association->id_association, $type='A');	
				}	
				
			}
			
			$message = "
			Bonjour,
			<br>
			<br>
			Votre commande à bien été prise en compte.<br>
			";
		}
		
		// Si utilisateur connecté (sois au moment de l'achat, soit suite à l'inscription)
		if (isConnect() ){
			if (isAssociation() ) {
				$type_utilisateur = 'A';
				if (!isset($association)) $association = new association($_SESSION['utilisateur']['id']);
			}
			else if (isPersonne() ) {
				$type_utilisateur = 'P';
				if (!isset($personne)) $personne = new personne($_SESSION['utilisateur']['id']);
			}
		}
	
		// Sauvegarde commande
		$commande = new commande();
		$commande->id_utilisateur = $_SESSION['utilisateur']['id'];
		$commande->type_utilisateur = $type_utilisateur;
		//$commande->date_creation = time();
		$commande->payement = $_SESSION['type_payement'];
		$commande->livraison_nom = $_SESSION['adresse_livraison']['nom'];
		$commande->livraison_prenom = $_SESSION['adresse_livraison']['prenom'];
		$commande->livraison_adresse = $_SESSION['adresse_livraison']['adresse'];
		$commande->livraison_pays = $_SESSION['adresse_livraison']['pays'];
		
		if ($_SESSION['adresse_livraison']['pays'] == ID_FRANCE) {
			$commande->livraison_ville = $_SESSION['adresse_livraison']['ville'];
		} else {
			$commande->livraison_ville = $_SESSION['adresse_livraison']['code_pays'].' '.$_SESSION['adresse_livraison']['ville_pays'];
		}
		
		// PAIEMENT CHEQUE...
		if ($_SESSION['type_payement'] > 1) $commande->etat = 1;
		// PAIEMENT CB
		else {
			$transaction_id = $result[6];
			$response_code = $result[11];
		
			switch ($response_code) {
				case '00':	
					$commande->etat = 3;
				break;
				case '02':	
					$commande->etat = 1;
					$commande->commentaire = 'Demande d’autorisation par téléphone à la banque à cause d’un dépassement de plafond d’autorisation sur la carte ';
				break;
			}
			$commande->reference = $transaction_id;
		}

		$resultat += $commande->sauve();
		
		
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
		
		$_SESSION['message'] = $message;
        $_SESSION['payement_finalise'] = true;
		

		// Email alerte 
		$email = new email();
		
		if (isset($association)) {
			$email->to ( $association->courriel , $association->nom );
			$acheteur = $association->id_association.' - '.$association->nom.'<br>'.$association->courriel.'<br><br>';
    		}
    	else if (isset($personne)) {
			$email->to ( $personne->courriel , $personne->prenom.' '.$personne->nom );
			$acheteur = $personne->id_personne.' - '.$personne->prenom.' '.$personne->nom.'<br>'.$personne->courriel.'<br><br>';
    		}
    	
		$email->sujet = 'Cercle National des Bénévoles - Récapitulatif de votre commande';
		$email->message = $message;
		$email->envoyer();
		
		// Envoi à l'admin
		$email = new email();
		$email->to ( 'tresorier@cercle-benevoles.fr' );
		$email->to ( 'alain.fontaine@cercle-benevoles.fr' );
		$email->to ( 'slebonnois@labo83.com' );
		$email->to ( config('email_commandes') );
    	$email->to ( config('email_contact') );
    	
    	$email->sujet = 'Cercle National des Bénévoles - Nouvelle commande';
		$email->message = $acheteur.$message;
		$email->envoyer();
	}