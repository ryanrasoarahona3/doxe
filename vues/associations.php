<section class="associations">
	<div id="conteneur" class="associations recherche">
	
	<form id="recherche_associations" class="recherche">
		<article class="associations">
		<h2 class="col3 left"><span class="icon-associations"></span> Informations générales</h2>
            
        <div id="zone_validation" class="right">
			<input type="hidden" name="recherche" id="recherche" value="associations">
			<input type="hidden" name="affiche_resultats" id="affiche_resultats" value="">
			<button type="button" id="action_recherche"><span class="icon-cherche"></span>  Rechercher</button>
		</div>
            
		<br class="clear">
			<div>
				<fieldset class="col1_5">
				<label for="numero_adherent">N°Association</label>
				<input type="text" name="numero_adherent"  value="" id="numero_adherent" />
				</fieldset>
		
				<fieldset class="col1_5">
				<label for="numero_dossier">N°Dossier</label>
				<input type="text" name="numero_dossier"  value="" id="numero_dossier" />
				</fieldset>
				
				
				<fieldset class="col1_5">
				<label for="type">Type</label>
				<select type="text"    name="association_type" id="association_type" >
					<option value="0"></option>
					<?php echo $AssociationsTypes; ?>
				</select>
				</fieldset>
				
		
				<fieldset>
				<label for="nom_association">Nom association</label>
				<input type="text" name="nom_association"  value="" id="nom_association" />
				<ul id="nom_association_resultat" name="nom_association_resultat">
				</ul>
				<input type="hidden" name="id_association"  value="" id="id_association" />
				</fieldset>
				
				<fieldset>
				<label for="sigle">Sigle</label>
				<input type="text" name="sigle"  value="" id="sigle" />
				</fieldset>
				
				<fieldset  class="col2">
				<label for="numero_siret">SIRET</label>
				<input type="text" name="numero_siret"  value="" id="numero_siret" />
				</fieldset>
				
				<fieldset class="col2">
				<label for="date_declaration_jo">Déclaration JO</label>
				<input type="text" name="date_declaration_jo"  value="" id="date_declaration_jo" class="date"/>
				</fieldset>
				
			</div>
			<div>	
				<fieldset>
				<label for="activites">Activités</label>
				<select type="text"   multiple="multiple" name="activites[]" id="activites" >
					<?php echo $Activites ?>
				</select>
				</fieldset>
				
				<fieldset class="col2">
				<label for="code_postal">Code postal</label>
				<input type="text" name="code_postal"  value="" id="code_postal" />
				<ul id="code_postal_resultat">
					
				</ul>
				<input hidden type="text" name="ville" id="ville" value="" >
				</fieldset>
				
					
				<br class="clear">
				
				<fieldset class="col2">
				<label for="saisie_debut">Saisie : du</label>
				<input type="text" name="saisie_debut"  value="" id="saisie_debut" class="date"/>
				</fieldset>
		
				<fieldset class="col2">
				<label for="saisie_fin">au</label>
				<input type="text" name="saisie_fin"  value="" id="saisie_fin" class="date"/>
				</fieldset>
				
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

			</div>
			
			
		</article>
	
		<article class="associations">
		<h2><span class="icon-associations"></span> Contact</h2>
		<div>
				<fieldset class="">
				<label for="tel_mobile">Téléphone mobile</label>
				<input type="text" name="tel_mobile"  value="" id="tel_mobile" />
				</fieldset>
		
				<fieldset class=" ">
				<label for="tel_fixe">Téléphone fixe</label>
				<input type="text" name="tel_fixe"  value="" id="tel_fixe" />
				</fieldset>
		
				<!---->
		
				<fieldset>
				<label for="courriel">Courriel</label>
				<input type="text" name="courriel"  value="" id="courriel" />
				</fieldset>
			
			</div>
		
			<h2><span class="icon-associations"></span> Type d'association</h2>
			<div>
				
				<fieldset class="col05">
				<label for="prospect">Prospect <input type="checkbox" name="prospect"  value="" id="prospect" /></label>
				</fieldset>
		
			</div>
		</article>
		
		<!--
		<article class="associations">
		<h2 class="left"><span class="icon-associations"></span> Assurance gratuite</h2>
		
			<div>		
		
				<fieldset >
				<label for="inscrit">Inscrit pour les années</label>
					<?php echo $inscrit ?>
				</fieldset>
		</div>
			<div>
				<fieldset >
				<label for="non_inscrit">Non inscrit pour les années</label>
					<?php echo $non_inscrit ?>
				</fieldset>
		</div>
		</article>
		-->
		
		<!--
		<article class="personnes">
		<h2><span class="icon-personnes"></span> Président</h2>
		<br class="clear">
			<div>
			
				
				<fieldset class="col2 nomargin">
				<label for="nom">Nom</label>
				<input type="text" name="nom_president"  value="" id="nom_president" />
				<ul id="nom_president_resultat" name="nom_president_resultat">
				</ul>
				<input type="hidden" name="id_president"  value="" id="id_president" />
				</fieldset>
					
			
		
				<fieldset class="col2">
				<label for="prenom_president">Prénom</label>
				<input type="text" name="prenom_president"  value="" id="prenom_president" />
				</fieldset>
		
			
		
				<fieldset class="col2">
				<label for="naissance_debut_president">Naissance du</label>
				<input type="text" name="naissance_debut_president"  value="" id="naissance_debut_president" class="date"/>
				</fieldset>
		
				<fieldset class="col2 ">
				<label for="naissance_fin_president">au</label>
				<input type="text" name="naissance_fin_president"  value="" id="naissance_fin_president" class="date"/>
				</fieldset>	
				
				<br class="clear">
				
				<fieldset>
				<label for="adresse_president">Adresse</label>
				<textarea id="adresse_president" name="adresse_president"></textarea >
				</fieldset>
				
				
				
				<fieldset class="col2" id="zone_ville_president">
				<label for="code_postal_president">Code postal</label>
				<input type="text" name="code_postal_president"  value="" id="code_postal_president" />
				<ul id="code_postal_president_resultat">
					
				</ul>
				<input hidden type="text" name="ville_president" id="ville_president" value="" >
				</fieldset>
				
			
		
			</div>
			<div>	
				<fieldset class="col2">
				<label for="pays_president">Pays</label>
				<select type="text" name="pays_president"  value="" id="pays_president" />
					<option value="0">Tous</option>
					<?php echo $Pays ?>
				</select>
				</fieldset>
				
				<fieldset class="col2" id="zone_ville_pays_president">
				<label for="code_pays">Code postal</label>
				<input type="text" name="code_president_pays"  value="<?php echo $perso->code_pays ?>" id="code_president_pays" />
				<label for="ville_pays">Ville</label>
				<input type="text" name="ville_president_pays"  value="<?php echo $perso->ville_pays ?>" id="ville_president_pays" />
				</fieldset>
				
				
				<fieldset class="col2 ">
				<label for="departement_president">Département</label>
				<select type="text" name="departement_president"  value="" id="departement_president" />
					<option value="0"></option>
					<?php echo $DepartementsP ?>
				</select>
				</fieldset>
		
		
				
				<fieldset class="col2 ">
				<label for="region_president">Région</label>
				<select type="text" name="region_president"  value="" id="region_president" />
					<option value="0"></option>
					<?php echo $RegionsP ?>
				</select>
				</fieldset>
			
			
				<fieldset class="col2">
				<label for="tel_mobile_president">Tel mobile</label>
				<input type="text" name="tel_mobile_president"  value="" id="tel_mobile_president" />
				</fieldset>
		
				<fieldset class="col2">
				<label for="tel_fixe_president">Tel fixe</label>
				<input type="text" name="tel_fixe_president"  value="" id="tel_fixe_president" />
				</fieldset>
		
			
		
				<fieldset class="col2">
				<label for="courriel_president">Courriel</label>
				<input type="text" name="courriel_president"  value="" id="courriel_president" />
				</fieldset>
			</div>
		
		</article>
		
	-->
		
		
		
		
		
		<article class="amis">
		<h2><span class="icon-amis"></span> Adhérent CNB</h2>
		<br class="clear">
		
		<div>
			
				<fieldset class="col2">
				<label for="etat_paiement">État pour l'année</label>
				<select id="laf_annee" name="laf_annee">
					<option value="0"></option>
					<?php echo $laf_annee ?>
					</select>
				</fieldset>
				
				
				<span id="zone_laf_2">
				
				
				
				<br class="clear">
				
				<fieldset class="col1_5">
				<label for="date_paiement_debut">Paiement du</label>
				<input type="text" name="date_paiement_debut"  value="" id="date_paiement_debut" class="date"/>
				</fieldset>
		
				<fieldset class="col1_5">
				<label for="date_paiement_fin">au</label>
				<input type="text" name="date_paiement_fin"  value="" id="date_paiement_fin" class="date"/>
				</fieldset>
				
				<fieldset class="col1_5">
				<label for="etat_paiement">État paiement</label>
				<select type="text" name="etat_paiement"  value="" id="etat_paiement" />
					<option value="0"></option>
					<option value="1">Validé</option>
					<option value="2">Non validé</option>
				</select>
				</fieldset>
				
				<!--
				<br class="clear">
				
				
				<fieldset class=" ">
				<label for="gmf">Assurance Multigaranties GMF 
				<select name="gmf" id="gmf" class="fixe">
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</label>
				</fieldset>
				
				<fieldset class=" ">
				<label for="depasse_gmf">Dépasse les critères GMF
				<select name="depasse_gmf" id="depasse_gmf"  class="fixe">
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</label>
				</fieldset>
				
				<fieldset class=" ">
				<label for="citizenplace">Logiciel CitizenPlace
				<select name="citizenplace" id="citizenplace" class="fixe" >
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</label>
				</fieldset>
				
				<fieldset class=" ">
				<label for="aide_citizenplace">Aide CitizenPlace
				<select name="aide_citizenplace" id="aide_citizenplace" class="fixe" >
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</label>
				</fieldset>
				
				<fieldset class=" ">
				<label for="groupama">Assurance Groupama
				<select name="groupama" id="groupama" class="fixe" >
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</label>
				</fieldset>
				
				<fieldset class="">
				<label for="banque_postale">Banque postale 
				<select name="banque_postale" id="banque_postale" class="fixe" >
					<option value="2"></option>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
				</fieldset>
				
				</span>
				-->
			</div>
			<div>	
				
				<span id="zone_laf_1">
				
				<fieldset class=" ">
				<label for="adherent_annees">Adhérent pour les années</label>
				<?php echo $adherent_annees ?>
				</fieldset>
				
				<br class="clear">
				
				<fieldset class="col2">
				<label for="nouvel_adherent">Nouvel adhérent</label>
				<select type="text" name="nouvel_adherent"  value="" id="nouvel_adherent" />
					<option value="0"></option>
					<?php echo $nouvel_adherent ?>
				</select>
				</fieldset>
				
				<fieldset class="alerte">
				<label for="non_inscrit_annee">Non inscrit (2014) <input type="checkbox" name="non_inscrit_annee"  value="" id="non_inscrit_annee" /></label>
				
				</fieldset>
		
				<fieldset class="alerte">
				<label for="renouvele_annee">Renouvelé (2014) <input type="checkbox" name="renouvele_annee"  value="" id="renouvele_annee" /></label>
				
				</fieldset>
		
				<fieldset class="alerte">
				<label for="non_renouvele_annee">Non renouvelé (2014) <input type="checkbox" name="non_renouvele_annee"  value="" id="non_renouvele_annee" /></label>
				
				</fieldset>
				
				
				</span>
				
				
				
				
				
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
			  <th data-dynatable-column="numero_dossier">N°</th>
			  <th data-dynatable-column="region">Région</th>
			  <th data-dynatable-column="departement">Département</th>
			  <th data-dynatable-column="telephone_fixe">Téléphone</th>
			  <th data-dynatable-column="telephone_mobile">Téléphone</th>
			  <th data-dynatable-column="courriel">Courriel</th>
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