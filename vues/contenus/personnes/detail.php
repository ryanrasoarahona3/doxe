<div>
<?php if (isset($perso->portrait) && is_string($perso->portrait) && strlen($perso->portrait)>0) : ?>
			<aside class="image">
				<img src="/upload/portraits/<?php echo trim($perso->portrait) ?>">
			</aside>
<?php endif; ?>
			
			
			
<aside>

<?php if ($perso->date_naissance != '0000-00-00') : ?>
    <p>Né(e) le <?php echo affDate($perso->date_naissance) ?> <em>( <?php echo age($perso->date_naissance) ?> ans )</em></p><br>
<?php endif; ?>

<?php if (!empty($perso->profession)) : ?>
    <p><?php echo $perso->profession ?></p><br>
     
<?php endif; ?>
        
<p><?php echo $perso->adresse ?></p>
<?php if ($perso->pays == ID_FRANCE) : ?>
<p><?php echo $perso->code_postal ?> <?php echo $perso->ville_label ?></p>
<p><em><?php echo $perso->departement ?></em></p>
<p><em><?php echo $perso->region ?></em></p>
<?php endif; ?>

<?php if ($perso->pays != ID_FRANCE) : ?>
<p><?php echo $perso->code_pays ?> <?php echo $perso->ville_pays ?></p>
<p><em><?php echo $perso->pays_label ?></em></p>
<?php endif; ?>

<br>
<p><?php echo phone($perso->telephone_fixe) ?></p>
<p><?php echo phone($perso->telephone_mobile) ?> </p>
<p><a href="mailto:<?php echo $perso->courriel ?>"><?php echo $perso->courriel ?></a></p>
</aside>

<aside>
<?php echo $siege?>
<?php echo $delegueDetail ?>
<br>
<?php echo ( isset($prospect) ? $prospect : '' )?>
<?php echo ( isset($elu) ? $elu : '' ) ?>
<?php echo ( isset($presse) ? $presse : '' ) ?>
</aside>
</div>
			
			
