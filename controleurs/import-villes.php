<section>
<div id="conteneur" class="associations">
<article class="associations">
<h2><span class="icon-gestion"></span> Import des villes</h2>
<?php
exit();

require('libs/import.php');


$reqAssoc = $connect->prepare('SELECT * FROM new_villes ORDER BY nom_comm ASC');
$i=1;
try {
  $reqAssoc->execute();
   
  // Traitement
  while( $asso = $reqAssoc->fetch(PDO::FETCH_OBJ)){
   
   		

		// Recherche de la ville
		$req = 'SELECT * FROM villes WHERE nom = "'.$asso->nom_comm.'" AND code_postal = "'.$cp.'" ;';
		$reqVille = $connect->prepare($req);
		try {
			$reqVille->execute();
			$asso2 = $reqVille->fetch(PDO::FETCH_OBJ);
			
			
			
			if (strlen($asso->insee_com) == 4) $insee = '0'.$asso->insee_com;
   			else $insee = $asso->insee_com;
   			
   			// update
			echo $i.'->'.$insee . ' '.$asso->nom_comm.'<br>';
			
			$modVille = $connect->prepare("UPDATE  `villes` SET `insee` = '".$insee."'  WHERE id = '".$asso2->id."' ");
			$modVille->execute();
			
		} catch( Exception $e ){
		  echo 'Erreur de suppression : ', $e->getMessage();
		}
	$i++;

  }
  
} catch( Exception $e ){
  echo 'Erreur de suppression : ', $e->getMessage();
}


?>
</article>
</div>
</span>