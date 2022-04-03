<div>
				<h2>Représentant</h2>
				<button type="button" class="edit right" form-action="associations" form-type="representants" form-id="<?php echo $asso->id_association ?>"></button>
				<br class="clear">
				
				<aside>
				<?php if (!empty($detailRepresentant)) : ?>
					<?php if(!empty($representant->courriel)) : ?>
								<button form-action="mail" form-element="<?php echo $representant->courriel ?>" class="right envoyer action" title="" ></button>
						<?php endif; ?>
						<button type="button" form-action="lien" form-element="/personnes/detail/<?php echo $representant->id_personne ?>"  class="right details action"></button>

					<div class="left">
						<?php echo $detailRepresentant ?>
						
					</div>
					
				<?php endif; ?>
				</aside>
							</div>

<div id="dialog-modal-email" class="modal">
  <p>
  <h3>Contacter le représentant</h3>
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