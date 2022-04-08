<div>
				<h2><span class="icon-distinctions"></span> Distinctions</h2>
				<button id="bokotra" type="button" class="ajouter right" form-action="personnes" form-type="distinctions" form-id="<?php echo $perso->id_personne ?>" ></button>
				
				<?php echo (isset($plus) ? $plus : '') ?>
				<br class="clear">
				
				<?php if (!empty($affDistinctions)) : ?>
					<?php echo $affDistinctions ?>
					<?php echo $fermer ?>
				<?php else : ?>
					<em>Aucune distinction</em>
				<?php endif; ?>
				
</div>
<script>
	$('#bokotra').on('click', function() {
		setTimeout(function () {
			$('#ajouter_distinctions').attr('perso', '<?php echo $perso->id_personne ?>');
			$('#choix_personne').val(<?php echo $perso->id_personne ?>);
			$('#personne').val(<?php echo $perso->id_personne ?>);
		}, 2000);
	});
</script>