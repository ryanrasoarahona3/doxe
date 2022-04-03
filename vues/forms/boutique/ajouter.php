<section>
	<div id="conteneur" class="boutique ajouter">

	<form id="ajouter_boutique">
		
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
				
				<fieldset class="col1">
				<label for="type">Type de paiement</label>
					<select id="payement" name="payement">
						<?php echo $typePaiements ?>
					</select>
				</fieldset>
				
				<fieldset class="col1">
				<label for="type">État du paiement</label>
					<select id="etat" name="etat">
						<?php echo $etatPaiements ?>
					</select>
				</fieldset>
				
				<fieldset class="col1">
				<label for="type">Référence du paiement</label>
					<input type="text" id="reference" name="reference" value="<?php echo $commande->reference ?>">
				</fieldset>
				
				<div id="erreur">
				</div>
				<br class="clear">
				<div id="zone_validation">
					<button type="button" id="<?php echo $form->lien_annulation ?>" class="annuler">X Annuler</button>
					<button type="button" id="action_valider"><span class="icon-achats"></span>  Valider</button>
				</div>

		
				<!-- HIDDEN -->
				<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
				<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
				<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
				<input type="hidden" id="id_lien" name="id_commande" value="<?php echo $commande->id_commande ?>">
		
		
		
			</article>
		</div>
		<br class="clear">
		
		
	</form>
	
</div>
</section>