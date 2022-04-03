<section>
	<div id="conteneur" class="amis ajouter">

	<form id="ajouter_amis">
		<article class="amis">
		
		<h2><span class="icon-amis"></span> Cercle National des Bénévoles</h2>
			
			<div>
				<?php echo $alerte_chargement  ?>
				
				
				<div class="zone_form_amis">
				
					
					
					
					<?php if (!isset($commande)) : // Si ajout ou si pas un paiement déjà créé ?>
						
						<fieldset class="">
						<label for="annee">Année</label>
						<select  id="annee" name="annee">
								<?php echo $menuAnnee ?>
						</select>	
						</fieldset>
					
					
					
						<fieldset class="moyen">
							<label for="type_paiement">Moyen de paiement</label>
							<select  id="payement" name="payement" required>
									<?php echo $typesPaiement ?>
							</select>	
						</fieldset>
						
						<fieldset class="etat">
							<label for="etat">Etat du paiement</label>
							<select  id="etat" name="etat" required>
									<?php echo $etatPaiement ?>
							</select>	
						</fieldset>
					
						<fieldset class="reference">
							<label for="reference">Référence du paiement (n°chèque...)</label>
							<input type="text" name="reference"  value="<?php echo $commande->reference ?>" id="reference"  />
						</fieldset>
						
						
					
						<fieldset class="ladate">
							<label for="date_paiement">Date du paiement</label>
							<input type="text" name="date_creation"  value="<?php if($commande->date_creation != '0000-00-00') echo $commande->date_creation ?>" id="date_paiement" class="date" required>
						</fieldset>

					<hr>
				<?php else:  ?>
					Cette adhésion est liée à la commande <?php echo $commande->numero_commande ?><br>
					Moyen de paiement : <?php echo $commande->payement_libelle ?><br>
					Etat : <?php echo $commande->etat_libelle ?><br>
					Date : <?php echo $commande->date_creation ?><br>
				<?php endif; ?>
				
			</div>
			<div class="zone_form_amis">
			
			
				
			
				
			</div>
			
			<div class="zone_form_amis">
				
		
				<fieldset class="">
				<label for="connaissance">Comment avez-vous eu connaissance du Cercle National des Bénévoles  ?</label>
				<select id="connaissance" name="connaissance">
					<option value="0">Choisissez</option>
					<?php echo $menuConnaissance ?>	
				</select>
				</fieldset>
				
				
			</div>
				
			</div>
				
				
		</article>
		
		<div id="erreur">
				</div>
		<div id="zone_validation">
			<?php if ($form->suppression) : ?>
				<button type="button" id="action_supprimer" class="annuler">- Supprimer</button>
			<?php else : ?>
				<?php echo $alerte_supprime ?>
			<?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
			<button type="button" id="action_pre_valider"><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			<button type="button" id="action_valider" hidden><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			
			
			
		</div>
		
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<input type="hidden" id="id_association" name="id_association" value="<?php echo $form->id_association ?>">
		<input type="hidden" id="id_lien" name="id_lien" value="<?php echo $form->id_lien ?>">
			
			<?php if (!empty($commande)) : ?>
				<input type="hidden" id="id_commande" name="id_commande" value="<?php echo $commande->id_commande ?>">
			<?php endif; ?>
		
		
	</form>
	
</div>
</section>	

<div id="dialog-modal-amis" title="Alerte" class="modal alerte">
	<p>Il existe déjà une adhésion aux Cercle National des Bénévoles pour l'année sélectionnée.</p>
</div>