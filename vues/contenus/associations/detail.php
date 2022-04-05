<div>
			<?php if (isset($asso) && isset($asso->logo) && is_string($asso->logo) && strlen($asso->logo)>0) : ?>
			<aside class="image">
				<img src="css/images/<?php echo $asso->logo ?>">
			</aside>
			<?php endif; ?>
			<aside>
<h2>Informations générales</h2>
<br class="clear">


<em>Numéro :</em> <span><?php echo $asso->numero_adherent ?></span><br>
				
<em>Sigle :</em>  <span><?php echo $asso->sigle ?></span><br>

<?php if ($asso->association_type == 1) : ?>
	<em>Siret : </em> <span><?php echo $asso->numero_siret ?></span><br>
	<em>Code APE/NAF : </em> <span><?php echo $asso->code_ape_naf ?></span><br>
	<em>Activités :</em>  <span><?php echo $asso->affActivites()?></span><br>
	<em>Déclaration JO  : </em> <span><?php echo affDate($asso->date_declaration_jo) ?></span><br>
<?php else : ?>
	<em>Convention : </em> <span><?php echo $asso->numero_convention ?></span><br>
<?php endif; ?>
<em>Saisie :</em>  <span><?php echo  affDate($asso->date_creation,true); ?></span><br>
<em>Modification :</em>  <span><?php echo  affDate($asso->date_modification,true); ?> <em>par</em> <?php echo $asso->modificateur_label; ?></span><br>

</aside>

<aside>		
<h2>&nbsp;</h2>
<br class="clear">	
<?php echo $asso->adresse ?><br>
<?php echo $asso->code_postal ?> - <?php echo $asso->ville_label ?><br>

<em>Région :</em>  <?php echo $asso->region ?><br>

<em>Département :</em>  <?php echo substr($asso->code_postal,0,2) ?> - <?php echo $asso->departement ?><br>



<em>Tél fixe :</em> <?php echo $asso->telephone_fixe ?><br>
<em>Tél mobile :</em> <?php echo $asso->telephone_mobile ?><br>
<em>Fax :</em> <?php echo $asso->fax ?><br>
<em>Courriel :</em> <a href="mailto<?php echo $asso->courriel ?>"><?php echo $asso->courriel ?></a>

</aside>
			</div>