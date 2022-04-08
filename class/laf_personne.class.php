<?php
class laf_personne {

    public $id_laf;
    public $id_personne;
    public $date;
    public $annee;
    public $connaissance;
    public $connaissance_label;
    public $organisme_payeur;
	public $delegue;
    public $zone_delegation;
	public $distinction;
	public $distinction_annee;
	public $annuaire;
	public $informations_bp;
	public $id_commande;
	public $carte;
	
	public $commande;
	
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

          $this->connection = $GLOBALS['connection'];
    	  $this->modificateur = $_SESSION['utilisateur']['id'];
    	
    	  //
    	  // Requetes SQL préparées
    	  //

    	  // Modifier 
		  $this->reqModifie = $this->connection->prepare("UPDATE  `laf_adhesions_personnes` SET
		  `personne` =  :id_personne,
		  `date` =  NOW(),
		  `annee` =  :annee,
		  `connaissance` =  :connaissance, 
		  `organisme_payeur` =  :organisme_payeur, 
		  `delegue` =  :delegue, 
		  `zone_delegation` =  :zone_delegation, 
		  `distinction` =  :distinction, 
		  `distinction_annee` =  :distinction_annee, 
		  `annuaire` =  :annuaire, 
		    `id_commande` =  :id_commande 
		  WHERE id = :id_laf ");
		
		 $this->reqModifie->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11); 	
		 $this->reqModifie->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':annee', $this->annee, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':connaissance', $this->connaissance, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':organisme_payeur', $this->organisme_payeur, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':delegue', $this->delegue, PDO::PARAM_INT, 1); 
		 $this->reqModifie->bindParam(':zone_delegation', $this->zone_delegation, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':distinction', $this->distinction, PDO::PARAM_INT, 2);
		 $this->reqModifie->bindParam(':distinction_annee', $this->distinction_annee, PDO::PARAM_STR, 4);
		 $this->reqModifie->bindParam(':annuaire', $this->annuaire, PDO::PARAM_INT, 1);
		 $this->reqModifie->bindParam(':id_commande', $this->id_commande,PDO::PARAM_INT, 11);
			
		
    	  // Enregistrement
    	  
		  $this->reqAjout = $this->connection->prepare("INSERT INTO  `laf_adhesions_personnes` 
		  (`id`, `personne`, `date`,`annee`, `connaissance`, `organisme_payeur`, `delegue`, `zone_delegation`, `distinction`, `distinction_annee`, `annuaire`, `informations_bp`,`id_commande`) 
		  VALUES 
		   (NULL, :id_personne, NOW(),:annee,  :connaissance, :organisme_payeur, :delegue, :zone_delegation, :distinction, :distinction_annee, :annuaire, :informations_bp,:id_commande) ;");
 
		 $this->reqAjout->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT); 
		 $this->reqAjout->bindParam(':annee', $this->annee, PDO::PARAM_STR, 4);
		 $this->reqAjout->bindParam(':connaissance', $this->connaissance, PDO::PARAM_INT, 11); 
		 $this->reqAjout->bindParam(':organisme_payeur', $this->organisme_payeur, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':delegue', $this->delegue, PDO::PARAM_INT, 1); 
		 $this->reqAjout->bindParam(':zone_delegation', $this->zone_delegation, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':distinction', $this->distinction, PDO::PARAM_INT, 2);
		 $this->reqAjout->bindParam(':distinction_annee', $this->distinction_annee, PDO::PARAM_STR, 4);
		 $this->reqAjout->bindParam(':annuaire', $this->annuaire, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':informations_bp', $this->informations_bp, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':id_commande', $this->id_commande,PDO::PARAM_STR, 255);
		 
		 // Enregistrement des associations
    	  
		 $this->reqAssociations = $this->connection->prepare("INSERT INTO  `laf_personnes_associations` 
		 (`id`,`adhesion`, `association`, `fonction`) 
		 VALUES 
		 ('', :id_laf, :association,  :fonction);");
		 
		 
		 $this->reqAssociations->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11); 
		 
		 
		 // Suppression des anciennes associations
		 
		 $this->reqDeleteAssociations = $this->connection->prepare("DELETE FROM `laf_personnes_associations` WHERE `adhesion` = :id_laf ");
		 $this->reqDeleteAssociations->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11);
		 
		 
		// Chargement  
		
		$this->reqCharge = $this->connection->prepare('SELECT 
	laf_adhesions_personnes.distinction_annee, 
	laf_adhesions_personnes.informations_bp, 
	laf_adhesions_personnes.annuaire, 
	laf_adhesions_personnes.distinction, 
	laf_adhesions_personnes.zone_delegation, 
	laf_adhesions_personnes.delegue, 
	laf_adhesions_personnes.organisme_payeur, 
	laf_adhesions_personnes.connaissance, 
	laf_adhesions_personnes.annee, 
	laf_adhesions_personnes.date, 
	laf_adhesions_personnes.personne AS id_personne, 
	laf_adhesions_personnes.id_commande, 
	laf_adhesions_personnes.carte, 
	connaissance.nom AS connaissance_label
FROM laf_adhesions_personnes 
LEFT JOIN connaissance ON laf_adhesions_personnes.connaissance = connaissance.id
WHERE laf_adhesions_personnes.id = :id_laf');
		$this->reqCharge->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11);
		
		$this->reqChargeAssociations = $this->connection->prepare('
		SELECT *
		FROM laf_personnes_associations
	 	WHERE adhesion = :id_laf ');
		$this->reqChargeAssociations->bindParam(':id_laf', $this->id_laf, PDO::PARAM_INT, 11);
	
	
		
		$this->reqSupprimer = $this->connection->prepare("DELETE FROM  `laf_adhesions_personnes`  WHERE id = :id_laf ");
	
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
    						if(property_exists('laf_personne', $cle)) 
								{
									if ($cle == 'date_paiement' ) $val = convertDate($val,'php');
									$this->{$cle} = $val;
								}	
    					}
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
				
				// Chargement de la commande si nécessaire
				if ($this->id_commande > 0) {
					$this->commande = new commande($this->id_commande);
				}
		
	}
	
	// Enregistrements
	public function sauve () {
		
		$retour = '';
		
		// Traitement
		/*
		if ($this->paiement > 0) {
			// Ces éléments seront gérés au niveau de la commande
			$this->type_paiement = '';
			$this->etat_paiement = '';
			$this->date_paiement='';
		} else $this->date_paiement = convertDate($this->date_paiement);
		*/
		
		// Ajout
		if (!isset($this->id_laf)) 
		{
							
				try {
				   
  					 $resultat = $this->reqAjout->execute();

   					if( $resultat==true ) {
   						$this->id_laf = $this->connection->lastInsertId('id'); 
   						return $this->reqAjout;
   					} else print_r( $this->reqAjout->errorInfo());
   					/*
  					 if( $resultat===true ) {
    					
    					$this->id_laf = $this->connection->lastInsertId('id'); 
    					
    					// Enregistrement des associations
    					if (count($this->associations)>0) $retour = $this->sauveAssociations();		
    					
    					// Enregistrement de la liaison avec l'association
    					$laf = new association(ID_LAF);
    					$laf->ajoutePersonne('',$this->id_personne,$this->annee,$this->date,0,1,0,'00/00/0000');
    						
    					return $retour;
    								
 					 } else return $this->reqAjout->errorInfo();
  						*/
  						
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
  					 
    					// Enregistrement des associations
    						if (isset($this->associations) && is_countable($this->associations) && count($this->associations)>0) $retour = $this->sauveAssociations();		
    						return true;
    								
 					 } else print_r( $this->reqModifie->errorInfo());
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	
	public function sauveAssociations() {
			$this->reqDeleteAssociations->execute();
			
			foreach ($this->associations as $val) {
				try {
				   	 $this->reqAssociations->bindValue(':association', $val['association'], PDO::PARAM_INT); 
				   	 $this->reqAssociations->bindValue(':fonction', $val['fonction'], PDO::PARAM_INT); 
  					 $resultat = $this->reqAssociations->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
			return "Enregistrement terminé";
	}
	
	
	
	public function supprime() {
				
				// Supprimer les associations
				$this->reqDeleteAssociations->execute();
				
				// Supprimer la ligen LAF
				$this->reqSupprimer->bindValue(':id_laf', $this->id_laf);
				
				// Supprimer la liaison personne / association
				$asso = new association (ID_LAF);
				$asso->supprimePersonneId($this->id_personne);
				
				return $this->reqSupprimer->execute();
		
	}
	
	
/*	$this->reqAjouterPersonne = $this->connection->prepare("INSERT INTO  `personnes_associations` 
		  (`id`, `personne`, `association`, `date`, `annee`, `etat`, `date_etat` , `cons_admin` , `benevole`) 
		  VALUES 
		  ('', ':personne',  :id_association, :date, :annee , :etat , :date_etat , :cons_admin , :benevole );");
		$this->reqAjouterPersonne->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
*/	

}
?>
