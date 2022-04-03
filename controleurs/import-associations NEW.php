<section>
<div id="conteneur" class="associations">
<article class="associations">
<h2><span class="icon-associations"></span> Import des associations</h2>
<?php

//exit();
/*

Table ASSOC

type : lié à la table catégories
1 = association
2 = collectivité
5 = Association la banque postale
6 = amis de la fondtaion

president :
semble lié à adh_assoc

connex 

actif : assos actif ou non 
o / n

connex : nombre de connexions

téléphone : remplace . par espace

etat : est-ce utile de le conserver ?




*/

// Vide les tables existantes
$vide = $connect->prepare('TRUNCATE associations;');	
$vide->execute();

$vide = $connect->prepare('TRUNCATE associations_activites;');	
$vide->execute();


$vide = $connect->prepare('TRUNCATE laf_adhesions_associations;');	
$vide->execute();


 	
				 
				 
/*
// Pré traitement
$reqAssoc = $connect->prepare('SELECT * FROM assoc ORDER BY id ASC');
try {
  $reqAssoc->execute();
   
  // Traitement
  while( $asso = $reqAssoc->fetch(PDO::FETCH_OBJ)){
  	if (strtoupper($asso->ville) == 'LA GARENNE COLOMBES' ) edit_asso($asso->id,'ville','LA GARENNE-COLOMBES');
  	
  }
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}
*/
require('libs/import.php');

// Associations
$i=1;
$ok = 0;

$reqAjout = $connect->prepare("INSERT INTO  `associations` 
			  (`id`, `association_type`, `numero_adherent`,`nom`, `nom_soundex`, `sigle`, `date_declaration_jo`, `numero_siret`, `numero_dossier`, `numero_convention`, `code_ape_naf`, `ville` , `cedex`, `adresse`, `telephone_fixe`, `telephone_mobile`, `fax`, `courriel`, `mdp`,  `banque_postale`, `logo`, `date_creation`, `date_modification`, `modificateur`) 
			  VALUES 
			  (:id, :association_type,  :numero_adherent, :nom,:nom_soundex, :sigle, :date_declaration_jo, :numero_siret, :numero_dossier, :numero_convention, :code_ape_naf, :ville, :cedex, :adresse, :telephone_fixe, :telephone_mobile, :fax, :courriel, :mdp,  :banque_postale, :logo, :date_creation, CURRENT_TIMESTAMP, :modificateur);");

$reqActivites = $connect->prepare("INSERT INTO  `associations_activites` 
			  (`id`, `association`, `activite`) 
			  VALUES 
			  ('', :association,  :activite);");
			  
$reqPresident = $connect->prepare("INSERT INTO  `import_president` 
			  (`id`, `president`) 
			  VALUES 
			  (:association,  :president);");
	
$reqAjoutLaf = $connect->prepare("INSERT INTO  `laf_adhesions_associations` 
			  (`id`, `association`, `date`,`annee`, `montant`, `assurance_gmf`, `logiciel_citizenplace`, `aide_citizenplace`, `assurance_groupama`, `acces_info_banque_postale`, `nbr_adherents`, `nbr_salaries` , `budget_fonctionnement`, `acceptation_conditions`, `paiement`, `etat_paiement`, `type_paiement`, `date_paiement`, `commande`) 
			  VALUES 
			  (:id, :association,  :date, :annee,:montant, :assurance_gmf, :logiciel_citizenplace, :aide_citizenplace, :assurance_groupama, :acces_info_banque_postale, :nbr_adherents, :nbr_salaries, :budget_fonctionnement, :acceptation_conditions, :paiement, :etat_paiement, :type_paiement, :date_paiement, :commande);");


$reqAssoc = $connect->prepare('SELECT * FROM associations_import ORDER BY ville ASC');

$erreurs[0] = array('id','association','ville','code postal','erreur');


$reqAjout->bindValue(':id', 1);
	 $reqAjout->bindValue(':association_type', 1);
	 $reqAjout->bindValue(':numero_adherent', 1);
	 $reqAjout->bindValue(':nom', 'Le cercle des Bénévoles');
	 $reqAjout->bindValue(':nom_soundex', '');
	 $reqAjout->bindValue(':sigle','');
	 $reqAjout->bindValue(':date_declaration_jo','');
	 $reqAjout->bindValue(':numero_siret', '');
	 $reqAjout->bindValue(':numero_dossier', '');
	 $reqAjout->bindValue(':numero_convention', '');
	 $reqAjout->bindValue(':code_ape_naf','');
	 $reqAjout->bindValue(':ville', '');
	 $reqAjout->bindValue(':cedex', '');
	 $reqAjout->bindValue(':adresse', '');
	 $reqAjout->bindValue(':telephone_fixe', '');
	 $reqAjout->bindValue(':telephone_mobile', '');
	 $reqAjout->bindValue(':fax','');
	 $reqAjout->bindValue(':courriel','');
	 $reqAjout->bindValue(':mdp','');
	 $reqAjout->bindValue(':banque_postale','');
	 $reqAjout->bindValue(':logo', '');
	 $reqAjout->bindValue(':modificateur', '');
	 $reqAjout->bindValue(':date_creation', '');
	 $resultat = $reqAjout->execute();	
	 
	 
try {
  $reqAssoc->execute();
   
  // Traitement
  while( $asso = $reqAssoc->fetch(PDO::FETCH_OBJ)){
   	if (($asso->cotisation2013 == 1) || ($asso->cotisation2014 == 1) || ($perso->cotisation2015 == 1)) { 
		if ( (!empty($asso->ville)) || (!empty($asso->code_postal)) ) {
	
			// Init
			$isCedex = false;
			$cedex=array();
			$erreur = '';
		
		
		
			// Clean ville
			$ville = traite_nom_ville($asso->ville,traite_cp($asso->code_postal));
			$cp = traite_cp($asso->code_postal,$asso->id);
   
			// Test CEDEX
			$isCedex = strpos($ville, '-CEDEX');
			$isCedex2 = strpos(strtoupper($cp), 'CEDEX');
			$isCedex3 = strpos(strtoupper($asso->adresse), 'CEDEX');
			if ($isCedex !== false) {
				$cedex[0] = substr($ville,$isCedex+7);
				$cedex[1] = $cp;
				$ville = substr($ville,0,$isCedex);
				$reqCP = '  ';
			} else if ($isCedex2 !== false) {
				$cedex[0] = trim(substr($cp,$isCedex2+5));
				$cedex[1] = trim($cp);
				$reqCP = '  ';
			} else if ($isCedex3 !== false) {
				$cedex[0] = trim(substr($asso->adresse,$isCedex3+5));
				$cedex[1] = trim($cp);
				$reqCP = '  ';
			} else $reqCP = ' AND code_postal LIKE "%'.$cp.'%" ';
	
			// Test villes à arrondissements
			if ((substr($ville,0,5) == 'PARIS') && (substr($cp,0,2) =='75' ))  $reqVilles = ' nom LIKE "PARIS%" ';
			else if ((substr($ville,0,9) == 'MARSEILLE') && (substr($cp,0,2) =='13' ))   $reqVilles = ' nom LIKE "MARSEILLE%" ';
			else if ((substr($ville,0,4) == 'LYON') && (substr($cp,0,2) =='69' ))   $reqVilles = ' nom LIKE "LYON%" ';
			else $reqVilles = ' nom = "'.$ville.'" ';
 
			// Recherche de la ville
			$req = 'SELECT * FROM villes WHERE'. $reqVilles .$reqCP;
   
			$reqVille = $connect->prepare($req);
			try {
				$reqVille->execute();
				$count = $reqVille->rowCount();
		
				if ($count == 1) {
					$resultVille = $reqVille->fetch(PDO::FETCH_OBJ);
			
					$newAsso = new stdClass;
					$newAsso->id = $asso->id;
					 $newAsso->association_type = 1;

					$newAsso->numero_adherent = '';
					$newAsso->nom = trim(traite_nom($asso->nom));
					$newAsso->nom_soundex = phonetique(traite_nom($asso->nom));
					$newAsso->sigle = trim($asso->sigle);
					$newAsso->date_declaration_jo = $asso->date;
					$newAsso->numero_siret = trim($asso->siret);
					$newAsso->numero_dossier = '';
					$newAsso->numero_convention = '';
					$newAsso->code_ape_naf = '';
					$newAsso->ville = $resultVille->id;
					if(count($cedex)>0) $newAsso->cedex = serialize($cedex);
					else $newAsso->cedex='';
					$newAsso->adresse = trim($asso->adresse);
					if (!empty($asso->adresse2))  $newAsso->adresse .="\n". trim($asso->adresse2);
					$newAsso->telephone_fixe = trim($asso->telephone);
					$newAsso->telephone_mobile ='';
					$newAsso->fax = trim($asso->fax);
					$newAsso->courriel = trim($asso->mail);
					$newAsso->mdp = '';
					$newAsso->banque_postale = 0;
					$newAsso->modificateur = 1;
					$newAsso->date_creation = $asso->date_saisie;
			
					//d($newAsso);
		
					 $reqAjout->bindValue(':id', $newAsso->id);
					 $reqAjout->bindValue(':association_type', $newAsso->association_type);
					 $reqAjout->bindValue(':numero_adherent', $newAsso->numero_adherent);
					 $reqAjout->bindValue(':nom', $newAsso->nom);
					 $reqAjout->bindValue(':nom_soundex', $newAsso->nom_soundex);
					 $reqAjout->bindValue(':sigle', $newAsso->sigle);
					 $reqAjout->bindValue(':date_declaration_jo', $newAsso->date_declaration_jo);
					 $reqAjout->bindValue(':numero_siret', $newAsso->numero_siret);
					 $reqAjout->bindValue(':numero_dossier', $newAsso->numero_dossier);
					 $reqAjout->bindValue(':numero_convention', $newAsso->numero_convention);
					 $reqAjout->bindValue(':code_ape_naf', $newAsso->code_ape_naf);
					 $reqAjout->bindValue(':ville', $newAsso->ville);
					 $reqAjout->bindValue(':cedex', $newAsso->cedex);
					 $reqAjout->bindValue(':adresse', $newAsso->adresse);
					 $reqAjout->bindValue(':telephone_fixe', $newAsso->telephone_fixe);
					 $reqAjout->bindValue(':telephone_mobile', $newAsso->telephone_mobile);
					 $reqAjout->bindValue(':fax', $newAsso->fax);
					 $reqAjout->bindValue(':courriel', $newAsso->courriel);
					 $reqAjout->bindValue(':mdp', $newAsso->mdp);
					 $reqAjout->bindValue(':banque_postale', $newAsso->banque_postale);
					 $reqAjout->bindValue(':logo', $newAsso->logo);
					 $reqAjout->bindValue(':modificateur', $newAsso->modificateur);
					 $reqAjout->bindValue(':date_creation', $newAsso->date_creation);
					 $resultat = $reqAjout->execute();	
				
					 if (!$resultat) $erreur = 'Erreur d\'enregistrement';
					 else $ok++;
			
					// Activité
					for ($i=1;$i<=3;$i++) {
						$nomActivite = 'id_activite'.$i;
					
			
						$reqActivites->bindValue(':association', $newAsso->id);
						$reqActivites->bindValue(':activite', $asso->{$nomActivite});
						$resultat2 = $reqActivites->execute();	
			
					}
				
				
					// Sauvegarde temporaire du président
					/*
					if (!empty($asso->president)) {
						$reqPresident->bindValue(':association', $newAsso->id);
						$reqPresident->bindValue(':president', $asso->president);
						$resultat3 = $reqPresident->execute();				
					}
					*/
				
				
				
			
				} else if ($count == 0){
					$erreur = 'Correspondance Ville / Codepostal';
					if (substr($asso->code_postal,4) != '0') $erreur .= ' / Manque Cedex ?';
					echo $asso->id.' - '.$asso->nom.'<br>';
					echo 	$asso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
				} else if ($count > 1) {
					$erreur = 'Correspondance Ville / Codepostal';
					echo 'PROBLEME PLUSIEURS CORRESPONDANCE<br>';
					echo $asso->id.' - '.$asso->nom.'<br>';
					echo 	$asso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
				}
			
			
				// Parcours de associations
					for ($i=2013;$i<=2016;$i++) {
						$nomAsso = 'cotisation'.$i;
			
			
						if ($asso->{$nomAsso} == 1) { 
						
						
							$reqAjoutLaf->bindValue(':id', ''); 
							$reqAjoutLaf->bindValue(':association', $newAsso->id);
							$reqAjoutLaf->bindValue(':date', $i.'-01-01');
							$reqAjoutLaf->bindValue(':annee', $i);
							$reqAjoutLaf->bindValue(':montant', $asso->prix_cotisation);
							$reqAjoutLaf->bindValue(':assurance_gmf', $asso->service12);
							$reqAjoutLaf->bindValue(':logiciel_citizenplace', $asso->service3);
							$reqAjoutLaf->bindValue(':aide_citizenplace', $asso->service4);
							$reqAjoutLaf->bindValue(':assurance_groupama', $asso->service5);
							$reqAjoutLaf->bindValue(':acces_info_banque_postale', $asso->service6);
							$reqAjoutLaf->bindValue(':nbr_adherents', $asso->nb_adherents);
							$reqAjoutLaf->bindValue(':nbr_salaries', $asso->nb_salaries);
							$reqAjoutLaf->bindValue(':budget_fonctionnement', $asso->budget);
							$reqAjoutLaf->bindValue(':acceptation_conditions', 1);
						
							$reqAjoutLaf->bindValue(':paiement', 0);
							$reqAjoutLaf->bindValue(':etat_paiement', 'success');
							$reqAjoutLaf->bindValue(':type_paiement', 1);
							$reqAjoutLaf->bindValue(':date_paiement', $i.'-01-01');
							$reqAjoutLaf->bindValue(':commande', '');
			
							$resultat3 = $reqAjoutLaf->execute();	
							if (!$resultat3) {
								$err =  $reqAjoutLaf->errorInfo();
								echo $newAsso->id.' - '.$i.' / '.$err[2].'<br>';
							}
						}
					}
				
				
				if(!empty($erreur)) $erreurs[$i] = array($asso->id, $asso->nom,$asso->ville,$asso->code_postal,$erreur);
			
			} catch( Exception $e ){
			  echo 'Erreur de suppression : ', $e->getMessage();
			}

		  }
	  else $erreurs[$i] = array($asso->id, $asso->nom,$asso->ville,$asso->code_postal,'Ville ou code postal absent');
  
	  $i++;
	  //if ($i==500) break;
	  }
  }
  
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}



$chemin = $_SESSION['ROOT'].'/erreurs.csv';
$delimiteur = ';';
$fichier_csv = fopen($chemin, 'w+');
fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
foreach($erreurs as $ligne){
	fputcsv($fichier_csv, $ligne, $delimiteur);
}
fclose($fichier_csv);

echo $i.' Villes<br>';
echo $ok.' Enregistrements<br>';
echo '<a href="/erreurs.csv">'.count($erreurs).' Erreurs</a>';

/*
$vide = $connect->prepare('SELECT * FROM personnes_associations INNER JOIN associations ON personnes_associations.association = associations.id WHERE personnes_associations.id =  :id ');	
$vide->bindValue(':id', $form->id_lien, PDO::PARAM_INT);
$vide->execute();
*/

?>
</article>
</div>
</span>