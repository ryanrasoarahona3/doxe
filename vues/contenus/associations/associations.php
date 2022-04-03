<div>
				<h2><span class='icon-associations'></span> Assurance Gratuite</h2>
				<button type="button" class="ajouter right" form-action="associations" form-type="personnes" form-id="<?php echo $asso->id_association ?>"></button>
				
				<?php if (!empty($assuranceGratuite)) : ?>
				
				<?php echo $plus ?>
				<br class="clear">
				<table>
				  <thead>
					<tr>
					  <th>Année</th>
					  <th  align="right">Bénévoles</th>
					  <th></th>
					  <th class="actions"></th>
					 
					</tr>
				  </thead>
				  <tbody>
					<?php echo $assuranceGratuite; ?>
					</tbody>
				</table>
					<?php echo $fermer ?>
				<?php else : ?>
					<br class="clear">
					<em></em>
				<?php endif; ?>
			
</div>


