<section>
	<div id="conteneur" class="associations ajouter">

	<form id="ajouter_associations">
		<article class="associations">
		
		<h2><span class="icon-associations"></span> Assurance gratuite - Ajouter une personne</h2>
			<div>
				<fieldset class="">
				<label for="annee">Année</label>
				<select type="text" name="annee"  value="" id="annee" required>
					<?php echo $menuAnnee ?>
				</select>
				</fieldset>
				<br class="clear">
				<fieldset class="">
				<label for="association">Recherche de personnes</label>
					<select name="type_recherche" id="type_recherche">
					<option value="1" selected>Dans toute la base</option>
					<option value="2">Inscrites les années précédentes (mais pas cette année)</option>
				</select>
				</fieldset>
			
				
				<fieldset class="">
				<label for="association">Personne (nom ou n°adhérent)</label>

				<input type="text" name="choix_personne"  value="" id="choix_personne"  />
				<ul id="choix_personne_resultat">
					
				</ul>
				<input hidden type="text" name="id_personne" id="id_personne" value=""   required>
				</fieldset>
				
				<br class="clear">
				
				
				
				<fieldset class="">
				<label for="choix_date">Date d'inscription / renouvellement</label>
				<select name="choix_date" id="choix_date">
					<option value="1" selected>Aujourd'hui</option>
					<option value="2" >Début de l'année</option>
					<option value="3">Choisir</option>
				</select>
				</fieldset>
				
				<fieldset class="" id="field_choix_date">
				<label for="date">Choix de la date</label>
				<input type="text" name="date"  value="<?php echo $dateInscrition ?>" id="date" class="date" required>
				</fieldset>
				
				<?php if ($asso->asspciation_type==1) : ?>
					<fieldset class=" nomargin">
					<label for="cons_admin">Membre du CA</label>
					<select type="text" name="cons_admin"  value="" id="cons_admin" >
						<option value="0">Non</option>
						<?php echo $menuCA ?>
					</select>
					</fieldset>
				<?php endif; ?>
				
				<fieldset>
				<label for="benevole">Bénévole <input type="checkbox" name="benevole" value="1" id="benevole" <?php echo $benevole ?>/></label>
				</fieldset>
				
				<br class="clear">
				
				<fieldset class=" nomargin">
				<label for="etat">État</label>
				<select type="text" name="etat"  value="" id="etat" >
					<?php echo $menuEtat ?>
				</select>
				</fieldset>
				
				<fieldset class="">
				<label for="date_etat">Date du changement d'état</label>
				<input type="text" name="date_etat"  value="" id="date_etat" class="date"/>
				</fieldset>
				
			
				
				</div>
				
		</article>
		
		<div id="erreur">
				</div>
		<div id="zone_validation">
			<?php if ($form->suppression) : ?><button type="button" id="action_supprimer" class="annuler">- Supprimer</button><?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
			<button type="button" id="action_pre_valider"><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			<button type="button" id="action_valider" hidden><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
		</div>
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<input type="hidden" id="id_personne" name="id_association" value="<?php echo $form->id_association ?>">
		
		
	</form>
	
</div>
</section>	

<div id="dialog-modal" title="Alerte" class="alerte">
	<p>Cette personne est déjà membre de cette association pour l'année sélectionnée.</p>
</div>