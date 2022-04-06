<?php
class Distinction
{
    
    public $id_distinction;
    public $num_demande;
    public $personne;
    public $date_demande;
    public $annee;
    public $distinction_type;
    public $distinction_code;
    public $distinction_type_label;
    public $distinction_type_decision;
    public $distinction_type_decision_label;
    public $nbr_annees;
    public $demandeur;
    public $modificateur;
    public $type_validation;
    public $annuaire = 1;
    
    
    public $activites;
    public $activites_passees;
    
    public $parrains;
    public $parrains_texte;
    
    public $documents;
    
    public $distinctions;
    
    public $documents_complets = 1;
    
    public $commentaire;
    
    static $connect;
    
    // Nouveaux champs
    public $delegue;
    public $avis;
    public $domaines;
    public $domaines_autres;
    public $domaines_serialize;
    public $distinction_avis_label;
    
    // SQL
    public $reqModifie;
    public $reqAjout;
    public $reqCharge;
    
    public $reqActivites;
    public $reqParrains;
    public $reqDocuments;
    public $reqDistinctions;
    
    public $reqDeleteActivites;
    public $reqDeleteParrains;
    public $reqDeleteDocuments;
    public $reqDeleteDistinctions;
    
    public $reqChargeActivites;
    public $reqChargeParrains;
    public $reqChargeDocuments;
    public $reqChargeDistinctions;
    
    
    //Constructeur 
    public function __construct($id = 0)
    {
        
        
        //
        // Initialisation
        //
        
        $this->connection   = connect();
        $this->modificateur = $_SESSION['utilisateur']['id'];
        
        
        //
        // Requetes SQL préparées 
        //
        
        
        // Modifier 
        
        $this->reqModifie = $this->connection->prepare("UPDATE  `distinctions` SET
			   `annee` = :annee,
			   `date_demande` = :date_demande,
			  `nbr_annees` = :nbr_annees,
			  `personne` = :personne ,
			  `demandeur` = :demandeur ,
			  `distinction_type` = :distinction_type,
			  `distinction_type_decision` = :distinction_type_decision,
			  `type_validation` = :type_validation,
			  `commentaire` = :commentaire,
			  `documents_complets` = :documents_complets,
			  `date_modification` = NOW(),
			  `modificateur` = :modificateur,
			  `annuaire` = :annuaire,
			   `parrains_texte` = :parrains_texte,
			   `delegue` = :delegue,
			   `avis` = :avis,
			   `domaines` = :domaines,
			   `domaines_autres` = :domaines_autres
		  WHERE id = :id_distinction ");
        
        $this->reqModifie->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':annee', $this->annee, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':date_demande', $this->date_demande, PDO::PARAM_STR, 255);
        $this->reqModifie->bindParam(':nbr_annees', $this->nbr_annees, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':personne', $this->personne, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':demandeur', $this->demandeur, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':distinction_type', $this->distinction_type, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':distinction_type_decision', $this->distinction_type_decision, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':type_validation', $this->type_validation, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR, 25500);
        $this->reqModifie->bindParam(':documents_complets', $this->documents_complets, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':annuaire', $this->annuaire, PDO::PARAM_INT, 11);
        $this->reqModifie->bindParam(':parrains_texte', $this->parrains_texte, PDO::PARAM_STR, 25500);
        
         $this->reqModifie->bindParam(':delegue', $this->delegue, PDO::PARAM_INT, 11);
         $this->reqModifie->bindParam(':avis', $this->avis, PDO::PARAM_INT, 11);
         $this->reqModifie->bindParam(':domaines', $this->domaines_serialize, PDO::PARAM_STR, 25500);
          $this->reqModifie->bindParam(':domaines_autres', $this->domaines_autres, PDO::PARAM_STR, 25500);
        
        // Enregistrer
        
        $this->reqAjout = $this->connection->prepare("INSERT INTO  `distinctions` 
		  (`id`, `date_demande`, `annee`,`nbr_annees`, `personne`, `demandeur`, `distinction_type`, `distinction_type_decision`, `type_validation`, `commentaire`, `documents_complets`, `date_modification`, `modificateur`, `annuaire`, `parrains_texte`  , `delegue`, `avis`, `domaines`, `domaines_autres`)
		  VALUES 
		  ('', NOW(),  :annee, :nbr_annees, :personne,:demandeur, :distinction_type, :distinction_type_decision, :type_validation, :commentaire, :documents_complets, NOW(), :modificateur,:annuaire ,:parrains_texte   ,:delegue,:avis,:domaines,:domaines_autres);");
        $this->reqAjout->bindParam(':annee', $this->annee, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':nbr_annees', $this->nbr_annees, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':personne', $this->personne, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':demandeur', $this->demandeur, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':distinction_type', $this->distinction_type, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':distinction_type_decision', $this->distinction_type_decision, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':type_validation', $this->type_validation, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR, 25500);
        $this->reqAjout->bindParam(':documents_complets', $this->documents_complets, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':modificateur', $this->modificateur, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':annuaire', $this->annuaire, PDO::PARAM_INT, 11);
        $this->reqAjout->bindParam(':parrains_texte', $this->parrains_texte, PDO::PARAM_STR, 25500);
        
         $this->reqAjout->bindParam(':delegue', $this->delegue, PDO::PARAM_INT, 11);
         $this->reqAjout->bindParam(':avis', $this->avis, PDO::PARAM_INT, 11);
         $this->reqAjout->bindParam(':domaines', $this->domaines_serialize, PDO::PARAM_STR, 25500);
         $this->reqAjout->bindParam(':domaines_autres', $this->domaines_autres, PDO::PARAM_STR, 25500);
         
         $this->reqAjoutNum = $this->connection->prepare("UPDATE  `distinctions` SET
			  `num_demande` = :num_demande
		  WHERE id = :id_distinction ");
        $this->reqAjoutNum->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        $this->reqAjoutNum->bindParam(':num_demande', $this->num_demande, PDO::PARAM_STR, 12);
        
        // Suppression 
        
        $this->reqSupprimerDistinction = $this->connection->prepare("DELETE FROM `distinctions` WHERE `id` = :id_distinction");
        $this->reqSupprimerDistinction->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Enregistrement des activités
        
        $this->reqActivites = $this->connection->prepare("INSERT INTO  `distinctions_activites` 
		  (`id`, `distinction`, `association`, `fonction`, `annee_debut`, `annee_fin`) 
		  VALUES 
		  ('', :id_distinction,  :association, :fonction, :annee_debut, :annee_fin );");
        $this->reqActivites->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
          $this->reqActivitesAutre = $this->connection->prepare("INSERT INTO  `distinctions_fonctions_autres` 
		  (`id`, `id_activite`, `fonction`) 
		  VALUES 
		  ('', LAST_INSERT_ID() ,  :fonction_autre );");
       
        
        
        // Suppression des anciennes activités
        
        $this->reqDeleteActivites = $this->connection->prepare("DELETE FROM `distinctions_activites` WHERE `distinction` = :id_distinction");
        $this->reqDeleteActivites->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        
        
        
        
        // Enregistrement des parrains
        
        $this->reqParrains = $this->connection->prepare("INSERT INTO  `distinctions_parrains` 
		  (`id`, `distinction`, `personne`) 
		  VALUES 
		  ('', :id_distinction,  :id_personne);");
        $this->reqParrains->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Suppression des anciennes activités
        
        $this->reqDeleteParrains = $this->connection->prepare("DELETE FROM `distinctions_parrains` WHERE `distinction` = :id_distinction");
        $this->reqDeleteParrains->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Enregistrement des documents
        
        $this->reqDocuments = $this->connection->prepare("INSERT INTO  `distinctions_documents` 
		  (`id`, `distinction`, `document`, `nom`) 
		  VALUES 
		  ('', :id_distinction,  :document, :nom);");
        $this->reqDocuments->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Suppression d'un document
        
        $this->reqDeleteDocuments = $this->connection->prepare("DELETE FROM `distinctions_documents` WHERE `distinction` = :id_distinction");
        $this->reqDeleteDocuments->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Enregistrement des distinctions
        
        $this->reqDistinctions = $this->connection->prepare("INSERT INTO  `distinctions_distinctions` 
		  (`id`, `distinction`, `annee`, `distinction_passee`) 
		  VALUES 
		  ('', :id_distinction,  :annee, :distinction_passee);");
        $this->reqDistinctions->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Suppression des anciennes distinctions
        
        $this->reqDeleteDistinctions = $this->connection->prepare("DELETE FROM `distinctions_distinctions` WHERE `distinction` = :id_distinction");
        $this->reqDeleteDistinctions->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        // Chargement  
        
        $this->reqCharge = $this->connection->prepare('
		SELECT 
			dt1.nom AS distinction_type_label,
			dt1.code AS distinction_code, 
			dt2.nom AS distinction_type_decision_label, 
			distinctions.date_demande, 
			distinctions.annee, 
			distinctions.nbr_annees, 
			distinctions.personne, 
			distinctions.demandeur, 
			distinctions.distinction_type, 
			distinctions.distinction_type_decision, 
			distinctions.acceptation_conditions, 
			distinctions.type_validation, 
			distinctions.commentaire, 
			distinctions.documents_complets, 
			distinctions.date_modification, 
			distinctions.modificateur,
			distinctions.num_demande,
			
			distinctions.delegue,
			distinctions.avis,
			distinctions.domaines,
			distinctions.domaines_autres
		FROM distinctions 
			LEFT OUTER JOIN distinctions_types dt1 ON distinctions.distinction_type = dt1.id
			LEFT OUTER JOIN distinctions_types_decisions dt2 ON distinctions.distinction_type_decision = dt2.id
		WHERE distinctions.id = :id_distinction
		');
        $this->reqCharge->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
  
        $this->reqChargeActivites = $this->connection->prepare('
		SELECT distinctions_activites.id, 
			distinctions_activites.association, 
			distinctions_activites.fonction, 
			distinctions_activites.annee_debut, 
			distinctions_activites.annee_fin, 
			distinctions_fonctions_autres.fonction AS fonction_autre, 
			cons_admin_fonctions.nom AS fonction_label
		FROM distinctions_activites 
			LEFT JOIN cons_admin_fonctions ON distinctions_activites.fonction = cons_admin_fonctions.id
			LEFT JOIN cons_admin_fonctions ON distinctions_activites.fonction = cons_admin_fonctions.id
		WHERE distinctions_activites.distinction = :id_distinction AND distinctions_activites.annee_fin IS NULL
		');
        $this->reqChargeActivites->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        $this->reqChargeActivitesPassees = $this->connection->prepare('
		SELECT distinctions_activites.id, 
			distinctions_activites.association, 
			distinctions_activites.fonction, 
			distinctions_activites.annee_debut, 
			distinctions_activites.annee_fin, 
			distinctions_fonctions_autres.fonction AS fonction_autre, 
			cons_admin_fonctions.nom AS fonction_label
		FROM distinctions_activites 
			LEFT JOIN cons_admin_fonctions ON distinctions_activites.fonction = cons_admin_fonctions.id
			LEFT JOIN distinctions_fonctions_autres ON distinctions_fonctions_autres.id_activite =  distinctions_activites.id
		WHERE distinctions_activites.distinction = :id_distinction AND distinctions_activites.annee_fin IS NOT NULL
		');
        $this->reqChargeActivitesPassees->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        $this->reqChargeParrains = $this->connection->prepare('
		SELECT *
		FROM distinctions_parrains 
		WHERE distinctions_parrains.distinction = :id_distinction');
        $this->reqChargeParrains->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        $this->reqChargeDocuments = $this->connection->prepare('
		SELECT *
		FROM distinctions_documents 
		WHERE distinctions_documents.distinction = :id_distinction');
        $this->reqChargeDocuments->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        $this->reqChargeDistinctions = $this->connection->prepare('
		SELECT *
		FROM distinctions_distinctions, distinctions_types 
		WHERE distinctions_types.id = distinctions_distinctions.distinction_passee AND  distinctions_distinctions.distinction = :id_distinction');
        $this->reqChargeDistinctions->bindParam(':id_distinction', $this->id_distinction, PDO::PARAM_INT, 11);
        
        
        if ($id > 0) {
            $this->id_distinction = $id;
            $this->charge();
        }
    }
    
    
    
    // Récupère 
    public function charge() {
            // Chargement des informations de base
            try {
                $this->reqCharge->execute();
                while ($enregistrement = $this->reqCharge->fetch(PDO::FETCH_OBJ)) {
                    
                    foreach ($enregistrement as $cle => $val) {
                        if (property_exists('distinction', $cle)) {
                            $this->{$cle} = $val;
                        }
                    }
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            // Restauration domaines
            $this->domaines = unserialize($this->domaines);
            $avis = selectValeur('distinctions_avis','id',$this->avis);
            $this->distinction_avis_label =  (isset($avis->nom) ? $avis->nom : '');
            
            // Chargement des activites
            try {
                $this->reqChargeActivites->execute();
                while ($enregistrement = $this->reqChargeActivites->fetch(PDO::FETCH_OBJ)) {
                    $this->activites[$enregistrement->id]['association']    = $enregistrement->association;
                    $this->activites[$enregistrement->id]['fonction']       = $enregistrement->fonction;
                     $this->activites_passees[$enregistrement->id]['fonction_autre']       = $enregistrement->fonction_autre;
                    $this->activites[$enregistrement->id]['fonction_label'] = $enregistrement->fonction_label;
                    $this->activites[$enregistrement->id]['annee_debut']    = $enregistrement->annee_debut;
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            // Chargement des activites passées
            try {
                $this->reqChargeActivitesPassees->execute();
                while ($enregistrement = $this->reqChargeActivitesPassees->fetch(PDO::FETCH_OBJ)) {
                    $this->activites_passees[$enregistrement->id]['association']    = $enregistrement->association;
                    $this->activites_passees[$enregistrement->id]['fonction']       = $enregistrement->fonction;
                    $this->activites_passees[$enregistrement->id]['fonction_autre']       = $enregistrement->fonction_autre;
                    $this->activites_passees[$enregistrement->id]['fonction_label'] = $enregistrement->fonction_label;
                    $this->activites_passees[$enregistrement->id]['annee_debut']    = $enregistrement->annee_debut;
                    $this->activites_passees[$enregistrement->id]['annee_fin']      = $enregistrement->annee_fin;
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            
            // Chargement des parrains
            try {
                $this->reqChargeParrains->execute();
                while ($enregistrement = $this->reqChargeParrains->fetch(PDO::FETCH_OBJ)) {
                    $this->parrains[$enregistrement->id] = $enregistrement->personne;
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            // Chargement des documents
            try {
                $this->reqChargeDocuments->execute();
                while ($enregistrement = $this->reqChargeDocuments->fetch(PDO::FETCH_OBJ)) {
                    $this->documents[$enregistrement->id]['document'] = $enregistrement->document;
                    $this->documents[$enregistrement->id]['nom']      = $enregistrement->nom;
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
            
            
            // Chargement des distinctions
            try {
                $this->reqChargeDistinctions->execute();
                while ($enregistrement = $this->reqChargeDistinctions->fetch(PDO::FETCH_OBJ)) {
                    $this->distinctions[$enregistrement->id]['annee']              = $enregistrement->annee;
                    $this->distinctions[$enregistrement->id]['distinction_passee'] = $enregistrement->nom;
                }
            }
            catch (Exception $e) {
                echo "Erreur d'enregistrement : ", $e->getMessage();
            }
        
    }
    
    // Enregistrements
    public function sauve()
    { 
        // Conversion
        $this->date_saisie = convertDate($this->date_saisie);
      	$this->domaines_serialize = serialize($this->domaines);
      	
        $retour = '';

        // Ajout
        if (empty($this->id_distinction)) {
            
            if (isGestion()) {
                $this->modificateur = $_SESSION['utilisateur']['id'];
                $this->demandeur    = $_SESSION['utilisateur']['id'];
            }
            
            try {
                
                $resultat = $this->reqAjout->execute();
                
                if ($resultat === true) {
                    
                    $this->id_distinction = $this->connection->lastInsertId('id');
                    
                    $this->num_demande = $this->annee.$this->distinction_code.$this->id_distinction;
                    try {
                    	$this->reqAjoutNum->execute();
                    }
                    catch (Exception $e) {
                		return "Erreur d'enregistrement : " . $e->getMessage();
            		}
                    
                    // Enregistrements annexes
                    if (count($this->activites) > 0)
                        $retour = $this->sauveActivites();
                    if (count($this->activites_passees) > 0)
                        $retour = $this->sauveActivites();
                    if (count($this->parrains) > 0)
                        $retour = $this->sauveParrains();
                    if (count($this->distinctions) > 0)
                        $retour = $this->sauveDistinctions();
                    if (count($this->documents) > 0)
                        $retour = $this->sauveDocuments();
                    
                    return $resultat;
                    
                } else
                    return $this->reqAjout->errorInfo();
                
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
        }
        
        // Modification
        else {
            try {
                
                $resultat = $this->reqModifie->execute();
                
                if ($resultat === true) {
                    
                    // Enregistrements annexes
                    if (count($this->activites) > 0)
                        $retour = $this->sauveActivites();
                    if (count($this->activites_passees) > 0)
                        $retour = $this->sauveActivites();
                    if (count($this->parrains) > 0)
                        $retour = $this->sauveParrains();
                    if (count($this->distinctions) > 0)
                        $retour = $this->sauveDistinctions();
                    if (count($this->documents) > 0)
                        $retour = $this->sauveDocuments();
                    
                    return $retour;
                    
                } else
                    print_r($this->reqModifie->errorInfo());
                
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
            
        
        }
    }
    
    public function sauveActivites()
    {
        $this->reqDeleteActivites->execute();
        
        
        if(!empty($this->activites)) {
          foreach ($this->activites as $val) {
            try {
                if (!empty($val['fonction_autre'])) $val['fonction']=35;

                $this->reqActivites->bindValue(':association', $val['association'], PDO::PARAM_INT);
                $this->reqActivites->bindValue(':fonction', $val['fonction'], PDO::PARAM_INT);
                $this->reqActivites->bindValue(':annee_debut', $val['annee_debut'], PDO::PARAM_INT);
                $this->reqActivites->bindValue(':annee_fin', NULL, PDO::PARAM_INT);
                $resultat = $this->reqActivites->execute();
                
                $this->reqActivitesAutre->bindValue(':fonction_autre', $val['fonction_autre'], PDO::PARAM_INT);
               // $this->reqActivitesAutre->bindValue(':id_activite', $this->reqActivites->lastInsertId() , PDO::PARAM_INT);
            	$resultat = $this->reqActivitesAutre->execute();
            	
            
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
        }
        }
        if(!empty($this->activites_passees)) {
			foreach ($this->activites_passees as $val) {
				try {
					if (!empty($val['fonction_autre'])) $val['fonction']=35;
					
					$this->reqActivites->bindValue(':association', $val['association'], PDO::PARAM_INT);
					$this->reqActivites->bindValue(':fonction', $val['fonction'], PDO::PARAM_INT);
					$this->reqActivites->bindValue(':annee_debut', $val['annee_debut'], PDO::PARAM_INT);
					$this->reqActivites->bindValue(':annee_fin', $val['annee_fin'], PDO::PARAM_INT);
					$resultat = $this->reqActivites->execute();
					
				    $this->reqActivitesAutre->bindValue(':fonction_autre', $val['fonction_autre'], PDO::PARAM_INT);
                	//$this->reqActivitesAutre->bindValue(':id_activite', $this->reqActivites->lastInsertId() , PDO::PARAM_INT);
            		$resultat = $this->reqActivitesAutre->execute();
            		
            		
				}
				catch (Exception $e) {
					return "Erreur d'enregistrement : " . $e->getMessage();
				}
			}
        }
        return "Enregistrement terminé";
    }
    
    public function sauveParrains()
    {
        $this->reqDeleteParrains->execute();
       
        // Si !gestion, création des personnes
        if (isGestion()) {
        }
        
        foreach ($this->parrains as $val) {
            try {
                $this->reqParrains->bindValue(':id_personne', $val, PDO::PARAM_INT);
                $resultat = $this->reqParrains->execute();
                
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
            
        }
    }
    
    public function sauveDistinctions()
    {
        $this->reqDeleteDistinctions->execute();
        
        foreach ($this->distinctions as $val) {
            try {
                $this->reqDistinctions->bindValue(':annee', $val['annee'], PDO::PARAM_INT);
                $this->reqDistinctions->bindValue(':distinction_passee', $val['distinction'], PDO::PARAM_INT);
                $resultat = $this->reqDistinctions->execute();
                
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
        }
    }
    
    public function sauveDocuments()
    {
        $this->reqDeleteDocuments->execute();
        
        foreach ($this->documents as $val) {
            try {
                $this->reqDocuments->bindValue(':document', $val['filename'], PDO::PARAM_INT);
                $this->reqDocuments->bindValue(':nom', $val['nom'], PDO::PARAM_INT);
                $resultat = $this->reqDocuments->execute();
                
            }
            catch (Exception $e) {
                return "Erreur d'enregistrement : " . $e->getMessage();
            }
        }
    }
    
    public function supprime()
    {
        $this->reqSupprimerDistinction->execute();
        $this->reqDeleteActivites->execute();
        $this->reqDeleteDocuments->execute();
        $this->reqDeleteDistinctions->execute();
        $this->reqDeleteParrains->execute();
    }
    
    public function affActivitesPassees()
    {
        
        // Affichage des activités passées
        
        $aff = '';
        
        if (count($this->activites_passees) > 0) {
            foreach ($this->activites_passees as $cle => $val) {
            	if (!empty ($val['annee_fin']) ) {
            		$annee = ($val['annee_fin'] - $val['annee_debut']);
            		$an_fin =  $val['annee_fin'];
            	}
            	else {
            		$annee = ( date(Y) - $val['annee_debut']);
					$an_fin = '<em>En cours</em>';
            	}
            	
            	if (!empty( $val['fonction_autre'] )) $fonction = $val['fonction_autre'];
            	else $fonction = $val['fonction_label'];
                $aff .= '<tr><td>' . $val['association'] . '</td><td>' . $fonction . '</td><td>' . $val['annee_debut'] . '</td><td>' .$an_fin . '</td><td>' . $annee . '</td></tr>';
            }
        }
        
        return $aff;
    }
    
    public function affActivites()
    {
        
        // Affichage des activités 
        
        $aff = '';
        
        if (count($this->activites) > 0) {
            foreach ($this->activites as $cle => $val) {
            	if (!empty( $val['fonction_autre'] )) $fonction = $val['fonction_autre'];
            	else $fonction = $val['fonction_label'];
                $aff .= '<tr><td>' . $val['association'] . '</td><td>' . $fonction . '</td><td>' . $val['annee_debut'] . '</td><td></td><td>' . (date('Y') - $val['annee_debut']) . '</td></tr>';
            }
        }
        
        return $aff;
    }
    
     public function affDomaines()
    {
        
        // Affichage des activités 
        
        $aff = '';
        
        if (count($this->domaines) > 0) {
            foreach ($this->domaines as $cle => $val) {
            	$domaine = selectValeur('distinctions_domaines','id',$val);
               	$aff.= $domaine->nom.', ';
            }    	
        }
        $aff.= $this->domaines_autres;
        
        return $aff;
    }
    
    
    public function affDistinctions()
    {
        
        $aff = '';
        
        if (count($this->distinctions) > 0) {
            foreach ($this->distinctions as $cle => $val) {
                $aff .= '<tr><td>' . $val['distinction_passee'] . '</td><td>' . $val['annee'] . '</td></tr>';
            }
        }
        
        return $aff;
    }
    
    public function affDocuments()
    {
        
        $aff = '';
        
        if (count($this->documents) > 0) {
            foreach ($this->documents as $cle => $val) {
                $parrain = new personne($val);
                $aff .= '<tr>';
                $aff .= '<td><a href="/../../upload/distinctions/' . trim($val['document']). '" target="_blank" class="">';
                if(!empty($val['nom'] )) $aff .= $val['nom'] ;
                else $aff .= $val['document'] ;
                $aff .= '</a></td>';
                $aff .= '<td>';
                //$aff .= '<button form-action="download" form-element="'.$_SESSION['WEBROOT'] . '/../uploads/distinctions/' . $val['document'] . '" class="right telecharger action"></button> ';
                
                 $aff .= '<a href="/../../upload/distinctions/' . trim($val['document']). '" target="_blank" class="icon-telecharger right telecharger action">&nbsp;</a> ';
                $aff .= '</td>';
            	$aff .= '</tr>';
            	
            }
        } else $aff = 'Aucun documents associés à cette demande de distinction.';
        
        return $aff;
    }
    
    public function affParains()
    {
        
        $aff = '';
        
        if (count($this->parrains) > 0) {
            foreach ($this->parrains as $cle => $val) {
                $parrain = new personne($val);
                $aff .= '<tr>';
                $aff .= '<td>'.$parrain->prenom . ' ' . $parrain->nom .'</td>';
                $aff .= '<td>';
                $aff .= '<button form-action="lien" form-element="/personnes/detail/'.$parrain->id_personne.'" class="right details action"></button>';
            	$aff .= '<button form-action="mail" form-element="'.$parrain->courriel.'" class="right envoyer action"></button> ';
            	$aff .= '</td>';
            	$aff .= '</tr>';
            }
        }
        
        return $aff;
    }
    
    
    public function affHeader()
    {
        
        $aff = '';
        
        if ($this->distinction_type_decision == 0) {
            $alerte           = 'alerte';
            $distinctionLabel = 'Demande : ' . $this->distinction_type_label;
        } 
        else if ($this->distinction_type_decision == 10) {
            $alerte           = 'alerte';
            $distinctionLabel = 'Refusée : ' . $this->distinction_type_label;
        } else {
            $alerte           = '';
            $distinctionLabel = $this->distinction_type_decision_label;
        	if ($this->distinction_type_decision != $this->distinction_type) $demande = '<em>Demande initiale : ' . $this->distinction_type_label.'</em>';
        }
        
        $aff = '<h1 class="' . $alerte . '">' . $distinctionLabel . ' ' . $this->annee . '</h1>';
        $aff .= '<h3>Numéro  ' .$this->num_demande.'</h3>';
        $aff .= (isset($demande) ? $demande : '');
        
        return $aff;
    }
    
    public function affDecision()
    {
        
        $aff = '';
        
      	if ($this->distinction_type_decision == 0) {
            $alerte           = 'alerte';
            $distinctionLabel = 'Demande : ' . $this->distinction_type_label;
        } 
        else if ($this->distinction_type_decision == 10) {
            $alerte           = 'alerte';
            $distinctionLabel = 'Refusée : ' . $this->distinction_type_label;
        } else {
            $alerte           = '';
            $distinctionLabel = $this->distinction_type_decision_label;
        	if ($this->distinction_type_decision != $this->distinction_type) $demande = '<em>Demande initiale : ' . $this->distinction_type_label.'</em>';
        }
        
        $aff = '<h3 class="' . $alerte . '">' . $distinctionLabel . ' ' . $this->annee . '</h3>';
        $aff .= $demande.'<bR>';
        $aff .= '<h3 >Avis du délégué : '.$this->distinction_avis_label.'</h3>';
         
      
        if ($this->documents_complets == 0) $aff .= '<h3 class="alerte">Documents : Incomplets</h3><br/>';
        else  $aff .= '<h3>Documents : Complets</h3><br/>';
        $aff .= '<br/><strong>Commentaires : </strong><br/>'.$this->commentaire;
        
        return $aff;
    }
}
?>