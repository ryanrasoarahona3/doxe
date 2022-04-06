<?php
    include_once "../libs/fonctions.php";

    function copieCA($asso, $annee, $anneeACopier) {
        $bdd= connect();

        $suppression = $bdd->prepare('delete from personnes_associations where association=? and annee=?');
        $suppression->execute(array($asso, $annee));

        $stmt= $bdd -> query('select * from personnes_associations where association='.$asso.' and annee='.$anneeACopier);
        while($donnee = $stmt->fetch()) {
            $insertion = $bdd->prepare('insert into personnes_associations (personne, association, date, annee, etat, date_etat, cons_admin, benevole) values (?,?,?,?,?,?,?,?)');
            $insertion->execute(array($donnee['personne'],$donnee['association'],$donnee['date'],$annee,$donnee['etat'],$donnee['date_etat'],$donnee['cons_admin'],$donnee['benevole']));
        }
    }

?>