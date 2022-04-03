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

$vide = $connect->prepare('TRUNCATE documents;');
$vide->execute();

$vide = $connect->prepare('TRUNCATE personnes_associations;');
$vide->execute();

$vide = $connect->prepare('TRUNCATE laf_adhesions_personnes;');
$vide->execute();

$vide = $connect->prepare('TRUNCATE commerce_commandes;');
$vide->execute();

$vide = $connect->prepare('TRUNCATE commerce_commandes_produits;');
$vide->execute();

require('libs/import.php');

// Personnes
$nbrPerso  = 1;
$ok = 0;
$ko = 0;

// Stockage erreurs
$chemin      = $_SESSION['ROOT'] . '/erreurs.csv';
$delimiteur  = ';';
$fichier_csv = fopen($chemin, 'w+');
fprintf($fichier_csv, chr(0xEF) . chr(0xBB) . chr(0xBF));



// Requetes
$reqPersos = $connect->prepare('SELECT * FROM benevoles ORDER BY nom ASC');


$reqSauve = $connect->prepare("INSERT INTO  `personnes` 
		  (`id`, `numero_adherent`, `civilite`, `prenom`, `nom`, `nom_soundex`, `prenom_soundex`, `nom_jeune_fille`, `date_naissance`, `courriel`, `mdp`, `adresse`, `ville`,`pays`, `telephone_fixe`, `telephone_mobile`, `profession`, `prospect`, `elu`, `presse`, `date_creation`,`date_modification`, `modificateur`) 
		  VALUES 
		  (:id, :numero_adherent, :civilite, :prenom , :nom , :nom_soundex , :prenom_soundex , :nom_jeune_fille , :date_naissance ,  :courriel , :mdp , :adresse , :ville ,:pays , :telephone_fixe , :telephone_mobile , :profession , :prospect , :elu , :presse ,  :date_creation ,NOW(), :modificateur);");

$reqLien = $connect->prepare("INSERT INTO  `personnes_associations` 
			  (`id`, `personne`, `association`, `date`, `annee`, `etat`, `date_etat`, `cons_admin`,`benevole`) 
			  VALUES 
			  ('', :personne,  1, :date, :annee, :etat, '0000-00-00', 0, 1 );");

$reqLafPersonne = $connect->prepare("INSERT INTO  `laf_adhesions_personnes` 
			  (`id`, `personne`,  `date`,  `annee`,  `connaissance`,`organisme_payeur` ,`delegue`,`zone_delegation`,`distinction`,`annuaire`,`informations_bp`,`distinction_annee`,`id_commande`) 
			  VALUES 
			  ('', :personne,   :date,  :annee,  :connaissance,:organisme_payeur ,:delegue,:zone_delegation,:distinction,:annuaire,:informations_bp,:distinction_annee,:id_commande );");




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

        if (($perso->cotisation2013 == 1) || ($perso->cotisation2014 == 1) || ($perso->cotisation2015 == 1)) {

            // Clean 
            //$pays  = traite_pays($perso->pays);
            $ville = traite_nom_ville($perso->ville, traite_cp($perso->code_postal));
            $ville = traite_nom_ville_perso($ville, traite_cp($perso->code_postal));
            $cp    = traite_cp($perso->code_postal, $perso->id);

            $newPerso->pays = 75;

            // SI France, recherche de la ville
            if ($newPerso->pays == 75) {


                // Recherche de la ville
                if (!empty($perso->ville) && !empty($perso->code_postal)) {

                    // Teste si le code postal à été saisit dans la ville et inversement

                    if (ctype_digit($ville)) {
                        $ville = traite_nom_ville($perso->codepostal, traite_cp($perso->ville));
                        $ville = traite_nom_ville_perso($ville, traite_cp($perso->ville));
                        $cp    = traite_cp($perso->ville, $perso->id);
                    }
                    $perso->adresse = $perso->adresse.' '.$perso->adresse2;

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
                                //$ok++;
                            }
                            else if (count($villeTrouve) == 0) {
                                $erreur .= 'Ville existe mais pas de CP';
                                $ko++;
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
                                $ko++;
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
                                $perso->code_postal,
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

            $temp_date = substr($perso->date_naissance,6,4).substr($perso->date_naissance,3,2).substr($perso->date_naissance,0,2);
            $newPerso->numero_adherent = strtolower(substr($perso->nom,0,5)).strtolower(substr($perso->prenom,0,3)).$temp_date;


            if ($perso->civilite == '0') $newPerso->civilite='M.';
            if ($perso->civilite == '1') $newPerso->civilite='Mme';
            if ($perso->civilite == '2') $newPerso->civilite='Mlle';
            $newPerso->prenom = firstmaj($perso->prenom);
            $newPerso->nom = maj($perso->nom);
            $newPerso->nom_soundex = phonetique($newPerso->nom);
            $newPerso->prenom_soundex = phonetique($newPerso->prenom);
            $newPerso->nom_jeune_fille = $perso->nom_jeune_fille;
            $newPerso->date_naissance = substr($perso->date_naissance,6,4).'-'.substr($perso->date_naissance,3,2).'-'.substr($perso->date_naissance,0,2);

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
            $tempEmail = str_replace(' ','',$perso->mail);
            $tempEmail = str_replace('-','',$tempEmail);
            $tempEmail = str_replace('.','',$tempEmail);

            // Si l'email contient le téléphone
            if (ctype_digit($tempEmail)) { // tel
                $telephone = $perso->mail;
            } else {
                $telephone = $perso->telephone;
                $newPerso->courriel = $perso->mail;
            }
            // Si le téléphone contient l'email
            $tempTel = traite_tel($telephone); 
            if (strpos($tempTel,'@') !== false ) {
                $newPerso->courriel = $telephone;
            } else if (!ctype_digit($tempTel)) {
                $newPerso->fonction = $telephone;
            } else if (substr($perso->telephone,0,2)=='06') $newPerso->telephone_mobile = $telephone;
                else $newPerso->telephone_fixe = $telephone;

                $newPerso->telephone_mobile = $perso->portable;

            $newPerso->profession = $perso->profession;
            $newPerso->date_creation = $perso->date_saisie;


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
            else {
                $ko++;
                $err = $reqSauve->errorInfo();
                echo '<tr class="alerte">';
                echo '<td class="alerte">' . $i . '</td>';
                echo '<td class="alerte">' . $perso->id . '</td>';
                echo '<td class="alerte">' . $perso->nom . '</td>';
                echo '<td class="alerte">' . $perso->ville . '</td>';
                echo '<td class="alerte">' . $ville . '</td>';
                echo '<td class="alerte">' . $perso->codepostal . '</td>';
                echo '<td class="alerte">' . $cp . '</td>';
                echo '<td class="alerte" style="font-size: 12px;">' . $req . '</td>';
                echo '<td class="alerte">'.$err[2].'</td>';
                echo '</tr>';
            }


            // Parcours des années
            for ($i=2013;$i<=2016;$i++) {
                $cotis = 'cotisation'.$i;

                if ($perso->{$cotis} == 1) { // Si une cotisation existe


                    // Lien Personne / Association

                    $reqLien->bindValue(':personne', $newPerso->id);
                    $reqLien->bindValue(':date', $i.'-01-01');
                    $reqLien->bindValue(':annee', $i); 
                    if ($i>=2015)  $reqLien->bindValue(':etat', 4); 
                    else $reqLien->bindValue(':etat', 1); 
                    $resultat2 = $reqLien->execute();	


                    // Création commande si 2015

                    if ($i == 2015) {
                        // Création de la commande
                        $commande = new commande();
                        $commande->id_utilisateur = $newPerso->id;
                        $commande->type_utilisateur = 'P';

                        if (substr($perso->date_saisie,0,4) == 2015) $commande->date_creation =  substr($perso->date_saisie,8,2).'/'.substr($perso->date_saisie,5,2).'/2015';
                        else $commande->date_creation =  '01/01/2015';
                        $commande->payement = 2; // chèque
                        $commande->etat = 3; // encaissé
                        $commande->sauve();

                        $commande_produit = new commande_produit();
                        $commande_produit->copie(ID_ADHESION_PERSONNE);
                        $commande_produit->nom = $commande_produit->nom .' '.$i;
                        $commande_produit->id_commande = $commande->id_commande;
                        $commande_produit->quantite = 1;
                        $commande_produit->sauve();


                        // Création des documents
                        $document = new document('FAC_'.$commande->id_commande);
                        $document->creation();

                        //CAR_type_id_adhésion
                        //LET_type_id_adhésion

                    }


                    // LAF 
                    $reqLafPersonne->bindValue(':personne', $newPerso->id);
                    $reqLafPersonne->bindValue(':date', $i.'-01-01');
                    $reqLafPersonne->bindValue(':annee', $i); 
                    $reqLafPersonne->bindValue(':connaissance', '0');
                    $reqLafPersonne->bindValue(':organisme_payeur', $perso->payeur);
                    $reqLafPersonne->bindValue(':delegue', $perso->delegue);
                    $reqLafPersonne->bindValue(':zone_delegation', $perso->zone_delegue);
                    $reqLafPersonne->bindValue(':distinction', $perso->palmes);
                    $reqLafPersonne->bindValue(':distinction_annee', $perso->annee);
                    $reqLafPersonne->bindValue(':annuaire', $perso->amicale);
                    $reqLafPersonne->bindValue(':informations_bp', $perso->bp);


                    if  ($i==2015) $reqLafPersonne->bindValue(':id_commande',$commande->id_commande);
                    else $reqLafPersonne->bindValue(':id_commande','0');

                    $resultat3 = $reqLafPersonne->execute();	
                    //print_r( $reqLafPersonne->errorInfo());


                }
            }

            $nbrPerso++;
            //if ($nbrPerso==20) exit();
            //if ($i==10000) break;
        }
    }
}
catch (Exception $e) {
    echo 'Erreur de suppression : ', $e->getMessage();
}

$reqSauve->bindValue(':id', '');
$reqSauve->bindValue(':numero_adherent', '');
$reqSauve->bindValue(':civilite', 'Mr');
$reqSauve->bindValue(':prenom','Stephane');
$reqSauve->bindValue(':nom', 'Lebonnois');
$reqSauve->bindValue(':nom_soundex', '');
$reqSauve->bindValue(':prenom_soundex', '');
$reqSauve->bindValue(':nom_jeune_fille','');
$reqSauve->bindValue(':date_naissance', '');
$reqSauve->bindValue(':courriel', 'slebonnois@labo83.com');
$reqSauve->bindValue(':mdp', '026a2ee3967eae0f35afc36c74dc3520');
$reqSauve->bindValue(':adresse','');
$reqSauve->bindValue(':ville', '');
$reqSauve->bindValue(':pays', '');
$reqSauve->bindValue(':telephone_fixe', '');
$reqSauve->bindValue(':telephone_mobile', '');
$reqSauve->bindValue(':profession', '');
$reqSauve->bindValue(':prospect', '0');
$reqSauve->bindValue(':elu', '0');
$reqSauve->bindValue(':presse', '');
$reqSauve->bindValue(':date_creation', '');
$reqSauve->bindValue(':modificateur', 1);

$resultat = $reqSauve->execute();	

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
echo '<br>';
echo $nbrPerso . ' Personnes<br>';
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