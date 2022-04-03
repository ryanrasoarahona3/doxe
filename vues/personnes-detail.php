
<section class="personnes">
	<div id="header" class="personnes modifier">
		<?php include(ROOT.'controleurs/contenus/personnes/header.php');?>

	</div>

	<!-- ZONE DE FORMULAIRES -->
	<div id="contenu_formulaire">

	</div>

	<div id="conteneur" class="detail personnes modifier">
		<article id="detail">
			<?php include(ROOT.'controleurs/contenus/personnes/detail.php');?>

			<article class="modifier distinctions" id="contenu_distinctions">
			<?php include(ROOT.'controleurs/contenus/personnes/distinctions.php');?>
			</article>

			<article class="modifier bienfaiteur" id="contenu_bienfaiteur">
				<?php include(ROOT.'controleurs/contenus/personnes/bienfaiteur.php');?>
			</article>
	<!--
			<article class="modifier achats" id="contenu_achats">
				<?php include(ROOT.'controleurs/contenus/personnes/achats.php');?>
			</article>

			<article class="modifier annonces" id="contenu_annonces">
				<?php include(ROOT.'controleurs/contenus/personnes/annonces.php');?>
			</article>
	-->
		</article>



		<article class="modifier amis"  id="contenu_amis">
			<?php include(ROOT.'controleurs/contenus/personnes/amis.php');?>
		</article>



		<!--
		<article class="modifier associations"  id="contenu_associations">
			<?php include(ROOT.'controleurs/contenus/personnes/associations.php');?>
		</article>
		-->





	</div>
</section>

<div id="dialog-modal" title="<?php echo $modalTitre ?>" class="<?php echo $modalClasse ?>">
	<p><?php echo $modalTexte ?></p>
</div>

<div id="dialog-modal-envoyer-fichier" class="modal">
  <p>
  <h3>Envoyer le fichier</h3>
 <div id="retour"></div>
  <form id="envoyer_fichier_unique">
     <fieldset class="left">
      <label for="password"><span class="icon-personnes"></span> Sélectionner le(s) destinataire(s) :</label><br class="clear">

    	<span id="zone_president">
      		<input type="checkbox" name="president" value="1"> Président actuel de l'association <br class="clear">
		</span>

      <?php if (!empty($emailDelegue)) : ?>
    	<input type="checkbox" name="delegue" value="<?php echo $emailDelegue ?>"> Délégué(s)<br class="clear">
      <?php endif; ?>

       <?php if (!empty($perso->courriel)) : ?>
    	<input type="checkbox" name="benevole" value="<?php echo $perso->courriel ?>"> Bénévole<br class="clear">
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
 		<textarea name="message">L’association le Cercle National des Bénévoles vous a déclenché l’envoi d’un nouveau mot de passe utilisable avec votre adresse internet sur laquelle vous recevez ce message.

Il vous permet de gérer vos coordonnées, rédiger des demandes de distinctions ou encore rédiger une annonce gratuite de bénévolat.</textarea>

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
