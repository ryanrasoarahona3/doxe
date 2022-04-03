<div>
				<h2><span class="icon-amis"></span> Cercle National des Bénévoles</h2>
				<button type="button" class="ajouter right" form-action="associations" form-type="amis" form-id="<?php echo $asso->id_association ?>" ></button>
				
				
				
				<?php if (!empty($amis)) : ?>
					<?php echo $plus ?>
					<br class="clear">
					<?php echo $amis ?>
					<?php echo $fermer ?>
				<?php else : ?>
					<br class="clear">
					<em>Aucune adhésion aux Cercle National des Bénévoles</em>
				<?php endif; ?>
			

</div>