<div class="contenu achats">
	<h2><span class="icon-achats"></span> Achats : <?php echo $totalAchats ?></h2>

	<a href="<?php echo SITE_ADMIN ?>commerce/orders/add?id_utilisateur=personnes_<?php echo $perso->id_personne ?>" class="right"><button type="button" class="ajouter_externe right"></button></a>
	<?php echo $plus ?>
	<br class="clear">
	
		<?php if (!empty($achats)) : ?>
		
			<table>
			  <thead>
				<tr>
				   <th>N°</th>
				   <th>Date</th>
				  <th align="right">Montant</th>
				   <th  align="right">Paiement</th>
				  <th align="right">État</th>
				 <th class="actions"></th>
				</tr>
			  </thead>
			  <tbody>
				<?php echo $achats ?>
				</tbody>
			</table>
			<?php echo $fermer ?>
		<?php else : ?>
		<em>Aucun achat</em>
		<?php endif; ?>
	
</div>