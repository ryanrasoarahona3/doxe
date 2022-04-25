<?php if (isSiege()) : ?>
	<button  type="button" form-action="supprimer" form-element="section=personnes&id=<?php echo $perso->id_personne ?>" class="supprimer right"></button>
<?php endif; ?>
		<aside>
			<h1 class="left"> <a href="/personnes/detail/<?php echo $perso->id_personne?>"><?php echo $perso->civilite ?> <?php echo $perso->prenom ?> <?php echo $perso->nom ?> <?php echo ( isset($nom_jeune_fille) ? $nom_jeune_fille : '') ?></a></h1><button type="button" form-action="<?php echo $form_action ?>" form-type="ajouter" form-id="<?php echo $id ?>" class="edit"  title="Modifier"></button>
			<?php if (!empty($perso->courriel)) :?>
				<button form-action="envoyer_password" form-element="<?php echo $perso->id_personne ?>" form-type="personnes" class="envoyer_password edit" title="Envoyer un mot de passe"></button>
			<?php endif; ?>
			
			<br class="clear">
			<em><strong>N°Adhérent : <?php echo $perso->id_personne ?></strong></em> 
				<!-- <span class="alertes"><a href="#"><span class="icon-alertes"></span> Doublon</a></span> -->
		</aside>
		<aside>
			
			<?php echo (isset($siege) ? $siege : '') ?>
			<?php echo (isset($prospect) ? $prospect : '') ?>
			<?php echo (isset($delegueHeader) ? $delegueHeader : ''); ?>
			<?php echo (isset($elu) ? $elu : '') ?>
			<?php echo (isset($presse) ? $presse : '') ?>
			
			
		</aside>
		
	