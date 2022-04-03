<div>
				<h2><span class="icon-annonces"></span> Annonces</h2>
				<button type="button" class="ajouter right" form-action="associations" form-type="annonces" form-id="<?php echo $asso->id_association ?>" ></button>
				<?php echo $plus ?>
				<br class="clear">
	<?php if (!empty($annonces)) : ?>
	
				<table>
				  <thead>
					<tr>
					  <th>Titre</th>
					  <th>État</th>
					  <th class="actions">Actions</th>
					</tr>
				  </thead>
				  <tbody>
					<?php echo $annonces ?>
					</tbody>
				</table>
				<?php echo $fermer ?>
	<?php else : ?>
	<em>Aucune annonce</em>
	<?php endif; ?>
	
	
			</div>