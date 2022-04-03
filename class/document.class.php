<?php
class Document
{
    
    public $codes_autorises;
    
    public $id_document;
    public $filename;
    public $demande = array();
    public $code;
    public $type;
    public $laf;
    public $table;
    public $date;
    public $annee;
    public $id_personne;
    public $id_association;
    public $personne;
    public $association;
    public $commande;
    public $auth;
    
     public $telecharge;
    
    public $data;
    public $path;
    
    public $footer;
    public $contenus;
    public $gabarit;
    public $nom;
    public $balises;
    
    static $connect;
    
    // SQL
    public $reqExiste;
    public $reqContenu;
    public $reqModifie;
    
    //Constructeur 
    public function __construct($filename)
    {
       
        //
        // Initialisation
        //
        
        /*
        $this->codes_autorises = array(
            'AIN', // Attestation individuelle
            'AAS', // Attestation association
            'AAB', // Liste des bénévoles d'une association
            'AAE', // Liste des élus d'une association
            'ACO', // Attestation collectivité
            'ACB', // Liste des bénévoles d'une collectivité
            'ALA', // Adhésion LAF Association
            'ALI', // Adhésion LAF Personne
            'LAF', // Reçu LAF
            'DON', // Reçu DON
            'FAC', // Facture
            'BDC', // Bon de commande
            'CAR' // CARTE DE MEMBRE
        );
        */
        
        
            
        // Nouvelle version Cercle
        
        $this->codes_autorises = array(  
            'ACA', // Adhésion Cercle Association
            'ACP', // Adhésion Cercle Personne
            'CNB', // Reçu Adhésion
            'DON', // Reçu DON
            'FAC', // Facture
            'DEV', // DEVIS
            'BDC', // Bon de commande
            'CAR', // CARTE DE MEMBRE CAR_ID
            'LET'  // Lettre
        );
        
        $this->path            = $_SESSION['ROOT'] . DOCUMENTS;
        $this->connection      = connect();
       
        //
        // Requetes SQL préparées 
        //
        
        // Existence d'un document dans la base
        $this->reqExiste = $this->connection->prepare('SELECT * FROM documents WHERE documents.filename = :filename');
        $this->reqExiste->bindParam(':filename', $this->filename, PDO::PARAM_STR, 100);
        
        // Récupération pied de page
        $this->reqFooter = $this->connection->prepare('SELECT * FROM configuration WHERE configuration.code = "footer_document" ');
        
        // Récupération de la structure du contenu
        $this->reqContenu = $this->connection->prepare('SELECT * FROM documents_contenus WHERE documents_contenus.code = :code ');
        $this->reqContenu->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        
        // Enregistrement dans la base
        $this->reqAjout = $this->connection->prepare("INSERT INTO  `documents` 
		  (`id`, `filename`, `auth`) 
		  VALUES 
		  ('', :filename,  :auth);");
        $this->reqAjout->bindParam(':filename', $this->filename, PDO::PARAM_STR, 100);
        
        // Sauvegarde gabarit
        $this->reqModifie = $this->connection->prepare("UPDATE  `documents_contenus` SET `contenu` = :contenus,  `nom` = :nom WHERE code = :code ");
        $this->reqModifie->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':nom', $this->nom, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':contenus', $this->contenus, PDO::PARAM_STR, 10000);
        
        
        $this->filename = $filename;
        $this->demande  = explode("_", $this->filename);
        
        // Récupère et teste le code
        $this->code = $this->demande[0];
        
        
        
        if (!in_array($this->code, $this->codes_autorises)) {
            // echo 'Demande incorrecte Code';
            return false;
        }
        
        switch ($this->code) {
        
            case 'BDC': 
                $this->id_association = $this->demande[1];
                $this->commande    = $this->demande[2];
            	$this->association = new association($this->id_association);
                
            	$this->gabarit = file_get_contents($_SESSION['ROOT'].'/documents/'.$this->code.'.html');
        		$this->gabarit = str_replace('{url}',$_SESSION['ROOT'],$this->gabarit);
        		$this->gabarit = str_replace('{nom}',$this->nom,$this->gabarit);
        	break;
        	

        	case 'DEV': // Devis achat 
        	case 'FAC': // Facture achat 
        	case 'DON': // Reçu fiscal DON
        		//FAC_id de la commande
        		$this->commande     = $this->demande[1];
        		$this->gabarit = file_get_contents($_SESSION['ROOT'].'../gestion/documents/'.$this->code.'.html');
        		$this->gabarit = str_replace('{url}',$_SESSION['ROOT'].'../gestion/',$this->gabarit);
        		$this->gabarit = str_replace('{nom}',$this->nom,$this->gabarit);
        	break;
        	
        	case 'CAR': // Carte
        	case 'CNB': // Recu
        	case 'LET': // Lettre
        		//CNB_type_id adhésion
        		//CAR_type_id_adhésion
        		//LET_type_id_adhésion
        		$this->type = $this->demande[1];
        		$this->laf = $this->demande[2];
        		 
        		$this->gabarit = file_get_contents($_SESSION['ROOT'].'../gestion/documents/'.$this->code.'.html');
        		$this->gabarit = str_replace('{url}',$_SESSION['ROOT'].'../gestion/',$this->gabarit);
        		$this->gabarit = str_replace('{nom}',$this->nom,$this->gabarit);
        		
        	break;
                
        }
        
        // Chargement footer
        $this->reqFooter->execute();
        $enregistrement = $this->reqFooter->fetch(PDO::FETCH_OBJ);
        $this->footer   = $enregistrement->contenu;
        
        // Chargement du gabarit de contenu
        $this->reqContenu->execute();
        $enregistrement = $this->reqContenu->fetch(PDO::FETCH_OBJ);
        $this->contenus = $enregistrement->contenu;
        $this->nom      = $enregistrement->nom;
        $this->balises  = $enregistrement->balises;
        $this->contenus = html_entity_decode($this->contenus);
     
    }
    
    public function creation()
    {
        
        // Création du document
        $fonction = 'creation_' . $this->code;
        if ($this->code != 'DEV') return $this->{$fonction}();
        else return $this->creation_FAC();
        
    }
    
    public function creation_CNB()
    {
    	
    	if ($this->type == 'A') {
        	$laf = new laf_association ($this->laf); 
        	$this->id_association = $laf->id_personne;
            $client = new association($this->id_association);
            $beneficiaire .= '<strong>'.$client->nom.'</strong><br>';
			$beneficiaire .= ''.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif;  
            
        } else if ($this->type == 'P') {
        	$laf = new laf_personne ($this->laf);
        	$this->id_personne = $laf->id_personne;
            $client = new personne($this->id_personne);
            
            $beneficiaire .= '<strong>'.$client->prenom.' '.$client->nom.'</strong><br>';
			$beneficiaire .= ''.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif; 

        }			
		// Chargement de la commande
		$commande = new commande($laf->id_commande);
		
		// Recherche du produits
		foreach ($commande->produits as $cle=>$prod) {
			if (($this->type == 'A') && ($prod->id_source == ID_ADHESION_ASSOCIATION)) {
				$adhesion = $prod;
				break;
			}
			
			if (($this->type == 'P') && ($prod->id_source == ID_ADHESION_PERSONNE)) {
				$adhesion = $prod;
				break;
			}
		}
		
		$num_adherent = $laf->annee.' / '.$client->id_personne;
		if (!empty($client->courriel))  $num_adherent .= '<br><em>Courriel : '. $client->courriel.'</em>';
		
		
		$this->contenus = str_replace('{montant}', $adhesion->prix , $this->contenus);
        $this->contenus = str_replace('{annee}', $laf->annee, $this->contenus);
        $this->contenus = str_replace('{reglement}', $commande->payement_libelle, $this->contenus);
        
        $this->contenus = str_replace('{date}', affDate(time()), $this->contenus);
		
		// Remplissage	
		$this->gabarit = str_replace('{beneficiaire}', $beneficiaire, $this->gabarit);
		$this->gabarit = str_replace('{numero_adherent}', $num_adherent, $this->gabarit);
		
		$this->footer = str_replace('{pied-de-page}', '<br>', $this->footer);
		$this->gabarit = str_replace('{numero-facture}', $order->order_number, $this->gabarit);
		$this->gabarit = str_replace('{total}', formateMontant($laf->montant,false), $this->gabarit);
		$this->gabarit = str_replace('{contenu}', $this->contenus, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer , $this->gabarit); 

        return $this->pdf();	
    }

    
	// Facture achat
	
    public function creation_FAC() 
    {
    	$commande = new commande ($this->commande);

    	if (empty($commande))  return false;
		
		// Chargement Utilisateur
		if ($commande->type_utilisateur == 'P') {
			$client = new personne ($commande->id_utilisateur);
		
			// Test utilisateur
			if (empty($client)) return false;

			// Vérifier l'état du paiement (3)
			if ($commande->payement != ID_MANDAT) {
				if ($commande->etat != ETAT_PAYE) return false;
			}
			
			// Detail
			$detail = '';
			$detail.= affDate(date('d F Y', strtotime($commande->date_creation))).'<br/>';
			$detail.= 'Réglement  '.$commande->payement_libelle.'<br/>';
		
			// Adresses de livraison
			$adresse = '';
			$adresse .= '<strong>'.$client->prenom.' '.$client->nom.'</strong><br>';
			$adresse .= ''.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$adresse .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$adresse .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$adresse .= '<em>'. $client->pays_label.'</em><br>';
			endif; 

		}
		
		// Générer le tableau html
		$contenu = "<p><strong>Récapitulatif de votre commande</strong></p>";
		$contenu .='<table border="0" style="width:95%;margin:0px;padding:0px;">
			<thead>
			<tr>
				<th style="width:55%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;" >Produit</th>
				<th align="right" style="width:10%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Quantité</th>
				<th align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Prix</th>
				<th align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Total</th></tr>
			</thead>
			<tbody>
			';
		
		foreach ($commande->produits as $id=>$produit) {
			$contenu .= '<tr>';
			$contenu .= '<td style="width:55%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->nom;
			if (!empty($produit->personnalisation)) $contenu .= '<br><strong>'.implode(' ',unserialize($produit->personnalisation)).'</strong>';
			$contenu .='</td>';
			$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->quantite.'</td>';
			$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->prix.' €</td>';
			$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;"><strong>'.($produit->prix*$produit->quantite).' €</strong></td>';
			$contenu .= '</tr>';
		}
	
		$contenu .='</tbody></table><br>';
	
		$contenu .='
			<table border="0" style="width:100%;margin:0px;padding:0px;">
				<tr>
					<td align="right" style="width:100%;">
						<h3>Total '. $commande->total .'€ TTC</h3>
					</td>
				</tr>
			</table>';
		
			
		// Total		

		
		// Remplissage	
		$this->footer = str_replace('{pied-de-page}', '<br>', $this->footer);
		$this->gabarit = str_replace('{numero-facture}', $commande->numero_commande, $this->gabarit);
		$this->gabarit = str_replace('{detail-facture}', $detail, $this->gabarit);
		$this->gabarit = str_replace('{adresse}', $adresse, $this->gabarit);
		
		$this->gabarit = str_replace('{contenu}', $contenu, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer , $this->gabarit);
        //echo $this->gabarit;

        return $this->pdf();
    }
    
    
    // Reçu Don
    
    public function creation_DON() 
    {
    	$commande = new commande ($this->commande);

    	if (empty($commande))  return false;
		
		// Chargement Utilisateur
		if ($commande->type_utilisateur == 'P') {
			$client = new personne ($commande->id_utilisateur);
		
			// Test utilisateur
			if (empty($client)) return false;

			// Vérifier l'état du paiement (3)
			if ($commande->payement != ID_MANDAT) {
				if ($commande->etat != ETAT_PAYE) return false;
			}
			
			// Detail
			$detail = '';
			$detail.= affDate(date('d F Y', strtotime($commande->date_creation))).'<br/>';
			$detail.= 'Réglement  '.$commande->payement_libelle.'<br/>';
		
			// Adresses de livraison
			$adresse = '';
			$adresse .= '<strong>'.$client->prenom.' '.$client->nom.'</strong><br>';
			$adresse .= ''.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$adresse .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$adresse .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$adresse .= '<em>'. $client->pays_label.'</em><br>';
			endif; 

		}
		
		// Générer le tableau html
		$contenu = "<p><strong>Récapitulatif de votre commande</strong></p>";
		$contenu .='<table border="0" style="width:95%;margin:0px;padding:0px;">
			<thead>
			<tr>
				<th style="width:55%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;" >Produit</th>
				<th align="right" style="width:10%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Quantité</th>
				<th align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Prix</th>
				<th align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">Total</th></tr>
			</thead>
			<tbody>
			';
		
		foreach ($commande->produits as $id=>$produit) {
			if ($produit->id_source == 1) {
				$contenu .= '<tr>';
				$contenu .= '<td style="width:55%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->nom;
				if (!empty($produit->personnalisation)) $contenu .= '<br><strong>'.implode(' ',unserialize($produit->personnalisation)).'</strong>';
				$contenu .='</td>';
				$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->quantite.'</td>';
				$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;">'.$produit->prix.' €</td>';
				$contenu .= '<td align="right" style="width:17.5%;border-bottom:1px solid #aaa;font-size:12px;padding:10px;margin:0px;"><strong>'.($produit->prix*$produit->quantite).' €</strong></td>';
				$contenu .= '</tr>';
			}
		}
	
		$contenu .='</tbody></table><br>';
	
		$contenu .='
			<table border="0" style="width:100%;margin:0px;padding:0px;">
				<tr>
					<td align="right" style="width:100%;">
						<h3>Total '. $commande->total .'€ TTC</h3>
					</td>
				</tr>
			</table>';
		
			
		// Total		

		
		// Remplissage	
		$this->footer = str_replace('{pied-de-page}', '<br>', $this->footer);
		$this->gabarit = str_replace('{numero-facture}', $commande->numero_commande, $this->gabarit);
		$this->gabarit = str_replace('{detail-facture}', $detail, $this->gabarit);
		$this->gabarit = str_replace('{adresse}', $adresse, $this->gabarit);
		
		$this->gabarit = str_replace('{contenu}', $contenu, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer , $this->gabarit);
        //echo $this->gabarit;

        return $this->pdf();
        
        
        /*
    	// Chargement DRUPAL
    	if (!function_exists(commerce_order_load)) loadDrupal();
    	
    	// Chargement commande et tests
    	
		$order = commerce_order_load($this->commande);	
		if (empty($order)) {
			// echo 'Demande incorrecte commande vide';
            return false;
		}
		
		if ($order->order_number != str_replace('DON_','',$this->filename)) {
			// echo 'Demande incorrecte commande ne correspond pas';
            return false;
		}
		
		// récupérer l'utilistateur de la commande et vérifier qu'il existe
		$user=user_load($order->uid);
		$username=explode('_',$user->name);
		
		if ($username[0]== 'associations') $client = new association($username[1]);
		else $client = new personne($username[1]);
		
		// Test utilisateur
		if (!$client) {
			// echo 'Demande incorrecte asso2';
            return false;
		}

		// Vérifier l'état du paiement
		$total = 0;
		$modes_paiement=array();
		$payments = commerce_payment_transaction_load_multiple(array(), array('order_id' =>  $this->commande));
		foreach ($payments as $pay) {
			
			$modes_paiement[$pay->payment_method] = paiement($pay->payment_method);
			if($pay->status != 'success') {
				// echo 'Demande incorrecte payement';
            	return false;
			} else {
				$total = $total + $pay->amount;
			}
		}
		
		
		// Vérification du total
		if ($order->commerce_order_total['und'][0]['amount'] != $total) {
			// echo 'Demande incorrecte total payement';
        	return false;
		}
		
		// Detail
		$detail = '';
		$detail.= date('d F Y', $order->created).'<br/>';
		$detail.= 'Réglement  '.lister($modes_paiement).'<br/>';
		
		
		
		// Remplissage	
		$this->footer = str_replace('{pied-de-page}', '<br>', $this->footer);
		$this->gabarit = str_replace('{numero-facture}', $order->order_number, $this->gabarit);
		$this->gabarit = str_replace('{total}', formateMontant($don), $this->gabarit);
		$this->gabarit = str_replace('{contenu}', $contenu, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer , $this->gabarit);
        
 	return $this->pdf();
        */
       
    }
    
	
	// Attestation Cercle Association
    
    public function creation_ACA()
    {
    	// Attestation amis association		
        // ALI_2014_123456.pdf
        // CODE_ANNEE_PERSONNE
        
        // Vérification de la légitimité de la demande
        
        // La personne et l'association existent
        if (!$this->personne) {
            // echo 'Demande incorrecte personne innexistante';
            return false;
    
        }
        
        $this->contenus = str_replace('{date-debut}', '1er janvier ' . $this->annee, $this->contenus);
        $this->contenus = str_replace('{date-fin}', '31 décembre ' . $this->annee, $this->contenus);
        
        
        // Fonction de la personne
        if (is_array($this->association->conseil_administration[$this->annee])) {
            foreach ($this->association->conseil_administration[$this->annee] as $cle => $val) {
                if ($val['id_personne'] == $this->personne->id_personne)
                    $statut .= $val['fonction_label'];
            }
        }
        $personne = '<b>' . $this->personne->civilite . ' ' . $this->personne->prenom . ' ' . $this->personne->nom . ' ' . $statut . '</b>, ' . $this->personne->adresse . ' - ' . $this->personne->code_postal . ' ' . $this->personne->ville_label . ' ' . $this->personne->pays_label;
        $personne .= '<br>Courriel : ' . vide($this->personne->courriel) . ' - Téléphone fixe : ' . vide($this->personne->telephone_fixe) . ' - Téléphone Mobile : ' . vide($this->personne->telephone_mobile) . '';
        $this->contenus = str_replace('{benevole}', $personne, $this->contenus);
        
        $this->gabarit = str_replace('{contenu}', $this->contenus, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer, $this->gabarit);
        
        return $this->pdf();
    }
  
    
	// Attestation Cercle Personne
	
    public function creation_ACP()
    {

        // La personne et l'association existent
        if (!$this->personne) return false;
        
        
        $this->contenus = str_replace('{date-debut}', '1er janvier ' . $this->annee, $this->contenus);
        $this->contenus = str_replace('{date-fin}', '31 décembre ' . $this->annee, $this->contenus);
        
        
        // Fonction de la personne
        if (is_array($this->association->conseil_administration[$this->annee])) {
            foreach ($this->association->conseil_administration[$this->annee] as $cle => $val) {
                if ($val['id_personne'] == $this->personne->id_personne)
                    $statut .= $val['fonction_label'];
            }
        }
        $personne = '<b>' . $this->personne->civilite . ' ' . $this->personne->prenom . ' ' . $this->personne->nom . ' ' . $statut . '</b>, ' . $this->personne->adresse . ' - ' . $this->personne->code_postal . ' ' . $this->personne->ville_label . ' ' . $this->personne->pays_label;
        $personne .= '<br>Courriel : ' . vide($this->personne->courriel) . ' - Téléphone fixe : ' . vide($this->personne->telephone_fixe) . ' - Téléphone Mobile : ' . vide($this->personne->telephone_mobile) . '';
        $this->contenus = str_replace('{benevole}', $personne, $this->contenus);
       
        $this->gabarit = str_replace('{contenu}', $this->contenus, $this->gabarit);
        
        $this->gabarit = str_replace('{pied-de-page}', $this->footer, $this->gabarit);
        
        return $this->pdf();
        
    }
    
    
    
    public function creation_CAR()
    {
        // CARTE DE MEMBRE
        
        // Vérification de la légitimité de la demande
        
        if ($this->type == 'A') {
        	$laf = new laf_association ($this->laf); 
        	$this->id_association = $laf->id_personne;
            $client = new association($this->id_association);
            $beneficiaire .= '<strong style="color:#0e72b5;">'.$client->nom.'</strong><br>';
			$beneficiaire .= '<div>'.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif;
			$beneficiaire .= '</div>';  
            
        } else if ($this->type == 'P') {
        	$laf = new laf_personne ($this->laf);
        	$this->id_personne = $laf->id_personne;
            $client = new personne($this->id_personne);
            
            $beneficiaire .= '<span style="color:#757575;font-size:11px;font-weight:normal;">Adhérent : '.$laf->annee.' / '.$client->id_personne.'<br><br></span>';
            
            $beneficiaire .= '<strong style="color:#0e72b5;font-size:13px;font-weight:bold;">'.$client->prenom.' '.$client->nom.'</strong><br>';
			$beneficiaire .= '<span style="color:#757575;font-size:13px;font-weight:normal;;">'.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif; 
			if (!empty($client->courriel))  $beneficiaire .= '<em>'. $client->courriel.'</em>';
            
            $beneficiaire .= '<br><br><span style="color:#0e72b5;">www.cercle-benevoles.fr</span>';

			$beneficiaire .= '</span>';

        }			
       
        $this->gabarit = str_replace('{image}','fond_carte.png' , $this->gabarit);

        $this->gabarit = str_replace('{contenu}', $beneficiaire, $this->gabarit);
        
        $connection = connect();
		$requete = $connection->prepare("UPDATE  `laf_adhesions_personnes` SET
				  `carte` =  1
				  WHERE id = :id_laf ");
		$requete->bindParam(':id_laf', $this->laf, PDO::PARAM_INT, 11); 
		$resultat = $requete->execute();

        return $this->pdf();
        
        
    }
    
    
    public function creation_LET ()
    {
        // LETTRE
        
        // Vérification de la légitimité de la demande
        
        if ($this->type == 'A') {
        	$laf = new laf_association ($this->laf); 
        	$this->id_association = $laf->id_personne;
            $client = new association($this->id_association);
            $beneficiaire .= '<strong style="color:#0e72b5;">'.$client->nom.'</strong><br>';
			$beneficiaire .= '<div>'.$client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif;
			$beneficiaire .= '</div>';  
            
        } else if ($this->type == 'P') {
        	$laf = new laf_personne ($this->laf);
        	$this->id_personne = $laf->id_personne;
            $client = new personne($this->id_personne);
            
            $beneficiaire .= '<strong >'.$client->prenom.' '.$client->nom.'</strong><br>';
			$beneficiaire .= $client->adresse.'<br>';
			if ($client->pays == ID_FRANCE) : 
				$beneficiaire .= ''.$client->code_postal.' '.$client->ville_label.'<br>';
			else :
				$beneficiaire .= ''. $client->code_pays .' '. $client->ville_pays .'<br>';
				$beneficiaire .= '<em>'. $client->pays_label.'</em><br>';
			endif; 
			

        }		

		$num_adherent = $laf->annee.' / '.$client->id_personne;
		if (!empty($client->courriel))  $num_adherent .= '<br><em>Courriel :<br> '. $client->courriel.'</em>';
		
        // Remplissage	
        $this->contenus = str_replace('{annee}',$laf->annee , $this->contenus);
        if ( ($client->civilite == 'Mme') || ($client->civilite == 'Mlle') ) $this->contenus = str_replace('{civilite}', 'Chère amie' , $this->contenus);
        else $this->contenus = str_replace('{civilite}', 'Cher ami' , $this->contenus);
        
		$this->footer = str_replace('{pied-de-page}', '<br>', $this->footer);
		$this->gabarit = str_replace('{beneficiaire}', $beneficiaire, $this->gabarit);
        $this->gabarit = str_replace('{numero_adherent}', $num_adherent, $this->gabarit);
	    $this->gabarit = str_replace('{contenu}', $this->contenus, $this->gabarit);
        $this->gabarit = str_replace('{pied-de-page}', $this->footer , $this->gabarit); 
        
        return $this->pdf();
        
    }
  
    
    private function pdf()
    {
         
         // Sauvegarde si nécessaire
         $this->auth();
         
         // Utilisation de la librairie PHP 
			require_once($_SESSION['ROOT'] . '../gestion/libs/html2pdf/html2pdf.class.php');
			
			if ($this->code != 'CAR') $html2pdf = new HTML2PDF('P','A4','fr');
			else {
				$width_in_mm = 85.6; 
				$height_in_mm = 53.98;
				$html2pdf = new HTML2PDF('L', array($width_in_mm,$height_in_mm), 'fr');
			}
			//$html2pdf->setModeDebug();
			$html2pdf->WriteHTML($this->gabarit);
			//$html2pdf->Output($this->filename.'.pdf', 'D');
			$html2pdf->Output($this->path.$this->filename.'.pdf', 'F'); // Enregistrement si besoin
			
			encode($this->filename,$this->path);
			if ($this->telecharge) decode($this->filename,$this->path);
			return true;
			
    }
    
    
    public function telecharge()
    {
        $this->telecharge = true;
        $this->creation();

    }
    
    public function sauve()
    {
        $this->reqModifie->execute();
    }
    
    public function auth()
    {
        // Teste la présence dans la base pour ne pas enregistrer une nouvelle fois en cas de modification
        $this->reqExiste->execute();
        if ($this->reqExiste->rowCount() == 0) {
        	$this->auth = genererMdp(20);
            $this->reqAjout->bindValue(':auth', $this->auth);
            $this->reqAjout->execute();
        } else {
        	$enregistrement = $this->reqExiste->fetch(PDO::FETCH_ASSOC);
        
        	$this->auth = $enregistrement['auth'];
        }
    }
    
  
    
    
}
?>