<?php
class Personne {

    public $id_personne;
    public $numero_adherent;
    public $civilite;
  
	public $nom;
    public $nom_soundex;
    public $prenom;
    public $prenom_soundex;
    public $nom_jeune_fille;
    
	public $date_naissance;
	public $date_naissance_mysql;
	
	public $mdp;
	public $mdp_clair;
	
	public $courriel;
	
	public $adresse;
	public $ville;
	public $ville_label;
	public $pays;
	public $pays_label;
	public $pays_iso;
	public $region;
	public $region_id;
	public $code_postal;
	public $departement;
	public $departement_id;
	public $telephone_fixe;
	public $telephone_mobile;
	
	public $etat;
	
	public $recuperation;
	
	public $ville_pays;
	public $code_pays;
	
	public $profession;
	public $elu;
	public $elu_fonction;
	
	public $presse;
	 	
	public $prospect; 
	public $portrait; 
	public $date_creation; 
	public $date_modification; 
	public $modificateur ; 
	public $modificateur_label; 
	
	public $association_special; 
	public $association_special_label; 
	
	public $siege; 
	public $siege_label; 
	public $siege_habilite; 
	
	public $delegue_statut; 
	public $delegue_type; 
	public $delegue_adjoint; 
	public $delegue_habilite; 
	public $delegue_regions; 
	public $delegue_departements; 
	public $delegue_regions_label; 
	public $delegue_departements_label; 
	public $delegue_tous_departements; 
	
	public $newsletter;
	
	public $commandes;
	
	public $assurance_gratuite; 
	public $lesamis; 
	
	public $distinctions;
	public $distinctionsAutre;
	
	private $connection;
	
	public $doublon_certain;
    public $doublon_id;
	
	// SQL
	public $reqModifie; 
	public $reqAjout; 
	public $reqCharge; 
	public $reqChargeCommandes; 
	
	public $reqDoublonsCertains; 
	public $reqDoublonsSoundex; 
	
    //Constructeur 
    public function __construct ($id=0) {    
    	 
    	  
    	  //
    	  // Initialisation
    	  //

		  $this->connection = $GLOBALS['connection'];
    	  @$this->modificateur = $_SESSION['utilisateur']['id'];
    	  
    	  $this->prospect = 0; 
		
        
    	  //
    	  // Requetes SQL préparées 
    	  //


    	  // MODIFIER 
    	  
		  $this->reqModifie = $this->connection->prepare("UPDATE  `personnes` SET
			  `numero_adherent` =  :numero_adherent,
			  `civilite` =  :civilite,
			  `prenom` =  :prenom,
			  `nom` =  :nom,
			  `nom_soundex` =  :nom_soundex,
			  `prenom_soundex` =  :prenom_soundex,
			  `nom_jeune_fille` =  :nom_jeune_fille,
			  `date_naissance` =  :date_naissance,
			  `ddn` =  :ddn,
			  `courriel` =  :courriel,
			  `adresse` =  :adresse,
			  `ville` =  :ville,
			  `pays` =  :pays,
			  `telephone_fixe` =  :telephone_fixe,
			  `telephone_mobile` =  :telephone_mobile,
			  `profession` =  :profession,
			  `prospect` =  :prospect,
			  `elu` =  :elu,
			  `presse` =  :presse,
			  `portrait` =  :portrait,
			  `date_modification` = CURRENT_TIMESTAMP, 
			  `modificateur` =  :modificateur,
			  `newsletter` =  :newsletter,
			  `recuperation` =  :recuperation
		  WHERE id = :id_personne ");
			
        $this->reqModifie->bindParam(':numero_adherent', $this->numero_adherent, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':civilite', $this->civilite, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':prenom', $this->prenom, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':prenom_soundex', $this->prenom_soundex, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':nom_jeune_fille', $this->nom_jeune_fille, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':date_naissance', $this->date_naissance_mysql, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':ddn', $this->ddn, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 11);
        $this->reqModifie->bindParam(':adresse', $this->adresse, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':ville', $this->ville,  PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':pays', $this->pays,  PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':telephone_fixe', $this->telephone_fixe, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':telephone_mobile', $this->telephone_mobile, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':profession', $this->profession, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':prospect', $this->prospect, PDO::PARAM_INT, 1);
        $this->reqModifie->bindParam(':elu', $this->elu, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':presse', $this->presse, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':portrait', $this->portrait, PDO::PARAM_STR, 100);
        $this->reqModifie->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':newsletter', $this->newsletter, PDO::PARAM_INT, 11);	
        $this->reqModifie->bindParam(':recuperation', $this->recuperation, PDO::PARAM_STR, 12);	
		 
		// MOT DE PASSE
		
		$this->reqModifieMdp = $this->connection->prepare("UPDATE  `personnes` SET
		  `mdp` =  :mdp
		  WHERE id = :id_personne ");
		$this->reqModifieMdp->bindParam(':mdp', $this->mdp, PDO::PARAM_STR, 255);  
		$this->reqModifieMdp->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		
		
		$this->reqExtranetModifie = $this->connection->prepare("UPDATE  `personnes` SET
  		`siege` =  :siege,
  		`siege_habilite` =  :siege_habilite,
  		`delegue_statut` =  :delegue_statut,
  		`delegue_type` =  :delegue_type, 
  		`delegue_adjoint` =  :delegue_adjoint,
  		`delegue_habilite` =  :delegue_habilite,
  		`date_modification` = CURRENT_TIMESTAMP, 
  		`modificateur` =  :modificateur
		WHERE id = :id_personne ");
			
		$this->reqExtranetModifie->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':siege', $this->siege, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':siege_habilite', $this->siege_habilite, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':delegue_statut', $this->delegue_statut, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':delegue_type', $this->delegue_type, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':delegue_adjoint', $this->delegue_adjoint, PDO::PARAM_INT, 11);
		$this->reqExtranetModifie->bindParam(':delegue_habilite', $this->delegue_habilite, PDO::PARAM_INT, 11);
				 
		  // DÉLÉGUÉ RÉGIONS
		  
		  $this->reqAjouteDelegueRegions = $this->connection->prepare("INSERT INTO `delegues_regions` 
		  (`id`, `personne`, `region`) 
		  VALUES 
		  ('', :id_personne, :region);");
		  $this->reqAjouteDelegueRegions->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 
		  // DÉLÉGUÉ DÉPARTEMENTS
		
		  $this->reqAjouteDelegueDepartements = $this->connection->prepare("INSERT INTO `delegues_departements` 
		  (`id`, `personne`, `departement`) 
		  VALUES 
		  ('', :id_personne, :departement);");
		  $this->reqAjouteDelegueDepartements->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 
		  // SUPPRESSION DELEGUE 
		  		 
		 $this->reqDeleteDelegueRegions = $this->connection->prepare( 'DELETE FROM `delegues_regions` WHERE `personne` = :id_personne');
		 $this->reqDeleteDelegueRegions->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		 
		 $this->reqDeleteDelegueDepartements = $this->connection->prepare( 'DELETE FROM `delegues_departements` WHERE `personne` = :id_personne');
		 $this->reqDeleteDelegueDepartements->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		 
		 
		 // Supprimer Activer
		
		$this->reqSupprime = $this->connection->prepare("UPDATE `personnes` SET `etat` = 0 WHERE `personnes`.`id` = :id_personne ;");
		 $this->reqSupprime->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 
		 $this->reqActive = $this->connection->prepare("UPDATE `personnes` SET `etat` = 1 WHERE `personnes`.`id` = :id_personne ;");
		 $this->reqActive->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 
		 
		
		// DÉLÉGUÉ SPÉCIAL
    	  
		 $this->reqDelegueSpecial = $this->connection->prepare("UPDATE `associations` SET `delegue_special` = :id_personne WHERE `associations`.`id` = :id_association ;");
		 $this->reqDelegueSpecial->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11); 
		 
		  // SUPPRESSION DELEGUE SPÉCIAL
		 $this->reqDeleteDelegueSpecial = $this->connection->prepare("UPDATE `associations` SET `delegue_special` = '' WHERE `associations`.`delegue_special` = :id_personne ;");
		 $this->reqDeleteDelegueSpecial->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		 
		 
    	  // AJOUT
    	  
		  $this->reqAjout = $this->connection->prepare("INSERT INTO  `personnes` 
		  (`id`, `numero_adherent`, `civilite`, `prenom`, `nom`, `nom_soundex`, `prenom_soundex`, `nom_jeune_fille`, `date_naissance`, `ddn`, `courriel`, `mdp`, `adresse`, `ville`,`pays`, `telephone_fixe`, `telephone_mobile`, `profession`, `prospect`, `elu`, `presse`, `portrait`,`date_creation`,`date_modification`, `modificateur`, `newsletter`) 
		  VALUES 
		  (null, :numero_adherent, :civilite, :prenom , :nom , :nom_soundex , :prenom_soundex , :nom_jeune_fille , :date_naissance , :ddn , :courriel , :mdp , :adresse , :ville ,:pays , :telephone_fixe , :telephone_mobile , :profession , :prospect , :elu , :presse , :portrait ,   NOW(),NOW(), :modificateur, :newsletter);");
	
		 $this->reqAjout->bindParam(':numero_adherent', $this->numero_adherent, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':civilite', $this->civilite, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':prenom', $this->prenom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':nom', $this->nom, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':prenom_soundex', $this->prenom_soundex, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':nom_jeune_fille', $this->nom_jeune_fille, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':date_naissance', $this->date_naissance_mysql, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':ddn', $this->ddn, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 11);
		 $this->reqAjout->bindParam(':mdp', $this->mdp, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':adresse', $this->adresse, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':ville', $this->ville,  PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':pays', $this->pays,  PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':telephone_fixe', $this->telephone_fixe, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':telephone_mobile', $this->telephone_mobile, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':profession', $this->profession, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':prospect', $this->prospect, PDO::PARAM_INT, 1);
		 $this->reqAjout->bindParam(':elu', $this->elu, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':presse', $this->presse, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':portrait', $this->portrait, PDO::PARAM_STR, 100);
		 $this->reqAjout->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
		 $this->reqAjout->bindParam(':newsletter', $this->newsletter, PDO::PARAM_INT, 11);
		
		
		// Chargement  
		$this->reqCharge = $this->connection->prepare('
SELECT personnes.id, 
	personnes.numero_adherent, 
	personnes.civilite, 
	personnes.prenom, 
	personnes.nom, 
	personnes.prenom_soundex, 
	personnes.nom_soundex, 
	personnes.nom_jeune_fille, 
	personnes.date_naissance, 
	personnes.ddn, 
	personnes.courriel, 
	personnes.adresse, 
	personnes.ville, 
	personnes.pays, 
	personnes.telephone_fixe, 
	personnes.telephone_mobile, 
	personnes.profession, 
	personnes.prospect, 
	personnes.modificateur, 
	personnes.date_modification, 
	personnes.date_creation, 
	personnes.portrait, 
	personnes.presse, 
	personnes.elu, 
	personnes.etat, 
	personnes.siege, 
	personnes.siege_habilite, 
	personnes.delegue_statut, 
	personnes.delegue_type, 
	personnes.delegue_adjoint, 
	personnes.delegue_habilite, 
	personnes.newsletter, 
	personnes.recuperation, 
	villes.code_postal, 
	villes.nom AS ville_label, 
	pays.nom_fr_fr AS pays_label, 
	pays.alpha2 AS pays_iso, 
	regions.nom AS region, 
	departements.nom AS departement, 
	regions.id AS region_id, 
	departements.id AS departement_id, 
	elus_fonctions.fonction AS elu_fonction, 
	siege.nom AS siege_label
FROM personnes LEFT OUTER JOIN villes ON personnes.ville = villes.id
	 LEFT OUTER JOIN elus_fonctions ON personnes.elu = elus_fonctions.id
	 LEFT OUTER JOIN regions ON villes.region = regions.id
	 LEFT OUTER JOIN departements ON villes.departement = departements.id
	 LEFT OUTER JOIN pays ON personnes.pays = pays.id
	 LEFT OUTER JOIN  siege ON personnes.siege = siege.id
WHERE personnes.id = :id_personne LIMIT 1');
	 		
		$this->reqCharge->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		$this->reqChargeCourriel = $this->connection->prepare('
SELECT personnes.id AS id_personne FROM personnes WHERE personnes.courriel = :courriel LIMIT 1');
 		
		$this->reqChargeCourriel->bindParam(':courriel', $this->courriel, PDO::PARAM_INT, 11);
		
		
		$this->reqChargeDelegueRegions = $this->connection->prepare('SELECT regions.id, 
	regions.nom
FROM delegues_regions INNER JOIN regions ON delegues_regions.region = regions.id
WHERE personne =  :id_personne ');	
		$this->reqChargeDelegueRegions->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		$this->reqChargeDelegueDepartements = $this->connection->prepare('SELECT departements.id, 
	departements.numero, 
	departements.nom
FROM delegues_departements INNER JOIN departements ON delegues_departements.departement = departements.id
WHERE personne =:id_personne ');	
		$this->reqChargeDelegueDepartements->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		$this->reqChargeDelegueSpecial = $this->connection->prepare('SELECT *
FROM associations
WHERE delegue_special =:id_personne ');	
		$this->reqChargeDelegueSpecial->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
			
		
		
		// Chargement Assurance Gratuite
		
		$this->reqAssuranceGratuite = $this->connection->prepare('
SELECT associations.nom AS association, 
	personnes_associations_etat.nom AS etat, 
	personnes_associations.date, 
	personnes_associations.date_etat, 
	personnes_associations.annee, 
	personnes_associations.benevole, 
	personnes_associations.cons_admin, 
	cons_admin_fonctions.nom AS fonction, 
	associations.id AS id_association, 
	personnes_associations_etat.id AS id_etat, 
	personnes_associations.id AS id_lien
FROM personnes_associations INNER JOIN personnes_associations_etat ON personnes_associations.etat = personnes_associations_etat.id
	 INNER JOIN associations ON personnes_associations.association = associations.id
	 INNER JOIN personnes ON personnes.id = personnes_associations.personne
	 LEFT OUTER JOIN cons_admin_fonctions ON personnes_associations.cons_admin = cons_admin_fonctions.id AND associations.association_type = cons_admin_fonctions.association_type
WHERE personnes.id=:id_personne
GROUP BY personnes_associations.annee, personnes_associations.association
ORDER BY personnes_associations.annee DESC, personnes_associations.date DESC');
	 		
		$this->reqAssuranceGratuite->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		// Chargement LAF
		$this->reqLaf = $this->connection->prepare('SELECT laf_adhesions_personnes.id, 
	laf_adhesions_personnes.annee
FROM laf_adhesions_personnes
WHERE laf_adhesions_personnes.personne = :id_personne
ORDER BY laf_adhesions_personnes.annee DESC
		');
	 		
		$this->reqLaf->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		
		// Chargement Distinctions
		$this->reqDistinctions = $this->connection->prepare('SELECT  distinctions.id, distinctions.annee
FROM distinctions
WHERE distinctions.personne = :id_personne
ORDER BY distinctions.annee DESC 
		');	 		
		$this->reqDistinctions->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		$this->reqDistinctionsAutre = $this->connection->prepare('SELECT  distinctions.id, distinctions.annee
FROM distinctions
WHERE distinctions.demandeur = :id_personne AND  distinctions.personne <> :id_personne
ORDER BY distinctions.annee DESC 
		');	
		$this->reqDistinctionsAutre->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		
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
	 WHERE commerce_commandes.type_utilisateur = "P" AND
			commerce_commandes.id_utilisateur =  :id_personne 
	GROUP BY commerce_commandes.id
	ORDER BY date_creation DESC ');
	 	
	 	
		$this->reqChargeCommandes->bindParam(':id_personne', $this->id_personne, PDO::PARAM_INT, 11);
		
		
		
	
		// Doublons
		$this->reqDoublonsCertains = $this->connection->prepare('SELECT * FROM `personnes` WHERE courriel=:courriel ');
		$this->reqDoublonsCertains->bindParam(':courriel', $this->courriel, PDO::PARAM_STR, 100);
		
		/*
		$this->reqDoublonsCertains = $this->connection->prepare('SELECT * FROM `personnes` WHERE UPPER(nom)=UPPER(:nom) OR UPPER(date_naissance)=UPPER(:date_naissance) ');
	
		$this->reqDoublonsCertains->bindParam(':date_naissance', $this->date_naissance_mysql, PDO::PARAM_STR, 100);
		$this->reqDoublonsCertains->bindParam(':nom', $this->nom, PDO::PARAM_INT, 11);
		
		
		// Doublons Soundex
		$this->reqDoublonsSoundex = $this->connection->prepare('SELECT * FROM `associations` WHERE nom_soundex=:nom_soundex  AND id <> :id_association');
		$this->reqDoublonsSoundex->bindParam(':nom_soundex', $this->nom_soundex, PDO::PARAM_INT, 11);
		$this->reqDoublonsSoundex->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		// Enregistre doublons
		$this->reqInsertDoublons = $this->connection->prepare("INSERT INTO  `doublons` 
		  (`id`, `nomtable`, `id1`, `id2`, `type`) 
		  VALUES 
		  ('', 'associations',  :id_association, :id_doublon, :type);");
		$this->reqInsertDoublons->bindParam(':id_association', $this->id_association, PDO::PARAM_INT, 11);
		
		*/
		 
		 if ($id>0) {
			$this->id_personne = $id;
			$this->charge();
		}
		
	}
	
	public function chargeCourriel ($c) {
		$this->courriel=$c;
		
				try {
  					$this->reqChargeCourriel->execute();
   	 				while( $enregistrement = $this->reqChargeCourriel->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('personne', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
 					}
 					$this->charge();
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}
	}
	// Récupère la personne
	public function charge () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('personne', $cle)) 
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
				
				
				// Chargements délégués
				if ( ($this->delegue_statut > 0) && ($this->delegue_type < 3) ) {

					// Régions
					try {
						$this->reqChargeDelegueRegions->execute();
						while( $enregistrement = $this->reqChargeDelegueRegions->fetch(PDO::FETCH_OBJ)){
							$this->delegue_regions[] = $enregistrement->id;
    						$this->delegue_regions_label[$enregistrement->id] = $enregistrement->nom;
						}
					} catch( Exception $e ) {
						echo "Erreur d'enregistrement : ", $e->getMessage();
					}
					// Chargement des départements des régions sélectionnées
					if (is_countable($this->delegue_regions) && count($this->delegue_regions)>0) $this->delegue_tous_departements = getDepartements($this->delegue_regions);
					
					// Départements
					try {
						$this->reqChargeDelegueDepartements->execute();
						while( $enregistrement = $this->reqChargeDelegueDepartements->fetch(PDO::FETCH_OBJ)){
							$this->delegue_tous_departements[$enregistrement->id] = $enregistrement->id;
							$this->delegue_departements[] = $enregistrement->id;
    						$this->delegue_departements_label[$enregistrement->id] = $enregistrement->numero.' - '.$enregistrement->nom;
						}
					} catch( Exception $e ) {
						echo "Erreur d'enregistrement : ", $e->getMessage();
					}
				}
				
				// Chargement délégués spéciaux
				try {
						$this->reqChargeDelegueSpecial->execute();
						while( $enregistrement = $this->reqChargeDelegueSpecial->fetch(PDO::FETCH_OBJ)){
							$this->association_special[] = $enregistrement->id;
    						$this->association_special_label[$enregistrement->id] = $enregistrement->nom;
						}
					} catch( Exception $e ) {
						echo "Erreur d'enregistrement : ", $e->getMessage();
					}
		}
	}
	
	// Enregistrements
	public function sauve () {
		
		// Conversion
		$this->date_naissance_mysql = convertDate($this->date_naissance);
		$this->nom_soundex = phonetique($this->nom);
		$this->prenom_soundex = phonetique($this->prenom);
		
		/*
		if (!empty( $this->mdp_clair))  {
			$this->mdp = md5($this->mdp_clair);
		} else if (!empty( $this->mdp)) {
			$this->mdp = md5($this->mdp);
		}
		*/
		// Création du mdp si besoin 
				
				
		if ($this->pays != ID_FRANCE) {
    			$temp = array();
    			$temp['adresse'] = $this->adresse;
    			$temp['ville'] = $this->ville_pays;
    			$temp['code'] = $this->code_pays;
    			$this->adresse = serialize($temp);
    			$this->ville = 0;
    	}
		$retour = '';
		
		// Ajout
		if (!isset($this->id_personne)) 
		{
				
				if (empty( $this->mdp))  {
					$this->mdp_clair = genererMdp(); // Envoyer le mot de passe
					$this->mdp = md5($this->mdp_clair);
				} else {
					$this->mdp_clair = $this->mdp;
					$this->mdp  = md5($this->mdp_clair);
				}
				// Création numéro adhérent
				$temp_date = str_replace('-','',$this->date_naissance_mysql);
				$this->numero_adherent = substr($this->nom,0,5).substr($this->prenom,0,3).$temp_date;
						
				try {
				   	 
  					 $resultat = $this->reqAjout->execute();
  					 
  					 if( $resultat===true ) {
    					
    					$this->id_personne = $this->connection->lastInsertId('id'); 
    					
    					// Enregistrement des liens delegues speciaux
    					if ($_SESSION['utilisateur']['siege'] == 1)  {
							// Ce bout de code doit être revu
							if ($this->association_special != "")
    							if (count($this->association_special)>0) $retour = $this->sauveDelegueSpecial();
    					}
    					// Enregistrement accès extranet
    					if ($_SESSION['utilisateur']['siege'] == 1) $retour = $this->sauveAccesExtranet();
    					
    					// Rechargement ville
    					$data_ville = selectValeur('villes','id',$this->ville);
    					if (isset($data_ville->nom) && isset($data_ville->code_postal)) {
							$this->ville_label = $data_ville->nom;
    						$this->code_postal = $data_ville->code_postal;
						}
    					
    					/*
    					
    					// Vérification des doublons
    					$this->doublons();
    					*/		
    					
    					// Connection Send In Blue
    					$data_ville = selectValeur('villes','id',$this->ville);
    					if (isset($data_ville->nom) && isset($data_ville->code_postal)) {
							$this->ville_label = $data_ville->nom;
    						$this->code_postal = $data_ville->code_postal;
						}
    					
    					sib_personne ($this);
    				
    					
    					return $resultat;
    								
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
    					
    					// Enregistrement des liens delegues speciaux
    					if ($_SESSION['utilisateur']['siege'] == 1)  {
    						if (is_countable($this->association_special) && count($this->association_special)>0) $retour = $this->sauveDelegueSpecial();
    					}
    					// Enregistrement accès extranet
    					if ($_SESSION['utilisateur']['siege'] == 1) $retour = $this->sauveAccesExtranet();
    					
    					// Rechargement ville
    					$data_ville = selectValeur('villes','id',$this->ville);
    					$this->ville_label = $data_ville->nom;
    					$this->code_postal = $data_ville->code_postal;
    					
    					
    					// Mise à jour du mot de passe si besoin
    					$retour = $this->sauveMdp();
    					
    					// Connection Send In Blue
    					
    					sib_personne ($this);
    					
    					return $retour;
    								
 					 } else return  $this->reqModifie->errorInfo();
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}	
		}
	}

	
	
	public function sauveAccesExtranet() {
			
			
			// Sauvegarde de l'accès
			try {
			   $resultat = $this->reqExtranetModifie->execute();
			  // echo $id_association.'-';
			} catch( Exception $e ) {
					$retour .=  "Erreur d'enregistrement : ". $e->getMessage();
			}
			
			// Suppression des liens précédents
			$this->supprimeDelegue();
			$retour = '';
			
			if ( ($this->delegue_statut > 0) && ($this->delegue_type < 3) ) {
					
					// Ajout des régions
					if (!empty($this->delegue_regions)) {
				
					foreach ($this->delegue_regions as $region) {
						try {
							   $this->reqAjouteDelegueRegions->bindValue(':region', $region, PDO::PARAM_INT);
							   $resultat = $this->reqAjouteDelegueRegions->execute();
							  // echo $id_association.'-';
							} catch( Exception $e ) {
									$retour .=  "Erreur d'enregistrement : ". $e->getMessage();
							}
					}
					}
					
					// Ajout des départements
					if (!empty($this->delegue_departements)) {
					foreach ($this->delegue_departements as $departement) {
						try {
							   $this->reqAjouteDelegueDepartements->bindValue(':departement', $departement, PDO::PARAM_INT);
							   $resultat = $this->reqAjouteDelegueDepartements->execute();
							  // echo $id_association.'-';
							} catch( Exception $e ) {
									$retour .=  "Erreur d'enregistrement : ". $e->getMessage();
							}
					}
					}
				
			}
			
			return 	$retour;
	}
	
	
	public function supprimeDelegue() {
		
		try {
		  $resultat = $this->reqDeleteDelegueRegions->execute();
		  // echo $id_association.'-';
		} catch( Exception $e ) {
			return "Erreur d'enregistrement : ". $e->getMessage();
		}
		
		try {
		  $resultat = $this->reqDeleteDelegueDepartements->execute();
		  // echo $id_association.'-';
		} catch( Exception $e ) {
			return "Erreur d'enregistrement : ". $e->getMessage();
		}
	}
	
	public function supprime() {
		try {
		  $resultat = $this->reqSupprime->execute();
		  // echo $id_association.'-';
		} catch( Exception $e ) {
			return "Erreur d'enregistrement : ". $e->getMessage();
		}
	}
	
	public function active() {
		try {
		  $resultat = $this->reqActive->execute();
		  // echo $id_association.'-';
		} catch( Exception $e ) {
			return "Erreur d'enregistrement : ". $e->getMessage();
		}
	}
	
	
	
	public function sauveDelegueSpecial() {
			// Suppression des liens précédents
			try {
				   $resultat = $this->reqDeleteDelegueSpecial->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
		
			// Ajout des nouveaux liens
			foreach ($this->association_special as $id_association) {
				try {
					   $this->reqDelegueSpecial->bindValue(':id_association', $id_association, PDO::PARAM_INT);
					   $resultat = $this->reqDelegueSpecial->execute();
					  // echo $id_association.'-';
					} catch( Exception $e ) {
					  	return "Erreur d'enregistrement : ". $e->getMessage();
					}
			}
	}
	
	
	
	public function assuranceGratuite() {
		try {
    			$this->reqAssuranceGratuite->execute();
    			while( $enregistrement = $this->reqAssuranceGratuite->fetch(PDO::FETCH_OBJ)){
						$this->assurance_gratuite[$enregistrement->annee][$enregistrement->id_association]  =   $enregistrement;
				}			
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
	}
	
	
	public function lesAmis() {
		try {
    			$this->reqLaf->execute();
    			while( $enregistrement = $this->reqLaf->fetch(PDO::FETCH_OBJ)){
    					$laf = new laf_personne($enregistrement->id);
						$this->lesamis[$enregistrement->annee] = $laf;
				}
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
	}
	
	public function distinctions() {
		try {
    			$this->reqDistinctions->execute();
    			while( $enregistrement = $this->reqDistinctions->fetch(PDO::FETCH_OBJ)){
    					$distinction = new distinction($enregistrement->id);
						$this->distinctions[$enregistrement->id] = $distinction;
				}			
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
	}
	
	public function distinctionsAutre() {
		try {
    			$this->reqDistinctionsAutre->execute();
    			while( $enregistrement = $this->reqDistinctionsAutre->fetch(PDO::FETCH_OBJ)){
    					$distinction = new distinction($enregistrement->id);
						$this->distinctionsAutre[$enregistrement->id] = $distinction;
				}			
			} catch( Exception $e ) {
			  	return "Erreur d'enregistrement : ". $e->getMessage();
			}
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
	
	
	public function doublons() {
			$this->reqDoublonsCertains->execute();
   	 		while( $enregistrement = $this->reqDoublonsCertains->fetch(PDO::FETCH_OBJ)){
    			$_SESSION['doublons'] = $this->id_personne;
    			try {
    				$this->reqInsertDoublons->bindValue(':id_doublon', $enregistrement->id, PDO::PARAM_INT);
    				$this->reqInsertDoublons->bindValue(':type', '100', PDO::PARAM_INT);
				    $resultat = $this->reqInsertDoublons->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
 			}
 			
 			$this->reqDoublonsSoundex->execute();
   	 		while( $enregistrement = $this->reqDoublonsSoundex->fetch(PDO::FETCH_OBJ)){
    			$_SESSION['doublons'] = $this->id_personne;
    			try {
    				$this->reqInsertDoublons->bindValue(':id_doublon', $enregistrement->id, PDO::PARAM_INT);
    				$this->reqInsertDoublons->bindValue(':type', '50', PDO::PARAM_INT);
				    $resultat = $this->reqInsertDoublons->execute();
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
 			}
	}

	public function doublonsCertains() {
		$this->doublon_certain = false;
        $this->doublon_id = false;
		$this->reqDoublonsCertains->execute();
        $count = $this->reqDoublonsCertains->rowCount();
		if ($count == 1) {
            $enregistrement = $this->reqDoublonsCertains->fetch(PDO::FETCH_OBJ);
            $this->doublon_certain = true;
            $this->doublon_id = $enregistrement->id;
        } else if ($count > 1) {
            $this->doublon_certain = true;
        }
	}
	
	
	
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
	
	public function demandeMdp () {
		$this->recuperation = genererMdp(12);
		$this->sauve();
		
		$email = new email();
		$email->to ( $this->courriel , $this->prenom.' '.$this->nom );
		$email->sujet = 'Cercle National des Bénévoles - Demande de mot de passe';
		$email->message = 'Bonjour, <br><br> vous avez fait une demande de mot de passe pour accéder à votre espace personnel.<br><br><a href="http://www.cercle-benevoles.fr/mon_compte?recuperation='.$this->recuperation.'&courriel='.$this->courriel.'">Pour confimer votre demande de mot de passe, cliquez ici.</a><br><br>Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer ce message.';
		$email->envoyer();
		
		$retour='Votre demande de nouveau mot de passe à été prise en compte.<br>Vous allez reçevoir un courriel vous indiquant la procédure à suivre.';
		return $retour;
	}
	
	public function creationMdp ($code) {
		$retour='';
		
		if ($code == $this->recuperation) {
			$retour='Vous allez reçevoir votre nouveau mot de passe par email.';
			$pass=genererMdp(6,true);
				
			$this->recuperation = null;
			$this->sauve();
			
			$this->mdp = $pass;
			$this->sauveMdp();
			
			$email = new email();
			$email->to ( $this->courriel , $this->prenom.' '.$this->nom );
			$email->sujet = 'Cercle National des Bénévoles - Nouveau mot de passe';
			$email->message = 'Bonjour, <br> voici votre nouveau mot de passe pour vous permettre d\'accéder à votre espace personnel : <strong>'.$pass.'</strong>.<br><br>Votre espace personnel : <a href="http://www.cercle-benevoles.fr/mon_compte"><a href="http://www.cercle-benevoles.fr/mon_compte</a>.';
			$email->envoyer();
			
			$retour='Votre nouveau mot de passe vous à été envoyé par courriel.';
		}
		else {
			$retour='Votre demande de mot de passe n\'est plus valide.<br>Veuillez faire une nouvelle demande.';
		}
		return $retour;
	}

	public function detailDelegue($type) {
		$delegue = '';
		if ($type=='header') {
			if ($this->delegue_statut >0 ) {
				$delegue = '<h3>';
	
				switch ($this->delegue_statut) {
					case '1' :
						$delegue .= 'Conseillé ';
					break;
					case '2' :
						$delegue .= 'Délégué ';
					break;
				}
	
				switch ($this->delegue_type) {
					case '1' :
						$delegue .= 'Régional';
					break;
					case '2' :
						$delegue .= 'Départemental';
					break;
					case '3' :
						$delegue .= 'Circonscription';
					break;
				}
	
				if ($this->delegue_adjoint == '1') $delegue .= ' Adjoint';
				if ($this->delegue_habilite == '1') $delegue .= ' / Habilité';
	
				$delegue .= '</h3>';
			}
		}
	
	if ($type=='detail') {
		if ($this->delegue_statut >0 ) {
			$delegue = '<p><strong>Statut</strong></p>';
	
				switch ($this->delegue_statut) {
					case '1' :
						$delegue .= 'Conseillé ';
					break;
					case '2' :
						$delegue .= 'Délégué ';
					break;
				}
	
				switch ($this->delegue_type) {
					case '1' :
						$delegue .= 'Régional';
					break;
					case '2' :
						$delegue .= 'Départemental';
					break;
					case '3' :
						$delegue .= 'Circonscription';
					break;
				}
				if ($this->delegue_adjoint == '1') $delegue .= ' Adjoint';
				if ($this->delegue_habilite == '1') $delegue .= ' Habilité';
				$delegue .= '<br>';
				if(!empty($this->delegue_regions_label)) {
					$delegue .= lister($this->delegue_regions_label,'<br>');
					$delegue .= '<br>';
				}
				if (!empty($this->delegue_departements_label)) {
					$delegue .= lister($this->delegue_departements_label,'<br>');
					$delegue .= '<br>';
				}
				
				
				if(!empty($this->association_special_label)) {
					$delegue .= '<br><p><strong>Délégué spécial</strong></p>';
					foreach ($this->association_special_label as $id=>$asso) {
						$delegue .= '<a href='.$_SESSION['WEBROOT'].'associations/detail/'.$id.'>'.$asso.'</a><br>';
					}
				}
				$delegue .= '';
			}
		}
		
		return $delegue;
	}

}
?>
