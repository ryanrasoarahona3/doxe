
	
<section class="personnes">
	<div id="conteneur" class="personnes recherche">
	<form id="recherche_personnes" class="recherche">
		<article class="personnes"  id="infos_generales">
		
        <h2 class="left col3"><span class="icon-personnes"></span> Informations générales</h2>
		
            
        <div id="zone_validation" class="right">
			<input type="hidden" name="recherche" id="recherche" value="personnes">
			<input type="hidden" name="affiche_resultats" id="affiche_resultats" value="">
			<button type="button" id="action_recherche"><span class="icon-cherche"></span>  Rechercher</button>
		</div>
            
            <br class="clear">
			<div>
				<fieldset class="col1">
				<label for="civilite">Civilité</label>
				<select type="text" name="civilite"  value="" id="civilite" >
					<option value="0"></option>
					<option value="Mlle">Mlle</option>
					<option value="Mme">Mme</option>
					<option value="M.">M.</option>
				</select>
				</fieldset>
				
				<fieldset class="col3 nomargin">
				<label for="nom">Nom</label>
				<input type="text" name="nom"  value="" id="nom" />
				<ul id="nom_resultat" name="nom_resultat">
				</ul>
				<input type="hidden" name="id_personne"  value="" id="id_personne" />
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="prenom">Prénom</label>
				<input type="text" name="prenom"  value="" id="prenom" />
				</fieldset>
				
				<fieldset class="col2">
				<label for="num_adherent">N°Adhérent</label>
				<input type="text" name="num_adherent"  value="" id="num_adherent" />
				</fieldset>
		
			
				<!---->
		
				<fieldset class="col2">
				<label for="naissance_debut">Date de naissance : du</label>
				<input type="text" name="naissance_debut"  value="" id="naissance_debut" class="date"/>
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="naissance_fin">au</label>
				<input type="text" name="naissance_fin"  value="" id="naissance_fin" class="date"/>
				</fieldset>

					<!---->
		
				
		
				
				<fieldset class="col2">
				<label for="nom_jeune_fille">Nom de jeune fille</label>
				<input type="text" name="nom_jeune_fille"  value="" id="nom_jeune_fille" />
				</fieldset>
				
				<fieldset class="col2 nomargin">
				<label for="courriel">Courriel</label>
				<input type="text" name="courriel"  value="" id="courriel" />
				</fieldset>
				
		
				<!---->
		
				<fieldset>
				<label for="non_courriel">Courriel non renseigné <input type="checkbox" name="non_courriel"  value="1" id="non_courriel" /></label>
				
				</fieldset>
		
			</div>
			<div>
				<fieldset class="col2">
				<label for="tel_mobile">Téléphone mobile</label>
				<input type="text" name="telephone_mobile"  value="" id="telephone_mobile" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="tel_fixe">Téléphone fixe</label>
				<input type="text" name="telephone_fixe"  value="" id="telephone_fixe" />
				</fieldset>
				
				<fieldset>
				<label for="adresse">Adresse</label>
				<textarea id="adresse" name="adresse" ></textarea >
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="pays">Pays</label>
				<select type="text" name="pays"  value="" id="pays" />
					<?php echo $Pays ?>
				</select>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2" id="zone_ville">
				<label for="code_postal">Code postal</label>
				<input type="text" name="code_postal"  value="" id="code_postal" />
				<ul id="code_postal_resultat">
					
				</ul>
				<input hidden type="text" name="ville" id="ville" value="" >
				</fieldset>
				
				<fieldset class="col2" id="zone_ville_pays">
				<label for="code_pays">Code postal</label>
				<input type="text" name="code_pays"  value="<?php echo $perso->code_pays ?>" id="code_pays" />
				<label for="ville_pays">Ville</label>
				<input type="text" name="ville_pays"  value="<?php echo $perso->ville_pays ?>" id="ville_pays" />
				</fieldset>
				<!---->
		
				
		
				
			</div>
			<div>
				
		
				<fieldset>
				<label for="region">Région</label>
				<select type="text"   multiple="multiple" name="region[]" id="region" >
					<?php echo $Regions; ?>
				</select>
				</fieldset>
				
				<fieldset>
				<label for="departement">Departement</label>
				<select type="text"   multiple="multiple" name="departement[]" id="departement" >
					<?php echo $Departements; ?>
				</select>
				</fieldset>
				<!---->
		
				<hr>
		
				<!---->
		
				<!--
				<fieldset class="col2">
				<label for="nom_association">Nom association</label>
				<input type="text" name="nom_association"  value="" id="nom_association" />
				<ul id="nom_association_resultat" name="nom_association_resultat">
				</ul>
				<input type="hidden" name="id_association"  value="<?php echo $id_association?>" id="id_association" />
				</fieldset>
				
				
				
				<fieldset class="col2">
				<label for="num_dossier">N° Dossier/Commune</label>
				<input type="text" name="num_dossier"  value="<?php echo $num_dossier ?>" id="num_dossier" />
				</fieldset>
				
				
				<fieldset class="col2 nomargin">
				<label for="num_assos">N°Association</label>
				<input type="text" name="num_assos"  value="" id="num_assos" />
				</fieldset>
				-->
				
			</div>
		</article>

		<article class="personnes"  id="particularites">
		<h2>Particularités</h2>
			<div class="simple">
				<!--
				
				<fieldset class="col4 nomargin">
				<label for="membre_ca">Membre d’un CA</label>
				<select type="text" name="membre_ca"  value="" id="membre_ca" />
					<option value="0">&nbsp;</option>
					<?php echo $menuCA ?>
				</select>
				</fieldset>
				-->
				<?php if ($_SESSION['utilisateur']['siege'] == 1) : ?>
				
					<fieldset class="col4 nomargin">
					<label for="siege">Siège</label>
					<select type="text" name="siege"  value="" id="siege" />
						<option value="0">&nbsp;</option>
						<?php echo $menuSiege ?>
					</select>
					</fieldset>
		
					<fieldset class="col4 nomargin">
					<label for="delegue_statut">Statut</label>
					<select type="text" name="delegue_statut"  value="" id="delegue_statut" />
						<option value="0">&nbsp;</option>
						<option value="1">Conseiller</option>
						<option value="2">Délégué</option>
					</select>
					</fieldset>
		
		
					<fieldset class="col4 nomargin">
					<label for="delegue_type">Type responsabilité</label>
					<select type="text" name="delegue_type"  value="" id="delegue_type" />
						<option value="0" >&nbsp;</option>
						<option value="1" >Régional</option>
						<option value="2" >Départemental</option>
						<option value="3" >Circonscription</option>
					</select>
					</fieldset>
		
				<?php endif; ?>
		
				<fieldset  class="col1_5">
				<label for="bienfaiteur">Bienfaiteur <input type="checkbox" name="bienfaiteur"  value="1" id="bienfaiteur" /></label>
				
				</fieldset>
		
				<fieldset class="col1_5">
				<label for="presse">Presse <input type="checkbox" name="presse"  value="1" id="presse" /></label>
				</fieldset>
		
				<fieldset  class="col1_5">
				<label for="prospect">Prospect <input type="checkbox" name="prospect"  value="1" id="prospect" /></label>
				
				</fieldset>
		
				<fieldset  class="col1_5">
				<label for="elu_local">Élu local <input type="checkbox" name="elu_local"  value="1" id="elu_local" /></label>
				
				</fieldset>
			</div>
		</article>
		
		<!--
		<article class="associations"  id="assurance_gratuite">
		<h2><span class="icon-associations"></span> Assurance gratuite</h2>
			<div>
				<fieldset class="col1">
				<label for="assure">Assuré <input type="checkbox" name="assure"  value="1" id="assure" /></label>
				
				</fieldset>
		
			
				<fieldset class="col1">

				<select type="text" name="actif"  value="" id="actif" />
					<?php echo $menuActif ?>
				</select>
				</fieldset>
		
		
				<fieldset class="col2">
				<label for="inscrit">Inscrit pour les années</label>
				<?php echo $inscrit ?>
				</fieldset>
		
				<fieldset  class="col2">
				<label for="non_inscrit">Non inscrit pour les années</label>
				<?php echo $non_inscrit ?>
				</fieldset>
			</div>
		</article>
		
		-->
		<article class="amis"  id="amis">
		<h2><span class="icon-amis"></span> Cercle National des Bénévoles</h2>
			<div class="double">
		
				<fieldset class="col01">
					<label for="amis">Adhérent <input type="checkbox" name="amis"  value="1" id="amis" /></label>
				</fieldset>
				
				<!--
				<fieldset class="col05">
					<label for="banque_postale">Banque postale </label>
					<select name="banque_postale" id="banque_postale">
						<option value=""></option>
						<option value="0">Non</option>
						<option value="1">Oui</option>
					</select>
				</fieldset>
		
				-->
		
		
				<fieldset class="col1 ">
					<label for="renouvele_annee">Année</label>
					<?php echo $adherent_annees ?>
					
				</fieldset>
		
				
				
				<fieldset class="col1 ">
				<label for="etat_paiement">Paiement </label>
				<select type="text" name="etat_paiement"  value="" id="etat_paiement" />
					<option value="0"></option>
					<option value="1">Validé</option>
					<option value="2">Non validé</option>
				</select>
				</fieldset>
		
				<fieldset class="col1">
				<label for="date_paiement_debut">Validation paiement</label>
				<input type="text" name="date_paiement_debut"  value="" id="date_paiement_debut" class="date"/>
				</fieldset>
		
				<fieldset class="col1 nomargin">
				<label for="date_paiement_fin">au</label>
				<input type="text" name="date_paiement_fin"  value="" id="date_paiement_fin" class="date"/>
				</fieldset>
				
		
		
			</div>
		</article>

		<article class="distinctions"  id="distinctions">
		<h2><span class="icon-distinctions"></span>Distinctions</h2>
			<div >
				<fieldset class="col3 ">
				<label for="type_distinction">Type de distinction</label>
				<select type="text" name="type_distinction"  value="" id="type_distinction" />
				<option value="0"></option>
				<?php echo $distinctions_types ?>
				</select>
				</fieldset>
		
				<fieldset class="col2 ">
				<label for="annee_distinction">Année</label>
				<select type="text" name="annee_distinction"  value="" id="annee_distinction" />
				<option value="0"></option>
				<?php echo $annee_distinction ?>
				</select>
				</fieldset>
	
	
	
				<fieldset class="col1 ">
				<label for="annuaire">Annuaire</label>
				<select name="annuaire" id="annuaire" >
					<option value=""></option>
					<option value="0">Non</option>
					<option value="1">Oui</option>
				</select>
				</fieldset>
				
				
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
			  <th data-dynatable-column="nom">Nom</th>
			  <th data-dynatable-column="prenom">Prénom</th>
			  <th data-dynatable-column="region">Région</th>
			  <th data-dynatable-column="departement">Département</th>
			  <th data-dynatable-column="telephone_fixe">Téléphone</th>
			  <th data-dynatable-column="telephone_mobile">Téléphone</th>
			  <th data-dynatable-column="courriel">Courriel</th>
			  <th data-dynatable-column="details">Détails</th>
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

<div id="dialog-modal" title="<?php echo $modalTitre ?>" class="<?php echo $modalClasse ?>">
	<p><?php echo $modalTexte ?></p>
</div>