<div class="contenu personnes">
	<h2>Bienfaiteur</h2>
	<button type="button" class="ajouter right" form-action="commun" form-type="bienfaiteur" form-id="<?php echo $perso->id_personne ?>" form-type-utilisateur="P" ></button>
	
	<?php echo (isset($plus) ? $plus : '') ?>
	<br class="clear">
	
	<?php if (!empty($bienfaiteur)) : ?>
	
	<table>
	  <thead>
		<tr>
		  <!--<th>N°</th>-->
		  <th>Date</th>
		  <th align="right">Montant</th>
		  <th class="actions"></th>
		 
		</tr>
	  </thead>
	  <tbody>
		<?php echo $bienfaiteur ?>
		</tbody>
	</table>
	<?php echo $fermer ?>
	
	<?php else : ?>
	<em>Aucun don</em>
	<?php endif; ?>
</div>
