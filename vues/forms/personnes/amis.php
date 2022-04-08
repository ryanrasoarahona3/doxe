<section>
	<div id="conteneur" class="amis ajouter">

	<form id="ajouter_amis">
		<article class="amis">
		
		<h2><span class="icon-amis"></span> Cercle National des Bénévoles</h2>
			
			<div>
				<?php echo (isset($alerte_chargement) ? $alerte_chargement : '')  ?>;
				
				
				<div class="zone_form_amis">
				
					
					
					
					<?php if (!isset($commande)) : // Si ajout ou si pas un paiement déjà créé ?>
						
						<fieldset class="">
						<label for="annee">Année</label>
						<select  id="annee" name="annee">
								<?php echo $menuAnnee ?>
						</select>	
						</fieldset>
					
						<!--
						<fieldset class="">
							<label for="paiement">Créer un paiement dans la boutique ?</label>
							<select id="paiement" name="paiement" >
								<option value="1" <?php if ($laf->paiement==1) echo 'selected'?>>Oui</option>
								<option value="0" <?php if ($laf->paiement==0) echo 'selected'?> >Non</option>
							</select>
							
						</fieldset>
						-->
						
						<!--
							<fieldset class="montant">
							<label for="montant">Montant du paiement</label>
							<input type="text" name="montant"  value="<?php echo $laf->montant ?>" id="montant"  />
							</fieldset>
						-->
					
					
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
							<input type="text" name="reference"  value="<?php echo (isset($commande->reference) ? $commande->reference : '') ?>" id="reference"  />
						</fieldset>
						
						
					
						<fieldset class="ladate">
							<label for="date_paiement">Date du paiement</label>
							<input type="text" name="date_creation"  value="<?php if(isset($commande->date_creation) && $commande->date_creation != '0000-00-00') echo $commande->date_creation ?>" id="date_paiement" class="date" required>
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
			
			
				<fieldset class="">
				<label for="organisme_payeur">Nom de l'association ou de l'organisme payeur <em>(le cas échéant)</em></label>
				<input type="text" name="organisme_payeur"  value="<?php echo (isset($laf->organisme_payeur) ? $laf->organisme_payeur : '') ?>" id="organisme_payeur"  />
				</fieldset>
				
				<fieldset class="">
				<label for="delegue">Êtes vous délégué du Cercle National des Bénévoles ?</label>
				<input type="radio" name="delegue" value="1" id="delegue" <?php echo $delegueOui ?>/> Oui | <input type="radio" name="delegue" value="0" id="delegue" <?php echo $delegueNon ?>/> Non
				</fieldset>
				
				<div id="delegue_oui">
					<fieldset class="">
					<label for="zone_delegation">Quelle est votre zone de délégation ?</label>
					<input type="text" name="zone_delegation"  value="<?php echo (isset($laf->zone_delegation) ? $laf->zone_delegation : '') ?>" id="zone_delegation"  />					
					</fieldset>
					
				</div>
				
				<fieldset class="">
				<label for="distinction">Êtes-vous titulaire d'une distinction du bénévolat ?</label>
				<select id="distinction" name="distinction">
					<option value="0">Aucune</option>
					<?php echo $selectDistinction ?>	
				</select>
				</fieldset>
			
				
			</div>
			
			<div class="zone_form_amis">
				
				<div id="distinction_oui">
					<fieldset class="">
					<label for="distinction_annee">En quelle année avez-vous reçu(e) cette distinction ?</label>
					<input type="text" name="distinction_annee"  value="<?php echo (isset($laf->distinction_annee) ? $laf->distinction_annee : '') ?>" id="distinction_annee"  />
					</fieldset>
					
					<fieldset class="">
					<label for="annuaire">Acceptez-vous de figurer dans l'annuaire de l'amicale des palmes ?</label>
					<input type="radio" name="annuaire" value="1" id="annuaire" <?php echo $annuaireOui ?>/> Oui | <input type="radio" name="annuaire" value="0" id="annuaire" <?php echo $annuaireNon ?>/> Non
					</fieldset>
				
				
				</div>
				<!--
				<fieldset class="">
					<label for="informations_bp">Souhaitez-vous bénéficier des informations associatives de la banque postale ?</label>
					<input type="radio" name="informations_bp" value="1" id="informations_bp" <?php echo $informations_bpOui ?>/> Oui | <input type="radio" name="informations_bp" value="0" id="informations_bp" <?php echo $informations_bpNon ?>/> Non
				</fieldset>
				-->
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
			<?php if (isset($form->suppression) && $form->suppression) : ?>
				<button type="button" id="action_supprimer" class="annuler">- Supprimer</button>
			<?php else : ?>
				<?php echo (isset($alerte_supprime) ? $alerte_supprime : '') ?>
			<?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
			<button type="button" id="action_pre_valider"><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			<button type="button" id="action_valider" hidden><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			
			
			
		</div>
		
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<input type="hidden" id="id_personne" name="id_personne" value="<?php echo $form->id_personne ?>">
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