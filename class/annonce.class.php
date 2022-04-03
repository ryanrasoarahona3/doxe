<?php
class Annonce {

    public $id_annonce;
    public $createur;
    public $type;
    public $categorie;
    public $titre;
    public $texte;
    public $date_saisie;
    public $date_validation;
    public $validation;
	public $refus;
	public $validation_label;
    public $activites;
    public $activites_label;
    public $departements;
    public $departements_label;

	static $connect;
	
	// SQL
	public $reqModifie; 
	public $reqAjout; 
	public $reqCharge; 
	public $reqActivites; 
	public $reqDeleteActivites; 
	
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
    	  
		  $this->reqModifie = $this->connection->prepare("UPDATE  `annonces` SET
		  `titre` =  :titre, 
		  `texte` =  :texte, 
		  `categorie` =  :categorie, 
		  `date_validation` =  :date_validation, 
		  `validation` =  :validation, 
		  `refus` =  :refus
		  WHERE id = :id_annonce ");
			
		 $this->reqModifie->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':titre', $this->titre, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':categorie', $this->categorie, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':texte', $this->texte, PDO::PARAM_STR, 255);
		 //$this->reqModifie->bindParam(':date_saisie', $this->date_saisie, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':date_validation', $this->date_validation, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':validation', $this->validation, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':refus', $this->refus, PDO::PARAM_STR, 100);
		
		// Refuser l'annonce
		
		  $this->reqRefuser = $this->connection->prepare("UPDATE  `annonces` SET
		 `validation` =  2, 
		  `refus` =  :refus
		  WHERE id = :id_annonce ");
			
		 $this->reqRefuser->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11); 
		 $this->reqRefuser->bindParam(':refus', $this->refus, PDO::PARAM_STR, 100);
		 
		 
		 // Valider l'annonce
		
		  $this->reqValider = $this->connection->prepare("UPDATE  `annonces` SET
		 `validation` =  1,
		 `date_validation` = NOW()
		  WHERE id = :id_annonce ");
		 $this->reqValider->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11); 
		
		
    	  // Enregistrer
    	  
		  $this->reqAjout = $this->connection->prepare("INSERT INTO  `annonces` 
		  (`id`, `createur`, `type`, `categorie`,`titre`, `texte`, `date_saisie`, `date_validation`, `validation`, `refus`)
		  VALUES 
		  ('', :createur,  :type,:categorie, :titre, :texte, NOW(), '', 3, '');");
	
		 $this->reqAjout->bindParam(':createur', $this->createur, PDO::PARAM_INT, 11); 
		 $this->reqAjout->bindParam(':type', $this->type, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':categorie', $this->categorie, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':titre', $this->titre, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':texte', $this->texte, PDO::PARAM_STR, 25500);
		 
		 
		 // Enregistrement des activités
    	  
		  $this->reqActivites = $this->connection->prepare("INSERT INTO  `annonces_activites` 
		  (`id`, `annonce`, `activite`) 
		  VALUES 
		  ('', :id_annonce,  :id_activite);");
	
		 $this->reqActivites->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11); 
		 
		 
		  // Enregistrement des départements
    	  
		  $this->reqDepartements = $this->connection->prepare("INSERT INTO  `annonces_departements` 
		  (`id`, `annonce`, `departement`) 
		  VALUES 
		  ('', :id_annonce,  :id_departement);");
	
		 $this->reqDepartements->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11); 
		 
		 // Suppression des anciennes activités
		 $this->reqDeleteActivites = $this->connection->prepare("DELETE FROM `annonces_activites` WHERE `annonce` = :id_annonce");
		 $this->reqDeleteActivites->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);
		 
		 // Suppression des anciens départements
		 $this->reqDeleteDepartements = $this->connection->prepare("DELETE FROM `annonces_departements` WHERE `annonce` = :id_annonce");
		 $this->reqDeleteDepartements->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);
		 
		 
		// Chargement  
		$this->reqCharge = $this->connection->prepare('
SELECT 
	annonces_validation.nom AS validation_label, 
	annonces.validation, 
	annonces.refus, 
	annonces.date_validation, 
	annonces.date_saisie, 
	annonces.texte, 
	annonces.titre, 
	annonces.type, 
	annonces.categorie, 
	annonces.createur, 
	annonces.id
FROM annonces INNER JOIN annonces_validation ON annonces.validation = annonces_validation.id
WHERE annonces.id = :id_annonce
		');
		$this->reqCharge->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);
		
		
		
		$this->reqChargeActivites = $this->connection->prepare('
		SELECT activites.nom, 
		activites.id
		FROM annonces_activites 
		INNER JOIN activites ON annonces_activites.activite = activites.id
	 	INNER JOIN annonces ON annonces.id = annonces_activites.annonce
	 	WHERE annonces.id = :id_annonce');
		$this->reqChargeActivites->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);
		
		$this->reqChargeDepartements = $this->connection->prepare('
		SELECT departements.nom, 
		departements.numero, 
		departements.id
		FROM annonces_departements 
		INNER JOIN departements ON annonces_departements.departement = departements.id
	 	INNER JOIN annonces ON annonces.id = annonces_departements.annonce
	 	WHERE annonces.id = :id_annonce');
		$this->reqChargeDepartements->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);
		
		
		// Supprimer 
		$this->reqSupprimerAnnonce = $this->connection->prepare("DELETE FROM  `annonces`  WHERE id = :id_annonce ");
		$this->reqSupprimerAnnonce->bindParam(':id_annonce', $this->id_annonce, PDO::PARAM_INT, 11);  
		
		
		// Chargement
	
		if ($id>0) {
			$this->id_annonce = $id;
			$this->charge();
		}
	}
	
	
	
	// Récupère l'association
	public function charge () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('annonce', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
				
				// Chargement des activites
				try {
  					$this->reqChargeActivites->execute();
   	 				while( $enregistrement = $this->reqChargeActivites->fetch(PDO::FETCH_OBJ)){
    					$this->activites[] = $enregistrement->id;
    					$this->activites_label[$enregistrement->id] = $enregistrement->nom;
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
				
				// Chargement des departements
				try {
  					$this->reqChargeDepartements->execute();
   	 				while( $enregistrement = $this->reqChargeDepartements->fetch(PDO::FETCH_OBJ)){
    					$this->departements[] = $enregistrement->id;
    					$this->departements_label[$enregistrement->id] = $enregistrement->nom;
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
		}
	}
	
	// Enregistrements
	public function sauve () {
		
		// Conversion
		$this->date_saisie = convertDate($this->date_saisie);
	
		$retour = '';

		
		// Ajout
		if (empty($this->id_annonce)) 
		{
							
				try {
				   	 
  					 $resultat = $this->reqAjout->execute();
   
  					 if( $resultat===true ) {
    					
    					$this->id_annonce = $this->connection->lastInsertId('id'); 
    					
    					// Enregistrement des activités
    					if (count($this->activites)>0) $retour = $this->sauveActivites();
    					
    					// Enregistrement des départements
    					if (count($this->departements)>0) $retour = $this->sauveDepartements();
    					
    					return $retour;
    								
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
    					
    					// Enregistrement des activités
    					if (count($this->activites)>0) $retour = $this->sauveActivites();		
    					
    					// Enregistrement des départements
    					if (count($this->departements)>0) $retour = $this->sauveDepartements();
    					
    					return $retour;
    								
 					 } else print_r( $this->reqModifie->errorInfo());
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	
	public function sauveActivites() {
			$this->reqDeleteActivites->execute();
		
			foreach ($this->activites as $val) {
				try {
				   	 $this->reqActivites->bindValue(':id_activite', $val, PDO::PARAM_INT); 
  					 $resultat = $this->reqActivites->execute();
  					
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
			return "Enregistrement terminé";
	}
	
	
	public function sauveDepartements() {
			$this->reqDeleteDepartements->execute();
		
			foreach ($this->departements as $val) {
				try {
				   	 $this->reqDepartements->bindValue(':id_departement', $val, PDO::PARAM_INT); 
  					 $resultat = $this->reqDepartements->execute();
  					
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
			return "Enregistrement terminé";
	}

	public function supprime() {
			$this->reqDeleteActivites->execute();
			$this->reqDeleteDepartements->execute();
			return $this->reqSupprimerAnnonce->execute();
	}
	
	public function refuser() {
			return $this->reqRefuser->execute();
			
			// ENVOYER EMAIL			
	}
	
	public function valider() {
			return $this->reqValider->execute();
			
			// ENVOYER EMAIL			
	}
	
	public function affActivites() {
		
			// Affichage des associations
			$i=1;
			$affActivites='';
			if (count($this->activites_label)>0) {
				foreach ($this->activites_label as $val) {
					$affActivites .= $val;
					if ($i<count($this->activites_label)) 	$affActivites .= ', ';
					$i++;
				}
			}
		return $affActivites;
	}
	
	public function affDepartements() {
		
			// Affichage des départements
			$i=1;
			$affDepartements='';
			if (count($this->departements_label)>0) {
				foreach ($this->departements_label as $val) {
					$affDepartements .= $val;
					if ($i<count($this->departements_label)) 	$affDepartements .= ', ';
					$i++;
				}
			}
		return $affDepartements;
	}

}
?>