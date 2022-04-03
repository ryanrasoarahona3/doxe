
<section>
	<div id="conteneur" class="distinctions ajouter">
	<form id="ajouter_distinctions">
		<article class="distinctions">
		
		<h2><span class="icon-distinctions"></span> Demande de Distinction</h2>
		<br class="clear">	
			<div>
				
				<?php if(isGestion() && $isChoixPersonne ) :  ?>
				
					<fieldset class="col1"  id="select_personne">
					<label for="choix_personne">Personne</label>
					<input type="text" name="choix_personne"  value="" id="choix_personne"  />
					<ul id="choix_personne_resultat">
						<?php echo $selectPersonne ?>
					</ul>
					<input hidden type="text" name="personne" id="personne" value=""   required>
					</fieldset>
				
				<?php endif; ?>
				
				<?php if(isGestion() ) : ?>
					<fieldset class="col1">
						<label for="annee">Année</label>
						<select  id="annee" name="annee">
								<?php echo $menuAnnee ?>
						</select>	
					</fieldset>
				<?php else : ?>	
					Année sélectionnée automatiquement A FAIRE
				<?php endif; ?>
				
				<fieldset class="col1">
						<label for="distinction_type">Distinction demandée</label>
						<select  id="distinction_type" name="distinction_type">
								<?php echo $menuDistinctions ?>
						</select>	
				</fieldset>
					
				<fieldset class="col1" >
					<label for="nbr_annees">Nombre d'années de bénévolat</label>
					<input type="number" name="nbr_annees"  value="<?php echo $distinction->nbr_annees?>" id="nbr_annees"   /> ans
					
				</fieldset>
				
				<br class="clear" />
				
				<fieldset class="" >
					<label for="nbr_annees">Domaines</label>
					<?php echo $choixDomaines ?>
					
				</fieldset>
				
				
				<!--
				
				<div id="ajoutActivites" class="groupe_champs">
					<h2>Activités en cours</h2>
					
				
					  <div id="ajoutActivites_controls" class="controls left">
						<button id="ajoutActivites_add" class="ajouter"></button>
						<button id="ajoutActivites_remove_last" class="supprimer"></button>
				
					  </div>
					  <br class="clear" />
					  
					  <div id="ajoutActivites_template" class="groupe_champs">
							<fieldset class="col3">
							<label for="ajoutActivites_#index#_association">Association <span id="ajoutActivites_label"></span></label>
							<input id="ajoutActivites_#index#_association" name="activites[#index#][association]" type="text" required/>
							</fieldset>
						
							<fieldset class="col3">
							<label for="ajoutActivites_#index#_fonction">Fonction </label>
							<select  id="ajoutActivites_#index#_fonction" name="activites[#index#][fonction]" required>
								<option value="0">Aucune</option>
								<?php echo $selectFonctions ?>
							</select>
							</fieldset>
							
							<fieldset class="col3">
							<label for="ajoutActivites_#index#_annee_debut">Depuis </label>
							<select  id="ajoutActivites_#index#_annee_debut" name="activites[#index#][annee_debut]" required>
								<?php echo $menuActivitesAnneeDebut ?>
							</select>
							</fieldset>
					
						<a id="ajoutActivites_remove_current"  class="left">
						  <img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
						</a>
					  </div>		 
					 
					  <div id="ajoutActivites_noforms_template">Veuillez ajouter une association</div>
					
				</div>
				
				<br class="clear">
				-->
				<hr>
				
				<div id="ajoutActivitesPassees" class="groupe_champs">
					<h2>Activités</h2>
					  <!-- Controls -->
					  <div id="ajoutActivitesPassees_controls" class="controls left">
						<button id="ajoutActivitesPassees_add"  class="ajouter"></button>
						<button id="ajoutActivitesPassees_remove_last" class="supprimer"></button>
						<!--<div id="ajoutActivitesPassees_remove_all"><a><span>Supprimer tout</span></a></div>
						<div id="ajoutActivitesPassees_add_n">
						  <input id="ajoutActivitesPassees_add_n_input" type="text" size="4" />
						  <div id="ajoutActivitesPassees_add_n_button"><a><span>Add</span></a></div>
						</div>-->
					  </div>
					  <!-- /Controls -->
					  <br class="clear" />
					  <!-- Form template-->
					  <div id="ajoutActivitesPassees_template" class="groupe_champs" >
							
							<fieldset class="col3">
							<label for="ajoutActivitesPassees_#index#_association">Association <span id="ajoutActivitesPassees_label"></span></label>
							<input id="ajoutActivitesPassees_#index#_association" name="activites_passees[#index#][association]" type="text" required/>
							</fieldset>
							
							<fieldset class="col3">
							<label for="ajoutActivitesPassees_#index#_fonction">Fonction </label>
							<select  id="ajoutActivitesPassees_#index#_fonction" name="activites_passees[#index#][fonction]" required>
								<option value="0">Aucune</option>
								<?php echo $selectFonctions ?>
							</select>
							</fieldset>
							
							
							<fieldset class="col3">
							<label for="ajoutActivitesPassees_#index#_fonction_autre">Autre fonction </label>
							<input id="ajoutActivitesPassees_#index#_fonction_autre" name="activites_passees[#index#][fonction_autre]" type="text" />
							</fieldset>
							
							<fieldset class="col3">
							<label for="ajoutActivitesPassees_#index#_annee_debut">Depuis </label>
							<select  id="ajoutActivitesPassees_#index#_annee_debut" name="activites_passees[#index#][annee_debut]" required>
								<?php echo $menuActivitesPasseesAnneeDebut ?>
							</select>
							</fieldset>
							
							<fieldset class="col3">
							<label for="ajoutActivitesPassees_#index#_annee_fin">Jusqu'à </label>
							<select  id="ajoutActivitesPassees_#index#_annee_fin" name="activites_passees[#index#][annee_fin]" >
								<option></option>
								<?php echo $menuActivitesPasseesAnneeFin ?>
							</select>
							</fieldset>
					
							<a id="ajoutActivitesPassees_remove_current"  class="left">
							  <img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
							</a>

							<hr>
					  </div>
					  <!-- /Form template-->
   
					  <!-- No forms template -->
					  <div id="ajoutActivitesPassees_noforms_template">Veuillez ajouter une association</div>
					  <!-- /No forms template-->
   
					
  
				</div>
				
				<br class="clear">
				<hr>
				<div id="ajoutDistinctions" class="groupe_champs">
					<h2>Distinctions</h2>
					 <!-- Controls -->
					  <div id="ajoutDistinctions_controls" class="controls left">
						<button id="ajoutDistinctions_add" class="ajouter"></button>
						<button id="ajoutDistinctions_remove_last" class="supprimer"></button>
						<!--<div id="ajoutDistinctions_remove_all"><a><span>Supprimer tout</span></a></div>
						<div id="ajoutDistinctions_add_n">
						  <input id="ajoutDistinctions_add_n_input" type="text" size="4" />
						  <div id="ajoutDistinctions_add_n_button"><a><span>Add</span></a></div>
						</div>-->
					  </div>
					  <!-- /Controls -->
					  <br class="clear" />
					  <!-- Form template-->
					  <div id="ajoutDistinctions_template"  class="groupe_champs">
						<div class="left">
							<label for="ajoutDistinctions_#index#_distinction">Distinction <span id="ajoutDistinctions_label"></span></label><br>
							<select  id="ajoutDistinctions_#index#_distinction" name="distinctions[#index#][distinction]" type="text" required>
								<?php echo $menuDistinctions ?>
							</select>	
						
							<label for="ajoutDistinctions_#index#_annee">Année de remise </label>
							<select  id="ajoutDistinctions_#index#_annee" name="distinctions[#index#][annee]">
								<?php echo $menuDistinctionsAnnee ?>
							</select>
								
						</div>
						<a id="ajoutDistinctions_remove_current"  class="left">
						  <img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
						</a>
					  </div>
					  <!-- /Form template-->
   
					  <!-- No forms template -->
					  <div id="ajoutDistinctions_noforms_template">Vous pouvez ajouter une distinction</div>
					  <!-- /No forms template-->
   
					 
  
				</div>
				
				
				<br class="clear" />
					<hr>
				<div id="ajoutDocuments"  class="groupe_champs">
					<h2>Documents</h2>
					
					<div>
						
							<fieldset class="col3">
							<input type="file" name="file_upload" id="file_upload" /> 

							</fieldset>
					</div>
						
						
					
					
  
				</div>
				
				
				<br class="clear" />
				<hr>
				<div id="ajoutParrains" class="groupe_champs">
				<h2><span class="icon-personnes"></span> Parrains</h2>

<?php if (!isGestion() ) : ?>

  <!-- Controls -->
					  <div id="ajoutParrains_controls" class="controls left">
						<button id="ajoutParrains_add" class="ajouter"></button>
						<button id="ajoutParrains_remove_last" class="supprimer"></button>
						<!--<div id="ajoutParrains_remove_all"><a><span>Supprimer tout</span></a></div>
						<div id="ajoutDocuments_add_n">
						  <input id="ajoutDocuments_add_n_input" type="text" size="4" />
						  <div id="ajoutDocuments_add_n_button"><a><span>Add</span></a></div>
						</div>-->
					  </div>
					  <br class="clear" />
<!-- Form template-->
<div id="ajoutParrains_template" class="groupe_champs">
 
 	
			<div>
				
				<!---->
		
				<fieldset class="col1">
				<label for="civilite">Civilité</label>
				<select type="text" id="ajoutParrains_#index#_civilite"  value="" name="parrains[#index#][civilite]" >
					<?php echo $menuCivilite ?>
				</select>
				</fieldset>
		
				<fieldset class="col3 nomargin">
				<label for="nom">Nom</label>
				<input type="text" id="ajoutParrains_#index#_nom"  value="<?php echo $perso->nom ?>" name="parrains[#index#][nom]" required/>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="prenom">Prénom</label>
				<input type="text" id="ajoutParrains_#index#_prenom"  value="<?php echo $perso->prenom ?>" name="parrains[#index#][prenom]" required/>
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="nom_jeune_fille">Nom de jeune fille</label>
				<input type="text" id="ajoutParrains_#index#_nom_jeune_fille"  value="<?php echo $perso->nom_jeune_fille ?>" name="parrains[#index#][nom_jeune_fille]" />
				</fieldset>
		
				<!---->
				<?php if(isGestion()) : ?>
					<fieldset>
					<label for="numero_adherent">N°Adhérent</label>
					<input type="text" id="ajoutParrains_#index#_numero_adherent"  value="<?php echo $perso->numero_adherent ?>" name="parrains[#index#][numero_adherent]" title="A renseigner uniquement pour saisir un ancien adhérent."/>
					</fieldset>
				<?php endif; ?>
		
				<fieldset class="col2">
				<label for="date_naissance">Date de naissance</label>
				<input type="text" id="ajoutParrains_#index#_date_naissance"  value="<?php if($perso->date_naissance != '0000-00-00') echo convertDate($perso->date_naissance,'php') ?>" name="parrains[#index#][date_naissance]" class="date"/>
				</fieldset>
		
			</div>
			<div>
				<fieldset>
				<label for="adresse">Adresse</label>
				<textarea name="parrains[#index#][adresse]" id="ajoutParrains_#index#_adresse" ><?php echo $perso->adresse ?></textarea >
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="pays">Pays</label>
				<select type="text" id="ajoutParrains_#index#_pays"  value="" name="parrains[#index#][pays]" >
					<?php echo $Pays ?>
				</select>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2" name="parrains[#index#][zone_ville">
				<label for="code_postal">Code postal</label>
				<input type="text" id="ajoutParrains_#index#_code_postal"  value="" name="parrains[#index#][code_postal]" title="Saisir le code postal et sélectionner une ville" />
				
				<ul name="parrains[#index#][code_postal_resultat">
					<?php echo $villeSelected ?>
				</ul>
				<?php if ($perso->ville > 0) : ?>
					<input hidden type="text" id="ajoutParrains_#index#_ville" name="parrains[#index#][ville]" data-parsley-trigger="change bind" value="<?php echo $perso->ville ?>" >
				<?php endif; ?>
				</fieldset>
				
				<fieldset class="col2" name="parrains[#index#][zone_ville_pays">
				<label for="code_pays">Code postal</label>
				<input type="text" id="ajoutParrains_#index#_code_pays"  value="<?php echo $perso->code_pays ?>" name="parrains[#index#][code_pays]" />
				<label for="ville_pays">Ville</label>
				<input type="text" id="ajoutParrains_#index#_ville_pays"  value="<?php echo $perso->ville_pays ?>" name="parrains[#index#][ville_pays]" />
				</fieldset>
			</div>
			<div>
				<fieldset class="col2 nomargin">
				<label for="telephone_fixe">Téléphone fixe</label>
				<input type="text" id="ajoutParrains_#index#_telephone_fixe"  value="<?php echo $perso->telephone_fixe ?>" name="parrains[#index#][telephone_fixe]" />
				</fieldset>
				
				<fieldset class="col2">
				<label for="telephone_mobile">Téléphone mobile</label>
				<input type="text" id="ajoutParrains_#index#_telephone_mobile"  value="<?php echo $perso->telephone_mobile ?>" name="parrains[#index#][telephone_mobile]" />
				</fieldset>
		
				
		
				<!---->
		
				<fieldset>
				<label for="courriel">Courriel</label>
				<input type="email" id="ajoutParrains_#index#_courriel"  value="<?php echo $perso->courriel ?>" name="parrains[#index#][courriel]" data-parsley-type="email" data-parsley-trigger="change"  />
				</fieldset>
				
				<fieldset>
				<label for="profession">Profession</label>
				<input type="text" id="ajoutParrains_#index#_profession"  value="<?php echo $perso->profession ?>" name="parrains[#index#][profession]" />
				</fieldset>
				
		
			</div>
		
	
			<div class="simple">

				<fieldset class="col1">
				<label for="presse">Organisme de presse</label>
				<input type="text" id="ajoutParrains_#index#_presse"  value="<?php echo $perso->presse ?>" name="parrains[#index#][presse]" />
				</fieldset>
		
				<fieldset class="col1">
				<label for="elu">Élu local</label>
				<select type="text" id="ajoutParrains_#index#_elu"  value="" name="parrains[#index#][elu]" >
					<option value="0">Choisissez</option>
					<?php echo $ElusFonctions ?>
				</select>
				</fieldset>
				
				<input type="hidden" id="ajoutParrains_#index#_prospect"  value="1" name="parrains[#index#][prospect]" />
				
			</div>
		<a id="ajoutParrains_remove_current"  class="left">
						  <img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
		</a>
</div>
					 <!-- /Form template-->
					  <!-- No forms template -->
					  <div id="ajoutParrains_noforms_template">Veuillez ajouter un parrain</div>
					  <!-- /No forms template-->
   
<?php endif; ?>			
	
	
<?php if (isGestion() ) : ?>				
	<!-- Controls -->
	  <div id="ajoutParrains_controls" class="controls left" >
		<button id="ajoutParrains_add" class="ajouter"></button>
		<button id="ajoutParrains_remove_last" class="supprimer"></button>
		<!--<div id="ajoutParrains_remove_all"><a><span>Supprimer tout</span></a></div>
		<div id="ajoutDocuments_add_n">
		  <input id="ajoutDocuments_add_n_input" type="text" size="4" />
		  <div id="ajoutDocuments_add_n_button"><a><span>Add</span></a></div>
		</div>-->
	  </div>
	 <br class="clear">	


	<!-- Form template-->
	<div id="ajoutParrains_template" class="groupe_champs">
 
	
		
		<fieldset class="col3 ">
		<label for="nom">Nom</label>
		<input type="text" id="ajoutParrains_#index#_nom"  value="<?php echo $perso->nom ?>" name="parrains[#index#][nom]" />
		</fieldset><br>
		<ul id="ajoutParrains_#index#_nom_resultat">
				
					<!--
					<li class="ajoutParrains_0_nom_53">Lebonnois Stéphane (123)<a href="#" class="ajoutParrains_0_nom_remove ui-icon ui-icon-circle-close right"></a><input type="hidden" name="ajoutParrains_0_nom_tab_resultat[]" value="53" class="ajoutParrains_0_nom_53"></li>
					-->
		</ul>
		<a id="ajoutParrains_remove_current"  class="left">
						  <img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
		</a>		
		
	</div>

	
	 <!-- /Form template-->
	  <!-- No forms template -->
	  <div id="ajoutParrains_noforms_template">Veuillez ajouter un parrain</div>
	  <!-- /No forms template-->
</div>

<?php endif; ?>
<hr>
<br class="clear">

<fieldset class="col1">
						<label for="annuaire">Accepter de figurer dans l'annuaire</label>
						<select  id="annuaire" name="annuaire">
								<?php echo $menuAnnuaire ?>
						</select>	
				</fieldset>
	

<?php if (isGestion() ) : ?>	

				<fieldset class="col1">
						<label for="distinction_avis">Avis du délégué</label>
						<select  id="avis" name="avis">	
								<?php echo $menuAvis ?>
						</select>	
				</fieldset>
				
				<fieldset class="col1">
						<label for="distinction_type_decision">Décision du jury</label>
						<select  id="distinction_type_decision" name="distinction_type_decision">
								<option value="0">Décision en attente </option>
								<?php echo $menuDistinctionsDecision ?>
						</select>	
				</fieldset>
				
				<fieldset class="col1">
				<label for="type_validation">Type de validation</label>
				<select type="text" id="type_validation"   name="type_validation" >
					<?php echo $menuValidation ?>
				</select>
				</fieldset>
				
				
				<fieldset class="col1">
				<label for="documents_complets">Documents complets</label>
				<select name="documents_complets" id="documents_complets">
					<option value="1" <?php if ((!empty($distinction->documents_complets)) && ($distinction->documents_complets == 1)) echo 'selected';?>>Oui</option>
					<option value="0" <?php if ((!empty($distinction->documents_complets)) && ($distinction->documents_complets == 0)) echo 'selected';?>>Non</option>
				</select>
				</fieldset>
				
				<fieldset class='zone_tinymce'>
				<label for="commentaire">Commentaire (200 caractères maximum)</label>
				<script language="javascript"> 
function maxlength(text,length) {if(text.innerText.length>length) text.innerText=text.innerText.substr(0,length); } 
</script> 
				<textarea name="commentaire"   id="commentaire" maxlength="200" onkeypress=\"javascript:maxlength(this,200);\"><?php echo $distinction->commentaire ?></textarea>
				</fieldset>
				
	<?php endif; ?>

	</div>				
		</article>
		
		<div id="erreur">
				</div>
		<div id="zone_validation" style="margin-bottom:100px;float:none;clear: both;overflow: auto;">
			<?php if ($form->suppression) : ?>
				<button type="button" id="action_supprimer" class="annuler">- Supprimer</button>
			<?php else : ?>
				<?php echo $alerte_supprime ?>
			<?php endif; ?>
			<?php if ($form->annulation) : ?><button type="button" id="action_annuler" class="annuler">X Annuler</button><?php endif; ?>
			
			<button type="button" id="action_valider" ><span class="icon-associations"></span>  <?php echo $form->label_validation ?></button>
			
		</div>
		
		
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		<?php if(!$isChoixPersonne) : ?>
			<input type="hidden" id="personne" name="personne" value="<?php echo $form->personne ?>">
		<?php endif; ?>
		<input type="hidden" id="id_lien" name="id_lien" value="<?php echo $form->id_lien ?>">
		
		
	</form>
	
</div>
</section>	

<div id="dialog-modal-distinctions" title="Alerte" class="modal alerte">
	<p>Il existe déjà une adhésion aux Cercle National des Bénévoles pour l'année sélectionnée.</p>
</div>
