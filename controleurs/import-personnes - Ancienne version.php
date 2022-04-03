<section>
<div id="conteneur" class="personnes">
<article class="personnes">
<h2><span class="icon-personnes"></span> Import des personnes</h2>
<?php
set_time_limit (10000);

$date = date("d-m-Y");
$heure = date("H:i");
Print("Nous sommes le $date et il est $heure");
/*

Table affilies



*/

// Vide les tables existantes
$vide = $connect->prepare('TRUNCATE personnes;');
$vide->execute();

$vide = $connect->prepare('TRUNCATE personnes_associations;');
$vide->execute();


require('libs/import.php');

// Personnes
$i  = 1;
$ok = 0;
$ko = 0;

// Stockage erreurs
$chemin      = $_SESSION['ROOT'] . '/erreurs.csv';
$delimiteur  = ';';
$fichier_csv = fopen($chemin, 'w+');
fprintf($fichier_csv, chr(0xEF) . chr(0xBB) . chr(0xBF));


$dateRenouvel2015 = new DateTime("2014-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
$dateRenouvel2014 = new DateTime("2013-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2013 = new DateTime("2012-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2012 = new DateTime("2011-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2011 = new DateTime("2010-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2010 = new DateTime("2009-12-01");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2009 = new DateTime("2008-12-05");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		$dateRenouvel2008 = new DateTime("2007-12-17");//date ‡ remplacer par la date du jour de la mise en place du renouvellement
		
		
// Requetes
$reqPersos = $connect->prepare('SELECT * FROM affilies ORDER BY nom ASC');

$reqPresident = $connect->prepare('SELECT * FROM import_president WHERE id=:association AND president= :president ');

$reqSauve = $connect->prepare("INSERT INTO  `personnes` 
		  (`id`, `numero_adherent`, `civilite`, `prenom`, `nom`, `nom_soundex`, `prenom_soundex`, `nom_jeune_fille`, `date_naissance`, `courriel`, `mdp`, `adresse`, `ville`,`pays`, `telephone_fixe`, `telephone_mobile`, `profession`, `prospect`, `elu`, `presse`, `date_creation`,`date_modification`, `modificateur`) 
		  VALUES 
		  (:id, :numero_adherent, :civilite, :prenom , :nom , :nom_soundex , :prenom_soundex , :nom_jeune_fille , :date_naissance ,  :courriel , :mdp , :adresse , :ville ,:pays , :telephone_fixe , :telephone_mobile , :profession , :prospect , :elu , :presse ,  :date_creation ,NOW(), :modificateur);");
	
$reqLien = $connect->prepare("INSERT INTO  `personnes_associations` 
			  (`id`, `personne`, `association`, `date`, `annee`, `etat`, `date_etat`, `cons_admin`,`benevole`) 
			  VALUES 
			  ('', :personne,  :association, :date, :annee, '0', '0000-00-00', :cons_admin, 1 );");
			  
			  
$erreurs[0] = array(
	'id',
	'personne',
	'ville',
	'ville modifiée',
	'code postal',
	'pays',
	'erreur'
);


echo '<table>';
echo '<thead><tr>';
echo '<th>N°</th>';
echo '<th>ID</th>';
echo '<th>Nom</th>';
echo '<th>Ville</th>';
echo '<th>Ville Modifiée</th>';
echo '<th>CP</th>';
echo '<th>CP Modifiée</th>';
echo '<th>Requete</th>';
echo '<th>Erreur</th>';
echo '</tr></thead>';

try {
	$reqPersos->execute();
	
	// Traitement
	while ($perso = $reqPersos->fetch(PDO::FETCH_OBJ)) {
		
		// Init
		$erreur   = '';
		$newPerso = new stdClass;
		
		// Clean 
		$pays  = traite_pays($perso->pays);
		$ville = traite_nom_ville($perso->ville, traite_cp($perso->codepostal));
		$ville = traite_nom_ville_perso($ville, traite_cp($perso->codepostal));
		$cp    = traite_cp($perso->codepostal, $perso->id);
		
		// Recherche du pays si pas France
		if (strtoupper($pays) != 'FRANCE') {
			$req     = 'SELECT * FROM pays WHERE nom_fr_fr = "' . ucfirst($pays) . '"; ';
			$reqPays = $connect->prepare($req);
			try {
				$reqPays->execute();
				$count = $reqPays->rowCount();
				if ($count == 0) {
					$erreur .= 'Pas de correspondance pays /';
				} else {
					$resultPays     = $reqPays->fetch(PDO::FETCH_OBJ);
					$newPerso->Pays = $resultPays->id;
				}
				
			}
			catch (Exception $e) {
				echo 'Erreur de suppression : ', $e->getMessage();
			}
		} else
			$newPerso->pays = 75;
		
		// SI France, recherche de la ville
		if ($newPerso->pays == 75) {
			
			
			// Recherche de la ville
			if (!empty($perso->ville) && !empty($perso->codepostal)) {
				
				// Teste si le code postal à été saisit dans la ville et inversement
			
				if (ctype_digit($ville)) {
					$ville = traite_nom_ville($perso->codepostal, traite_cp($perso->ville));
					$ville = traite_nom_ville_perso($ville, traite_cp($perso->ville));
					$cp    = traite_cp($perso->ville, $perso->id);
				}
				
				
				// Test CEDEX
				$isCedex  = strpos($ville, '-CEDEX');
				$isCedex2 = strpos(strtoupper($cp), 'CEDEX');
				$isCedex3 = strpos(strtoupper($perso->adresse), 'CEDEX');
				if ($isCedex !== false) {
					$cedex[0] = substr($ville, $isCedex + 7);
					$cedex[1] = $cp;
					$ville    = substr($ville, 0, $isCedex);
					$reqCP    = '  ';
				} else if ($isCedex2 !== false) {
					$cedex[0] = trim(substr($cp, $isCedex2 + 5));
					$cedex[1] = trim($cp);
					$reqCP    = '  ';
				} else if ($isCedex3 !== false) {
					$cedex[0] = trim(substr($perso->adresse, $isCedex3 + 5));
					$cedex[1] = trim($cp);
					$reqCP    = '  ';
				} //else $reqCP = '  AND  code_postal LIKE "%'.$cp.'%" ';
				
				// Test villes à arrondissements
				if ((substr($ville, 0, 5) == 'PARIS') && (substr($cp, 0, 2) == '75'))
					$reqVilles = ' nom LIKE "PARIS%" ';
				else if ((substr($ville, 0, 9) == 'MARSEILLE') && (substr($cp, 0, 2) == '13'))
					$reqVilles = ' nom LIKE "MARSEILLE%" ';
				else if ((substr($ville, 0, 4) == 'LYON') && (substr($cp, 0, 2) == '69'))
					$reqVilles = ' nom LIKE "LYON%" ';
				else
					$reqVilles = ' nom = "' . trim($ville) . '" ';
				
				
				$req = 'SELECT * FROM villes WHERE' . $reqVilles;
				
				$reqVille = $connect->prepare($req);
				try {
					$reqVille->execute();
					$count = $reqVille->rowCount();
					
					if ($count == 1) {
						$resultVille = $reqVille->fetch(PDO::FETCH_OBJ);
						$newPerso->ville = $resultVille->id;
						// On ne prend pas en compte le code postal
						
						
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
						
					} else if ($count == 0) {
						
						// Aucune ville trouvée
						
						$erreur .= 'Pas de Ville';
						if (substr($perso->codepostal, 4) != '0')
							$erreur .= ' / Manque Cedex ?';
						
						echo '<tr class="alerte">';
						echo '<td class="alerte">' . $i . '</td>';
						echo '<td class="alerte">' . $perso->id . '</td>';
						echo '<td class="alerte">' . $perso->nom . '</td>';
						echo '<td class="alerte">' . $perso->ville . '</td>';
						echo '<td class="alerte">' . $ville . '</td>';
						echo '<td class="alerte">' . $perso->codepostal . '</td>';
						echo '<td class="alerte">' . $cp . '</td>';
						echo '<td class="alerte" style="font-size: 12px;">' . $req . '</td>';
						echo '<td class="alerte">' . $erreur . '</td>';
						echo '</tr>';
						
					} else if ($count > 1) {
						
						// On parcours les cp pour chercher s'il existe une correspondance de code postal
						$villeTrouve = array();
						while( $enregistrement = $reqVille->fetch(PDO::FETCH_OBJ)){
							// Gestion des codes multiples
							$codes = explode('/',$enregistrement->code_postal);
							
							if ( ($isCedex === false) && ($isCedex2 === false) && ($isCedex3 === false)) {
								if (in_array($cp, $codes)) $villeTrouve[] =  $enregistrement->id;
							} else {
								// cedex donc comparaison sur les premier caractères du code postal
								$tempCodes=array();
								foreach ($codes as $cle=>$val) {
									$tempCodes[$cle] = substr($val,0,4);
								}
								$tempCp = substr($cp,0,4);
								if (in_array($tempCp, $tempCodes)) $villeTrouve[] =  $enregistrement->id;
							}
						}
						
						if (count($villeTrouve) == 1) {
							$newPerso->ville = $villeTrouve[0];
							$ok++;
						}
						else if (count($villeTrouve) == 0) {
							$erreur .= 'Ville existe mais pas de CP';
							
							// Tester sur les deux premiers chiffres du CP ?
						
							echo '<tr class="alerte">';
							echo '<td class="alerte">' . $i . '</td>';
							echo '<td class="alerte">' . $perso->id . '</td>';
							echo '<td class="alerte">' . $perso->nom . '</td>';
							echo '<td class="alerte">' . $perso->ville . '</td>';
							echo '<td class="alerte">' . $ville . '</td>';
							echo '<td class="alerte">' . $perso->codepostal . '</td>';
							echo '<td class="alerte">' . $cp . '</td>';
							echo '<td class="alerte" style="font-size: 12px;">' . $req . '</td>';
							echo '<td class="alerte">' . $erreur . '</td>';
							echo '</tr>';
						
						} 
						
						else if (count($villeTrouve) > 1) {
							$erreur .= 'Plusieurs Ville/CP';
							
							echo '<tr class="alerte">';
							echo '<td class="alerte">' . $i . '</td>';
							echo '<td class="alerte">' . $perso->id . '</td>';
							echo '<td class="alerte">' . $perso->nom . '</td>';
							echo '<td class="alerte">' . $perso->ville . '</td>';
							echo '<td class="alerte">' . $ville . '</td>';
							echo '<td class="alerte">' . $perso->codepostal . '</td>';
							echo '<td class="alerte">' . $cp . '</td>';
							echo '<td class="alerte" style="font-size: 12px;">' . $req . '</td>';
							echo '<td class="alerte">' . $erreur . '</td>';
							echo '</tr>';
						}
						
						//echo 'PROBLEME PLUSIEURS CORRESPONDANCE<br>';
						//echo $perso->id.' - '.$perso->nom.'<br>';
						//echo 	$perso->ville.' => '.$ville.' / '.$cp.' -> '.$cedex[0].' '.$cedex[1].'<br><br>';
					}
					
					if (!empty($erreur)) {
						$erreurs[$i] = array(
							$perso->id,
							$perso->nom,
							$perso->ville,
							$ville,
							$perso->codepostal,
							$perso->pays,
							$erreur
						);
						fputcsv($fichier_csv, $erreurs[$i], $delimiteur);
					}
					
				}
				catch (Exception $e) {
					echo 'Erreur de suppression : ', $e->getMessage();
				}
			}
		}
		
		
		
		// Enregistrement 		
		
		// Enregistre même si pas de ville (pour le moment)
		
		$newPerso->id = $perso->id;
		$newPerso->numero_adherent = $perso->code;
		if ($perso->sexe == 'h') $newPerso->civilite='M.';
		if ($perso->sexe == 'f') $newPerso->civilite='Mme';
		$newPerso->prenom = $perso->prenom;
		$newPerso->nom = $perso->nom;
		$newPerso->nom_soundex = phonetique($newPerso->nom);
		$newPerso->prenom_soundex = phonetique($newPerso->prenom);
		$newPerso->nom_jeune_fille = '';
		$newPerso->date_naissance = $perso->date_naissance;
		
		$newPerso->mdp = ''; // GENERE EMAIL ET ENVOYER
		if ($newPerso->pays == 75) {
			$newPerso->adresse = $perso->adresse;
			if (strlen($perso->adresse2)>0) $newPerso->adresse .= " \n".$perso->adresse2;
			if (count($cedex)>0) $newPerso->cedex = serialize($cedex);
		} else {
			$temp = array();
			$temp['adresse'] = $perso->adresse;
			if (strlen($perso->adresse2)>0) $temp['adresse'] .= " \n".$perso->adresse2;
    		$temp['ville'] = $perso->ville;
    		$temp['code'] = $perso->codepostal;
    		$newPerso->adresse = serialize($temp);
    		$newPerso->ville = 0;
		}
		
		// Test de l'email
		$tempEmail = str_replace(' ','',$perso->email);
		$tempEmail = str_replace('-','',$tempEmail);
		$tempEmail = str_replace('.','',$tempEmail);
		
		// Si l'email contient le téléphone
		if (ctype_digit($tempEmail)) { // tel
			$telephone = $perso->email;
		} else {
			$telephone = $perso->tel;
			$newPerso->courriel = $perso->email;
		}
		// Si le téléphone contient l'email
		$tempTel = traite_tel($telephone); 
		if (strpos($tempTel,'@') !== false ) {
			$newPerso->courriel = $telephone;
		} else if (!ctype_digit($tempTel)) {
			$newPerso->fonction = $telephone;
		} else if (substr($perso->tel,0,2)=='06') $newPerso->telephone_mobile = $telephone;
		else $newPerso->telephone_fixe = $telephone;
		
		$newPerso->profession = $perso->profession;
		$newPerso->date_creation = $perso->heure_decla;

		
	
		$reqSauve->bindValue(':id', $newPerso->id);
		$reqSauve->bindValue(':numero_adherent', $newPerso->numero_adherent);
		$reqSauve->bindValue(':civilite', $newPerso->civilite);
		$reqSauve->bindValue(':prenom', $newPerso->prenom);
		$reqSauve->bindValue(':nom', $newPerso->nom);
		$reqSauve->bindValue(':nom_soundex', $newPerso->nom_soundex);
		$reqSauve->bindValue(':prenom_soundex', $newPerso->prenom_soundex);
		$reqSauve->bindValue(':nom_jeune_fille', $newPerso->nom_jeune_fille);
		$reqSauve->bindValue(':date_naissance', $newPerso->date_naissance);
		$reqSauve->bindValue(':courriel', $newPerso->courriel);
		$reqSauve->bindValue(':mdp', $newPerso->mdp);
		$reqSauve->bindValue(':adresse', $newPerso->adresse);
		$reqSauve->bindValue(':ville', $newPerso->ville);
		$reqSauve->bindValue(':pays', $newPerso->pays);
		$reqSauve->bindValue(':telephone_fixe', $newPerso->telephone_fixe);
		$reqSauve->bindValue(':telephone_mobile', $newPerso->telephone_mobile);
		$reqSauve->bindValue(':profession', $newPerso->profession);
		$reqSauve->bindValue(':prospect', '0');
		$reqSauve->bindValue(':elu', '0');
		$reqSauve->bindValue(':presse', '');
		$reqSauve->bindValue(':date_creation', $newPerso->date_creation);
		$reqSauve->bindValue(':modificateur', 1);
		
 		$resultat = $reqSauve->execute();	
		if ($resultat) $ok++;
		else $ko++;
  
  		// Parcours de associations
  		for ($i=1;$i<=7;$i++) {
  			$nomAsso = 'asso'.$i;
  			$nomAssurance = 'assurance_asso'.$i;
  			$nomActivite = 'activite_asso'.$i;
  			
			if (!empty($perso->{$nomAsso})) { // VERIFIER SI ASSO EXISTE
		  	
	
				$reqLien->bindValue(':personne', $newPerso->id);
				$reqLien->bindValue(':association', $perso->{$nomAsso});
				$reqLien->bindValue(':date', $perso->{$nomAssurance});
				$reqLien->bindValue(':annee', ''); // A CALCULER
				
				$date = new DateTime($perso->{$nomAssurance});
				if ( ( $date >= $dateRenouvel2008 ) && ( $date < $dateRenouvel2009 ) ) {
					$reqLien->bindValue(':annee', 2008);
				}
				
				if ( ( $date >= $dateRenouvel2009 ) && ( $date < $dateRenouvel2010 ) ) {
					$reqLien->bindValue(':annee', 2009);
				}
				
				if ( ( $date >= $dateRenouvel2010 ) && ( $date < $dateRenouvel2011 ) ) {
					$reqLien->bindValue(':annee', 2010);
				}
				
				if ( ( $date >= $dateRenouvel2011 ) && ( $date < $dateRenouvel2012 ) ) {
					$reqLien->bindValue(':annee', 2011);
				}
				
				if ( ( $date >= $dateRenouvel2012 ) && ( $date < $dateRenouvel2013 ) ) {
					$reqLien->bindValue(':annee', 2012);
				}
				
				if ( ( $date >= $dateRenouvel2013 ) && ( $date < $dateRenouvel2014 ) ) {
					$reqLien->bindValue(':annee', 2013);
				}
				
				if ( ( $date >= $dateRenouvel2014 ) && ( $date < $dateRenouvel2015 ) ) {
					$reqLien->bindValue(':annee', 2014);
				}
				
				
				if ($perso->{$nomActivite} == 'membre elu') $reqLien->bindValue(':cons_admin', ''); 
				else if ($perso->{$nomActivite} == 'mandataire') $reqLien->bindValue(':cons_admin', ''); 
				else $reqLien->bindValue(':cons_admin', ''); 
				
				// Recherche si président
				$reqPresident->bindValue(':association', $perso->{$nomAsso});
				$reqPresident->bindValue(':president', $newPerso->numero_adherent);
				$resultat3 = $reqPresident->execute();	
				$count = $reqPresident->rowCount();
				if ($count == 1) {	
					 $reqLien->bindValue(':cons_admin', 1); 
				} else $erreur .= 'Président en double';
				
				$resultat2 = $reqLien->execute();	

			}
  		}

		$i++;
		if ($i==10000) break;
	}
	
}
catch (Exception $e) {
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
echo $i . ' Personnes<br>';
echo $ok . ' Enregistrements<br>';
echo $ko . ' Erreur d\'enregistrement<br>';
echo '<a href="/erreurs.csv">' . count($erreurs) . ' Erreurs</a>';

$date = date("d-m-Y");
$heure = date("H:i");
Print("Nous sommes le $date et il est $heure");

/*
$vide = $connect->prepare('SELECT * FROM personnes_associations INNER JOIN associations ON personnes_associations.association = associations.id WHERE personnes_associations.id =  :id ');	
$vide->bindValue(':id', $form->id_lien, PDO::PARAM_INT);
$vide->execute();
*/

?>
</article>
</div>
</span>