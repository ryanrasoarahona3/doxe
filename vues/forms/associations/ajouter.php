<section id="formulaire">
<div id="conteneur" class="associations ajouter">

	<form id="ajouter_associations" >
		<?php if ($mode == 'site_ajouter') : ?>
			<ol id="nav_form">
				<li id="lien1" class="inactif">Informations générales</li>
				<li id="lien2" class="inactif">Membres du conseil d'administration</li>
				<li id="lien3" class="inactif">Finaliser l'inscription</li>
			</ol>
		<?php endif; ?>
		<article class="associations etape etape1">
		<h2><span class="icon-associations"></span>Informations générales</h2>
			<div>
				<fieldset class="col2">
				<label for="association_type"><?php echo $legendes['association_type'] ?></label>
				<?php echo $commentaires['association_type'] ?>
				<select type="text" name="association_type" id="association_type" >
					<?php echo $AssociationsTypes ?>
				</select>
				</fieldset>
				
				<fieldset class="col2 nomargin zone_association">
				<label for="numero_dossier"><?php echo $legendes['numero_dossier'] ?></label>
				<?php echo $commentaires['numero_dossier'] ?>
				<input type="text" name="numero_dossier"  value="<?php echo $assoc->numero_dossier ?>" id="numero_dossier" <?php echo $requis['numero_dossier'] ?> />
				<span id="erreur_numero_dossier" class="alerte"></span>
				</fieldset>
				
				<fieldset class="col2 nomargin zone_commune">
				<label for="numero_convention"><?php echo $legendes['numero_convention'] ?></label>
				<?php echo $commentaires['numero_convention'] ?>
				<input type="text" name="numero_convention"  value="<?php echo $assoc->numero_convention ?>" id="numero_convention" <?php echo $requis['numero_convention'] ?> />
				</fieldset>
				
				<br class="clear">
				
				<fieldset class="col2 nomargin">
				<label for="nom"><?php echo $legendes['nom'] ?></label>
				<?php echo $commentaires['nom'] ?>
				<input type="text" name="nom"  value="<?php echo $assoc->nom ?>" id="nom"  <?php echo $requis['nom'] ?>/ >
				</fieldset>
		
				<fieldset class="col2">
				<label for="sigle"><?php echo $legendes['sigle'] ?></label>
				<?php echo $commentaires['sigle'] ?>
				<input type="text" name="sigle"  value="<?php echo $assoc->sigle ?>" id="sigle" />
				</fieldset>
				
				<?php if(isGestion()) : ?>	
					<br class="clear">
				
					<fieldset class="col2">
					<label for="numero_adherent"><?php echo $legendes['numero_adherent'] ?>Numéro d'adhérent</label>
				<?php echo $commentaires['numero_adherent'] ?>
					<input type="text" name="numero_adherent"  value="<?php echo $assoc->numero_adherent ?>" id="numero_adherent" />
					</fieldset>
				<?php endif; ?>
				
				
		
				<br class="clear">
				
				<fieldset class="col2 zone_association">
				<label for="association_activites"><?php echo $legendes['association_activites'] ?></label>
				<?php echo $commentaires['association_activites'] ?>
				<select type="text" multiple="multiple"  name="association_activites[]"  value="" id="association_activites" >
					<option value="">Choisissez</option>
					<?php echo $Activites ?>
				</select>
				
				</fieldset>
		
		
				<fieldset class="col2 zone_association">
				<label for="date_declaration_jo" zone_association><?php echo $legendes['date_declaration_jo'] ?></label>
				<?php echo $commentaires['date_declaration_jo'] ?>
				<input type="text" name="date_declaration_jo"  value="<?php echo convertDate($assoc->date_declaration_jo,'php') ?>" id="date_declaration_jo" class="date"  data-parsley-trigger="change bind" />
				</fieldset>
				
			</div>

			<div>	
				
				<fieldset class="col2 zone_association">
				<label for="numero_siret"><?php echo $legendes['numero_siret'] ?></label>
				<?php echo $commentaires['numero_siret'] ?>
				<input type="text" name="numero_siret"  value="<?php echo $assoc->numero_siret ?>" id="numero_siret" <?php echo $requis['numero_siret'] ?> />
				</fieldset>
				
				<fieldset class="col2 nomargin zone_association">
				<label for="code_ape_naf"><?php echo $legendes['code_ape_naf'] ?></label>
				<?php echo $commentaires['code_ape_naf'] ?>
				<input type="text" name="code_ape_naf"  value="<?php echo $assoc->code_ape_naf ?>" id="code_ape_naf" <?php echo $requis['code_ape_naf'] ?> />
				</fieldset>
				
				<fieldset>
				<label for="adresse"><?php echo $legendes['adresse'] ?></label>
				<?php echo $commentaires['adresse'] ?>
				<textarea id="adresse" name="adresse" <?php echo $requis['adresse'] ?> ><?php echo $assoc->adresse ?></textarea >
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="code_postal" id="label_code_postal"><?php echo $legendes['code_postal'] ?></label>
				<?php echo $commentaires['code_postal'] ?>
				<input type="text" name="code_postal"  value="" id="code_postal" />
				<ul id="code_postal_resultat">
					<?php echo $villeSelected ?>
				</ul>
				<input hidden type="text" name="ville" id="ville" data-parsley-trigger="change bind" value="<?php echo $assoc->ville ?>" <?php echo $requis['ville'] ?> />
				</fieldset>
		
			</div>
			
			<div>	
			
				<fieldset class="col2">
				<label for="telephone_mobile"><?php echo $legendes['telephone_mobile'] ?></label>
				<?php echo $commentaires['telephone_mobile'] ?>
				<input type="text" name="telephone_mobile"  value="<?php echo $assoc->telephone_mobile ?>" id="telephone_mobile" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="telephone_fixe"><?php echo $legendes['telephone_fixe'] ?></label>
				<?php echo $commentaires['telephone_fixe'] ?>
				<input type="text" name="telephone_fixe"  value="<?php echo $assoc->telephone_fixe ?>" id="telephone_fixe" />
				</fieldset>
		
				<fieldset class="col2">
				<label for="fax"><?php echo $legendes['fax'] ?></label>
				<?php echo $commentaires['fax'] ?>
				<input type="text" name="fax"  value="<?php echo $assoc->fax ?>" id="fax" />
				</fieldset>
					
				<!---->
		
				<fieldset class="col2">
				<label for="courriel"><?php echo $legendes['courriel'] ?></label>
				<?php echo $commentaires['courriel'] ?>
				<input type="email" name="courriel"  value="<?php echo $assoc->courriel ?>" id="courriel" data-parsley-type="email" data-parsley-trigger="change" <?php echo $requis['courriel'] ?>/>
				</fieldset>
				
				<fieldset class="col2">
				<label for="mdp_clair"><?php echo $legendes['mdp_clair'] ?></label>
				<?php echo $commentaires['mdp_clair'] ?>
				<input type="text" name="mdp_clair"  value="" id="mdp_clair" <?php echo $requis['mdp'] ?>/>
				</fieldset>
				
				<?php if(isGestion()) : ?>	
					<fieldset  class="col2">
					<label for="delegue_special_select">Délégué spécial</label>
					<input type="text" name="delegue_special_select"  value="" id="delegue_special_select"  />
					<ul id="delegue_special_select_resultat">
					
					</ul>
					<input type="hidden" name="delegue_special" id="delegue_special" >
					</fieldset>
				
					<fieldset class="">
					<label for="prospect">Prospect <input type="checkbox" name="prospect"  value="1" id="prospect" <?php echo $selectProspect ?> /></label>
			
					</fieldset>
				<?php endif; ?>
				
				<fieldset class="col2">
				<label for="logo"><?php echo $legendes['logo'] ?></label>
				<?php echo $commentaires['logo'] ?>
				<input type="file" name="logo"  value="" id="logo" />
				</fieldset>
	
			</div>
			
		
		</article>
		
		<!-- ÉTAPE 2 DU FORMULAIRE -->
		
		<?php if ($mode == 'site_ajouter') : ?>
		
			<article class="associations etape etape2">
				<h2><span class="icon-personnes"></span>Membres du conseil d'administration</h2>			
				<div>
				
					<div id="ajoutCA">
				
						<!-- Controls -->
						<div id="ajoutCA_controls" class="controls left" >
							<button id="ajoutCA_add" class="ajouter"></button>
							<!--<button id="ajoutCA_remove_last" class="supprimer"></button>-->
							<!--<div id="ajoutCA_remove_all"><a><span>Supprimer tout</span></a></div>
							<div id="ajoutDocuments_add_n">
							  <input id="ajoutDocuments_add_n_input" type="text" size="4" />
							  <div id="ajoutDocuments_add_n_button"><a><span>Add</span></a></div>
							</div>-->
						</div>
						<br class="clear">	

						<!-- Form template-->
						<div id="ajoutCA_template">
		
							<fieldset class="">
							<label for="civilite">Civilité</label>
							<select type="text" name="ajoutCA[#index#][civilite]"  value="" id="cajoutCA_#index#_civilite" required>
								<?php echo $menuCivilite ?>
							</select>
							</fieldset>
				
							<fieldset class="">
							<label for="nom">Nom</label>
							<input type="text" id="ajoutCA_#index#_nom" name="ajoutCA[#index#][nom]" <?php echo $requis['nom_ca'] ?>/>
							</fieldset>
							<ul id="ajoutCA_#index#_nom_resultat">
							</ul>
							
							<fieldset class="">
							<label for="prenom">Prénom</label>
							<input type="text" id="ajoutCA_#index#_prenom" name="ajoutCA[#index#][prenom]" <?php echo $requis['prenom_ca'] ?>/>
							</fieldset>
							<ul id="ajoutCA_#index#_prenom_resultat">
							</ul>
						
							<fieldset class="">
							<label for="ca">Fonction au sein du conseil d'administration</label>
							<select type="text" name="ajoutCA[#index#][fonction]"  value="" id="cajoutCA_#index#_fonction" <?php echo $requis['fonction_ca'] ?>>
								<?php echo $fonctions ?>
							</select>
							</fieldset>
							
							<fieldset class="col2 nomargin">
							<label for="assure">Bénévole</label>
							<?php echo $commentaires['benevole'] ?>
							<input type="checkbox" name="ajoutCA[#index#][benevole]"  value="1" id="cajoutCA_#index#_benevole" />
							</fieldset>
							
							<fieldset>
							<label for="courriel">Courriel</label>
							<input type="email" name="ajoutCA[#index#][courriel]" id="ajoutCA_#index#_courriel" data-parsley-type="email" data-parsley-trigger="change"  <?php echo $requis['courriel_ca'] ?>/>
							</fieldset>
							
							<fieldset class="col2">
							<label for="date_naissance">Date de naissance</label>
							<input type="text" name="ajoutCA[#index#][date_naissance]"   id="ajoutCA_#index#_date_naissance" class="date"/>
							</fieldset>
							
							<fieldset>
							<label for="adresse">Adresse</label>
							<textarea id="ajoutCA_#index#_date_adresse" name="ajoutCA[#index#][adresse]" <?php echo $requis['adresse_ca'] ?>><?php echo $perso->adresse ?></textarea >
							</fieldset>
		
							<fieldset class="col2">
							<label for="pays">Pays</label>
							<select type="text" name="ajoutCA[#index#][pays]"  value="" id="ajoutCA_#index#_pays" >
								<?php echo $Pays ?>
							</select>
							</fieldset>	
		
							<fieldset class="col2" id="ajoutCA_#index#_zone_ville">
							<label for="code_postal" id="ajoutCA_#index#_label_code_postal">Code postal</label>
							<input type="text" name="ajoutCA_#index#_code_postal"  value="" id="ajoutCA_#index#_code_postal" />
							<ul id="ajoutCA_#index#_code_postal_resultat">
							</ul>
							<input hidden type="text" name="ajoutCA[#index#][ville]" id="ajoutCA_#index#_ville" data-parsley-trigger="change bind"  <?php echo $requis['ville_ca'] ?> />
							</fieldset>
				
							<fieldset class="col2" id="ajoutCA_#index#_zone_ville_pays">
							<label for="code_pays">Code postal</label>
							<input type="text" name="ajoutCA[#index#][code_pays]"  id="ajoutCA_#index#_code_pays" />
							<label for="ville_pays">Ville</label>
							<input type="text" name="ajoutCA[#index#][ville_pays]"  id="ajoutCA_#index#_ville_pays" />
							</fieldset>
		
							<fieldset class="col2 nomargin">
							<label for="telephone_fixe">Téléphone fixe</label>
							<input type="text" name="ajoutCA[#index#][telephone_fixe]" id="ajoutCA_#index#_telephone_fixe" />
							</fieldset>
				
							<fieldset class="col2">
							<label for="telephone_mobile">Téléphone mobile</label>
							<input type="text" name="ajoutCA[#index#][telephone_mobile]"  id="ajoutCA_#index#_telephone_mobile" />
							</fieldset>
	
							<a id="ajoutCA_remove_current"  class="left">
							<img class="delete" src="css/images/cross.png" width="16" height="16" border="0">
							</a>		
		
						</div>

						  <!-- /Form template-->
						  <!-- No forms template -->
						  <div id="ajoutCA_noforms_template">Veuillez ajouter un membre du conseil d'administration</div>
						  <!-- /No forms template-->
					</div>
					
				
					
				</div>
				
				
				
			</article>
			
			<article class="associations etape etape3">
				<h2><span class="icon-personnes"></span>Finaliser votre inscription</h2>			
				<div>
					<input type="checkbox" name="informer_ca"  value="1" id="informer_ca" />
					Souhaitez-vous informer les membres du conseil d'administration de l'inscription de l'association ?<br>
					Ils recevront un code d'accès qui leur permettra de gérer leurs informations personnelles.
					
					
					<fieldset class="col2">
					<label>Veuillez recopier le code de sécurité dans le champ ci-dessous.</label>
					<img src="<?php echo GESTION?>/libs/captcha.php" />
					<input type="text" name="captcha" id="captcha" style="width:70px"/>
					<span id="erreur_captcha" class="alerte"></span>
     				</fieldset>
				</div>
			</article>
			
		<?php endif; ?>
		
		<!-- ZONE DE VALIDATION -->
		
		<?php if (isGestion()) : ?>
			<div id="zone_validation">
				<?php if ($form->annulation) : ?>
					<button type="button" id="action_annuler" class="annuler"> Annuler</button>
					<button  type="button" form-action="supprimer" form-element="section=associations&id=<?php echo $assoc->id_association ?>" ><span class="icon-trash"></span> Supprimer</button>
					
				<?php endif; ?>
				<button type="button" id="action_valider"><span class="icon-personnes"></span><?php echo $form->label_validation ?></button>
			</div>
		<?php elseif ($mode == 'site_ajouter') : ?>
			<div id="zone_validation_inscription">
				<button form-action="inscription" form-type="retour" class="retour_inscription">Retour</button>	
				<button type="button" id="action_valider"><span class="icon-personnes"></span><?php echo $form->label_validation ?></button>
			</div>
		<?php endif; ?>
		
		<!-- HIDDEN -->
		
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		<input type="hidden" id="action" name="action" value="<?php echo $form->action ?>">
		<input type="hidden" id="section" name="section" value="<?php echo $form->section ?>">
		
		<?php if(isset($form->id_element)) : ?>
			<input type="hidden" id="id_element" name="id_element" value="<?php echo $form->id_element ?>">
		<?php endif; ?>
		
		
	</form>
</div>

</section>	