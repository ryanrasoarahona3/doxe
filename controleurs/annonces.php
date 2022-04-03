<?php

if (isset ($_GET['auto'])) {
	$auto = true;
} 

if ( (isset($_GET['retour'])) && (!empty($_SESSION['recherche'][$recherche])) ) {
	$choixRecherche = $_SESSION['recherche'][$recherche];
	$Activites = getSelect('activites' ,$choixRecherche['activites']);
	$validation = getSelect('annonces_validation',$choixRecherche['validation']);
} else {
	$Activites = getSelect('activites' );
	$validation = getSelect('annonces_validation',array(3) );
}

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>

<script>
jQuery(function($) {
	$('#choix_association').autoCompleteSection('nom_association',3,false,'association');
	
	$('#choix_personne').autoCompleteSection('personne',3,false,'personne');
	
	
	// Refus
	$( document.body ).on('click','.action_refuser',function(event) {
		var annonce = $(this).attr('element-id');
		$( "#refuser #id_lien" ).val(annonce);
		$( "#dialog-modal-refuser" ).dialog({
			modal: true,
			height: 300,
			width: 350,
			 buttons: {
				"Refuser": function() {
					$.ajax({
						url: 'json/sauve.php',
						type: 'post',
						dataType: 'json',
						data: $('#refuser').serialize(),
						success: function(data) {
							window.location.href='/annonces/?auto&retour';
						 },
						 error: function(jqXHR, textStatus, errorThrown){
							alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
						 }
			
					});
				  	$( this ).dialog( "close" );
				},
				"Annuler": function() {
				 	$( this ).dialog( "close" );
					return false;
				}
			  }
		});
	});
	
	// Validation
	$( document.body ).on('click','.action_valider',function(event) {
		var annonce = $(this).attr('element-id');
		$( "#valider #id_lien" ).val(annonce);
		
		$( "#dialog-modal-valider" ).dialog({
			modal: true,
			height: 150,
			width: 350,
			 buttons: {
				"Valider": function() {
					$.ajax({
						url: 'json/sauve.php',
						type: 'post',
						dataType: 'json',
						data: $('#valider').serialize(),
						success: function(data) {
							window.location.href='/annonces/?auto&retour';
						 },
						 error: function(jqXHR, textStatus, errorThrown){
							alert('ERREUR : '+errorThrown+textStatus+jqXHR); 
						 }
			
					});
				  	$( this ).dialog( "close" );
				},
				"Annuler": function() {
				 	$( this ).dialog( "close" );
					return false;
				}
			  }
		});
	});
		
		
	<?php if ($auto) : ?>
			$("#action_recherche").trigger("click");
	<?php endif; ?>
});
</script>
