<?php
class Association {

    public $id_association;
    public $numero_adherent;
    public $association_type;
    public $association_type_label;
    public $association_type_collectivite;
    public $association_activites = array();
    public $association_activites_label = array();
	public $nom;
    public $nom_soundex;
	public $sigle;
	public $code_ape_naf;
	public $date_declaration_jo;
	public $date_declaration_jo_mysql;
	public $numero_siret;
	public $numero_dossier;
	public $numero_convention;
	public $mdp;
	public $mdp_clair;
	public $adresse;
	
	public $pays;
	public $pays_label;
	public $pays_iso;
	
	public $ville_pays;
	public $code_pays;
	
	
	public $ville;
	public $ville_label;
	public $region;
	public $region_id;
	public $code_postal;
	public $insee;
	public $departement;
	public $departement_id;
	public $telephone_fixe;
	public $telephone_mobile;
	public $fax; 
	public $courriel; 
	public $delegue_special; 
	public $gestionnaire; 
	public $delegue_special_label; 
	public $prospect; 
	public $inscription_rdvf; 
	public $banque_postale; 
	public $logo; 
	public $date_creation; 
	public $date_modification; 
	public $modificateur ; 
	public $modificateur_label ; 
	public $activites = array();
	public $conseil_administration = array();
	public $presidents = array();

	public $lesamis;
	public $nbr_benevoles;
	public $benevoles;
    
    public $doublon_certain;
	
	public $commandes;
	
	static $connect;
	
	// SQL
	public $reqModifie; 
	public $reqAjout; 
	public $reqCharge; 
	public $reqActivites; 
	public $reqDeleteActivites; 
	public $reqDoublonsCertains; 
	public $reqDoublonsSoundex; 
	public $reqChargeCommandes;
	
    //Constructeur 
   public function __construct ($id=0) {    
    	 
    	  
    	  //
    	  // Initialisation
    	  //
 	 
 	 		
    	  $this->connection = connect();
    	  if (!empty($_SESSION['utilisateur']['id'])) $this->modificateur = $_SESSION['utilisateur']['id'];
    	  else $this->modificateur = 0;
    	  
    	  $this->prospect = 0; 
			$this->inscription_rdvf = 0; 
			$this->banque_postale = 0; 
	
    	  //
    	  // Requetes SQL préparées 
    	  //


    	  // Modifier 
		  $this->reqModifie = $this->connection->prepare("UPDATE  `associations` SET
		  `association_type` =  :association_type,
		  `numero_adherent` =  :numero_adherent,
		  `nom` =  :nom, 
		  `nom_soundex` =  :nom_soundex, 
		  `sigle` =  :sigle, 
		  `date_declaration_jo` =  :date_declaration_jo, 
		  `numero_siret` =  :numero_siret, 
		  `numero_dossier` =  :numero_dossier, 
		  `numero_convention` =  :numero_convention, 
		  `code_ape_naf` =  :code_ape_naf, 
		  `ville` =  :ville,
		  `pays` =  :pays, 
		  `adresse` =  :adresse, 
		  `telephone_fixe` =  :telephone_fixe, 
		  `telephone_mobile` =  :telephone_mobile, 
		  `fax` =  :fax, 
		  `courriel` =  :courriel, 
		  `delegue_special` =  :delegue_special, 
		  `prospect` =  :prospect, 
		  `logo` =  :logo, 
		  `date_modification` = CURRENT_TIMESTAMP, 
		  `modificateur` =  :modificateur 
		  WHERE id = :id_association ");
			
		 $this->reqModifie->bindParam(':association_type', $this->association_type, PDO::PARAM_INT, 11); 
		 $this->reqModifie->bindParam(':numero_adherent', $this->numero_adherent, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':sigle', $this->sigle, PDO::PARAM_STR, 255);
		 $this->reqModifie->bindParam(':date_declaration_jo', $this->date_declaration_jo_mysql, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':numero_siret', $this->numero_siret, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':numero_dossier', $this->numero_dossier, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':numero_convention', $this->numero_convention, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':code_ape_naf', $this->code_ape_naf, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':ville', $this->ville, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':pays', $this->pays, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':adresse', $this->adresse, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':telephone_fixe', $this->telephone_fixe, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':telephone_mobile', $this->telephone_mobile, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':fax', $this->fax, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':delegue_special', $this->delegue_special, PDO::PARAM_INT, 11);
		 $this->reqModifie->bindParam(':prospect', $this->prospect, PDO::PARAM_INT, 1);
		 $this->reqModifie->bindParam(':logo', $this->logo, PDO::PARAM_STR, 100);
		 $this->reqModifie->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
			$this->reqModifie->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
			
		$this->reqModifieMdp = $this->connection->prepare("UPDATE  `associations` SET
		  `mdp` =  :mdp
		  WHERE id = :id_association ");
		$this->reqModifieMdp->bindParam(':mdp', $this->mdp, PDO::PARAM_STR, 255);  
		$this->reqModifieMdp->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		
    	  // Enregistrement
    	  
		  $this->reqAjout = $this->connection->prepare("INSERT INTO  `associations` 
		  (`id`, `association_type`, `numero_adherent`,`nom`, `nom_soundex`, `sigle`, `date_declaration_jo`, `numero_siret`, `numero_dossier`, `numero_convention`, `code_ape_naf`, `ville`, `adresse`, `telephone_fixe`, `telephone_mobile`, `fax`, `courriel`, `mdp`, `delegue_special`, `prospect`, `inscription_rdvf`, `banque_postale`, `logo`, `date_creation`, `date_modification`, `modificateur`, `pays`) 
		  VALUES 
		  ('', :association_type,  :numero_adherent, :nom,:nom_soundex, :sigle, :date_declaration_jo, :numero_siret, :numero_dossier, :numero_convention, :code_ape_naf, :ville, :adresse, :telephone_fixe, :telephone_mobile, :fax, :courriel, :mdp, :delegue_special, :prospect, :inscription_rdvf, :banque_postale, :logo, NOW(), CURRENT_TIMESTAMP, :modificateur, :pays);");
	
		 $this->reqAjout->bindParam(':association_type', $this->association_type, PDO::PARAM_INT, 11); 
		 $this->reqAjout->bindParam(':numero_adherent', $this->numero_adherent, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':sigle', $this->sigle, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':date_declaration_jo', $this->date_declaration_jo_mysql, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':numero_siret', $this->numero_siret, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':numero_dossier', $this->numero_dossier, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':numero_convention', $this->numero_convention, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':code_ape_naf', $this->code_ape_naf, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':ville', $this->ville, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':adresse', $this->adresse, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':telephone_fixe', $this->telephone_fixe, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':telephone_mobile', $this->telephone_mobile, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':fax', $this->fax, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':mdp', $this->mdp, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':delegue_special', $this->delegue_special, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':prospect', $this->prospect, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':inscription_rdvf', $this->inscription_rdvf, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':banque_postale', $this->banque_postale, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':logo', $this->logo, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':pays', $this->pays, PDO::PARAM_INT, 11);
		 
		 // Enregistrement des activités
    	  
		  $this->reqActivites = $this->connection->prepare("INSERT INTO  `associations_activites` 
		  (`id`, `association`, `activite`) 
		  VALUES 
		  ('', :id_association,  :id_activite);");
	
		 $this->reqActivites->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		 
		 // Suppression des anciennes activités
		 $this->reqDeleteActivites = $this->connection->prepare("DELETE FROM `associations_activites` WHERE `association` = :id_association");
		 $this->reqDeleteActivites->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		 
		// Chargement  
		$this->reqCharge = $this->connection->prepare('
SELECT associations.id AS id_association, 
	associations.association_type, 
	associations.numero_adherent, 
	associations.nom, 
	associations.nom_soundex, 
	associations.sigle, 
	associations.date_declaration_jo, 
	associations.numero_siret, 
	associations.numero_dossier, 
	associations.numero_convention, 
	associations.code_ape_naf, 
	associations.ville, 
	associations.adresse, 
	associations.telephone_fixe, 
	associations.telephone_mobile, 
	associations.fax, 
	associations.courriel, 
	associations.mdp, 
	associations.delegue_special, 
	associations.prospect, 
	associations.inscription_rdvf, 
	associations.banque_postale, 
	associations.logo, 
	associations.date_creation, 
	associations.date_modification, 
	associations.modificateur, 
	associations.pays, 
	associations.gestionnaire, 
	associations_types.nom AS association_type_label, 
	villes.code_postal, 
	villes.insee, 
	regions.nom AS region, 
	regions.id AS region_id, 
	villes.nom AS ville_label, 
	pays.nom_fr_fr AS pays_label, 
	pays.alpha2 AS pays_iso, 
	departements.nom AS departement,
	departements.id AS departement_id
FROM associations INNER JOIN associations_types ON associations.association_type = associations_types.id
	 LEFT OUTER  JOIN villes ON associations.ville = villes.id
	 LEFT OUTER  JOIN regions ON villes.region = regions.id
	 LEFT OUTER  JOIN departements ON villes.departement = departements.id
	LEFT OUTER JOIN pays ON associations.pays = pays.id
	  
	 		WHERE associations.id = :id_association LIMIT 1');
		$this->reqCharge->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		$this->reqChargeActivites = $this->connection->prepare('
		SELECT activites.nom, 
		activites.id
		FROM associations_activites INNER JOIN activites ON associations_activites.activite = activites.id
	 	INNER JOIN associations ON associations.id = associations_activites.association
	 	WHERE associations.id = :id_association');
		$this->reqChargeActivites->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		
		 // Supprimer Activer
		
		$this->reqSupprime = $this->connection->prepare("UPDATE `associations` SET `etat` = 0 WHERE `associations`.`id` = :id_association ;");
		 $this->reqSupprime->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		 
		 $this->reqActive = $this->connection->prepare("UPDATE `associations` SET `etat` = 1 WHERE `associations`.`id` = :id_association ;");
		 $this->reqActive->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		 
		 
		
		// Doublons
		$this->reqDoublonsCertains = $this->connection->prepare('SELECT * FROM `associations` WHERE UPPER(numero_dossier)=UPPER(:numero_dossier) AND associations.etat=1 ');
		$this->reqDoublonsCertains->bindParam(':numero_dossier', $this->numero_dossier, PDO::PARAM_INT, 11);
		
		// Doublons Soundex
		$this->reqDoublonsSoundex = $this->connection->prepare('SELECT * FROM `associations` WHERE nom_soundex=:nom_soundex AND associations.etat=1 ');
		$this->reqDoublonsSoundex->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_INT, 11);

		
		// Enregistre doublons
		$this->reqInsertDoublons = $this->connection->prepare("INSERT INTO  `doublons` 
		  (`id`, `nomtable`, `id1`, `id2`, `type`) 
		  VALUES 
		  ('', 'associations',  :id_association, :id_doublon, :type);");
		$this->reqInsertDoublons->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		// Ajouter une personne assurance gratuite
		$this->reqAjouterPersonne = $this->connection->prepare("INSERT INTO  `personnes_associations` 
		  (`id`, `personne`, `association`, `date`, `annee`, `etat`, `date_etat` , `cons_admin` , `benevole`) 
		  VALUES 
		  ('', :personne,  :id_association, :date, :annee , :etat , :date_etat , :cons_admin , :benevole );");
	
	
		// Ajouter une personne CA
		$this->reqAjouterPersonneCA = $this->connection->prepare("INSERT INTO  `personnes_associations` 
		  (`id`, `personne`, `association`, `date`, `annee` , `cons_admin` , `benevole` ) 
		  VALUES 
		  ('', :personne,  :id_association, :date, :annee ,  :cons_admin , :benevole );");
	
				
		// Modifier une personne assurance gratuite
		$this->reqModifierPersonne = $this->connection->prepare("UPDATE  `personnes_associations` SET
		  `personne` =  :personne,
		  `association` =  :id_association, 
		  `date` =  :date, 
		  `annee` =  :annee, 
		  `etat` =  :etat, 
		  `date_etat`  =  :date_etat, 
		  `cons_admin`  =  :cons_admin, 
		  `benevole` =  :benevole
		  WHERE id = :id_lien ");
		 
		 
		 // Modifier une personne CA
		$this->reqModifierPersonneCA = $this->connection->prepare("UPDATE  `personnes_associations` SET
		  `cons_admin`  =  :cons_admin, 
		  `benevole` =  :benevole
		  WHERE id = :id_lien ");
		  
		   
		// Supprimer une personne assurance gratuite
		$this->reqSupprimerPersonne = $this->connection->prepare("DELETE FROM  `personnes_associations`  WHERE id = :id_lien ");
		
		// Supprimer une personne assurance gratuite par ID
		$this->reqSupprimerPersonneID = $this->connection->prepare("DELETE FROM  `personnes_associations`  WHERE personne = :personne ");
		
		
		// Supprimer une personne CA
		$this->reqSupprimerPersonneCA = $this->connection->prepare("UPDATE  `personnes_associations` SET `cons_admin` = 0  WHERE id = :id_lien ");
		  
		
		  
		  // Ajouter gestionnaire
    	  
		  $this->reqSauveGestionnaire = $this->connection->prepare("UPDATE  `associations` SET
		  `gestionnaire`  =  :gestionnaire
		  WHERE id_association = :id_association ");
	
		 $this->reqSauveGestionnaire->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11); 
		 
 		 
 		  /*
 		  
		 // Suppression representant
		 $this->reqDeleteRepresentant = $this->connection->prepare("DELETE FROM `collectivites_representant` WHERE `association` = :id_association");
		 $this->reqDeleteRepresentant->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
 
 
 			// Charge representant
		  $this->reqChargeRepresentant = $this->connection->prepare('
		SELECT *
		FROM collectivites_representant
	 	WHERE association = :id_association');
		$this->reqChargeRepresentant->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		*/
		
		  
		// Nbr benevoles
		$this->reqNbrBenevoles = $this->connection->prepare('SELECT COUNT(*) AS total, 
	personnes_associations.annee
FROM personnes INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne
WHERE personnes_associations.association = :id_association AND personnes_associations.benevole = 1 AND personnes_associations.etat = 0
GROUP BY personnes_associations.annee
ORDER BY personnes_associations.annee DESC ');
		$this->reqNbrBenevoles->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		// Nbr benevoles pour l'année
		$this->reqNbrBenevolesAnnee = $this->connection->prepare('SELECT COUNT(*) AS total, 
	personnes_associations.annee
FROM personnes INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne
WHERE personnes_associations.association = :id_association  AND personnes_associations.annee = :annee AND personnes_associations.benevole = 1  AND personnes_associations.etat = 0  AND personnes.etat = 1 
GROUP BY personnes_associations.annee
ORDER BY personnes_associations.annee DESC');
		$this->reqNbrBenevolesAnnee->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		// Nbr benevoles pour l'année
		$this->reqBenevolesAnnee = $this->connection->prepare('SELECT 
	personnes_associations.personne
FROM personnes INNER JOIN personnes_associations ON personnes.id = personnes_associations.personne
WHERE personnes_associations.association = :id_association  AND personnes_associations.annee = :annee AND personnes_associations.benevole = 1  AND personnes_associations.etat = 0 AND personnes.etat = 1 
ORDER BY personnes.nom ASC');
		$this->reqBenevolesAnnee->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
	
	// Vérifie si une personne fait partie de l'assoc pour une année donnée
	$this->reqCheckPersonneAnnee = $this->connection->prepare('SELECT DISTINCT
	personnes_associations.id
FROM personnes_associations
WHERE personnes_associations.personne= :personne AND personnes_associations.association = :id_association AND personnes_associations.annee = :annee ');

	$this->reqCheckPersonneAnnee->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
	
	// R2cupère les infos du lien personne / asso
	$this->reqCheckPersonneAssociation = $this->connection->prepare('
SELECT  
	personnes_associations.benevole, 
	personnes_associations.cons_admin, 
	personnes_associations.date_etat, 
	personnes_associations.etat, 
	personnes_associations.annee, 
	personnes_associations.date, 
	personnes_associations.association, 
	personnes_associations.personne
FROM personnes_associations
WHERE personnes_associations.id= :id_lien ');

		
		// Conseil d'administration
		$this->reqCA = $this->connection->prepare('
SELECT DISTINCT 
	cons_admin_fonctions.nom AS fonction, 
	personnes.id AS id_personne, 
	personnes_associations.id AS id_ca,
	personnes.prenom, 
	personnes.civilite, 
	personnes.nom, 
	personnes.numero_adherent, 
	personnes.courriel, 
	personnes_associations.annee, 
	cons_admin_fonctions.id AS cons_admin_fonction
FROM personnes_associations INNER JOIN cons_admin_fonctions ON personnes_associations.cons_admin = cons_admin_fonctions.id
	 INNER JOIN personnes ON personnes_associations.personne = personnes.id
WHERE personnes_associations.association = :id_association ORDER BY cons_admin_fonctions.id, annee DESC' );
		$this->reqCA->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
	
		
		// Chargement LAF
		$this->reqLaf = $this->connection->prepare('SELECT laf_adhesions_associations.id, 
	laf_adhesions_associations.annee
FROM laf_adhesions_associations
WHERE laf_adhesions_associations.association = :id_association
ORDER BY laf_adhesions_associations.annee DESC
		');
	 		
		$this->reqLaf->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
	
		
		// Chargement INSEE
		$this->reqInsee = $this->connection->prepare('SELECT *
FROM villes
WHERE id = :ville
		');
	 		
		$this->reqInsee->bindParam(':ville', $this->ville, PDO::PARAM_INT, 11);
	
		// Chargement commandes
		
		// Charge Commandes 
		$this->reqChargeCommandes = $this->connection->prepare('
	SELECT commerce_commandes.id AS id_commande, 
		commerce_commandes.numero_commande, 
		commerce_commandes.type_utilisateur, 
		commerce_commandes.id_utilisateur, 
		commerce_commandes.date_creation, 
		commerce_commandes.date_modification, 
		commerce_payement_etat.nom AS etat_libelle, 
		commerce_commandes.etat AS id_etat, 
		commerce_commandes.payement AS id_payement, 
		commerce_payement.nom AS payement_libelle,
		ROUND( SUM(commerce_commandes_produits.prix * commerce_commandes_produits.quantite), 2 ) AS total 
	FROM commerce_commandes 
		LEFT JOIN commerce_payement_etat ON commerce_commandes.etat = commerce_payement_etat.id
		LEFT JOIN commerce_payement ON commerce_commandes.payement = commerce_payement.id
	 	LEFT JOIN commerce_commandes_produits ON commerce_commandes.id = commerce_commandes_produits.id_commande
	 WHERE commerce_commandes.type_utilisateur = "A" AND
			commerce_commandes.id_utilisateur =  :id_association 
	GROUP BY commerce_commandes.id
	ORDER BY date_creation DESC ');
	 	
	 	
		$this->reqChargeCommandes->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		
		// Chargement
	
		if ($id>0) {
			$this->id_association = $id;
			$this->charge();
		}
	}
	
	
	
	// Récupère l'association
	public function charge () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_OBJ)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('association', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
    					
    					$this->code_postal = cleanCp($this->code_postal);
    					
    					if ($this->pays != ID_FRANCE) {
    						$temp = unserialize($this->adresse);
    						$this->adresse = $temp['adresse'];
    						$this->ville_pays = $temp['ville'];
    						$this->code_pays = $temp['code'];
    						$this->ville_label = $temp['ville'];
    						$this->code_postal = $temp['code'];
    					}
    					
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
				
			

				
				// Chargement des activites
				try {
  					$this->reqChargeActivites->execute();
   	 				while( $enregistrement = $this->reqChargeActivites->fetch(PDO::FETCH_OBJ)){
    					$this->association_activites[] = $enregistrement->id;
    					$this->association_activites_label[$enregistrement->id] = $enregistrement->nom;
 					}
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
		}
	}
	
	// Enregistrements
	public function sauve () {
		
		// Conversion
		$this->date_declaration_jo_mysql = convertDate($this->date_declaration_jo);
		$this->nom_soundex = phonetique($this->nom);
		/*
		if (!empty( $this->mdp_clair))  {
			$this->mdp = md5($this->mdp_clair);
		}
		*/
		
		if ($this->pays != ID_FRANCE) {
    			$temp = array();
    			$temp['adresse'] = $this->adresse;
    			$temp['ville'] = $this->ville_pays;
    			$temp['code'] = $this->code_pays;
    			$this->adresse = serialize($temp);
    			$this->ville = 0;
    	}
    	
		// Code commune
		$this->getInsee();
		if ($this->association_type == 2) $this->numero_dossier = 'C0000'.$this->insee;
		if ($this->association_type == 3) $this->numero_dossier = 'S'.strtoupper(substr($this->nom,0,4)).$this->insee;
		
		// Ajout
		if (!isset($this->id_association)) 
		{
				// Création du mdp si besoin 
				if (empty( $this->mdp))  {
					$this->mdp_clair = genererMdp(); // Envoyer le mot de passe
					$this->mdp = md5($this->mdp_clair);
				} else {
					$this->mdp_clair = $this->mdp;
					$this->mdp  = md5($this->mdp_clair);
				}
				
				
							
				try {
				   	 
  					 $resultat = $this->reqAjout->execute();
   
  					 if( $resultat===true ) {
    					
    					$this->id_association = $this->connection->lastInsertId('id'); 
    					
    					// Enregistrement des activités
    					if (count($this->association_activites)>0) $retour = $this->sauveActivites();
    					
    					// Rechargement ville
    					$data_ville = selectValeur('villes','id',$this->ville);
    					$this->ville_label = $data_ville->nom;
    					$this->code_postal = $data_ville->code_postal;
    					
    					// Vérification des doublons
    					$this->doublons();
    								
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
    					if (count($this->association_activites)>0) $retour = $this->sauveActivites();		
    					
    					// Mise à jour du mot de passe si besoin
    					if (!empty($this->mdp_clair)) 	 $retour = $this->sauveMdp();
    					
    					// Rechargement ville
    					$data_ville = selectValeur('villes','id',$this->ville);
    					$this->ville_label = $data_ville->nom;
    					$this->code_postal = $data_ville->code_postal;
    					
    					return $retour;
    								
 					 } else print_r( $this->reqModifie->errorInfo());
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}
	
	public function sauveActivites() {
			$this->reqDeleteActivites->execute();
			foreach ($this->association_activites as $val) {
				try {
				   	 $this->reqActivites->bindValue(':id_activite', $val, PDO::PARAM_INT); 
  					 $resultat = $this->reqActivites->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
			return "Enregistrement terminé";
	}
	
    /*
	public function sauveRepresentant() {
			$this->reqDeleteRepresentant->execute();
			
				try {
				   	 $resultat = $this->reqRepresentant->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
		
			return "Enregistrement terminé";
	}
	*/
    
	public function sauveMdp() {
			if (strlen($this->mdp)>0) {
				try {
					$this->mdp = md5($this->mdp);
				   	$this->reqModifieMdp->execute();
				   	return "Enregistrement terminé";
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
			}
	}
	
	public function doublons() {
			$this->doublon_certain = false;
        
            $this->reqDoublonsCertains->execute();
   	 		while( $enregistrement = $this->reqDoublonsCertains->fetch(PDO::FETCH_OBJ)){
    			$_SESSION['doublons'] = $this->id_association;
                $this->doublon_certain = true;
    			try {
    				$this->reqInsertDoublons->bindValue(':id_doublon', $enregistrement->id, PDO::PARAM_INT);
    				$this->reqInsertDoublons->bindValue(':type', '100', PDO::PARAM_INT);
				    $resultat = $this->reqInsertDoublons->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
 			}
 			
 			/*
 			$this->reqDoublonsSoundex->execute();
   	 		while( $enregistrement = $this->reqDoublonsSoundex->fetch(PDO::FETCH_OBJ)){
    			$_SESSION['doublons'] = $this->id_association;
    			$this->doublon_certain = true;
                try {
    				$this->reqInsertDoublons->bindValue(':id_doublon', $enregistrement->id, PDO::PARAM_INT);
    				$this->reqInsertDoublons->bindValue(':type', '50', PDO::PARAM_INT);
				    $resultat = $this->reqInsertDoublons->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
 			}
 			*/
	}
	
	public function ajoutePersonne($id,$id_personne,$annee,$date,$cons_admin=0,$benevole=0,$etat=0,$date_etat='00/00/0000') {
		if (empty($id)) {  
			try {
				$this->reqAjouterPersonne->bindValue(':id_association', $this->id_association);
				$this->reqAjouterPersonne->bindValue(':personne', $id_personne);
				$this->reqAjouterPersonne->bindValue(':annee', $annee);
				$this->reqAjouterPersonne->bindValue(':date', convertDate($date));
				$this->reqAjouterPersonne->bindValue(':cons_admin', $cons_admin);
				$this->reqAjouterPersonne->bindValue(':benevole', $benevole);
				$this->reqAjouterPersonne->bindValue(':etat', $etat);
				$this->reqAjouterPersonne->bindValue(':date_etat', convertDate($date_etat));
				$resultat = $this->reqAjouterPersonne->execute();

			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		}
		else {
			try {
				$this->reqModifierPersonne->bindValue(':id_lien', $id);
				$this->reqModifierPersonne->bindValue(':id_association', $this->id_association);
				$this->reqModifierPersonne->bindValue(':personne', $id_personne);
				$this->reqModifierPersonne->bindValue(':annee', $annee);
				$this->reqModifierPersonne->bindValue(':date', convertDate($date));
				$this->reqModifierPersonne->bindValue(':cons_admin', $cons_admin);
				$this->reqModifierPersonne->bindValue(':benevole', $benevole);
				$this->reqModifierPersonne->bindValue(':etat', $etat);
				$this->reqModifierPersonne->bindValue(':date_etat', convertDate($date_etat));
				$resultat = $this->reqModifierPersonne->execute();
			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		}
		
		return $resultat;
	}
	
	public function checkPersonneAnnee($id_personne,$annee) {
		try {
				$this->reqCheckPersonneAnnee->bindValue(':annee', $annee);
				$this->reqCheckPersonneAnnee->bindValue(':personne', $id_personne);
				$this->reqCheckPersonneAnnee->execute();
				
				return $this->reqCheckPersonneAnnee->fetch(PDO::FETCH_OBJ);
				
			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		
	}
	
	public function checkPersonneAssociation($id_lien) {
		try {
				$this->reqCheckPersonneAssociation->bindValue(':id_lien', $id_lien);
				$this->reqCheckPersonneAssociation->execute();
				
				return $this->reqCheckPersonneAssociation->fetch(PDO::FETCH_OBJ);
				
			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		
	}

	public function ajoutePersonneCA($id_personne,$annee,$date,$cons_admin=0,$benevole=0) {
	
		// Vérifie si la personne fait déjà partie de l'association pour l'année sélectionnée
		$check = $this->checkPersonneAnnee($id_personne,$annee);

		if (!$check) {  
			try {
				$this->reqAjouterPersonneCA->bindValue(':id_association', $this->id_association);
				$this->reqAjouterPersonneCA->bindValue(':personne', $id_personne);
				$this->reqAjouterPersonneCA->bindValue(':annee', $annee);
				$this->reqAjouterPersonneCA->bindValue(':date', convertDate($date));
				$this->reqAjouterPersonneCA->bindValue(':cons_admin', $cons_admin);
				$this->reqAjouterPersonneCA->bindValue(':benevole', $benevole);
				return $this->reqAjouterPersonneCA->execute();
				
			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		}
		else {
			
			try {	
				$this->reqModifierPersonneCA->bindValue(':id_lien', $check->id);
				$this->reqModifierPersonneCA->bindValue(':cons_admin', $cons_admin);
				$this->reqModifierPersonneCA->bindValue(':benevole', $benevole);
				$truc = $this->reqModifierPersonneCA->execute();

			} catch( Exception $e ) {
				return "Erreur d'enregistrement : ". $e->getMessage();
			}
		}
	}

	public function supprimePersonne($id) {
				$this->reqSupprimerPersonne->bindValue(':id_lien', $id);
				return $this->reqSupprimerPersonne->execute();
	}
	
	public function supprimePersonneId($id) {
				$this->reqSupprimerPersonneID->bindValue(':personne', $id);
				return $this->reqSupprimerPersonneID->execute();
	}
	
	public function gestionnaire($id) {
				$this->reqSauveGestionnaire->bindParam(':gestionnaire', $id, PDO::PARAM_INT, 11); 
				return $this->reqSauveGestionnaire->execute();
	}
	
	public function supprimePersonneCA($id) {
				// La personne est-elle bénévole ?
				$perso = $this->checkPersonneAssociation($id);
			
				if ($perso->benevole==1) { // Si bénévole pour l'année, on supprime le lien CA
					$this->reqSupprimerPersonneCA->bindValue(':id_lien', $id);
					return $this->reqSupprimerPersonneCA->execute();
				} else { // Sinon on la supprime
					$this->reqSupprimerPersonne->bindValue(':id_lien', $id);
					return $this->reqSupprimerPersonne->execute();
				}
	}


	// Mise en forme
	public function affActivites() {
		
			// Affichage des associations
			$i=1;
			$affActivites='';
			if (count($this->association_activites_label)>0) {
				foreach ($this->association_activites_label as $val) {
					$affActivites .= $val;
					if ($i<count($this->association_activites_label)) 	$affActivites .= ', ';
					$i++;
				}
			}
		return $affActivites;
	}
	
	
	// Récupérations 
	public function nbrBenevoles ($selectAnnee='') {	
		
		if (empty($selectAnnee)) {
			$this->nbr_benevoles = array();
			$this->reqNbrBenevoles->execute();
			if( $this->reqNbrBenevoles->rowCount() > 0) {
				while( $enregistrement = $this->reqNbrBenevoles->fetch(PDO::FETCH_OBJ)){
						$this->nbr_benevoles[$enregistrement->annee] =  $enregistrement->total;
				}
			}
		} else  { 
			$this->reqNbrBenevolesAnnee->bindValue(':annee',$selectAnnee);
			$this->reqNbrBenevolesAnnee->execute();
			if( $this->reqNbrBenevolesAnnee->rowCount() > 0) {
				$enregistrement = $this->reqNbrBenevolesAnnee->fetch(PDO::FETCH_OBJ);
				$this->nbr_benevoles[$enregistrement->annee] =  $enregistrement-> total;
			} 
		}
	}
	
	public function benevoles ($selectAnnee) {	
		
			$this->reqBenevolesAnnee->bindValue(':annee',$selectAnnee);
			$this->reqBenevolesAnnee->execute();
			if( $this->reqBenevolesAnnee->rowCount() > 0) {
				while( $enregistrement = $this->reqBenevolesAnnee->fetch(PDO::FETCH_OBJ)){
					$this->benevoles[$selectAnnee][] =  $enregistrement-> personne;
				}
			} 
	}
	
	public function conseilAdministration () {	
		$i=1;
	
			$this->nbr_benevoles = array();
			$this->reqCA->execute();
			if( $this->reqCA->rowCount() > 0) {
				while( $enregistrement = $this->reqCA->fetch(PDO::FETCH_OBJ)){
						$this->conseil_administration[$enregistrement->annee][$i]['personne'] =  ucwords($enregistrement->nom).' '.mb_strtoupper($enregistrement->prenom, 'UTF-8');
						$this->conseil_administration[$enregistrement->annee][$i]['civilite'] =  $enregistrement->civilite;
						$this->conseil_administration[$enregistrement->annee][$i]['courriel'] =  $enregistrement->courriel;
						$this->conseil_administration[$enregistrement->annee][$i]['fonction_label'] =  $enregistrement->fonction;
						$this->conseil_administration[$enregistrement->annee][$i]['fonction'] =  $enregistrement->cons_admin_fonction;
						$this->conseil_administration[$enregistrement->annee][$i]['id_personne'] =  $enregistrement->id_personne;
						$this->conseil_administration[$enregistrement->annee][$i]['id_ca'] =  $enregistrement->id_ca;
						
						if (($this->association_type == 1) && ($enregistrement->cons_admin_fonction == ID_PRESIDENT)) $this->presidents[$enregistrement->annee] =  $this->conseil_administration[$enregistrement->annee][$i];
					$i++;
				}
			}
		
	}
	
	/*
	public function presidents ($selectAnnee='') {
		
		if (empty($selectAnnee)) {
			$this->presidents = array();
			$this->reqCA->execute();
			if( $this->reqCA->rowCount() > 0) {
				while( $enregistrement = $this->reqCA->fetch(PDO::FETCH_OBJ)){
						$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['personne'] =  $enregistrement->nom.' '.$enregistrement->prenom;
						$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['courriel'] =  $enregistrement->courriel;
						$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['fonction_label'] =  $enregistrement->fonction;
						$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['fonction'] =  $enregistrement->cons_admin_fonction;
				}
			}
		} else  { 
			$this->reqCaAnnee->bindValue(':annee',$selectAnnee);
			$this->reqCaAnnee->execute();
			if( $this->reqCaAnnee->rowCount() > 0) {
				$enregistrement = $this->reqCaAnnee->fetch(PDO::FETCH_OBJ);
				$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['personne'] =  $enregistrement->nom.' '.$enregistrement->prenom;
				$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['courriel'] =  $enregistrement->courriel;
				$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['fonction_label'] =  $enregistrement->fonction;
				$this->conseil_administration[$enregistrement->id][$enregistrement->annee]['fonction'] =  $enregistrement->cons_admin_fonction;
			} 
		}
	}	
	*/
	public function lesAmis() {
		try {
    			$this->reqLaf->execute();
    			while( $enregistrement = $this->reqLaf->fetch(PDO::FETCH_OBJ)){
    					$laf = new laf_association($enregistrement->id);
						$this->lesamis[$enregistrement->annee] = $laf;
				}			
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
	}
	
	public function getInsee() {
		$this->reqInsee->execute();
		$enregistrement = $this->reqInsee->fetch(PDO::FETCH_OBJ);
		$this->insee =  $enregistrement->insee;
	}
	
	public function commandes() {
		try {
    			$this->reqChargeCommandes->execute();
    			while( $enregistrement = $this->reqChargeCommandes->fetch(PDO::FETCH_OBJ)){
    					$this->commandes[$enregistrement->id_commande] = $enregistrement;
				}			
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
	}
	
	
}
?>