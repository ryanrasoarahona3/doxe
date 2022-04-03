<section>
<!-- AskStats -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://askstats.fr/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '35']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
	
	<div id="conteneur" class="login">
	
	<form id="login">
		<article class="personnes">
	
			<div>
				<h1>Connexion </h1><br><br>
				<!---->
		
				<fieldset >
				<label for="courriel">Adresse email</label>
				<input type="email" name="courriel"  value="" id="courriel" required>
					
				</fieldset>
				<br class="clear">
				<fieldset >
				<label for="mdp">Mot de passe</label>
				<input type="password" name="mdp"  value="" id="mdp" required>
					
				</fieldset>
				
				<div id="retour" class="alerte">
				</div>
		</div>
		<br class="clear">
			<button type="button" id="check_login"><span class="icon-personnes"></span>  Valider</button>
	</article>
		
		<!-- HIDDEN -->
		<input type="hidden" id="destination_validation" name="destination_validation" value="<?php echo $form->destination_validation ?>">
		
		</form>
	</div>
</section>