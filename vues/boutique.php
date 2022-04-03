<!--
<div id="outils">
	<a href="annonces/ajouter"><button type="button" class="annonces"><span class="icon-annonces"></span>  +</button></a>
	<button type="button" class="annonces"><span class="icon-cherche"></span>  Recherches enregistrées</button>
</div>
-->
	
<section class="achats">
	<div id="conteneur" class="achats recherche">
	
	<form id="recherche_achats" class="recherche">
		<article class="achats">
		
			<div>
				
				<!--
					<fieldset class="col07">
					<label for="choix_association">Association</label>
					<input type="text" name="choix_association"  value="<?php echo $choixRecherche['choix_association'] ?>" id="choix_association"  />
					<ul id="choix_association_resultat">
						<?php echo $selectAssociation ?>
					</ul>
					<input hidden type="text" name="association" id="association" value="<?php echo $choixRecherche['association'] ?>"   >
					</fieldset>
				-->
				
				<fieldset class="col07">
				<label for="choix_personne">Personne</label>
				<input type="text" name="choix_personne"  value="<?php echo $choixRecherche['choix_personne'] ?>" id="choix_personne"  />
				<ul id="choix_personne_resultat">
					<?php echo $selectAssociation ?>
				</ul>
				<input hidden type="text" name="personne" id="personne" value="<?php echo $choixRecherche['personne'] ?>"   >
				</fieldset>
				
				<fieldset class="col07">
				<label for="saisie_debut">Achat du </label>
				<input type="text" name="saisie_debut"  value="<?php echo $choixRecherche['saisie_debut'] ?>" id="saisie_debut" class="date"/>
				</fieldset>
		
				<fieldset  class="col07">
				<label for="saisie_fin">au</label>
				<input type="text" name="saisie_fin"  value="<?php echo $choixRecherche['saisie_fin'] ?>" id="saisie_fin" class="date"/>
				</fieldset>
				
				<fieldset class="col07">
				<label for="etat">État</label>
				<select type="text" name="etat" id="etat" >
					<option></option>
					<?php echo $etats; ?>
				</select>
				</fieldset>
				
				
				<fieldset class="col07">
				<label for="etat">Contenant</label>
				<select type="text" name="produit" id="produit" >
					<option></option>
					<?php echo $produit; ?>
				</select>
				</fieldset>
				
				<div  class="col07">
					<?php if ($retour) : ?>
						<input type="hidden" name="retour" id="retour" value="1">
					<?php endif; ?>
					<?php if ($auto) : ?>
						<input type="hidden" name="auto" id="auto" value="1">
					<?php endif; ?>
					<input type="hidden" name="recherche" id="recherche" value="boutique">
					<input type="hidden" name="affiche_resultats" id="affiche_resultats" value="">
					<button type="button" id="action_recherche"><span class="icon-cherche"></span>  </button>
				</div>
			</div>
			
		</article>
		

		
		
		
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
			  <th data-dynatable-column="numero_commande">Numéro</th>
			  <th data-dynatable-column="nom">Client</th>
			  <th data-dynatable-column="date_creation">Date commande</th>
			  <th data-dynatable-column="payement">Paiement</th>
			  <th data-dynatable-column="etat">État</th>
			  <th data-dynatable-column="total">Total</th>
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


<div id="preparing-file-modal" title="Preparing report..." style="display: none;">
   Préparation de l'export
 <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
 
<div id="error-modal" title="Error" style="display: none;">
   Erreur d'export. Veuillez essayer ultérieurement.
</div>