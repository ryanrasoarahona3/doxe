
			<h1 class="left"> <?php echo $asso->nom ?></h1><button type="button" form-action="<?php echo $form_action ?>" form-type="ajouter" form-id="<?php echo $id ?>" class="edit"  title="Modifier"></button>
			<button form-action="envoyer_password" form-element="<?php echo $asso->id_association ?>" form-type="associations" class="envoyer_password edit" title="Envoyer un mot de passe"></button>
			
			<br class="clear">
			<h3><?php echo $asso->association_type_label ?> <br> <em><?php echo affProspect($asso->prospect) ?></em></h3>
			<em><strong>N°Dossier :  <?php echo $asso->numero_dossier ?></strong></em>  
			<br class="clear">
			<span class="alertes"><a href="#"><span class="icon-alertes"></span> Doublon</a></span>
			<br>
			<!--
				<em><strong>Nombres de bénévoles <?php echo ANNEE_COURANTE?> : <?php echo $asso->nbr_benevoles[ANNEE_COURANTE] ?></strong></em> 
			-->
			