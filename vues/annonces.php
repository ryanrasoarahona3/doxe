<!--
<div id="outils">
	<a href="annonces/ajouter"><button type="button" class="annonces"><span class="icon-annonces"></span>  +</button></a>
	<button type="button" class="annonces"><span class="icon-cherche"></span>  Recherches enregistrées</button>
</div>
-->
	
<section class="annonces">
	<div id="conteneur" class="annonces recherche">
	
	<form id="recherche_annonces" class="recherche">
		<article class="annonces">
		
			<div>
				
				<div  id="recherche_annonces_1">
				
				<fieldset class="col1">
				<label for="choix_association">Association</label>
				<input type="text" name="choix_association"  value="<?php echo $choixRecherche['choix_association'] ?>" id="choix_association"  />
				<ul id="choix_association_resultat">
					<?php echo $selectAssociation ?>
				</ul>
				<input hidden type="text" name="association" id="association" value="<?php echo $choixRecherche['association'] ?>"   >
				</fieldset>
		
				
				<fieldset class="col1">
				<label for="choix_personne">Personne</label>
				<input type="text" name="choix_personne"  value="<?php echo $choixRecherche['choix_personne'] ?>" id="choix_personne"  />
				<ul id="choix_personne_resultat">
					<?php echo $selectAssociation ?>
				</ul>
				<input hidden type="text" name="personne" id="personne" value="<?php echo $choixRecherche['personne'] ?>"   >
				</fieldset>
				
				<fieldset class="col1">
				<label for="saisie_debut">Début</label>
				<input type="text" name="saisie_debut"  value="<?php echo $choixRecherche['saisie_debut'] ?>" id="saisie_debut" class="date"/>
				</fieldset>
		
				<fieldset  class="col1">
				<label for="saisie_fin">fin</label>
				<input type="text" name="saisie_fin"  value="<?php echo $choixRecherche['saisie_fin'] ?>" id="saisie_fin" class="date"/>
				</fieldset>
				
				<br class="clear">
				
				
				<fieldset class="col1">
				<label for="titre">Titre</label>
				<input type="text" name="titre"  value="<?php echo $choixRecherche['titre'] ?>" id="titre" />
				</fieldset>
				
				
				<fieldset class="col2">
				<label for="texte">Texte</label>
				<input type="text" name="texte"  value="<?php echo $choixRecherche['texte'] ?>" id="texte" />
				</fieldset>
				
				
				
				
				<fieldset class="col1">
				<label for="validation">État</label>
				<select type="text" name="validation" id="validation" >
					<?php echo $validation; ?>
				</select>
				</fieldset>
				
				</diV>
				
				<div  id="recherche_annonces_2">
				
				<fieldset class="col1">
				<label for="activites">Activités</label>
				<select type="text"   multiple="multiple" name="activites[]" id="activites" >
					<?php echo $Activites ?>
				</select>
				</fieldset>
				
				</div>
			</div>
			
		</article>
		

		
		
		<div id="zone_validation">
			<?php if ($retour) : ?>
				<input type="hidden" name="retour" id="retour" value="1">
			<?php endif; ?>
			<?php if ($auto) : ?>
				<input type="hidden" name="auto" id="auto" value="1">
			<?php endif; ?>
			<input type="hidden" name="recherche" id="recherche" value="annonces">
			<input type="hidden" name="affiche_resultats" id="affiche_resultats" value="">
			<button type="button" id="action_recherche"><span class="icon-cherche"></span>  Rechercher</button>
		</div>
	</form>
	
			
	<div id="zone_resultats">	
		
		<h1><strong><span id="nbr_resultats">12</span></strong> <span id='label_resultat'>résultats</span></h1>
		<button type="button" id="export" class="icone_seul" form-action="export-csv"><span class="icon-telecharger"></span></button>
		<!--<button type="button" id="contacter" class="icone_seul" ><span class="icon-envoyer"></span></button>-->
			
	
		<button type="button" id="action_modif_recherche"><span class="icon-cherche"></span>  Modifier la recherche</button>
		
		<div id="zone_tableau">
		
		</div>
		
		<div id="modele_tableau">
		<table id="tab_resultats">
		  <thead>
			<tr>
			  <th data-dynatable-column="titre">Titre</th>
			  <th data-dynatable-column="activites">Activités</th>
			  <th data-dynatable-column="validation_nom">État</th>
			  <th data-dynatable-column="ladate">Date de saisie</th>
			  <th data-dynatable-column="date_validation">Date de validation</th>
			  <th data-dynatable-column="actions">Actions</th>
			</tr>
		  </thead>
		  <tbody>
			
		  </tbody>
		</table>
		</div>

	</div>
</div>
</section>

<div id="dialog-modal-refuser" title="Alerte" class="modal">
	<p><strong>Souhaitez-vous refuser cette annonce ?</strong></p><br><p> Vous devez saisir le motif du refus.</p><br>
	<form id="refuser">
	<fieldset>
		<textarea name="refus" id="refus" style="width:300px;"></textarea>
		<input type="hidden" name="id_lien" id="id_lien" value="">
		<input type="hidden" name="section" id="section" value="annonces_refuser">
	</fieldset>
	</form>
</div>

<div id="dialog-modal-valider" title="Alerte" class="modal">
	<p><br><strong>Souhaitez-vous valider cette annonce ?</strong></p><br>
	<form id="valider">
	<fieldset>
		<input type="hidden" name="id_lien" id="id_lien" value="">
		<input type="hidden" name="section" id="section" value="annonces_valider">
	</fieldset>
	</form>
</div>
<div id="preparing-file-modal" title="Preparing report..." style="display: none;">
   Préparation de l'export
 <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
 
<div id="error-modal" title="Error" style="display: none;">
   Erreur d'export. Veuillez essayer ultérieurement.
</div>