<section>
	<div id="conteneur" class="personnes ajouter">

	<form id="ajouter_personnes">
		<article class="personnes etape" id="infos_generales">
		<h2><span class="icon-personnes"></span> Informations générales</h2><br class="clear">
			<div>
				
				<!---->
		
				<fieldset class="col1">
				<label for="civilite">Civilité</label>
				<?php echo $commentaires['civilite'] ?>
				<select type="text" name="civilite"  value="" id="civilite" <?php echo $requis['civilite'] ?>>
					<option value="">Choisir</option>
					<?php echo $menuCivilite ?>
				</select>
				</fieldset>
		
				<fieldset class="col3 nomargin">
				<label for="nom">Nom</label>
				<?php echo $commentaires['nom'] ?>
				<input type="text" name="nom"  value="<?php echo $perso->nom ?>" id="nom"  <?php echo $requis['nom'] ?>/>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="prenom">Prénom</label>
				<?php echo $commentaires['prenom'] ?>
				<input type="text" name="prenom"  value="<?php echo $perso->prenom ?>" id="prenom"  <?php echo $requis['prenom'] ?>/>
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="nom_jeune_fille">Nom de jeune fille</label>
				<?php echo $commentaires['nom_jeune_fille'] ?>
				<input type="text" name="nom_jeune_fille"  value="<?php echo $perso->nom_jeune_fille ?>" id="nom_jeune_fille"  <?php echo $requis['nom_jeune_fille'] ?>/>
				</fieldset>
		
			<?php if (isGestion()) : ?>
				<!--
				<fieldset>
				<label for="numero_adherent">N°Adhérent</label>
				<?php echo $commentaires['association_type'] ?>
				<input type="text" name="numero_adherent"  value="<?php echo $perso->numero_adherent ?>" id="numero_adherent" title="A renseigner uniquement pour saisir un ancien adhérent."  <?php echo $requis['numero_adherent'] ?>/>
				</fieldset>
				-->
			<?php endif ; ?>
		
				<fieldset class="col2">
				<label for="date_naissance">Date de naissance</label>
				<?php echo $commentaires['date_naissance'] ?>
				<input type="text" name="date_naissance"  value="<?php if($perso->date_naissance != '0000-00-00') echo convertDate($perso->date_naissance,'php') ?>" id="date_naissance" class="date"  <?php echo $requis['date_naissance'] ?>/>
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="mdp">Mot de passe</label>
				<?php echo $commentaires['mdp'] ?>
				<input type="text" name="mdp"  value="" id="mdp" title="Généré automatiquement si non renseigné"  <?php echo $requis['mdp'] ?>/>
				</fieldset>
				
				<fieldset class="col2 habilite">
				<label for="delegue_habilite" class="clear_right">Abonnement Newsletter</label>
				<input type="radio" name="newsletter"  value="1" id="newsletter"  <?php echo $newsletterOui ?>/> Oui     		
				<input type="radio" name="newsletter"  value="0" id="newsletter"  <?php echo $newsletterNon ?>/> Non
				</fieldset>
				
				
			</div>
			<div>
				<fieldset>
				<label for="adresse">Adresse</label>
				<?php echo $commentaires['adresse'] ?>
				<textarea id="adresse" name="adresse"  <?php echo $requis['adresse'] ?>><?php echo $perso->adresse ?></textarea >
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="pays">Pays</label>
				<?php echo $commentaires['pays'] ?>
				<select type="text" name="pays"  value="" id="pays"  <?php echo $requis['pays'] ?>>
					<?php echo $Pays ?>
				</select>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2" id="zone_ville">
				<label for="code_postal">Code postal</label>
				<?php echo $commentaires['code_postal'] ?>
				<input type="text" name="code_postal"  value="" id="code_postal" title="Saisir le code postal et sélectionner une ville" />
				
				<ul id="code_postal_resultat">
					<?php echo $villeSelected ?>
				</ul>
				<?php if ($perso->ville > 0) : ?>
					<input hidden type="text" name="ville" id="ville" data-parsley-trigger="change bind" value="<?php echo $perso->ville ?>"  <?php echo $requis['ville'] ?>>
				<?php endif; ?>
				</fieldset>
				
				<fieldset class="col2" id="zone_ville_pays">
				<label for="code_pays">Code postal</label>
				<?php echo $commentaires['code_pays'] ?>
				<input type="text" name="code_pays"  value="<?php echo $perso->code_pays ?>" id="code_pays" />
				<label for="ville_pays">Ville</label>
				<?php echo $commentaires['ville_pays'] ?>
				<input type="text" name="ville_pays"  value="<?php echo $perso->ville_pays ?>" id="ville_pays" />
				</fieldset>
			</div>
			<div>
				<fieldset class="col2 nomargin">
				<label for="telephone_fixe">Téléphone fixe</label>
				<?php echo $commentaires['telephone_fixe'] ?>
				<input type="text" name="telephone_fixe"  value="<?php echo $perso->telephone_fixe ?>" id="telephone_fixe" />
				</fieldset>
				
				<fieldset class="col2">
				<label for="telephone_mobile">Téléphone mobile</label>
				<?php echo $commentaires['telephone_mobile'] ?>
				<input type="text" name="telephone_mobile"  value="<?php echo $perso->telephone_mobile ?>" id="telephone_mobile" />
				</fieldset>
		
				
		
				<!---->
		
				<fieldset>
				<label for="courriel">Courriel</label>
				<?php echo $commentaires['courriel'] ?>
				<!-- A ajouter si champ requis : data-parsley-type="email" data-parsley-trigger="change"   <?php echo $requis['courriel'] ?> -->
				<input type="email" name="courriel"  value="<?php echo $perso->courriel ?>" id="courriel" />
				<span id="erreur_courriel" class="alerte"></span>
				</fieldset>
				
				<fieldset>
				<label for="profession">Profession</label>
				<?php echo $commentaires['profession'] ?>
				<input type="text" name="profession"  value="<?php echo $perso->profession ?>" id="profession" />
				</fieldset>
				
				<?php if (isGestion()) : ?>
					<fieldset>
					<label for="portrait">Portrait</label>
					<?php echo $commentaires['portrait'] ?>
					
					
					<input type="file" name="file_upload" id="file_upload" /> 
					
					</fieldset>
				<?php endif; ?>
				
				<?php if (!isGestion()) : ?>
					<fieldset>
					<label for="newsletter">Lettre d'information</label>
					<?php echo $commentaires['newsletter'] ?>
					<input type="checkbox" name="newsletter"  value="1" id="newsletter" <?php echo $selectNewsletter ?>/>
					</fieldset>
				<?php endif; ?>
		
			</div>
		</article>
		
		<?php if (isGestion()) : ?>
		
		<article class="personnes"  id="particularites">
		<h2>Particularités</h2>
			<div class="simple">
				
				
				

				<fieldset class="col1">
				<label for="presse">Organisme de presse</label>
				<?php echo $commentaires['presse'] ?>
				<input type="text" name="presse"  value="<?php echo $perso->presse ?>" id="presse" />
				</fieldset>
		
				<fieldset class="col1">
				<label for="elu">Élu local</label>
				<?php echo $commentaires['elu'] ?>
				<select type="text" name="elu"  value="" id="elu" >
					<option value="0">Choisissez</option>
					<?php echo $ElusFonctions ?>
				</select>
				</fieldset>
				
				<fieldset class="col2 habilite">
				<label for="prospect" class="clear_right">Prospect</label>
				<input type="radio" name="prospect"  value="1" id="prospect"  <?php echo $prospectOui ?>/> Oui     		
				<input type="radio" name="prospect"  value="0" id="prospect"  <?php echo $prospectNon ?>/> Non
				</fieldset>
				
				
				
				
			</div>
		</article>
		
		<?php endif; ?>
		
		<?php if ($_SESSION['utilisateur']['siege'] == 1) : ?>
		
		
		<!--
		<article class="personnes"  id="delegue_special">
		<h2>Délégué spécial</h2>
			<div >
				<fieldset >
				<label for="ville">Association</label>
				<?php echo $commentaires['association_special_select'] ?>
				<input type="text" name="association_special_select"  value="" id="association_special_select"  />
				<ul id="association_special_select_resultat">
				<?php echo $delegueSpecial ?>
				</ul>	
				</fieldset>
				
			</div>
		</article>
		-->
		
		
		<article class="personnes"  id="delegue">
		<button type="button" class="edit" id="affiche_selection_delegue"><span class="icon-edit"></span></button><h2>Accès Extranet</h2>
			<div id="selection_delegue" >
				
				
				<fieldset class="col1">
				<label for="siege">Accès siège</label>
				<?php echo $commentaires['siege'] ?>
				<select type="text" name="siege"  value="" id="siege" >
					<option value="0">Aucun</option>
					<?php echo $menuSiege ?>
				</select>
				</fieldset>
				
				<fieldset class="col1 habilite">
				<label for="delegue_habilite" class="clear_right">Siège habilité</label>
				<?php echo $commentaires['siege_habilite'] ?>
				<input type="radio" name="siege_habilite"  value="1" id="siege_habilite"  <?php echo $selectSiegeHabiliteOui ?>/> Oui     		
				<input type="radio" name="siege_habilite"  value="0" id="siege_habilite"  <?php echo $selectSiegeHabiliteNon ?>/> Non
				</fieldset>
				
				
				<fieldset class="col1">
				<label for="delegue_statut">Statut</label>
				<?php echo $commentaires['delegue_statut'] ?>
				<select type="text" name="delegue_statut"  value="" id="delegue_statut" >
					<?php echo $menuDelegueStatut ?>
				</select>
				</fieldset>
				
				
				
				<fieldset class="col1 responsabilite">
				<label for="delegue_type">Type responsabilité</label>
				<?php echo $commentaires['delegue_type'] ?>
				<select type="text" name="delegue_type"  value="" id="delegue_type" >
					<?php echo $menuDelegueType ?>
				</select>
				</fieldset>
				
				
				<fieldset class="col1 adjoint">
				<label for="delegue_adjoint" class="clear_right">Adjoint</label>
				<?php echo $commentaires['delegue_adjoint'] ?>
				<input type="radio" name="delegue_adjoint"  value="1" id="delegue_adjoint" <?php echo $selectAdjointOui ?>/> Oui     		
				<input type="radio" name="delegue_adjoint"  value="0" id="delegue_adjoint" <?php echo $selectAdjointNon ?>/> Non
				</fieldset>
				
				
				<fieldset class="col1 habilite">
				<label for="delegue_habilite" class="clear_right">Habilité</label>
				<?php echo $commentaires['delegue_habilite'] ?>
				<input type="radio" name="delegue_habilite"  value="1" id="delegue_habilite"  <?php echo $selectHabiliteOui ?>/> Oui     		
				<input type="radio" name="delegue_habilite"  value="0" id="delegue_habilite"  <?php echo $selectHabiliteNon ?>/> Non
				</fieldset>
			
			<br class="clear">
			
				<fieldset class="region">
				<h2>Région / Département</h2>
				<br class="clear">
				
				<?php echo $MenuRegionsDepartements ?>
				
				</fieldset>
				
			</div>
		</article>
		
		<?php endif ; ?>	

		
		
			<div id="zone_validation">
				<?php if ($form->annulation) : ?>
					<button type="button" id="action_annuler" class="annuler">< Annuler</button>
					<?php if (isSiege()) : ?>
						<button  type="button" form-action="supprimer" form-element="section=personnes&id=<?php echo $perso->id_personne ?>" ><span class="icon-trash"></span> Supprimer</button>
					<?php endif; ?>
				<?php endif; ?>
				<button type="button" id="action_valider"><span class="icon-associations"></span> <?php echo $form->label_validation ?></button>
			</div>

	
		
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