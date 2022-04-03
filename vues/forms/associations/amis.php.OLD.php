<section>
	<div id="conteneur" class="amis ajouter">

	<form id="ajouter_amis">
		<article class="amis">
		
		<h2><span class="icon-amis"></span> Cercle National des Bénévoles</h2>
			<br class="clear">
			<div>
				<?php echo $alerte_chargement  ?>
				
				<?php if (isGestion()) : // Création du paiement ?>
					<fieldset class="">
						<label for="annee">Année</label>
						<select  id="annee" name="annee">
								<?php echo $menuAnnee ?>
						</select>	
					</fieldset>
					
					<?php if (($laf->paiement == 0) || (!isset($laf->paiement)) ) : // Si ajout ou si pas un paiement déjà créé ?>
					
						<fieldset class="">
							<label for="paiement">Créer un paiement dans la boutique ?</label>
							<select id="paiement" name="paiement" >
								<option value="0" <?php if ($laf->paiement==0) echo 'selected'?> >Non</option>
								<option value="1" <?php if ($laf->paiement==1) echo 'selected'?>>Oui (commande créée automatiquement)</option>
								<option value="2" <?php if ($laf->paiement==2) echo 'selected'?>>Lier à une commande existante (vérifier que la commande appartient bien à l'utilisateur)</option>
							</select>
							<!--<input type="radio" name="paiement" value="1" id="paiement" <?php echo $paiementOui ?>/> Oui | <input type="radio" name="paiement" value="0" id="paiement" <?php echo $paiementNon ?>/> Non -->
						</fieldset>
						
						<fieldset class="commande">
						<label for="commande">Numéro de commande</label>
						<input type="text" name="commande"  value="<?php echo $laf->commande ?>" id="commande"  />
						</fieldset>
						
						<fieldset class="montant">
						<label for="montant">Montant du paiement</label>
						<input type="text" name="montant"  value="<?php echo $laf->montant ?>" id="montant"  />
						</fieldset>
						
					
					
						<fieldset class="moyen">
							<label for="type_paiement">Moyen de paiement</label>
							<select  id="type_paiement" name="type_paiement" required>
									<?php echo $typesPaiement ?>
							</select>	
						</fieldset>
					
						<fieldset class="etat">
							<label for="etat">Etat du paiement</label>
							<select  id="info" name="etat_paiement" required>
									<?php echo $etatPaiement ?>
							</select>	
						</fieldset>
					
						<fieldset class="ladate">
							<label for="date_paiement">Date du paiement</label>
							<input type="text" name="date_paiement"  value="<?php if($laf->date_paiement != '0000-00-00') echo $laf->date_paiement ?>" id="date_paiement" class="date" required>
						</fieldset>
				
					<?php else : ?>
						<!--<input type="hidden" id="commande" name="commande" value="<?php echo $laf->commande ?>">-->
						<?php echo $alerte_commande ?>
						<label for="montant">Numéro de commande</label>
						<input type="text" name="commande"  value="<?php echo $laf->commande ?>" id="commande"  />
				
						
						<a href="<?php echo SITE_ADMIN ?>commerce/orders/<?php echo  $laf->id_commande ?>" >Gérer la commande<span class="icon-edit"></span></a>
					<?php endif; ?>
					
					<hr>
				<?php endif; ?>
			
				<h3>Cocher les services souhaités</h3>
				
				<fieldset class="">
					<!--<label for="assurance_gmf"> <input type="checkbox" name="assurance_gmf" value="1" id="assurance_gmf" <?php echo $assurance_gmfOui ?>/> Assurance Multigaranties Vie Associative de la GMF n°S151079.002 G</label>-->
					
					<label for="logiciel_citizenplace"> <input type="checkbox" name="logiciel_citizenplace" value="1" id="logiciel_citizenplace" <?php echo $logiciel_citizenplaceOui ?>/> Logiciel de comptabilité sur internet CitizenPlace</label>
					
					<label for="aide_citizenplace"> <input type="checkbox" name="aide_citizenplace" value="1" id="aide_citizenplace" <?php echo $aide_citizenplaceOui ?>/> Aide en matière de registre spécial CitizenPlace</label>
					
					<label for="assurance_groupama"> <input type="checkbox" name="assurance_groupama" value="1" id="assurance_groupama" <?php echo $assurance_groupamaOui ?>/> Accès à l’assurance Groupama n° 9617177001001</label>
					
					<label for="acces_info_banque_postale"> <input type="checkbox" name="acces_info_banque_postale" value="1" id="acces_info_banque_postale" <?php echo $acces_info_banque_postaleOui ?>/> Accès à l’information associative de La Banque Postale</label>
				</fieldset>
				
				
				<fieldset class="">
				<label for="nbr_adherents">Nombre d'adhérents</label>
				<input type="number" name="nbr_adherents" parsley-type="number" value="<?php echo $laf->nbr_adherents ?>" id="nbr_adherents"  />
				</fieldset>
				
				<fieldset class="">
				<label for="nbr_salaries">Nombre de salariés</label>
				<input type="number" name="nbr_salaries" parsley-type="number" value="<?php echo $laf->nbr_salaries ?>" id="nbr_salaries"  />
				</fieldset>
			
				<fieldset class="">
				<label for="budget_fonctionnement">Budget de fonctionnement (en euros)</label>
				<input type="number" name="budget_fonctionnement" parsley-type="number" value="<?php echo $laf->budget_fonctionnement ?>" id="budget_fonctionnement"  />
				</fieldset>
			
				
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
		
		
	</form>
	
</div>
</section>	

<div id="dialog-modal-amis" title="Alerte" class="modal alerte">
	<p>Il existe déjà une adhésion aux Cercle National des Bénévoles pour l'année sélectionnée.</p>
</div>