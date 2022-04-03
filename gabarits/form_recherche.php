<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Fondation</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet/less" type="text/css" href="../css/styles.less" />
	<link rel="stylesheet/less" type="text/css" href="../js/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />
	
	<script src="../js/less.js"></script>

	<script src="../js/jquery-ui/js/jquery-1.10.2.js"></script>
	<script src="../js/jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src="../js/dynatable/jquery.dynatable.js"></script>
	
	<script src="../js/init.js"></script>
	<script src="../js/fonctions.js"></script>
	
	
	<link rel="stylesheet" href="../css/icones/style.css" />
<!--[if lt IE 8]><!-->
	<link rel="stylesheet" href="../css/icones/ie7/ie7.css">
	<!--<![endif]-->
</head>
<body>

<nav>
<ul>
	<a href="#">
	<li class="home">
		<span class="icon-home"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="alertes">
		<span class="icon-alertes"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="personnes actif">
		<span class="icon-personnes"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="associations">
		<span class="icon-associations"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="distinctions">
		<span class="icon-distinctions"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="achats">
		<span class="icon-achats"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="annonces">
		<span class="icon-annonces"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="partenaires">
		<span class="icon-partenaires"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="fichiers">
		<span class="icon-fichiers"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="statistiques">
		<span class="icon-statistiques"></span>
	</li>
	</a>
	
	<a href="#">
	<li class="gestion">
		<span class="icon-gestion"></span>
	</li>
	</a>
</ul>
</nav>

<header>
	<div id="user">
	<span class="icon-personnes"></span> Michel Collin
	</div>
</header>

<div id="outils">
	<button type="button"><span class="icon-personnes"></span>  +</button>
	<button type="button"><span class="icon-cherche"></span>  Recherches enregistrées</button>
</div>
	
<section>
	<div id="conteneur" class="personnes">
	
	<form id="recherche_personnes">
		<article class="personnes">
		<h2><span class="icon-personnes"></span> Informations générales</h2>
			<div>
				<fieldset>
				<label for="num_adherent">N°Adhérent</label>
				<input type="text" name="num_adherent"  value="" id="num_adherent" />
				</fieldset>
		
				<!---->
		
				<fieldset class="col1">
				<label for="civilite">Civilité</label>
				<input type="text" name="texte"  value="" id="civilite" />
				</fieldset>
		
				<fieldset class="col3 nomargin">
				<label for="nom">Nom</label>
				<input type="text" name="nom"  value="" id="nom" />
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="prenom">Prénom</label>
				<input type="text" name="prenom"  value="" id="prenom" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="nom_jeune_fille">Nom de jeune fille</label>
				<input type="text" name="nom_jeune_fille"  value="" id="nom_jeune_fille" />
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
		
			</div>
			<div>
				<fieldset>
				<label for="adresse">Adresse</label>
				<textarea ></textarea >
				</fieldset>
		
				<!---->
		
				<fieldset>
				<label for="pays">Pays</label>
				<select type="text" name="pays"  value="" id="pays" />
					<option value="1">France</option>
				</select>
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="code_postal">Code postal</label>
				<input type="text" name="code_postal"  value="" id="code_postal" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="departement">Département</label>
				<select type="text" name="departement"  value="" id="departement" />
					<option value="1">75 - Paris</option>
				</select>
				</fieldset>
		
		
				<!---->
		
				<fieldset class="col2">
				<label for="ville">Ville</label>
				<input type="text" name="ville"  value="" id="ville" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="region">Région</label>
				<select type="text" name="region"  value="" id="region" />
					<option value="1">Ile de France</option>
				</select>
				</fieldset>
			</div>
			<div>
				<fieldset class="col2">
				<label for="tel_mobile">Téléphone mobile</label>
				<input type="text" name="tel_mobile"  value="" id="tel_mobile" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="tel_fixe">Téléphone fixe</label>
				<input type="text" name="tel_fixe"  value="" id="tel_fixe" />
				</fieldset>
		
				<!---->
		
				<fieldset>
				<label for="courriel">Courriel</label>
				<input type="text" name="courriel"  value="" id="courriel" />
				</fieldset>
		
				<!---->
		
				<fieldset>
				<label for="non_courriel">Courriel non renseigné</label>
				<input type="checkbox" name="non_courriel"  value="" id="non_courriel" />
				</fieldset>
		
				<!---->
		
				<hr>
		
				<!---->
		
				<fieldset>
				<label for="nom_association">Nom association</label>
				<input type="text" name="nom_association"  value="" id="nom_association" />
				</fieldset>
		
				<!---->
		
				<fieldset class="col2">
				<label for="num_dossier">N° Dossier/Commune</label>
				<input type="text" name="num_dossier"  value="" id="num_dossier" />
				</fieldset>
		
				<fieldset class="col2 nomargin">
				<label for="num_assos">N°Association</label>
				<input type="text" name="num_assos"  value="" id="num_assos" />
				</fieldset>
		
			</div>
		</article>

		<article class="personnes">
		<h2>Particularités</h2>
			<div class="simple">
				<fieldset class="col2_5 nomargin">
				<label for="membre_ca">Membre d’un CA</label>
				<select type="text" name="membre_ca"  value="" id="membre_ca" />
					<option value="1">Président</option>
				</select>
				</fieldset>
		
				<br class="clear">
		
				<fieldset class="col2_5 nomargin">
				<label for="statut">Statut</label>
				<select type="text" name="statut"  value="" id="statut" />
					<option value="1">Président</option>
				</select>
				</fieldset>
		
				<br class="clear">
		
				<fieldset class="col2_5 nomargin">
				<label for="responsabilite">Type responsabilité</label>
				<select type="text" name="responsabilite"  value="" id="responsabilite" />
					<option value="1">Président</option>
				</select>
				</fieldset>
		
				<fieldset>
				<label for="bienfaiteur">Bienfaiteur</label>
				<input type="checkbox" name="bienfaiteur"  value="" id="bienfaiteur" />
				</fieldset>
		
				<fieldset>
				<label for="presse">Presse</label>
				<input type="checkbox" name="presse"  value="" id="presse" />
				</fieldset>
		
				<fieldset>
				<label for="prospect">Prospect</label>
				<input type="checkbox" name="prospect"  value="" id="prospect" />
				</fieldset>
		
				<fieldset>
				<label for="elu_local">Élu local</label>
				<input type="checkbox" name="elu_local"  value="" id="elu_local" />
				</fieldset>
			</div>
		</article>
		<article class="amis">
		<h2><span class="icon-amis"></span> Amis de la fondation</h2>
			<div class="double">
		
				<fieldset class="col2">
				<label for="amis">Amis</label>
				<input type="checkbox" name="amis"  value="2010" id="amis" />
				</fieldset>
		
				<fieldset class="col3">
				<label for="banque_postale">Banque postale</label>
				<input type="checkbox" name="banque_postale"  value="1" id="banque_postale" /> Oui     		<input type="checkbox" name="banque_postale"  value="0" id="banque_postale" /> Non
				</fieldset>
		
				<!---->
		
				<br class="clear">
				<fieldset class="col2_5">
				<label for="non_inscrit_annee">Non inscrit (2014)</label>
				<input type="checkbox" name="non_inscrit_annee"  value="" id="non_inscrit_annee" />
				</fieldset>
		
				<fieldset class="col2_5">
				<label for="renouvele_annee">Renouvelé (2014)</label>
				<input type="checkbox" name="renouvele_annee"  value="" id="renouvele_annee" />
				</fieldset>
		
				<fieldset class="col2_5">
				<label for="non_renouvele_annee">Non renouvelé (2014)</label>
				<input type="checkbox" name="non_renouvele_annee"  value="" id="non_renouvele_annee" />
				</fieldset>
		
				<!---->
		
				<fieldset class="col2_5 ">
				<label for="etat_paiement">Paiement  (2014)</label>
				<select type="text" name="etat_paiement"  value="" id="etat_paiement" />
					<option value="1">Validé</option>
				</select>
				</fieldset>
		
				<fieldset class="col2_5">
				<label for="date_paiement_debut">Validation paiement</label>
				<input type="text" name="date_paiement_debut"  value="" id="date_paiement_debut" class="date"/>
				</fieldset>
		
				<fieldset class="col2_5 nomargin">
				<label for="date_paiement_fin">au</label>
				<input type="text" name="date_paiement_fin"  value="" id="date_paiement_fin" class="date"/>
				</fieldset>
		
		
				<fieldset class="col2_5 ">
				<label for="non_inscrit">Non inscrit pour la ou les années</label>
				<input type="checkbox" name="non_inscrit"  value="2010" id="non_inscrit" /> 2010  <input type="checkbox" name="assure"  value="" id="assure" /> 2010 
				</fieldset>
		
		
				<fieldset class="col2_5 ">
				<label for="origine_adhesion">Origine adhésion</label>
				<select type="text" name="origine_adhesion"  value="" id="origine_adhesion" />
					<option value="1">Site</option>
				</select>
				</fieldset>
		
				<fieldset class="col2_5 ">
				<label for="nouvel_adherent">Nouvel adhérent</label>
				<select type="text" name="nouvel_adherent"  value="" id="nouvel_adherent" />
					<option value="2014">2014</option>
				</select>
				</fieldset>
		
		
			</div>
		</article>

		<article class="distinctions">
		<h2><span class="icon-distinctions"></span>Distinctions</h2>
			<div class="simple">
				<fieldset class="col2_5 ">
				<label for="type_distinction">Type de distinction</label>
				<select type="text" name="type_distinction"  value="" id="type_distinction" />
					<option value="1">Palmes Or</option>
				</select>
				</fieldset>
		
				<fieldset class="col2_5 ">
				<label for="annee_distinction">Année</label>
				<select type="text" name="annee_distinction"  value="" id="annee_distinction" />
					<option value="1">2014</option>
				</select>
				</fieldset>
		
				<fieldset class="col2_5 ">
				<label for="annuaire">Annuaire</label>
				<input type="checkbox" name="annuaire"  value="1" id="annuaire" /> Oui  <input type="checkbox" name="annuaire"  value="O" id="annuaire" /> Non 
				</fieldset>
			</div>
		</article>


		<article class="associations">
		<h2><span class="icon-associations"></span> Assurance gratuite</h2>
			<div>
				<fieldset class="col2 nomargin">
				<label for="assure">Assuré</label>
				<input type="checkbox" name="assure"  value="" id="assure" />
				</fieldset>
		
			
				<fieldset class="col2 nomargin">

				<select type="text" name="actif"  value="" id="actif" />
					<option value="1">Actif</option>
				</select>
				</fieldset>
		
		
				<fieldset >
				<label for="inscrit">Inscrit pour la ou les années</label>
				<input type="checkbox" name="inscrit"  value="2010" id="inscrit" /> 2010  <input type="checkbox" name="assure"  value="" id="assure" /> 2010 
				</fieldset>
		
				<fieldset >
				<label for="non_inscrit">Non inscrit pour la ou les années</label>
				<input type="checkbox" name="non_inscrit"  value="2010" id="non_inscrit" /> 2010  <input type="checkbox" name="assure"  value="" id="assure" /> 2010 
				</fieldset>
			</div>
		</article>
		<div id="zone_validation">
			<button type="button" id="action_recherche"><span class="icon-cherche"></span>  Rechercher</button>
		</div>
	</form>
	
			
	<div id="zone_resultats">	
		
		<h1 class="left"><strong>123</strong> résultats</h1>
	
		<button type="button" id="action_modif_recherche"><span class="icon-cherche"></span>  Modifier la recherche</button>
		<table id="tab_resultats">
		  <thead>
			<tr>
			  <th>Name</th>
			  <th>Hobby</th>
			  <th>Favorite Music</th>
			  <th>Actions</th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Fred</td>
			  <td>Roller Skating</td>
			  <td>Disco</td>
			   <td>Modifier</td>
			</tr>
			<tr>
			  <td>Helen</td>
			  <td>Rock Climbing</td>
			  <td>Alternative</td>
			  <td>Modifier</td>
			</tr>
			<tr>
			  <td>Glen</td>
			  <td>Traveling</td>
			  <td>Classical</td>
			  <td>Modifier</td>
			</tr>
			
		  </tbody>
		</table>

	</div>
</div>
</section>


</body>
</html>
