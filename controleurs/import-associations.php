<section>
<div id="conteneur" class="associations">
<article class="associations">
<h2><span class="icon-associations"></span> Import des associations</h2>
<?php

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
/*
// Vide les tables existantes
$vide = $connect->prepare('TRUNCATE associations;');	
$vide->execute();

$vide = $connect->prepare('TRUNCATE associations_activites;');	
$vide->execute();

$vide = $connect->prepare('TRUNCATE import_president;');	
$vide->execute();
*/

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
	

$reqAssoc = $connect->prepare('SELECT * FROM assoc_import ORDER BY ville ASC');

$erreurs[0] = array('id','association','ville','code postal','erreur');

try {
  $reqAssoc->execute();
 
  // Traitement
  while( $asso = $reqAssoc->fetch(PDO::FETCH_OBJ)){
   
	if ( (!empty($asso->ville)) || (!empty($asso->codepostal)) ) {
   	
		// Init
		$isCedex = false;
		$cedex=array();
		$erreur = '';
		
		// Clean ville
		$ville = traite_nom_ville($asso->ville,traite_cp($asso->codepostal));
		$cp = traite_cp($asso->codepostal,$asso->id);
   
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
		$req = 'SELECT * FROM villes WHERE '. $reqVilles .$reqCP;
  	
		$reqVille = $connect->prepare($req);
		try {
			$reqVille->execute();
			$count = $reqVille->rowCount();
		
			if ($count == 1) {
				$resultVille = $reqVille->fetch(PDO::FETCH_OBJ);
			
				$newAsso = new stdClass;
				$newAsso->id = $asso->id;
				if ($asso->type == 1) $newAsso->association_type = 1;
				if ($asso->type == 0) $newAsso->association_type = 1;
				if ($asso->type == 6) $newAsso->association_type = 1;
				if ($asso->type == 5) $newAsso->association_type = 1;
				if ($asso->type == 2) $newAsso->association_type = 2;

				$newAsso->numero_adherent = '';
				$newAsso->nom = trim(traite_nom($asso->nom));
				$newAsso->nom_soundex = phonetique(traite_nom($asso->nom));
				$newAsso->sigle = trim($asso->sigle);
				$newAsso->date_declaration_jo = $asso->jo;
				$newAsso->numero_siret = trim($asso->siret);
				$newAsso->numero_dossier = '';
				$newAsso->numero_convention = '';
				$newAsso->code_ape_naf = trim($asso->ape);
				$newAsso->ville = $resultVille->id;
				if(count($cedex)>0) $newAsso->cedex = serialize($cedex);
				else $newAsso->cedex='';
				$newAsso->adresse = trim($asso->adresse);
				if (!empty($asso->adresse2))  $newAsso->adresse .="\n". trim($asso->adresse2);
				$newAsso->telephone_fixe = trim($asso->tel);
				$newAsso->telephone_mobile ='';
				$newAsso->fax = '';
				$newAsso->courriel = trim($asso->email);
				$newAsso->mdp = $asso->passw;
				if ($asso->type == 5) $newAsso->banque_postale = 1;
				$newAsso->banque_postale = 0;
				$newAsso->modificateur = 1;
				$newAsso->date_creation = $asso->heure_decla;
			
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
			 
			
				// Activité
				switch ($asso->activite) {
					case 'Autre':
						 $newAsso->activite=1;
					break;
					case 'Cultuelle':
						$newAsso->activite=3;
					break;
					case 'Culturelle':
						 $newAsso->activite=2;
					break;
					case 'Education':
						 $newAsso->activite=4;
					break;
					case 'Environnement':
						 $newAsso->activite=5;
					break;
					case 'Humanitaire':
						 $newAsso->activite=6;
					break;
					case 'Loisirs':
						 $newAsso->activite=7;
					break;
					case 'Militaire':
						 $newAsso->activite=8;
					break;
					case 'Sociale / Santé':
						 $newAsso->activite=9;
					break;
					case 'Sportive':
						 $newAsso->activite=10;
					break;	
				}
			
				// Sauvegarde des activités
				if (!empty($newAsso->activite)) {
					$reqActivites->bindValue(':association', $newAsso->id);
					$reqActivites->bindValue(':activite', $newAsso->activite);
					$resultat2 = $reqActivites->execute();	
				}
			
				// Sauvegarde temporaire du président
				if (!empty($asso->president)) {
					$reqPresident->bindValue(':association', $newAsso->id);
					$reqPresident->bindValue(':president', $asso->president);
					$resultat3 = $reqPresident->execute();				
				}
			
				 if (!$resultat) $erreur = 'Erreur d\'enregistrement';
			 	 else $ok++;
			
			} else if ($count == 0){
				$erreur = 'Correspondance Ville / Codepostal';
				if (substr($asso->codepostal,4) != '0') $erreur .= ' / Manque Cedex ?';
				//echo $asso->id.' - '.$asso->nom.'<br>';
				//echo 	$asso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
			} else if ($count > 1) {
				$erreur = 'Correspondance Ville / Codepostal';
				//echo 'PROBLEME PLUSIEURS CORRESPONDANCE<br>';
				//echo $asso->id.' - '.$asso->nom.'<br>';
				//echo 	$asso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
			}
			
			if(!empty($erreur)) $erreurs[$i] = array($asso->id, $asso->nom,$asso->ville,$asso->codepostal,$erreur);
  			
		} catch( Exception $e ){
		  echo 'Erreur de suppression : ', $e->getMessage();
		}

	  }
  else $erreurs[$i] = array($asso->id, $asso->nom,$asso->ville,$asso->codepostal,'Ville ou code postal absent');
  
  $i++;
  //if ($i==500) break;
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