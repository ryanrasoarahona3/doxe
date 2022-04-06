<?php
$btAjout='';
switch (substr($controlleur,0,5)) {
	case ('annon') :
		$btAjout =  '<a href="/annonces/ajouter" class="annonces"><button type="button" class="bouton-ajouter"><span class="icon-plus"></span></button></a>';
	break;
	
	case ('assoc') :
		$btAjout =  '<a href="/associations/ajouter" class="associations"><button type="button" class="bouton-ajouter"><span class="icon-plus"></span></button></a>';
	break;
	
	case ('perso') :
		$btAjout =  '<a href="/personnes/ajouter" class="personnes"><button type="button" class="bouton-ajouter"><span class="icon-plus"></span></button></a>';
	break;

	case ('disti') :
		$btAjout =  '<a href="/distinctions/ajouter" class="distinctions"><button type="button" class="bouton-ajouter"><span class="icon-plus"></span></button></a>';
	break;

}

$titre='';
switch ($controlleur) {
	case ('annonces') :
		$titre =  'Rechercher une annonce';
	break;
	case ('annonces-ajouter') :
		$titre =  'Ajouter une annonce';
	break;
	case ('annonces-detail') :
		$titre =  'Détail d\'une annonce';
	break;
	
	case ('associations') :
		$titre =  'Rechercher une association';
	break;
	case ('associations-ajouter') :
		$titre =  'Ajouter une association';
	break;
	case ('associations-detail') :
		$titre =  'Détail d\'une association';
	break;
	
	case ('personnes') :
		$titre =  'Rechercher une personne';
	break;
	case ('personnes-ajouter') :
		$titre =  'Ajouter une personne';
	break;
	case ('personnes-detail') :
		$titre =  'Détail d\'une personne';
	break;
	
	case ('boutique') :
		$titre =  'Rechercher une commande';
	break;
	case ('boutique-ajouter') :
		$titre =  'Ajouter une commande';
	break;
	case ('boutique-detail') :
		$titre =  'Détail d\'une commande';
	break;
	
	case ('distinctions') :
		$titre =  'Rechercher une distinction';
	break;
	case ('distinctions-ajouter') :
		$titre =  'Ajouter une distinction';
	break;
	case ('distinctions-detail') :
		$titre =  'Détail d\'une distinction';
	break;
	
	case ('gestion') :
		$titre =  'Gestion';
	break;
	case ('gestion-documents') :
		$titre =  'Documents';
	break;
	
	case ('alertes') :
		$titre =  'Alertes doublons';
	break;
	
}
?>
		
<header>
	<div class="left ">
		
			<?php echo $btAjout ?>
	</div>

		<h4>
			<?php echo $titre ?>
		</h4>
	
	<div id="user">
		<span class="icon-personnes"></span><a href="/personnes/detail/<?php echo $_SESSION['utilisateur']['id']?>">  <?php echo $_SESSION['utilisateur']['prenom']?>   <?php echo $_SESSION['utilisateur']['nom']?> </a> <a href="/deconnexion">( Déconnexion )</a>
	</div>

</header>