<?php
class Commande {


    public $id_commande;
    public $numero_commande;
	public $id_utilisateur;
	public $type_utilisateur;
	public $date_creation;
	public $date_creation_mysql;
	public $date_modification;
	public $annee;
	public $payement;
	public $payement_libelle;
	public $etat;
	public $etat_libelle;
	public $numero_colis;
	public $commentaire;
	public $reference;
	
	public $livraison_nom;
	public $livraison_prenom;
	public $livraison_adresse;
	public $livraison_ville;
	public $livraison_ville_label;
	public $livraison_pays;
	public $livraison_pays_label;
	public $livraison_code_postal;
	
	public $produits;
	public $total;
	public $tva;

	static $connect;
	
	// SQL
	public $reqModifie; 
	public $reqAjout; 
	public $reqSupprime; 
	public $reqCharge; 
	public $reqChargeProduits; 
	
    //Constructeur 
   public function __construct ($id=0) {    
    	 
    	  
    	  //
    	  // Initialisation
    	  //
 	 
    	  $this->connection = connect();
    	  
	
    	  //
    	  // Requetes SQL préparées 
    	  //


    	  // Modifier 
    	  
		  
		 
		// Chargement  
			
		
			$this->reqCharge = $this->connection->prepare('
			SELECT commerce_commandes.numero_commande, 
				commerce_commandes.id_utilisateur, 
				commerce_commandes.type_utilisateur, 
				commerce_commandes.date_modification, 
				commerce_commandes.date_creation, 
				commerce_commandes.payement, 
				commerce_commandes.etat, 
				commerce_commandes.numero_colis, 
				commerce_commandes.commentaire, 
				commerce_commandes.reference, 
				commerce_payement.nom AS payement_libelle, 
				commerce_payement_etat.nom AS etat_libelle,
				commerce_commandes.livraison_prenom,
				commerce_commandes.livraison_nom,
				commerce_commandes.livraison_adresse,
				commerce_commandes.livraison_ville,
				commerce_commandes.livraison_pays,
				villes.nom AS livraison_ville_label,
				villes.code_postal AS livraison_code_postal,
				pays.nom_fr_fr AS livraison_pays_label
			FROM commerce_commandes INNER JOIN commerce_payement ON commerce_commandes.payement = commerce_payement.id
				 INNER JOIN commerce_payement_etat ON commerce_commandes.etat = commerce_payement_etat.id
				 LEFT JOIN pays ON pays.id = commerce_commandes.livraison_pays
				 LEFT JOIN villes ON villes.id = commerce_commandes.livraison_ville
			WHERE commerce_commandes.id = :id_commande
			');
			$this->reqCharge->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		
		
		// Chargement par référence
		
		$this->reqChargeRef = $this->connection->prepare('
			SELECT commerce_commandes.id AS id_commande, 
			commerce_commandes.numero_commande, 
				commerce_commandes.id_utilisateur, 
				commerce_commandes.type_utilisateur, 
				commerce_commandes.date_modification, 
				commerce_commandes.date_creation, 
				commerce_commandes.payement, 
				commerce_commandes.etat, 
				commerce_commandes.numero_colis, 
				commerce_commandes.commentaire, 
				commerce_commandes.reference, 
				commerce_payement.nom AS payement_libelle, 
				commerce_payement_etat.nom AS etat_libelle,
				commerce_commandes.livraison_prenom,
				commerce_commandes.livraison_nom,
				commerce_commandes.livraison_adresse,
				commerce_commandes.livraison_ville,
				commerce_commandes.livraison_pays,
				villes.nom AS livraison_ville_label,
				villes.code_postal AS livraison_code_postal,
				pays.nom_fr_fr AS livraison_pays_label
			FROM commerce_commandes INNER JOIN commerce_payement ON commerce_commandes.payement = commerce_payement.id
				 INNER JOIN commerce_payement_etat ON commerce_commandes.etat = commerce_payement_etat.id
				 LEFT JOIN pays ON pays.id = commerce_commandes.livraison_pays
				 LEFT JOIN villes ON villes.id = commerce_commandes.livraison_ville
			WHERE commerce_commandes.reference = :reference
			');
			$this->reqChargeRef->bindParam(':reference', $this->reference, PDO::PARAM_INT, 11);
		
		
		// Chargement des produits
			
			$this->reqChargeProduits = $this->connection->prepare('
			SELECT commerce_commandes_produits.id as id_produit
			FROM commerce_commandes_produits
			WHERE commerce_commandes_produits.id_commande =  :id_commande ORDER BY commerce_commandes_produits.id ASC
			');
			$this->reqChargeProduits->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		
		
		// Sauvegarde Commande

		 $this->reqAjout = $this->connection->prepare("INSERT INTO  `commerce_commandes` 
		  (`id`, `numero_commande`,`id_utilisateur`, `type_utilisateur`, `date_creation`,  `payement`, `etat`, `numero_colis`, `commentaire`, `reference`,`livraison_nom`,`livraison_prenom`,`livraison_adresse`,`livraison_pays`,`livraison_ville`) 
		  VALUES 
		  ('',  :numero_commande, :id_utilisateur, :type_utilisateur , :date_creation ,  :payement , :etat , :numero_colis , :commentaire , :reference  , :livraison_nom, :livraison_prenom, :livraison_adresse, :livraison_pays, :livraison_ville);");
	
		 $this->reqAjout->bindParam(':numero_commande', $this->numero_commande,PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':id_utilisateur', $this->id_utilisateur, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':type_utilisateur', $this->type_utilisateur, PDO::PARAM_STR, 1);
		 $this->reqAjout->bindParam(':date_creation', $this->date_creation_mysql, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':payement', $this->payement, PDO::PARAM_INT, 2);
		 $this->reqAjout->bindParam(':etat', $this->etat, PDO::PARAM_INT, 2);
		 $this->reqAjout->bindParam(':numero_colis', $this->numero_colis, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR, 500);
		 $this->reqAjout->bindParam(':reference', $this->reference, PDO::PARAM_STR, 255);
		 
		 $this->reqAjout->bindParam(':livraison_nom', $this->livraison_nom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':livraison_adresse', $this->livraison_adresse, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':livraison_pays', $this->livraison_pays, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':livraison_prenom', $this->livraison_prenom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':livraison_ville', $this->livraison_ville, PDO::PARAM_STR, 255);
		 
		 
		 
	
		 $this->reqModifie = $this->connection->prepare("UPDATE  `commerce_commandes` SET 
			 
			  `payement` =  :payement,
			  `etat` =  :etat,
			  `numero_colis` =  :numero_colis,
			  `commentaire` =  :commentaire,
			  `reference` =  :reference
		  WHERE id = :id_commande ");
		 	
		 $this->reqModifie->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':payement', $this->payement, PDO::PARAM_INT, 2);
		 $this->reqModifie->bindParam(':etat', $this->etat, PDO::PARAM_INT, 2);
		 $this->reqModifie->bindParam(':numero_colis', $this->numero_colis, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR, 500);
		 $this->reqModifie->bindParam(':reference', $this->reference, PDO::PARAM_STR, 255);
		 
		 
		 
		 
		$this->reqModifieNum = $this->connection->prepare("UPDATE  `commerce_commandes` SET 
			  `numero_commande` =  :numero_commande
		  WHERE id = :id_commande ");
		 	
		 $this->reqModifieNum->bindParam(':numero_commande', $this->numero_commande, PDO::PARAM_STR, 255);
		 $this->reqModifieNum->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		 
		 
		 // Cahrge last num commande 
		 
		 $this->reqChargeNum = $this->connection->prepare('
			SELECT commerce_commandes.numero_commande
			FROM commerce_commandes
			WHERE commerce_commandes.date_creation LIKE :annee   ORDER BY date_creation  desc limit 1
			
			');
		$this->reqChargeNum->bindParam(':annee', $this->annee,  PDO::PARAM_STR, 255);
			
			
		// Chargement
	
		if (!empty($id)) {
			$this->id_commande = $id;
			$this->charge();
		}
	}
	
	public function chageRef($ref) {
		$this->reference = $ref;
		try {
			$this->reqChargeRef->execute();
			$enregistrement = $this->reqChargeRef->fetch(PDO::FETCH_ASSOC);

				foreach ($enregistrement as $cle=>$val) {	
					if(property_exists('commande', $cle)) 
						{
							$this->{$cle} = $val;
						}	
				}
			
			// Chargement des produits
			$this->charge_produits();			
			
		} catch( Exception $e ) {
			echo "Erreur : ", $e->getMessage();
		}
	}
	
	// Récupère le produit
	public function charge () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				$enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC);

    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('commande', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
 					
 					// Chargement des produits
 					$this->charge_produits();			
 					
				} catch( Exception $e ) {
				  	echo "Erreur : ", $e->getMessage();
				}

		}
	}
	
	public function charge_produits() {
		try {
			$this->reqChargeProduits->execute();
			while( $enregistrementP = $this->reqChargeProduits->fetch(PDO::FETCH_OBJ)){
					$prod = new commande_produit($enregistrementP->id_produit);
					$this->produits[$enregistrementP->id_produit] = $prod;
			}	
			
			$this->total();
					
		} catch( Exception $eP ) {
			return "Erreur : ". $eP->getMessage();
		}
	}
	
	public function sauve () {
		
		
		
		// Ajout
		if (!isset($this->id_commande)) 
		{
				
				// Traitement date en fonction du type d'enregistrement (auto ou manuel)
				if (!empty($this->date_creation)) {
                    $this->date_creation_mysql = convertDate($this->date_creation);
                    $date = date_parse(convertDate($this->date_creation));
				    $this->annee = $date['year'].'%';
                }
				else {
                    $this->date_creation_mysql = $this->date_creation = date(DATE_ATOM,time());
				    $this->annee = date('Y');
                    $date['year'] = date('Y');
                    $date['month'] = date('n');
                }
				
				try {
				   	 
  					 $resultat = $this->reqAjout->execute();
  					 if( $resultat===true ) {
    						
    						$this->id_commande = $this->connection->lastInsertId('id'); 	
    						
    						// Sauve numéro commande
    						$this->numero_commande = $date['year'].'_'.$date['month'].'_'.$this->id_commande ;
    						$this->reqModifieNum->execute();
    						
    						
    						// Création de la facture
    						if ($this->etat==ETAT_PAYE)  {
    							$document = new document('FAC_'.$this->id_commande);
    							$document->creation();
    						}
												
 					 } else print_r( $this->reqAjout->errorInfo() );
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
			
		// Modification
		else 
		{
			try {
				 $resultat = $this->reqModifie->execute();
   
  					 if( $resultat===true ) {
    					
    					// Création de la facture
    						if ($this->etat==ETAT_PAYE)  {
    							
    							$document = new document('FAC_'.$this->id_commande);
    							$document->creation();
    						}
    						
    					return true;
    								
 					 } else return  $this->reqModifie->errorInfo();
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	
	
	
	public function total () {
		
		if(is_array($this->produits))
		foreach ($this->produits as $cle=>$produit) {	
    			$this->total += $produit->prix * $produit->quantite;
    			$this->tva = $this->tva + $produit->tva;
    		}
    		
    		//$this->total += calculeLivraison($this->livraison_pays,$this->produits); 
    		$this->total = (isset($this->total)? number_format($this->total,2):0.0);
    		$this->tva = (isset($this->tva)?number_format($this->tva,2):0.0);
	}
	
	public function isAdhesion() {
		foreach ($this->produits as $cle=>$produit) {	
    			if ($produit->id_source == ID_ADHESION_PERSONNE) return 'P';
    			if ($produit->id_source == ID_ADHESION_ASSOCIATION) return 'A';
    	}
    	
	}
	
	
	
}
?>