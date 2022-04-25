<section>
	<div id="conteneur" class="documents ajouter">

	<form id="ajouter_documents">
		<article class="documents">
		
		<h2><span class="icon-gestion"></span> Modification d'un élément de configuration</h2>
			<br class="clear">
			
			<div>
				<fieldset class="col2">
				<label for="nom">Nom de l'élément</label>
				<input type="text" name="nom" id="nom" value="<?php echo $configuration->nom ?>">
				</fieldset>
					<br class="clear">
				<fieldset class='zone_tinymce col3'>
				<label for="contenus">Contenu</label>
				<textarea name="contenus"   id="contenus"><?php echo $configuration->contenu ?></textarea>
				</fieldset>
			
			
				<div class="left">
				<h3>Balises à utiliser dans le document</h3>
				<?php echo $configuration->balises ?>
				</div>
			</div>				
	
		</article>
		
		<div id="erreur">
				</div>
		<div id="zone_validation">
		
			
			<button type="button" id="action_valider" ><span class="icon-gestion"></span>  <?php echo $form->label_validation ?></button>
			
		</div>
		
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<input type="hidden" id="code" name="code" value="<?php echo $configuration->code ?>">
		
		
	</form>
	
</div>
</section>	