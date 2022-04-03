<section>
	<div id="conteneur" class="associations ajouter">

	<form id="ajouter_associations">
		<article class="associations">
		<h2><span class="icon-associations"></span> Assurance gratuite</h2>
			<div>
				<fieldset>
				<label for="annee">Année</label>
				<select type="text" name="annee"  value="" id="annee" >
					<option value="2014">2014</option>
				</select>
				</fieldset>
				
				<fieldset >
				<label for="association">Association</label>
				<input type="text" name="association"  value="" id="association"  />
				<ul id="association_resultat">
					
				</ul>
				

				</fieldset>
				
				<fieldset class=" nomargin">
				<label for="membre_ca">Membre du CA</label>
				<select type="text" name="membre_ca"  value="" id="membre_ca" >
					<option value="1">Président</option>
				</select>
				</fieldset>
				</div>
		</article>
		
		
		<div id="zone_validation">
			<?php if ($form->suppression) : ?><button type="button" id="action_supprimer" class="annuler">- Supprimer</button><?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
			<button type="button" id="action_valider"><span class="icon-personnes"></span>  <?php echo $form->label_validation ?></button>
		</div>
		
		<!-- HIDDEN -->
		<input type="hidden" id="idpersonne" name="idpersonne" value="<?php echo $form->idpersonne ?>">
		<input type="hidden" id="destination" name="destination" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		
	</form>
	
</div>
</section>	