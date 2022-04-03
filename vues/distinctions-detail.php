<!--
<div id="outils">
	<button type="button"><span class="icon-distinctions"></span>  +</button>
	<button type="button"><span class="icon-cherche"></span>  Recherches enregistrées</button>
</div>
-->
	
			
<section>
	<div id="header" class="distinctions modifier">
		<div class="left">
			<h1><span class="icon-distinctions"></span><a href="/personnes/detail/<?php echo $personne->id_personne ?>"><?php echo $personne->prenom.' '.$personne->nom ?></a> </h1>
			<em><strong>N°Adhérent : <?php echo $personne->id_personne ?></strong></em>
		</div>
		<div  class="left">
				<?php echo $distinction->affHeader() ?>
		</div>
		<button type="button" form-action="personnes" form-type="distinctions" form-id="<?php echo $personne->id_personne?>" form-id-lien="<?php echo $distinction->id_distinction?>" class="edit"></button>
		<br class="clear" />
	</div>
	<!-- ZONE DE FORMULAIRES -->
	<div id="contenu_formulaire">
		
	</div>
	
	<div id="conteneur" class="detail distinctions modifier">
	
		<div class="group1 left">
			<article style="width:50%;">
				<h2><?php echo $distinction->nbr_annees ?> année<?php echo s($distinction->nbr_annees)?> de bénévolat</h2>
			</article>	
		
			<article id="distinctions_domaines" style="width:50%;">
			<h2>Domaines d'activité</h2>
				<div>
					<?php echo $distinction->affDomaines() ?> 
				</div>
			</article>
	
			
			
			<!--
			<article id="distinctions_activitesencours">
			<h2>Activités en cours</h2>
				<div>
					<table>
					<thead><tr><th>Association</th><th>Fonction</th><th>Année de début</th><th>Nombre d'années</th></tr></thead>
					<?php echo $distinction->affActivites() ?>
					</table>
					
				</div>
			</article>
			-->
			<article id="distinctions_activitespassees">
				<h2>Activités </h2>
				<div>
					<table>
					<thead><tr><th>Association</th><th>Fonction</th><th>Année de début</th><th>Année de fin</th><th>Nombre d'années</th></tr></thead>
					<?php echo $distinction->affActivites() ?>
					<?php echo $distinction->affActivitesPassees() ?>
					</table>
				</div>
			</article>
			
			<article id="distinctions_decision">
				<h2><span class="icon-distinctions"></span> Décision du jury</h2>
				<div>
						<?php echo $distinction->affDecision() ?>
				</div>
			</article>
			
		</div>
		
		<div class="group2 left">
			<article id="distinctions_distinctions">
				<h2>Distinctions</h2>
				<div>
					<table>
					<thead><tr><th>Distinction</th><th>Année</th></tr></thead>
					<?php echo $distinction->affDistinctions() ?>
					</table>
				</div>
			</article>
		
			<article id="distinctions_documents">
				<h2><span class="icon-fichiers"></span> Documents</h2>
				<div>
					<table>
					<?php echo $distinction->affDocuments() ?>
					</table>
				</div>
			</article>
		
			<article id="distinctions_parrains">
				<h2><span class="icon-personnes"></span> Parrains</h2>
				<div>
					<table>
					<?php echo $distinction->affParains() ?>
					</table>
				</div>
			</article>
		</div>
	
	</div>
</section>

<div id="dialog-modal" title="<?php echo $modalTitre ?>" class="<?php echo $modalClasse ?>">
	<p><?php echo $modalTexte ?></p>
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