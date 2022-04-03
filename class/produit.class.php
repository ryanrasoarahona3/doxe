<?php
class Produit {

    public $id_produit;
    public $nom;
    public $description;
    public $image;
    public $image_grande;
    public $prix;
    public $tva;
    public $taux_tva;
    public $livrable;
    public $unique;
    public $type;
    public $personnalisable;
	public $personnalisation;
	public $poids;
	
	public $quantite;
	 
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
			commerce_produits.id AS id_produit, 
			commerce_produits.nom, 
			commerce_produits.description, 
			commerce_produits.image, 
			commerce_produits.image_grande, 
			commerce_produits.prix, 
			commerce_produits.livrable,
			commerce_produits.unique, 
			commerce_produits.type, 
			commerce_produits.personnalisable, 
			commerce_produits.personnalisation, 
			commerce_produits.tva, 
			commerce_produits.poids, 
			commerce_tva.montant AS taux_tva 
		FROM commerce_produits 
		LEFT JOIN commerce_tva ON commerce_produits.tva = commerce_tva.id 
		WHERE commerce_produits.id = :id_produit
		');
		$this->reqCharge->bindParam(':id_produit', $this->id_produit, PDO::PARAM_INT, 11);
		
		
		
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
    						if(property_exists('produit', $cle)) 
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
	
	
}
?>