
<section class="achat">
	
	<div id="header" class="personnes modifier">
		<h1 class="left">Commande <?php echo $commande->numero_commande ?></h1><?php echo $modifier?>
		<br class="clear">
		<h3 class="<?php echo $alerte ?>"><?php echo $commande->etat_libelle ?></h3>
	</div>
	
	<!-- ZONE DE FORMULAIRES -->
	<div id="contenu_formulaire">
		
	</div>
	
	<div id="conteneur" class="detail boutique modifier">
		<div>
			<article>	
				<?php echo $recap ?>
			</article>
		
			<article>	
				<h3>Livraison</h3>
				<?php echo $livraison ?>
			</article>
		</div>
		
		<div>
			<article>	
				<h3>Facturation</h3>
				<p class="<?php echo $alerte?>">Type de paiement : <?php echo $commande->payement_libelle?> <br>
				<?php   echo 'Référence : '.$commande->reference ?><br><br>
				<strong>État du paiement : <?php echo $commande->etat_libelle ?></strong></p>
				<?php echo $facture ?>
				<?php echo $recu ?>
			</article>
		</div>
	</div>
</section>


<?php if ($modal) : ?>
<script>
	jQuery(function($) {
		$( "#dialog-modal" ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
</script>
<?php endif; ?>	