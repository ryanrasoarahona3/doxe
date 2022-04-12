<?php
/*
function __autoload( $nom_de_classe ) { 
	if (file_exists(ROOT.'class/'.strtolower($nom_de_classe).'.class.php')) require_once( ROOT.'class/'.strtolower($nom_de_classe).'.class.php' ) ;
}
*/

function connect() {
	try {
	  $dns = 'mysql:host='.HOST.';dbname='.DBNAME.'';
	  $utilisateur = USER;
	  $motDePasse = PASS;
 
	//   Options de connection
	  $options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8",
		PDO::ATTR_ERRMODE => "PDO::ERRMODE_EXCEPTION"
	  );
 
	  // Initialisation de la connection
	  return new PDO( $dns, $utilisateur, $motDePasse, $options );
	 // $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch ( Exception $e ) {
	  echo "Connection � MySQL impossible : ", $e->getMessage();
	  die();
	}
}


function sib_personne ($perso) {
	$mailin = new Mailin("https://api.sendinblue.com/v2.0","EhRqm4tOgS3NT8Jj");
    if ($perso->newsletter==1) {
    	$data = array( "email" => $perso->courriel,
			"attributes" => array("NOM"=>$perso->nom, "PRENOM"=>$perso->prenom, "NUMERO_ID"=>$perso->id_personne, "VILLE"=>$perso->ville_label, "DATE_NAISSANCE"=>$perso->date_naissance, "CODE_POSTAL"=>$perso->code_postal  ),
			"listid" => array(16)
		);
	}
	else {
    	$data = array( "email" => $perso->courriel,
			"attributes" => array("NOM"=>$perso->nom, "PRENOM"=>$perso->prenom, "NUMERO_ID"=>$perso->id_personne, "VILLE"=>$perso->ville_label, "DATE_NAISSANCE"=>$perso->date_naissance, "CODE_POSTAL"=>$perso->code_postal  ),
			"listid_unlink" => array(16)
		);
	}

	$mailin->create_update_user($data);
}

function ouinon($retour) {
	if ($retour==1) return 'Oui';
	else  return 'Non';
}

function getActivites ($selection=array(0)) {
	
	$retour="";

	$connect = connect();
	
	$req = "SELECT * FROM activites_categories ORDER BY nom ASC;";
	
	// Cat�gories
	try {
	  $requete = $connect->query($req);
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		if (!empty($element->nom)) $retour .= '<optgroup label="'.$element->nom.'">';
		
		// Activit�s
		$req2 = "SELECT * FROM activites WHERE id_categorie='".$element->id."' ORDER BY nom ASC;";
		try {
		  $requete2 = $connect->query($req2);
		  while( $element2 = $requete2->fetch(PDO::FETCH_OBJ)){
		  
			if (in_array($element2->id, $selection)) $selected = ' selected="selected" ';
			else $selected = '';
			if (!empty($element2->nom)) $retour .= '<option value="'. $element2->id.'" '.$selected.'>'.$element2->nom.'</option>';
					
		  }
  
		} catch( Exception $e ){
		  echo 'Erreur de lecture de la base : ', $e->getMessage();
		}
		
		if (!empty($element->nom)) $retour .= '</optgroup>';
		
	  }
		return $retour;
  
	} catch( Exception $e ){
	  return 'Erreur de lecture de la base : '. $e->getMessage();
	}
}


function getDistinctionsDomaines ($selection=array(0)) {
	
	$connect = connect();
	
	$req = "SELECT * FROM distinctions_domaines ORDER BY nom ASC;";
	
	// Cat�gories
	try {
	  $requete = $connect->query($req);
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		$retour[$element->id] = $element->nom;
	  }
		return $retour;
  
	} catch( Exception $e ){
	  return 'Erreur de lecture de la base : '. $e->getMessage();
	}
}

function getDistinctions($annee=2000) {
	$connect = connect();

	$req = "SELECT * FROM distinctions_types";
	if ($annee >= 2015) $req .= ' WHERE id > 3 AND id < 9';
	$req .= ' ORDER BY id ASC';
	$retour = array();
	try {
	  $requete = $connect->query($req);
   
		  // Traitement
		  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
				$retour['detail'] .= '<div class="comment hidden" id="comment_'.$element->id.'">'.$element->commentaire.'</div>';
				$retour['select'] .= '<option value="'. $element->id.'" >';	
				$retour['select'] .= $element->nom;
				$retour['select'] .= '</option>';
		  }

		  return $retour;
  
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}

}

function getSelect($table, $selection=array(0), $schema = array('nom'),$id='id') {
	
	$connect = connect();
	
	if (!is_array($selection)) $selection=array(0);
	$retour = '';
	$req = "SELECT * FROM ".$table;
	if ($_SESSION['utilisateur']['siege'] == 0) { // Si d�l�gu�
		if (($table == 'regions') && (!empty($_SESSION['utilisateur']['regions']) )) $req .= ' WHERE id  IN ('.lister($_SESSION['utilisateur']['regions']).') ';
		if (($table == 'departements') && (!empty($_SESSION['utilisateur']['departements']) )) $req .= ' WHERE id  IN ('.lister($_SESSION['utilisateur']['departements']).') ';
	}
	$req .= " ORDER BY id ASC;";

	try {
	  $requete = $connect->query($req);
   
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		
			if (in_array($element->{$id}, $selection)) $selected = ' selected="selected" ';
			else $selected = '';
		
			$retour .= '<option value="'. $element->{$id}.'" '.$selected.'>';
				foreach ($schema as $key=>$label) {
					$retour .= $element->{$label};
					if ((count($schema)>1) && ($key < count($schema)-1)) {
						$retour .= ' - ';
					}
				}
			$retour .= '</option>';
	
	  }
	  
	 if ($_SESSION['utilisateur']['siege'] == 0) { // Si d�l�gu� et pas de s�election d�partement ou r�gion on ne retourne rien
		if (($table == 'regions') && (empty($_SESSION['utilisateur']['regions']) )) return false;
		if (($table == 'departements') && (empty($_SESSION['utilisateur']['departements']) )) return false;
	}
	return $retour;
  
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}

}




function config($config) {
	$retour =  selectValeur('configuration','code',$config);
	return $retour->contenu;
}
  
 
function selectValeur($table,$champ,$valeur) {
	global $connect;
	$connect = connect();
	$retour=array();
	$req = " SELECT * FROM ".$table." WHERE ".$champ." = '".$valeur."'; ";

	try {
	 	$requete = $connect->query($req);
		return $requete->fetch(PDO::FETCH_OBJ);
  
	} catch( Exception $e ){
	  return 'Erreur de lecture de la base : '.$e->getMessage();
	}
}

function getDepartements($regions=array(),$champ='id') {
	global $connect;
	$connect = connect();
	$retour=array();
	if (count($regions)>0)  $req = "SELECT * FROM departements WHERE region IN (".lister($regions).")";
	else  $req = "SELECT * FROM departements ";
	try {
	  $requete = $connect->query($req);
   
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		$retour[$element->{$champ}] = $element->{$champ};
	
	  }
	return $retour;
  
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}
}



function getContacts($format = 'select') {
	if ($format == 'select') {
		global $connect;
		$connect = connect();
		$retour='';
		$req = 'SELECT cons_admin_fonctions.nom AS ca, 
	personnes.nom,
	personnes.prenom,
	personnes.id
FROM personnes INNER JOIN cons_admin_fonctions ON personnes.siege = cons_admin_fonctions.id
WHERE personnes.siege > 0
ORDER BY personnes.siege ASC';

		try {
		  $requete = $connect->query($req);
   
		  // Traitement
		  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
			$retour.= '<option value='.$element->id.'>'.$element->ca.' - '.$element->prenom.' '.$element->nom.'</option>';
	
		  }
		return $retour;
  
		} catch( Exception $e ){
		  echo 'Erreur de lecture de la base : ', $e->getMessage();
		}
		
	}
}

function getAnnees($type,  $input='checkbox', $id='', $selection=array(0),$exclusion=array(0)) {
	
	if (is_string($selection)) $selection=array(0);
	if (is_string($exclusion)) $exclusion=array(0);

	switch ($type) {
		case 'age' :
			$min = 1900;
			$max = ANNEE_COURANTE-AGEMIN;
		break;
		
		case 'periode' :
			$min = 1900;
			$max = ANNEE_COURANTE;
		break;
		
		case 'system' :
			$min = DEBUT_DATE;
			$max = ANNEE_COURANTE+1;
		break;
		
		case 'distinctions' :
			$min = 2000;
			$lemois = date ('n');
   			 if ($lemois >= 12) $annee =  date ('Y')+1;
   			 else  $annee =  date ('Y');
    
   			$max = $annee;
		break;
	}
	
	$retour = '';
	for ($i = $max; $i >= $min; $i--) {
		
		if (!in_array($i, $exclusion)) {
			if ($input=='checkbox') {
				if (in_array($i, $selection)) $selected = ' checked ';
				else $selected = '';
				$retour .= '<span class="zone_checkbox"><input type="checkbox" name="'.$id.'" value="'.$i.'" id="'.$id.'" '.$selected.'>'.$i.'</span>';
			}
			else if ($input=='select') {
				if (in_array($i, $selection)) $selected = ' selected="selected" ';
				else $selected = '';
				$retour .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
			}
			
			else if ($input=='radio') {
				if (in_array($i, $selection)) $selected = ' checked ';
				else $selected = '';
				$retour .= '<span class="zone_checkbox"><input type="radio" name="'.$id.'" value="'.$i.'" id="'.$id.'" '.$selected.'>'.$i.'</span>';
			}
		}
	}
	return $retour;
}


function getPaysLivrables() {
	$connect = connect();
	$retour = array();
	$req = "SELECT id_pays FROM commerce_livraison_zone";
	try {
	  	$requete = $connect->query($req);
   		while( $element = $requete->fetch(PDO::FETCH_OBJ)){
			$retour[] = $element->id_pays;
		}
	  return $retour;
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}
}

function getLivraisonPays($selection=ID_FRANCE) {
	$connect = connect();
	$retour = '';
	
	$req = "SELECT pays.id, pays.nom_fr_fr FROM commerce_livraison_zone, pays WHERE commerce_livraison_zone.id_pays = pays.id ORDER by nom_fr_fr ASC ";
	try {
	  $requete = $connect->query($req);
   
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		if ( $element->id == $selection) $selected = ' selected="selected" ';
		else $selected = '';
		$retour .= '<option value="'.$element->id.'" '.$selected.'>'.$element->nom_fr_fr.'</option>'."\n";
	  }
	  return $retour;
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}
}


function getPays($selection=ID_FRANCE,$recherche=false) {
	$connect = connect();
	$retour = '';
	
	if ($recherche) $retour = '<option value="0" >Tous</option>';
	
	$req = "SELECT * FROM pays";
	try {
	  $requete = $connect->query($req);
   
	  // Traitement
	  while( $element = $requete->fetch(PDO::FETCH_OBJ)){
		if ( $element->id == $selection) $selected = ' selected="selected" ';
		else $selected = '';
		$retour .= '<option value="'.$element->id.'" '.$selected.'>'.$element->nom_fr_fr.'</option>'."\n";
	  }
	  return $retour;
	} catch( Exception $e ){
	  echo 'Erreur de lecture de la base : ', $e->getMessage();
	}

}

function isGestion() {
	if ($_SESSION['utilisateur']['droit']  == 'gestion') return true;
	else return false;
}
function isSiege() {
	if ($_SESSION['utilisateur']['siege'] > 0) return true;
	else return false;
}

function menuCivilite($civilite='') {
	$menuCivilite='';
	if ($civilite =='Mlle') $select1 = 'selected'; else $select1='';
	if ($civilite =='Mme') $select2 = 'selected';  else $select2='';
	if ($civilite =='M.') $select3 = 'selected'; else $select3='';
	$menuCivilite .= '<option value="Mlle" '.$select1.'>Mlle</option>';
	$menuCivilite .= '<option value="Mme" '.$select2.'>Mme</option>';
	$menuCivilite .= '<option value="M." '.$select3.'>M.</option>';
	return $menuCivilite;
}


function isSelected($val,$type='checkbox') {
	if (($val==1) && ($type='checkbox'))  return 'checked';
	if (($val==1) && ($type='select'))  return 'selected="selected"';
}

function age($date) {
	$age = (time() - strtotime($date)) / 3600 / 24 / 365;
	return floor($age);
}

function convertDate($ladate,$format='mysql') 
{
	if(!empty($ladate)) 
	{
		if($format=='mysql') {
			$datetab = explode('/', $ladate);
			$mysqldate = $datetab[2].'-'.$datetab[1].'-'.$datetab[0];
		} else if ($format=='php') {
			$datetab = explode('-', $ladate);
			$mysqldate = $datetab[2].'/'.$datetab[1].'/'.$datetab[0];
		} 
		return $mysqldate;
	} else return false;
}

function affProspect($prospect) {
	if ($prospect==1) return "Prospect";
}


function affDate($date,$heures=false) {
	if(!empty($date))
	{
		if (!is_numeric($date)) $date = strtotime($date);
		$mois = array(1=>'Janvier', 'F&eacute;vrier', 'Mars','Avril','Mai','Juin','Juillet','Ao&ucirc;t','Septembre','Octobre','Novembre','D&eacute;cembre');

		$ladate = new DateTime();
		$ladate->setTimestamp($date);
		if (!$heures) return $ladate->format('j').' '.$mois[$ladate->format('n')].' '.$ladate->format('Y');
		else  {
			if ($ladate->format('G:i:s') != '0:00:00' ) return $ladate->format('j').' '.$mois[$ladate->format('n')].' '.$ladate->format('Y').$ladate->format(' / G:i:s');
			else return $ladate->format('j').' '.$mois[$ladate->format('n')].' '.$ladate->format('Y');
		}
	} else return false;
}

function genererMdp ($longueur = 8, $simple=false){
    // initialiser la variable $mdp
    $mdp = "";
 
    // D�finir tout les caract�res possibles dans le mot de passe, 
    // Il est possible de rajouter des voyelles ou bien des caract�res sp�ciaux
    if (!$simple) $possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    else $possible = "abcdefghijklmnpqrstuvwxyz123456789";
 
    // obtenir le nombre de caract�res dans la cha�ne pr�c�dente
    // cette valeur sera utilis� plus tard
    $longueurMax = strlen($possible);
 
    if ($longueur > $longueurMax) {
        $longueur = $longueurMax;
    }
 
    // initialiser le compteur
    $i = 0;
 
    // ajouter un caract�re al�atoire � $mdp jusqu'� ce que $longueur soit atteint
    while ($i < $longueur) {
        // prendre un caract�re al�atoire
        $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
 
        // v�rifier si le caract�re est d�j� utilis� dans $mdp
        if (!strstr($mdp, $caractere)) {
            // Si non, ajouter le caract�re � $mdp et augmenter le compteur
            $mdp .= $caractere;
            $i++;
        }
    }
 
    // retourner le r�sultat final
    return $mdp;
}


function getSection() {
	$script = str_replace('.php','',$_SERVER['SCRIPT_FILENAME']);
	$tabScript = explode('/',$script);
	return $tabScript[count($tabScript)-2];
}

function getAction() {
	$script = str_replace('.php','',$_SERVER['SCRIPT_FILENAME']);
	$tabScript = explode('/',$script);
	return $tabScript[count($tabScript)-1];
}


function lister($tableau,$sep=',') {
	if (is_array($tableau)) {
		//$tableau = array_filter($tableau, '_remove_empty_internal');
		$retour = '';
		$i = 0;
		foreach ($tableau as $val) {
			$retour .= $val;
			if ($i < count($tableau)-1 ) $retour.=$sep;
			$i++;
		}
		return $retour;
	}
}

function formateMontant($montant,$commerce=false) {
	if ($commerce) $montant = $montant/100;
	$montant = number_format($montant, 2, ',', '&nbsp;');
	$montant .= '&nbsp;&euro;';
	return $montant;
}


function traiteTel($num) {
	$num = str_replace('.','',$num);
	$num = str_replace(' ','',$num);
	return $num;
}

function s($nbr) {
	if($nbr>1) return 's';
}

function traiteTexte ($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caract�res
    
    return strtolower($str);
}

function traiteCherche($str) {
	$debut = strpos( $str,'(' );
	$fin = 	strpos( $str,')' );
	if ( ($debut !== false) && ($fin !== false)) {
		return substr($str,0,$debut).substr($str,$fin);
	} else return $str;
}

function maj($chaine)
{
	$chaine=strtoupper($chaine);$chaine=utf8_decode($chaine);$chaine=trim($chaine);
	$chaine = strtr($chaine, '��������������������������������', '�����������������������������Ƽ��');
	$chaine=utf8_encode($chaine);
	return $chaine;
}
function firstmaj($mot)
{
	$mot=trim($mot);$maj=$mot[0];$maj = strtr($maj,'��������������������������������', '�����������������������������Ƽ��');
	$mot = substr_replace($mot,$maj,0,1);
	$mot=ucfirst($mot);
	return $mot;
}


function d($val) {
	echo '<pre>';
	//var_export($val);
	print_r($val);
	echo '</pre>';
}

function loadDrupal () {
	chdir($_SESSION['ROOT_DRUPAL']);	
	define('DRUPAL_ROOT', getcwd()); //the most important line
	require_once  $_SESSION['ROOT_DRUPAL'].'/includes/bootstrap.inc';
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
}



/**
 * Helper function to get the users already existing (as implemented: first)
 * commerce customer profile and return it.
 * If the user has no customer profile yet, create a new one and return that.
 *
 * @param string $type The type of the customer profile e.g. "billing".
 * @param stdClass $user The user object.
 * @return stdClass The CommerceCustomerProfile object.
 */
 
 
function create_order_get_first_customer_profile($type, stdClass $user) {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'commerce_customer_profile')
      ->propertyCondition('uid', $userDrupal->uid)
      ->propertyCondition('type', $type);

  $results = $query->execute();
  if (!empty($results['commerce_customer_profile'])) {
    // Profile already exists. Load!
    $profile_info = reset($results['commerce_customer_profile']);
    return commerce_customer_profile_load($profile_info->profile_id);
  }
  else {
    // No profile yet. Create one!
    return commerce_customer_profile_new($type, $userDrupal->uid);
  }
}


function vide($str) {
	if(empty($str) || is_null($str) || !isset($str) || strlen($str) == 0) return '<span style="color:#bbb;"><em>(non renseign&eacute;)</em></span>';
	else return $str;
}


function phone($phone)
{
    $phone = ($phone!=null?preg_replace("/[^0-9]/", "", $phone):"");
	if(strlen($phone) == 10)
        return preg_replace("/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "$1 $2 $3 $4 $5", $phone);
    else 
        return $phone;
}

function cleanCp($cp) {
	$temp = explode('/', ($cp != null ? $cp : ""));
	return $temp[0];
}


function getDelegues($region,$departement,$pays,$type='personnes') {
	$connect = connect();
	
	// D�l�gu� de r�gion
	
	// Si le pays est France, c'est forc�ment une personne
	if (($pays==ID_FRANCE) || ($type=='associations')) {
		$req = "
			SELECT personnes.delegue_type, 
				personnes.delegue_adjoint, 
				personnes.id, 
				personnes.courriel
			FROM personnes LEFT JOIN delegues_departements ON personnes.id = delegues_departements.personne
				 LEFT JOIN delegues_regions ON personnes.id = delegues_regions.personne
			WHERE personnes.delegue_habilite = 1 AND  personnes.delegue_type > 0 AND personnes.delegue_type <> 3 AND personnes.etat = 1
			AND (delegues_regions.region =".$region." OR  delegues_departements.departement =".$departement." )
			ORDER BY personnes.delegue_type DESC, personnes.delegue_adjoint ASC
			LIMIT 1";
			// echo $req;

		try {
		  	$requete = $connect->query($req);
		  	$requete->execute();
   			return $requete->fetch(PDO::FETCH_OBJ);
   		
		} catch( Exception $e ){
		  	echo 'Erreur de lecture de la base : ', $e->getMessage();
		}
	}

}

function encode($filename,$path)
    { 
        $data = implode('', file($path . $filename . '.pdf'));
        
        // Encodage et protection
        $file = '<?php header("HTTP/1.0 404 Not Found"); die(); ?>' . "\n";
        $file .= base64_encode($data);
        
        // Cr�ation nouveau fichier
        $newfile = $path . $filename . '.pdf.php';
        $f       = fopen($newfile, 'w');
        fwrite($f, $file);
        fclose($f);
        
        // Suppression fichier original
        unlink($path . $filename . '.pdf');
		
    }

function decode($filename,$path) {
        $filepath = $path . $filename . '.pdf.php';
        
        // Get file extension
        $ext       = explode('.', $filename . '.pdf.php');
        $extension = $ext[count($ext) - 1];
        
        // Try and find appropriate type
        switch (strtolower($extension)) {
            case 'txt':
                $type = 'text/plain';
                break;
            case "pdf":
                $type = 'application/pdf';
                break;
            case "exe":
                $type = 'application/octet-stream';
                break;
            case "zip":
                $type = 'application/zip';
                break;
            case "doc":
                $type = 'application/msword';
                break;
            case "xls":
                $type = 'application/vnd.ms-excel';
                break;
            case "ppt":
                $type = 'application/vnd.ms-powerpoint';
                break;
            case "gif":
                $type = 'image/gif';
                break;
            case "png":
                $type = 'image/png';
                break;
            case "jpg":
                $type = 'image/jpg';
                break;
            case "jpeg":
                $type = 'image/jpg';
                break;
            case "html":
                $type = 'text/html';
                break;
            default:
                $type = 'application/force-download';
        }
        
        // Get file data
        
        $contents = file($filepath);
        array_shift($contents);
        $contents = implode('', $contents);
        $contents = base64_decode($contents);
        
        // General download headers:
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Transfer-Encoding: binary");
        
        // Filetype header
        header("Content-Type: " . $type);
        
        // Filesize header
        header("Content-Length: " . strlen($contents));
        
        // Filename header
        header("Content-Disposition: attachment; filename=\"" . $filename . ".pdf\";");
        
        // Send file data
        
        echo $contents;
    }

	function formatLivraison($commande,$utilisateur) {
		$retour = '';
		if (!empty($commande->livraison_nom) && !empty($commande->livraison_ville)) {
			$retour .=  $commande->livraison_prenom.' '.$commande->livraison_nom.'<br>';
			$retour .=  $commande->livraison_adresse.'<br>';
			if ($commande->livraison_pays == ID_FRANCE) $retour .=  $commande->livraison_code_postal.' '.$commande->livraison_ville_label.'<br>';
			else $retour .=  $commande->livraison_ville.'<br>';
			$retour .=  $commande->livraison_pays_label.'<br>';
		} else {
		
			if ($utilisateur->pays == ID_FRANCE) {	
				$retour .=  $utilisateur->prenom.' '.$utilisateur->nom.'<br>';
				$retour .=  $utilisateur->adresse.'<br>';
				$retour .=  $utilisateur->code_postal.' '.$utilisateur->ville_label.'<br>';
				$retour .=  $commande->livraison_pays_label.'<br>';
			} else {
				$retour .=  $utilisateur->prenom.' '.$utilisateur->nom.'<br>';
				$retour .=  $utilisateur->adresse.'<br>';
				$retour .=  $utilisateur->code_pays.' '.$utilisateur->ville_pays.'<br>';
				$retour .=  $commande->livraison_pays_label.'<br>';
			}
		}
		
		return $retour;
	}

function securite($string)
	{
		// On regarde si le type de string est un nombre entier (int)
		/*
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = mysql_real_escape_string($string);
			$string = addcslashes($string, '%_');
		}
		*/
		return $string;
	}
	
	
/*
function paiement ($mode) {
	switch ($mode) {
		case 'commerce_cheque':
				 	return 'Ch�que';
		break;
		
		case 'bank_transfer':
				 	return 'Virement bancaire';
		break;
		
		default:
				 	return 'Carte de cr�dit';
		break;
	}
}

*/

?>