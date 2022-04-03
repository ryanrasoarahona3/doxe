<?php
class Commande_produit {

    public $id_produit;
    public $id_commande;
    public $id_source;
    public $nom;
    public $quantite;
    public $prix;
    public $tva;
    public $taux_tva;
    public $personnalisation;
    public $type;
	public $poids;
	public $livrable;
	 
	static $connect;
	
	// SQL
	public $reqModifie; 
	public $reqAjout; 
	public $reqSupprime; 
	public $reqCharge; 
	
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
		SELECT 
			commerce_commandes_produits.id AS id_produit,
			commerce_commandes_produits.id_source, 
			commerce_commandes_produits.nom, 
			commerce_commandes_produits.quantite, 
			commerce_commandes_produits.prix, 
			commerce_commandes_produits.tva, 
			commerce_commandes_produits.taux_tva, 
			commerce_commandes_produits.personnalisation, 
			commerce_commandes_produits.id_commande, 
			commerce_type_produits.type,
			commerce_commandes_produits.poids,
			commerce_commandes_produits.livrable
		FROM commerce_commandes_produits LEFT JOIN commerce_produits ON commerce_commandes_produits.id_source = commerce_produits.id
			 LEFT JOIN commerce_type_produits ON commerce_produits.type = commerce_type_produits.id
		WHERE commerce_commandes_produits.id =  :id_produit
		');
		$this->reqCharge->bindParam(':id_produit', $this->id_produit, PDO::PARAM_INT, 11);
		
		
		// Sauvegarde
  
		 $this->reqAjout = $this->connection->prepare("INSERT INTO  `commerce_commandes_produits` 
		  (`id`, `id_commande`, `id_source`,`nom`, `quantite`,  `prix`, `tva`, `taux_tva`, `personnalisation`, `poids`, `livrable`) 
		  VALUES 
		  ('',  :id_commande, :id_source ,:nom , :quantite ,  :prix , :tva , :taux_tva , :personnalisation , :poids, :livrable );");
	
		 $this->reqAjout->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':id_source', $this->id_source, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':quantite', $this->quantite, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':prix', $this->prix, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':tva', $this->tva, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':taux_tva', $this->taux_tva, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':personnalisation', $this->personnalisation, PDO::PARAM_STR, 500);
		$this->reqAjout->bindParam(':poids', $this->poids, PDO::PARAM_STR, 500);
	$this->reqAjout->bindParam(':livrable', $this->livrable, PDO::PARAM_STR, 500);
	 
	
		 $this->reqModifie = $this->connection->prepare("UPDATE  `commerce_commandes_produits` SET 
			  `id_commande` =  :id_commande,
			  `id_source` =  :id_source,
			  `nom` =  :nom,
			  `quantite` =  :quantite,
			  `prix` =  :prix,
			  `tva` =  :tva,
			  `taux_tva` =  :taux_tva,
			  `personnalisation` =  :personnalisation,
			  `poids` =  :poids,
			  `livrable` =  :livrable
		  WHERE id = :id_produit ");
		 $this->reqModifie->bindParam(':id_produit', $this->id_produit, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':id_source', $this->id_source, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':quantite', $this->quantite, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':prix', $this->prix, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':tva', $this->tva, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':taux_tva', $this->taux_tva, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':personnalisation', $this->personnalisation, PDO::PARAM_STR, 500);
		 $this->reqModifie->bindParam(':poids', $this->poids, PDO::PARAM_STR, 500);
		 $this->reqModifie->bindParam(':livrable', $this->livrable, PDO::PARAM_STR, 500);
		 
		// Chargement
	
		if (!empty($id)) {
			$this->id_produit = $id;
			$this->charge();
		}
	}
	
	
	
	// Récupère le produit
	public function charge () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('commande_produit', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}

		}
	}
	
	// Sauvegarde
	
	public function sauve () {
		
		// Ajout
		if (!isset($this->id_produit)) 
		{
				
							
				try {
				   	 
  					 $resultat = $this->reqAjout->execute();
  					 if( $resultat===true ) {
    					
    						$this->id_produit = $this->connection->lastInsertId('id'); 
    					    					
 					 } else return $this->reqAjout->errorInfo();
  
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
    					
    					return true;
    								
 					 } else return  $this->reqModifie->errorInfo();
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	
	// Copie les données d'un produit source
	
	public function copie ($id) {
		$produit = new produit($id);
		if (!empty($produit)) {
			$this->id_source = $produit->id_produit;
			$this->prix = $produit->prix;
			$this->tva = $produit->tva;
			$this->taux_tva = $produit->taux_tva;
			$this->type = $produit->type;
			$this->nom = $produit->nom;
			$this->poids = $produit->poids;
			$this->livrable = $produit->livrable;
		}
	} 
}
?>