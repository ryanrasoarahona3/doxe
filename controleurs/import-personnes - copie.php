<section>
<div id="conteneur" class="personnes">
<article class="personnes">
<h2><span class="icon-personnes"></span> Import des personnes</h2>
<?php
/*

Table affilies



*/

// Vide les tables existantes
$vide = $connect->prepare('TRUNCATE personnes;');	
//$vide->execute();

$vide = $connect->prepare('TRUNCATE personnes_associations;');	
//$vide->execute();


require('libs/import.php');

// Personnes
$i=1;
$ok = 0;

// Stockage erreurs
$chemin = $_SESSION['ROOT'].'/erreurs.csv';
$delimiteur = ';';
$fichier_csv = fopen($chemin, 'w+');
fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

	
// Requetes
$reqPersos = $connect->prepare('SELECT * FROM affilies ORDER BY ville ASC');

$erreurs[0] = array('id','personne','ville','code postal','pays','erreur');


echo '<table>';
echo '<thead><tr>';
echo '<th>ID</th>';
echo '<th>Nom</th>';
echo '<th>Ville</th>';
echo '<th>Ville Modifiée</th>';
echo '<th>CP</th>';
echo '<th>CP Modifiée</th>';
echo '<th>Requete</th>';
echo '</tr></thead>';

try {
  $reqPersos->execute();
   
  // Traitement
  while( $perso = $reqPersos->fetch(PDO::FETCH_OBJ)){
   
		// Init
		$erreur = '';
		$newPerso = new stdClass;
		
		// Clean 
		$pays = traite_pays($perso->pays);
   		$ville = traite_nom_ville($perso->ville,traite_cp($perso->codepostal));
   		$ville = traite_nom_ville_perso($ville,traite_cp($perso->codepostal));
		$cp = traite_cp($perso->codepostal,$perso->id);
		
		// Recherche du pays si pas France
		if (strtoupper($pays)!='FRANCE') {
			$req = 'SELECT * FROM pays WHERE nom_fr_fr = "'.ucfirst($pays).'"; ';
			$reqPays = $connect->prepare($req);
			try {
				$reqPays->execute();
				$count = $reqPays->rowCount();
				if ($count == 0) {
					$erreur .= 'Pas de correspondance pays /';
				}
				else {
					$resultPays = $reqPays->fetch(PDO::FETCH_OBJ);
					$newPerso->Pays = $resultPays->id;
				}
			
			} catch( Exception $e ){
			  echo 'Erreur de suppression : ', $e->getMessage();
			}
		} else $newPerso->Pays = 75;
		
		// SI France, recherche de la ville
		if ($newPerso->Pays == 75) {

				
				// Recherche de la ville
				if (!empty($perso->ville) && !empty($perso->codepostal)) {
						
						// Teste si le code postal à été saisit dans la ville et inversement
						 if ( (ctype_alpha($cp)) && (ctype_digit($ville))) {
						 	$ville = traite_nom_ville($perso->codepostal,traite_cp($perso->ville));
   							$ville = traite_nom_ville_perso($ville,traite_cp($perso->ville));
							$cp = traite_cp($perso->ville,$perso->id);
						 }


						
		
						// Test CEDEX
						$isCedex = strpos($ville, '-CEDEX');
						$isCedex2 = strpos(strtoupper($cp), 'CEDEX');
						$isCedex3 = strpos(strtoupper($perso->adresse), 'CEDEX');
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
							$cedex[0] = trim(substr($perso->adresse,$isCedex3+5));
							$cedex[1] = trim($cp);
							$reqCP = '  ';
						} else $reqCP = '  AND  code_postal LIKE "%'.$cp.'%" ';
				
						// Test villes à arrondissements
						if ((substr($ville,0,5) == 'PARIS') && (substr($cp,0,2) =='75' ))  $reqVilles = ' nom LIKE "PARIS%" ';
						else if ((substr($ville,0,9) == 'MARSEILLE') && (substr($cp,0,2) =='13' ))   $reqVilles = ' nom LIKE "MARSEILLE%" ';
						else if ((substr($ville,0,4) == 'LYON') && (substr($cp,0,2) =='69' ))   $reqVilles = ' nom LIKE "LYON%" ';
						else $reqVilles = ' nom LIKE "%'.trim($ville).'%" ';
						
						
						$req = 'SELECT * FROM villes WHERE'. $reqVilles .''.$reqCP;
   
						$reqVille = $connect->prepare($req);
						try {
							$reqVille->execute();
							$count = $reqVille->rowCount();
		
							if ($count == 1) {
								$resultVille = $reqVille->fetch(PDO::FETCH_OBJ);
				
								/*
								$newPerso = new stdClass;
								$newPerso->id = $perso->id;
								if ($perso->type == 1) $newPerso->association_type = 1;
								if ($perso->type == 0) $newPerso->association_type = 1;
								if ($perso->type == 6) $newPerso->association_type = 1;
								if ($perso->type == 5) $newPerso->association_type = 1;
								if ($perso->type == 2) $newPerso->association_type = 2;
			
			
				
								$newPerso->numero_adherent = '';
								$newPerso->nom = trim(traite_nom($perso->nom));
								$newPerso->nom_soundex = phonetique(traite_nom($perso->nom));
								$newPerso->sigle = trim($perso->sigle);
								$newPerso->date_declaration_jo = $perso->jo;
								$newPerso->numero_siret = trim($perso->siret);
								$newPerso->numero_dossier = '';
								$newPerso->numero_convention = '';
								$newPerso->code_ape_naf = trim($perso->ape);
								$newPerso->ville = $resultVille->id;
								if(count($cedex)>0) $newPerso->cedex = serialize($cedex);
								else $newPerso->cedex='';
								$newPerso->adresse = trim($perso->adresse);
								if (!empty($perso->adresse2))  $newPerso->adresse .="\n". trim($perso->adresse2);
								$newPerso->telephone_fixe = trim($perso->tel);
								$newPerso->telephone_mobile ='';
								$newPerso->fax = '';
								$newPerso->courriel = trim($perso->email);
								$newPerso->mdp = $perso->passw;
								if ($perso->type == 5) $newPerso->banque_postale = 1;
								$newPerso->banque_postale = 0;
								$newPerso->modificateur = 1;
								$newPerso->date_creation = $perso->heure_decla;
			
								//d($newPerso);
		
								 $reqAjout->bindValue(':id', $newPerso->id);
								 $reqAjout->bindValue(':association_type', $newPerso->association_type);
								 $reqAjout->bindValue(':numero_adherent', $newPerso->numero_adherent);
								 $reqAjout->bindValue(':nom', $newPerso->nom);
								 $reqAjout->bindValue(':nom_soundex', $newPerso->nom_soundex);
								 $reqAjout->bindValue(':sigle', $newPerso->sigle);
								 $reqAjout->bindValue(':date_declaration_jo', $newPerso->date_declaration_jo);
								 $reqAjout->bindValue(':numero_siret', $newPerso->numero_siret);
								 $reqAjout->bindValue(':numero_dossier', $newPerso->numero_dossier);
								 $reqAjout->bindValue(':numero_convention', $newPerso->numero_convention);
								 $reqAjout->bindValue(':code_ape_naf', $newPerso->code_ape_naf);
								 $reqAjout->bindValue(':ville', $newPerso->ville);
								 $reqAjout->bindValue(':cedex', $newPerso->cedex);
								 $reqAjout->bindValue(':adresse', $newPerso->adresse);
								 $reqAjout->bindValue(':telephone_fixe', $newPerso->telephone_fixe);
								 $reqAjout->bindValue(':telephone_mobile', $newPerso->telephone_mobile);
								 $reqAjout->bindValue(':fax', $newPerso->fax);
								 $reqAjout->bindValue(':courriel', $newPerso->courriel);
								 $reqAjout->bindValue(':mdp', $newPerso->mdp);
								 $reqAjout->bindValue(':banque_postale', $newPerso->banque_postale);
								 $reqAjout->bindValue(':logo', $newPerso->logo);
								 $reqAjout->bindValue(':modificateur', $newPerso->modificateur);
								 $reqAjout->bindValue(':date_creation', $newPerso->date_creation);
								 $resultat = $reqAjout->execute();	
			 
			
			
								// Sauvegarde des activités
								if (!empty($newPerso->activite)) {
									$reqActivites->bindValue(':association', $newPerso->id);
									$reqActivites->bindValue(':activite', $newPerso->activite);
									$resultat2 = $reqActivites->execute();	
								}
			
								// Sauvegarde temporaire du président
								if (!empty($perso->president)) {
									$reqPresident->bindValue(':association', $newPerso->id);
									$reqPresident->bindValue(':president', $perso->president);
									$resultat3 = $reqPresident->execute();				
								}
			
								 if (!$resultat) $erreur = 'Erreur d\'enregistrement';
								 else $ok++;
								 */
								 $ok++;
/*
echo'<tr>';
echo '<td>'.$perso->id.'</td>';
echo '<td>'.$perso->nom.'</td>';
echo '<td>'.$perso->ville.'</td>';
echo '<td>'.$ville.'</td>';
echo '<td>'.$perso->codepostal.'</td>';
echo '<td>'.$cp.'</td>';
echo '<td></td>';
echo '</tr>';	
*/		
							} else if ($count == 0){
								$erreur .= 'Correspondance Ville / Codepostal';
								if (substr($perso->codepostal,4) != '0') $erreur .= ' / Manque Cedex ?';
							
echo'<tr class="alerte">';
echo '<td class="alerte">'.$perso->id.'</td>';
echo '<td class="alerte">'.$perso->nom.'</td>';
echo '<td class="alerte">'.$perso->ville.'</td>';
echo '<td class="alerte">'.$ville.'</td>';
echo '<td class="alerte">'.$perso->codepostal.'</td>';
echo '<td class="alerte">'.$cp.'</td>';
echo '<td class="alerte">'.$req.'</td>';
echo '</tr>';
							} else if ($count > 1) {
								$erreur .= 'Correspondance Ville / Codepostal';
								//echo 'PROBLEME PLUSIEURS CORRESPONDANCE<br>';
								//echo $perso->id.' - '.$perso->nom.'<br>';
								//echo 	$perso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
							}
			
							if(!empty($erreur)) {
								$erreurs[$i] = array($perso->id, $perso->nom,$perso->ville,$perso->codepostal,$perso->pays,$erreur);
								fputcsv($fichier_csv, $erreurs[$i], $delimiteur);
							}
			
						} catch( Exception $e ){
						  echo 'Erreur de suppression : ', $e->getMessage();
						}
				}
		}	
	 
  
  $i++;
 // if ($i==20000) break;
  }
  
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}



/*
$chemin = $_SESSION['ROOT'].'/erreurs.csv';
$delimiteur = ';';
$fichier_csv = fopen($chemin, 'w+');
fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
foreach($erreurs as $ligne){
	fputcsv($fichier_csv, $ligne, $delimiteur);
}

fclose($fichier_csv);
*/

fclose($fichier_csv);
echo $i.' Personnes<br>';
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