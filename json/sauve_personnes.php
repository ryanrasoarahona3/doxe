<?php
session_start();
require_once '../libs/fonctions.php';
require_once '../libs/connect.php';
 
// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
 
$action = $_POST['action'];


switch($action) {
	
	case 'associations' : 
		$retour='ok';
	break;

}
 
 
 
$json = json_encode($retour);
print $json;
?>