
<section class="associations">
	<div id="header" class="associations modifier">
		<?php include(ROOT.'controleurs/contenus/associations/header.php');?>
	</div>
	<!-- ZONE DE FORMULAIRES -->
	<div id="contenu_formulaire">

	</div>
	<div id="conteneur" class="detail associations modifier">
		<article id="detail">
			<?php include(ROOT.'controleurs/contenus/associations/detail.php');?>

			<article class="personnes modifier" id="contenu_ca">
				<?php
				if ($asso->association_type == 1) include(ROOT.'controleurs/contenus/associations/conseil_administration.php');
				else if ($asso->association_type == 2) include(ROOT.'controleurs/contenus/associations/representants.php');
				?>
			</article>

		</article>




		<!--
		<article class="associations modifier"  id="contenu_associations">
			<?php include(ROOT.'controleurs/contenus/associations/associations.php');?>
		</article>
		-->
		<article class="amis modifier"  id="contenu_amis">
			<?php include(ROOT.'controleurs/contenus/associations/amis.php');?>
		</article>

		<!--
		<article class="achats modifier" id="contenu_achats">
			<?php include(ROOT.'controleurs/contenus/associations/achats.php');?>
		</article>

		<article class="annonces modifier" id="contenu_annonces">
			<?php include(ROOT.'controleurs/contenus/associations/annonces.php');?>
		</article>
			-->
	</div>
</section>


<!-- MODAL -->


<div id="dialog-modal" title="<?php echo $modalTitre ?>" class="<?php echo $modalClasse ?>">
	<p><?php echo (isset($modalTexte) ? $modalTexte : '') ?></p>
</div>

<div id="dialog-modal-envoyer" class="modal">
  <p>
  <h3>Envoyer les attestations Année</h3>
 <div id="retour"></div>
  <form id="envoyer_fichier">
     <fieldset class="left">
      <label for="password"><span class="icon-personnes"></span> Sélectionner le(s) destinataire(s) :</label><br class="clear">
      <?php if (!empty($president->courriel)) : ?>
      	<input type="checkbox" name="president" value="<?php echo $president->courriel ?>">
      		 <?php if ($asso->association_type == 1) : ?>
      		 	Président actuel
      		<?php else  : ?>
      			Représentant
      		<?php endif ;?>

      		<br class="clear">
      <?php endif; ?>

      <?php if (!empty($emailDelegue)) : ?>
    	<input type="checkbox" name="delegue" value="<?php echo $emailDelegue ?>"> Délégué(s)<br class="clear">
      <?php endif; ?>

   	Autre (saisir une ou plusieurs adresses séparées par des virgules)<br/>
      <input type="text" name="destinataire">
       </fieldset>

       <fieldset  class="left">
       <label for="password"><span class="icon-fichiers"></span> Sélectionner les documents à envoyer :</label><br class="clear">
 		<div id="zone_choix">
		</div>
 	  </fieldset>

 		<br class="clear">
 		 <fieldset>
       <label for="password">Sujet :</label><br class="clear">
 		<input type="text" name="sujet">
 		</fieldset>

 		 <fieldset>
       <label for="password">Message :</label><br class="clear">
 		<textarea name="message"></textarea>
 		</fieldset>

      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
 	<input type="hidden" name="action" id="action" value="fichiers_attestations">
  </form>
  </p>
</div>

<div id="dialog-modal-telecharger"  class="modal">
	<p>
	<h3>Télécharger les attestations Année</h3>
	<div id="zone_choix">
	</div>
	</p>
</div>

<div id="dialog-modal-envoyer-fichier" class="modal">
  <p>
  <h3>Envoyer le fichier</h3>
 <div id="retour"></div>
  <form id="envoyer_fichier_unique">
     <fieldset class="left">
      <label for="password"><span class="icon-personnes"></span> Sélectionner le(s) destinataire(s) :</label><br class="clear">

      	<input type="checkbox" name="president" value="1"> Président actuel de l'association

      		<br class="clear">


      <?php if (!empty($emailDelegue)) : ?>
    	<input type="checkbox" name="delegue" value="<?php echo $emailDelegue ?>"> Délégué(s)<br class="clear">
      <?php endif; ?>

       Autre (saisir une ou plusieurs adresses séparées par des virgules)<br/>
       <input type="text" name="destinataire">
       </fieldset>

 		<br class="clear">

 		<fieldset>
        <label for="password">Sujet :</label><br class="clear">
 		<input type="text" name="sujet">
 		</fieldset>

 		<fieldset>
        <label for="password">Message :</label><br class="clear">
 		<textarea name="message"></textarea>
 		</fieldset>

      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
 	  <input type="hidden" name="action" id="action" value="fichier">

  </form>
  </p>
</div>


<div id="dialog-modal-envoyer-password" class="modal">
  <p>
  <h3>Envoyer un mot de passe</h3>
 <div id="retour"></div>
  <form id="envoyer_password">
     <fieldset class="left">
     <p>
 		Attention, l'ancien mot de passe sera écrasé.
 		</p>
 		<fieldset>
        <label for="password">Message :</label><br class="clear">
 		<textarea name="message">

 		</textarea>
 		</fieldset>

      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
 	  <input type="hidden" name="action" id="action" value="password">

  </form>
  </p>
</div>


<?php if ($modal) : ?>
<script>
	jQuery(function($) {
		$( "#dialog-modal" ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
</script>
<?php endif; ?>
