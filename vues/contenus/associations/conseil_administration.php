<div>
				<h2>Conseil d'administration</h2>
				<button type="button" class="ajouter right" form-action="associations" form-type="ca" form-id="<?php echo $asso->id_association ?>"></button>
				
				<?php if (!empty($ca)) : ?>
				
				<?php echo $plus ?>
				<br class="clear">
				<table>
				  <thead>
					<tr>
					  <th>Année</th>
					  <th>Président</th>
					  <th class="actions"></th>
					 
					</tr>
				  </thead>
				  <tbody>
				  	<?php echo $ca ?>
				  </tbody>
				</table>
					<?php echo $fermer ?>
				<?php else : ?>
					<em></em>
				<?php endif; ?>
				
			
			</div>

<div id="dialog-modal-email" class="modal">
  <p>
  <h3>Contacter le président</h3>
 <div id="retour"></div>
  <form id="envoyer_email">
    
 	<fieldset>
       <label for="password">Sujet :</label><br class="clear">
 		<input type="text" name="sujet"  id="sujet">
 	</fieldset>
 		
 	<fieldset>
       <label for="password">Message :</label><br class="clear">
 		<textarea name="message"  id="message"></textarea>
 	</fieldset>
 	
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
 		<input type="hidden" name="type" id="type" value="email">
  </form>
  </p>
</div>