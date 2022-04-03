<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;
$form->destination_validation = "json/login.php";


//$_SESSION['utilisateur']['droit'] = 'utilisateur' ;
//$_SESSION['utilisateur']['id'] = 1;

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>

<script>

	$( document.body ).on('click','#check_login',function(event) {
		
		$.ajax({
       		url: $('#destination_validation').val(),
       		type: 'post',
        	dataType: 'json',
        	data: $('#login').serialize(),
        	success: function(data) {
             
             	if (data.login==1) {
             		window.location.href='/personnes/';
             	} else {
					$("#retour").html(data.message);
					
				}
             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
             }
            
    	});
	});
</script>