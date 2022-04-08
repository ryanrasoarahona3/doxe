<!--
<div id="outils">
	<a href="distinctions/ajouter"><button type="button" class="distinctions"><span class="icon-distinctions"></span>  +</button></a>
	<button type="button" class="distinctions"><span class="icon-cherche"></span>  Recherches enregistrées</button>
</div>
	-->
	
<section class="distinctions">
	<div id="conteneur" class="distinctions recherche">
	
	<form id="recherche_distinctions" class="recherche">
		<article class="distinctions">
		
			<div>
				
			
				
				<fieldset class="col01">
				<label for="annee">Année</label>
				<select type="text" name="annee" id="annee" >
					<?php echo $menuAnnee; ?>
				</select>
				</fieldset>
		
				<fieldset class="col05">
				<label for="demande">Demande</label>
				<select type="text" name="demande" id="demande" >
					<option value="">Toutes</option>
					<?php echo $menuDemande; ?>
				</select>
				</fieldset>
				
				<fieldset class="col05">
				<label for="decision">Décision du jury</label>
				<select type="text" name="decision" id="decision" >
					<option value="" >Toutes</option>
					<option value="0">En attente</option>
					<?php echo $menuDecision; ?>
				</select>
				</fieldset>
				
				<!--
					<fieldset class="col05">
					<label for="validation">Type de validation</label>
					<select type="text" name="validation" id="validation" >
						<option>Tous</option>
						<?php echo $menuValidation; ?>
					</select>
					</fieldset>
				-->
				<fieldset class="col05">
				<label for="documents_complets">Documents complets</label>
				<select type="text" name="documents_complets" id="documents_complets" >
					<option value="" >Tous</option>
					<option value="1" <?php if( (isset($choixRecherche)) && ($choixRecherche['documents_complets'] == 1)) echo 'selected' ?>>Oui</option>
					<option value="0" <?php if( (isset($choixRecherche)) && ($choixRecherche['documents_complets'] == 0)) echo 'selected' ?>>Non</option>
				</select>
				</fieldset>
				
				<fieldset class="col01">
				<label for="couriel_vide">Courriel</label>
				<select type="text" name="couriel_vide" id="couriel_vide" >
					<option value="" >Tous</option>
					<option value="1" <?php if( (isset($choixRecherche)) && ($choixRecherche['couriel_vide'] == 1)) echo 'selected' ?>>Non renseigné</option>
					<option value="0" <?php if( (isset($choixRecherche)) && ($choixRecherche['couriel_vide'] == 0)) echo 'selected' ?>>Renseigné</option>
				</select>
				</fieldset>
				
			
					<fieldset class="col05">
					<label for="personne">Personne</label>
					<input type="text" name="choix_personne"  id="choix_personne"  />
					<ul id="choix_personne_resultat">
						
					</ul>
					<input hidden type="text" name="personne" id="personne" >
					</fieldset>
					
					<fieldset class="col05">
					<label for="num_demande">Numéro de demande</label>
					<input type="text" name="choix_num_demande"  id="choix_num_demande"  />
					<ul id="choix_num_demande_resultat">
						
					</ul>
					<input hidden type="text" name="num_demande" id="num_demande" >
					</fieldset>
					
					<fieldset class="col05">
						<label for="region">Région</label>
						<select type="text"  name="region" id="region" >
							<option value="">Toutes</option>
							<?php echo $Regions; ?>
						</select>
					</fieldset>
					<script>
						jQuery(function($) {
							$("#region").append($("#region option").remove().sort(function(a, b) {
								var at = $(a).text(), bt = $(b).text();
								return (at > bt)?1:((at < bt)?-1:0);
							}));			
							$('#region').prepend('<option value="" selected>Toutes</option>');
						});
					</script>
			</div>	
			
		</article>
		

		
		
		<div id="zone_validation">
			<?php if (isset($retour) && $retour) : ?>
				<input type="hidden" name="retour" id="retour" value="1">
			<?php endif; ?>
			<?php if (isset($auto) && $auto) : ?>
				<input type="hidden" name="auto" id="auto" value="1">
			<?php endif; ?>
			<input type="hidden" name="recherche" id="recherche" value="distinctions">
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
				<th data-dynatable-column="num_demande">Numéro</th>
				<th data-dynatable-column="demande">Demande</th>
				<th data-dynatable-column="decision">Décision</th>
				<th data-dynatable-column="nom">Nom</th>
				<th data-dynatable-column="nbr_annees">Années de bénévolat</th>
				<th data-dynatable-column="documents">Documents</th>
				<th data-dynatable-column="region">Région</th>
				<th data-dynatable-column="departement">Département</th>
				<th data-dynatable-column="validation">Validation</th>
				<th data-dynatable-column="actions" align="center">Actions</th>
			</tr>
		  </thead>
		  <tbody>
			
		  </tbody>
		</table>
		</div>

	</div>
</div>
</section>


<div id="preparing-file-modal" title="Preparing report..." style="display: none;">
   Préparation de l'export
 <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
 
<div id="error-modal" title="Error" style="display: none;">
   Erreur d'export. Veuillez essayer ultérieurement.
</div>