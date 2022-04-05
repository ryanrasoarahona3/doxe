<div>
				<h2><span class="icon-amis"></span> Adhésion au Cercle National des Bénévoles</h2>
				<?php if ( isSiege() ) : ?>
					<button type="button" class="ajouter right" form-action="personnes" form-type="amis" form-id="<?php echo $perso->id_personne ?>" ></button>
				<?php endif; ?>
				
				<?php echo $plus ?>
				<br class="clear">
				
				<?php if (!empty($amis)) : ?>
					<?php echo $amis ?>
					<?php echo isset($fermer)?$fermer:""; ?>
				<?php else : ?>
					<em>Aucune adhésion au Cercle National des Bénévoles</em>
				<?php endif; ?>
				<!--
				<div class="titre attention">
					<h3 class="left">2014</h3>		<h3 class="left">En attente validation paiement</h3> 
					<button type="button" form-action="personnes" form-type="amis" form-id="53" form-id-lien="1" class="edit"><span class="icon-edit"></span></button>
				</div>
				
				<div class="left">
					<button type="button" class="valider" title="Valider le paiement"></button> <strong>Payement reçu</strong>
				</div>
				<div class="left">
					<p>Information association banque postale OUI</p>
					<p>Origine Adhésion SITE INTERNET</p>
				</div>
				
				
				<div class="titre">
					<h3 class="left">2013</h3>		<h3 class="left">Adhésion payée</h3>  <a href="" class="right bt_action_personne"><span class="icon-edit"></span></a>
				</div>
				
				<div class="left">
					<p>Date de validation paiement 25/02/2013</p>
					<p>Attestation 		   | Reçu</p>
				</div>
				<div class="left">
					<p>Information association banque postale OUI</p>
					<p>Origine Adhésion SITE INTERNET</p>
				</div>
				<br class="clear">
				-->

</div>