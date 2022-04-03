<section>
	<div id="conteneur" class="boutique ajouter">

	<form id="ajouter_boutique">
		

		<div>
			<article>	
					<h2>Ajouter un don</h2>
			</article>
			
			<article>
			
				<fieldset class="col1">
				<label for="type">Montant du Don</label>
					<input type="text" id="don" name="don" value="">
				</fieldset>
				
				<fieldset class="col1">
				<label for="type_payement">Type de paiement</label>
					<select id="type_payement" name="type_payement">
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
				
				<input type="hidden" id="bienfaiteur" name="bienfaiteur" value="<?php echo $form->bienfaiteur ?>">
				<input type="hidden" id="type_utilisateur" name="type_utilisateur" value="<?php echo $form->type_utilisateur ?>">
		
		
			</article>
		</div>
		<br class="clear">
		
		
	</form>
	
</div>
</section>