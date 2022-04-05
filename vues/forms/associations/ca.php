<section>
	<div id="conteneur" class="associations ajouter">

	<form id="ajouter_associations">
		<article class="associations">
		
		<h2><span class="icon-personnes"></span> <?php echo $titre ?></h2>
			<div>	
				
			<?php if ($ajouteCA) : ?>	
				<fieldset class="">
				<label for="annee">Année</label>
				<select type="text" name="annee"  value="" id="annee" required>
					<?php echo $menuAnnee ?>
				</select>
				</fieldset>
				
				<?php if ($copieAnnee) : ?>
				
					<fieldset class="">
					<label for="identique_annee">Reprendre le CA d'une année précédente </label>
					<select type="text" name="identique_annee"  value="" id="identique_annee" >
						<option value="0">Non</option>
						<?php echo $menuCopieAnnee ?>
					</select>
					</fieldset>
				
				<?php endif; ?>
			<?php else: ?>
				<table>
					<tr>
					  <th>Fonction</th>
					  <th>Nom</th>
					  <th class="actions"></th>
					</tr>
				<?php echo $detailCA;?>
				</table>
			<?php endif; ?>
			
			
			
			<div id="zone_ajout_personne">
				
				<h3>Ajout d'un membre du Conseil d'administration</h3>
				
				<fieldset class="">
				<label for="association">Recherche de personnes</label>
					<select name="type_recherche" id="type_recherche">
					<option value="1" selected>Dans toute la base</option>
					<option value="2">Inscrites à cette association</option>
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
				
				
				
				<fieldset class="" id="field_choix_date">
				<label for="date">Choix de la date (si la personne n'est pas déjà enregistrée)</label>
				<input type="text" name="date"  value="<?php echo $dateInscrition ?>" id="date" class="date" >
				</fieldset>
				
				
				<fieldset class=" nomargin">
				<label for="cons_admin">Fonction</label>
				<select type="text" name="cons_admin"  value="" id="cons_admin" >
					<?php echo $menuCA ?>
				</select>
				</fieldset>
				
				<fieldset>
				<label for="benevole">Également bénévole <input type="checkbox" name="benevole" value="1" id="benevole" /></label>
				</fieldset>
				
				
				</div>
			
				<br class="clear">
				
			</div>
				
		</article>
		
		<div id="erreur">
		</div>
		<div id="zone_validation">
			
				<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
				<button type="button" id="action_valider" ><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>

		</div>
		
		
		<!-- HIDDEN -->
		<?php if (!$ajouteCA) : ?>	
				<input type="hidden" id="annee" name="annee" value="<?php echo $form->annee ?>">
		<?php endif; ?>
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<input type="hidden" id="id_personne" name="id_association" value="<?php echo $form->id_association ?>">
		
		
	</form>
	
</div>
</section>	

<div id="dialog-modal" title="Alerte" class="alerte">
	<p>Cette personne ne sera plus membre du Conseil d'administration.
	<br><br> 
	Si elle est bénévole, elle sera conservée.
	<br> 
	Si elle n'est pas bénévole, elle ne sera plus liée à l'association pour l'année.
	</p>
</div>