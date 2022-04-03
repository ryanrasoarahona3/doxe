<div>
				<h2><span class="icon-distinctions"></span> Distinctions</h2>
				<button type="button" class="ajouter right" form-action="personnes" form-type="distinctions" form-id="<?php echo $perso->id_personne ?>" ></button>
				
				<?php echo $plus ?>
				<br class="clear">
				
				<?php if (!empty($affDistinctions)) : ?>
					<?php echo $affDistinctions ?>
					<?php echo $fermer ?>
				<?php else : ?>
					<em>Aucune distinction</em>
				<?php endif; ?>
				
</div>