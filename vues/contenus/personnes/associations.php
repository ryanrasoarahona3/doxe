<div>

		<h2><span class="icon-associations"></span> Assurance gratuite</h2>
		<?php if (!empty($menuAnnee)) : ?>
			<select id="select_annee" name="select_annee">
				<?php echo $menuAnnee ?>
			</select>		
		
		<?php endif; ?>
		
		<button type="button" class="ajouter right" form-action="personnes" form-type="associations" form-id="<?php echo $perso->id_personne ?>" ></button>
		
		
		<br class="clear">
		
		<?php if (!empty($menuAnnee)) : ?>
	
		<table>
			<?php echo $affAssurance ?>
		</table>
		<?php else : ?>
		<em>Aucune assurance gratuite</em>
		<?php endif; ?>
		
		<br class="clear">
				
</div>


