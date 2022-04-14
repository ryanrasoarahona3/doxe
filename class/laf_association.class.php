<?php
class laf_association {

    public $id_laf;
    public $id_association;
    public $date;
    public $annee;
	public $id_commande;
	
	private $connection;
		
	// SQL
	public $reqModifie;
	public $reqAjout; 
	public $reqCharge; 
	public $reqAssociations; 
	public $reqDeleteAssociations; 
	
    //Constructeur 
    public function __construct ($id=0) {    
    	 
    	  
    	  //
    	  // Initialisation
    	  //
 	 
    	  $this->connection = connect();
    	  $this->modificateur = $_SESSION['utilisateur']['id'];
    	
    	  //
    	  // Requetes SQL préparées 
    	  //

    	  // Modifier 
		  $this->reqModifie = $this->connection->prepare("UPDATE  `laf_adhesions_associations` SET
		  `association` =  :id_association,
		  `date` =  NOW(),
		  `annee` =  :annee, 
		  `id_commande` =  :id_commande 
		  WHERE id = :id_laf ");
		
		 $this->reqModifie->bindParam(':id_laf', $this->id_laf,PDO::PARAM_INT, 11);	
		 $this->reqModifie->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':id_commande', $this->id_commande,PDO::PARAM_INT, 11);
			
		
    	  // Enregistrement
    	  
		  $this->reqAjout = $this->connection->prepare("INSERT INTO  `laf_adhesions_associations` 
		  (`id`, `association`, `date`,`annee`,`id_commande`) 
		  VALUES 
		   (NULL, :id_association, NOW(),:annee, :id_commande) ;");
 
		 $this->reqAjout->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		 $this->reqAjout->bindParam(':annee', $this->annee,  PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':id_commande', $this->id_commande,PDO::PARAM_INT, 11);
		 
		
		 
		// Chargement  
		
		$this->reqCharge = $this->connection->prepare('
SELECT 	
	laf_adhesions_associations.id_commande, 
	laf_adhesions_associations.annee, 
	laf_adhesions_associations.date, 
	laf_adhesions_associations.association AS id_association, 
	laf_adhesions_associations.id
FROM laf_adhesions_associations 
	LEFT OUTER JOIN paiement_etats ON laf_adhesions_associations.etat_paiement = paiement_etats.`code`
	LEFT OUTER JOIN paiement_types ON laf_adhesions_associations.type_paiement = paiement_types.`code`
WHERE laf_adhesions_associations.id = :id_laf');
		$this->reqCharge->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11);
		
	
			
		
		$this->reqSupprimer = $this->connection->prepare("DELETE FROM  `laf_adhesions_associations`  WHERE id = :id_laf ");
	
		if ($id>0) {
			$this->id_laf = $id;
			$this->charge();
		}

	}
	
	
	
	// Récupère l'association
	public function charge () {
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('laf_association', $cle)) 
								{
									if ($cle == 'date_paiement' ) $val = convertDate($val,'php');
									$this->{$cle} = $val;
								}	
    					}
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
				
				
				
	
	}
	
	// Enregistrements
	public function sauve () {
		
		$retour = '';
	
		// Ajout
		if (!isset($this->id_laf)) 
		{		
			try {	 
  				$resultat = $this->reqAjout->execute();
  				
  				if( $resultat===true ) {
    					
    						$this->id_laf = $this->connection->lastInsertId('id'); 
    						return true;
    								
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
  					 	
 					 } else return $this->reqAjout->errorInfo();
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	

	
	
	
	public function supprime() {			
				$this->reqSupprimer->bindValue(':id_laf', $this->id_laf);
				return $this->reqSupprimer->execute();
	}
	
	

}
?>