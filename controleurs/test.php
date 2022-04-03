<section>
<pre>
<?php

/*
//echo config('payement_2');
$doc = new document('FAC_256');
			$doc->auth();
			echo '<br><a href="'.$_SESSION['ROOT_DRUPAL'].'/telecharger?filename=FAC_'.$commande->id_commande.'&auth='.$doc->auth.'" target="_new">Télécharger votre facture</a><br/>'; 

*/

	$email = new email();
	$email->to ( 'slebonnois@me.fr' , 'Stéphane Lebonnois' );
	$email->to ( 'phanoo@free.fr' , 'Stéphane Lebonnois' );
	
	$email->sujet = 'Cercle National des Bénévoles - Récapitulatif de votre commande';
	$email->message = 'mon message';
	$email->envoyer();
		
	echo $email->resultat;
?>
</pre>
</section>