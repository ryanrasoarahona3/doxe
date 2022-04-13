<?php

session_start();

header('Set-Cookie: fileDownload=true; path=/');
header('Cache-Control: max-age=60, must-revalidate');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=data.csv');

require_once '../libs/fonctions.php';
require_once '../libs/connect.php';
require_once '../libs/constantes.php';
require_once '../class/personne.class.php';
require_once '../class/distinction.class.php';
require_once '../class/laf_personne.class.php';
require_once '../class/commande.class.php';
require_once '../class/commande_produit.class.php';
require_once '../libs/class.html2text.inc';

// Création d'un pointer fichier
$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

$_SESSION['last']['sql'] = str_replace('LIMIT 10', ' ', $_SESSION['last']['sql']);
$_SESSION['last']['sql'] = str_replace('LIMIT 20', ' ', $_SESSION['last']['sql']);
$_SESSION['last']['sql'] = str_replace('LIMIT 50', ' ', $_SESSION['last']['sql']);
$_SESSION['last']['sql'] = str_replace('LIMIT 100', ' ', $_SESSION['last']['sql']);
$_SESSION['last']['sql'] = str_replace('OFFSET 0', ' ', $_SESSION['last']['sql']);

// Boutique

if ($_SESSION['last']['recherche']['recherche'] == 'boutique') {
    try {
        $select = $connect->query($_SESSION['last']['sql']);
        $select->setFetchMode(PDO::FETCH_OBJ);
        $i = 0;
        while ($enregistrement = $select->fetch()) {
            $noms = array();
            $properties = get_object_vars($enregistrement);

            // Nom des colonnes
            if ($i == 0) {
                foreach ($properties as $name => $value) {
                    $noms[] = $name;
                }
                fputcsv($output, $noms, ";");
            }

            // Clean
            foreach ($enregistrement as $name => $value) {
                if (($name == 'prenom') || ($name == 'nom') || ($name == 'adresse') || ($name == 'ville_label') || ($name == 'region') || ($name == 'departement') || ($name == 'profession') || ($name == 'payement') || ($name == 'etat')) {
                    $enregistrement->{$name} = $value;
                }
                if (($name == 'commentaire') || ($name == 'adresse')) {
                    $h2t = new html2text($value);
                    $enregistrement->{$name} = $h2t->get_text();
                }
            }
            $data = get_object_vars($enregistrement);

            fputcsv($output, $data, ";");
            $i++;
        }
    } catch (Exception $e) {
        echo "Une erreur est survenue lors de la récupération des créateurs";
    }
}

// Personnes 
if ($_SESSION['last']['recherche']['recherche'] == 'personnes') {
    $delegueType[0] = 'Aucun';
    $delegueType[1] = 'Régional';
    $delegueType[2] = 'Départemental';
    $delegueType[3] = 'Circonscription';

    $delegueStatut[0] = 'Aucun';
    $delegueStatut[1] = 'Conseillé';
    $delegueStatut[2] = 'Délégué';

    try {
        $select = $connect->query($_SESSION['last']['sql']);
        $select->setFetchMode(PDO::FETCH_OBJ);
        $i = 0;
        while ($enregistrement = $select->fetch()) {

            unset($enregistrement->ville);
            unset($enregistrement->mdp);
            unset($enregistrement->ddn);
            unset($enregistrement->prenom_soundex);
            unset($enregistrement->nom_soundex);
            unset($enregistrement->numero_adherent);
            unset($enregistrement->portrait);

            $perso = new personne($enregistrement->id);
            $perso->lesAmis();

            if(is_array($perso->lesamis)) {
                krsort($perso->lesamis);
                reset($perso->lesamis);
                $enregistrement->derniere_adhesion = key($perso->lesamis);
            }

            

            $noms = array();
            $properties = get_object_vars($enregistrement);

            // Nom des colonnes
            if ($i == 0) {
                foreach ($properties as $name => $value) {
                    $noms[] = $name;
                }
                fputcsv($output, $noms, ";");
            }

            // Clean
            foreach ($enregistrement as $name => $value) {
                if ($name == 'adresse') {
                    $enregistrement->{$name} = str_replace("\n", "<br />", $enregistrement->{$name});
                    $enregistrement->{$name} = str_replace("\t", "", $enregistrement->{$name});
                    $enregistrement->{$name} = str_replace("\r", "", $enregistrement->{$name});
                }

                if (($name == 'prenom') || ($name == 'nom') || ($name == 'ville_label') || ($name == 'region') || ($name == 'departement') || ($name == 'profession')) {
                    $enregistrement->{$name} = $value;

                }

                if ($name == 'commentaire') {
                    $h2t = new html2text($value);
                    $enregistrement->{$name} = $h2t->get_text();
                }


                if ($name == 'code_postal') {
                    $enregistrement->{$name} = cleanCp($value);
                }

                if (($name == 'delegue_habilite') || ($name == 'delegue_adjoint') || ($name == 'delegue_statut') || ($name == 'elu') || ($name == 'prospect')) {
                    if ($value == 0) $enregistrement->{$name} = 'NON';
                    else $enregistrement->{$name} = 'OUI';
                }

                if ($name == 'delegue_type') {
                    $enregistrement->{$name} = $delegueType[$value];
                }

                if ($name == 'delegue_statut') {
                    $enregistrement->{$name} = $delegueStatut[$value];
                }

                if (($name == 'telephone_mobile') || ($name == 'telephone_fixe')) {
                    $enregistrement->{$name} = phone($value);
                }
            }

            if ($enregistrement->delegue_statut == 0) $enregistrement->delegue_type = 'Aucun';
            $data = get_object_vars($enregistrement);

            fputcsv($output, $data, ";");
            $i++;
        }
    } catch (Exception $e) {
        echo "Une erreur est survenue lors de la récupération des créateurs";
    }
}


// Distinctions
if ($_SESSION['last']['recherche']['recherche'] == 'distinctions') {
    try {
        $select = $connect->query($_SESSION['last']['sql']);
        $select->setFetchMode(PDO::FETCH_OBJ);
        $i = 0;
        while ($enregistrement = $select->fetch()) {
            unset($enregistrement->distinction_type);
            unset($enregistrement->distinction_type_decision);
            unset($enregistrement->acceptation_conditions);
            unset($enregistrement->personne);

            $noms = array();
            $properties = get_object_vars($enregistrement);
            $distinction = new distinction ($enregistrement->id);

            // Nom des colonnes
            if ($i == 0) {
                foreach ($properties as $name => $value) {
                    if ($name == 'distinction_type_label') $noms[] = 'demande';
                    else if ($name == 'distinction_type_decision_label_refuse') $noms[] = 'En attente';
                    else if ($name == 'distinction_type_decision_label') $noms[] = 'decision jury';
                    else $noms[] = $name;
                }

                $noms[] = 'parrain';
                $noms[] = 'numero_parrain';

                for ($i = 1; $i <= 20; $i++) {
                    $noms[] = 'association ' . $i;
                    $noms[] = 'fonction';
                    $noms[] = 'autre_fonction';
                    $noms[] = 'annee_debut';
                    $noms[] = 'annee_fin';
                }

                fputcsv($output, $noms, ";");
            }

            // Clean
            foreach ($enregistrement as $name => $value) {
                if (($name == 'prenom') || ($name == 'nom') || ($name == 'adresse') || ($name == 'ville_label') || ($name == 'region') || ($name == 'departement') || ($name == 'profession') || ($name == 'commentaire')) {
                    $enregistrement->{$name} = $value;
                }
                if ($name == 'domaines_autres') {
                    $h2t = new html2text($value);
                    $enregistrement->{$name} = $h2t->get_text();
                }
                if ($name == 'domaines') {
                    $h2t = new html2text(htmlspecialchars($distinction->affDomaines()));
                    $enregistrement->{$name} = $h2t->get_text();
                }
                if ($name == 'code_postal') {
                    $enregistrement->{$name} = cleanCp($value);
                }
                if ($name == 'demandeur') {
                    $d = new personne($value);
                    $enregistrement->{$name} = $d->nom . ' ' . $d->prenom . "\n" . $d->adresse . "\n";
                    if ($d->pays == ID_FRANCE) $enregistrement->{$name} .= cleanCp($d->code_postal) . ' ' . $d->ville_label . "\n";
                    else $enregistrement->{$name} .= $d->code_pays . ' ' . $d->ville_pays . "\n" . $d->pays_label;
                }
                if ($name == 'distinction_type_decision_label_refuse') {
                    $temp = str_replace('<span class="alerte">', '', ($value != null ? $value : ''));
                    $temp = str_replace('</span>', '', $temp);
                    $enregistrement->{$name} = $temp;
                }
                if ($name == 'distinction_type_label') {
                    $temp = str_replace('<span class="alerte">', '', $value);
                    $temp = str_replace('</span>', '', $temp);
                    $enregistrement->{$name} = $temp;

                }
            }

            $temp = get_object_vars($enregistrement);


            //Parrain
            if(isset($distinction->parrains) && (is_array($distinction->parrains) || is_object($distinction->parrains)))
                foreach ($distinction->parrains as $cle => $val) {
                    $parrain = new personne($val);
                    array_push($temp, $parrain->nom . ' ' . $parrain->prenom);
                    array_push($temp, $parrain->id_personne);
                    break;
                }
            if (isset($distinction->parrains) && is_countable($distinction->parrains) && count($distinction->parrains) == 0) {
                $vide = '';
                array_push($temp, $vide);
                array_push($temp, $vide);
            }

            // Activités
            if (isset($distinction->activites) && (is_array($distinction->activites) || is_object($distinction->activites)))
                foreach ($distinction->activites as $cle => $val) {
                    array_push($temp, $val['association']);
                    array_push($temp, $val['fonction_label']);
                    array_push($temp, $val['fonction_autre']);
                    array_push($temp, $val['annee_debut']);
                    if (!empty($val['annee_fin'])) array_push($temp, $val['annee_fin']);
                    else array_push($temp, 'En cours');
                }

            if(isset($distinction->activites_passees) && (is_array($distinction->activites_passees) || is_object($distinction->activites_passees)))
                foreach ($distinction->activites_passees as $cle => $val) {
                    array_push($temp, $val['association']);
                    array_push($temp, $val['fonction_label']);
                    array_push($temp, $val['fonction_autre']);
                    array_push($temp, $val['annee_debut']);
                    if (!empty($val['annee_fin'])) array_push($temp, $val['annee_fin']);
                    else array_push($temp, 'En cours');
                }

            $data = $temp;

            fputcsv($output, $data, ";");
            $i++;
        }
    } catch (Exception $e) {
        echo "Une erreur est survenue lors de la récupération des créateurs";
    }
}

// Associations
if ($_SESSION['last']['recherche']['recherche'] == 'associations') {
    try {
        $select = $connect->query($_SESSION['last']['sql']);
        $select->setFetchMode(PDO::FETCH_OBJ);
        $i = 0;

        while ($enregistrement = $select->fetch()) {

            $noms = array();
            $properties = get_object_vars($enregistrement);
            $distinction = new distinction ($enregistrement->id);

            // Nom des colonnes
            if ($i == 0) {
                foreach ($properties as $name => $value) {
                    $noms[] = $name;
                }

                fputcsv($output, $noms, ";");
            }

            $data = get_object_vars($enregistrement);
            fputcsv($output, $data, ";");
            
            $i++;
        }

    } catch (Exception $e) {
        echo "Une erreur est survenue lors de la récupération des créateurs";
    }
}

?>
