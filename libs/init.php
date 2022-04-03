<?php
#See CERCLE_CONFIGURATION

// Ce bout de code doit être révisé
// De même que la structure globale du projet
if(session_status() == PHP_SESSION_NONE){
    ini_set('session.cookie_domain', '.cercledesbenevoles.fr' );
    session_set_cookie_params( 3600, '/', '.cercledesbenevoles.fr' );
}
if (!isset($_SESSION)) session_start();

define('WEBROOT',str_replace('index.php','',$_SERVER['SCRIPT_NAME']));
define('ROOT',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));

$_SESSION['ROOT'] = ROOT.'../gestion/';
#$_SESSION['ROOT_DRUPAL'] = str_replace('gestion','www',ROOT);
$_SESSION['WEBROOT'] = WEBROOT;
#$_SESSION['WEBROOT_DRUPAL'] = 'http://www.cercledesbenevoles.fr';
?>