<section>
	<div id="conteneur" class="annonces ajouter">

	<form id="ajouter_annonces">
		<article class="annonces">
		
		<h2><span class="icon-annonces"></span> Annonces</h2>
			<div>
			
			<?php echo $header ?>
			
			<?php if (isGestion() && $isChoixType) : ?>
				
				<fieldset class="col1">
				<label for="type">Type d'annonce</label>
					<select id="type" name="type">
						<option value="personnes" selected>Personne</option>
						<option value="associations">Association</option>
					</select>
				</fieldset>
				
				<fieldset class="col1" id="select_association">
				<label for="choix_association">Association</label>
				<input type="text" name="choix_association"  value="" id="choix_association"  />
				<ul id="choix_association_resultat">
					<?php echo $selectAssociation ?>
				</ul>
				
				</fieldset>
				
				<fieldset class="col1"  id="select_personne">
				<label for="choix_personne">Personne</label>
				<input type="text" name="choix_personne"  value="" id="choix_personne"  />
				<ul id="choix_personne_resultat">
					<?php echo $selectAssociation ?>
				</ul>
				
				</fieldset>
				
				<input hidden type="text" name="createur" id="createur" value=""   required>
			<?php endif ; ?>
				
				<fieldset class="">
				<label for="activites">Activités</label>
				<select type="text" multiple="multiple"  name="activites[]"  value="" id="activites" >
					<?php echo $Activites ?>
				</select>
				</fieldset>
				
				<fieldset class="">
				<label for="titre">Titre</label>
				<input type="text" name="titre"  value="<?php echo $annonce->titre ?>" id="titre" required>
				</fieldset>
				
				<fieldset class="">
				<label for="texte">Texte</label>
				<textarea name="texte" id="texte" required><?php echo $annonce->texte ?></textarea>
				</fieldset>
				
			<?php if (isGestion()) : ?>
				<fieldset class="">
				<label for="validation">État</label>
				<select name="validation" id="validation">
					<?php echo $menuValidation ?>
				</select>
				</fieldset>
				
				<fieldset class="">
				<label for="refus">Motif du refus</label>
				<textarea name="refus" id="refus" ><?php echo $annonce->refus ?></textarea>
				</fieldset>
			<?php endif ; ?>
			
			<?php if (!$isChoixType) : ?>
				<input type="hidden" id="type" name="type" value="<?php echo $form->type ?>">
			<?php endif ; ?>	
				
				</div>
				
		</article>
		
		<div id="erreur">
				</div>
		<div id="zone_validation">
			<?php if ($form->suppression) : ?><button type="button" id="action_supprimer" class="annuler">- Supprimer</button><?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="<?php echo $form->lien_annulation ?>" class="annuler">X Annuler</button><?php endif; ?>
			<button type="button" id="action_valider"><span class="icon-annonces"></span>  <?php echo $form->label_validation ?></button>
		</div>
		
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<?php if (!$isChoixType) : ?>
			<input type="hidden" id="createur" name="createur" value="<?php echo $form->id ?>">
		<?php endif ; ?>	
		<input type="hidden" id="id_lien" name="id_lien" value="<?php echo $form->id_lien ?>">
		
		
	</form>
	
</div>
</section>