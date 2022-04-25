<?php
    class Configuration {
       public $id;
       public $code;
       public $nom;
       public $contenu;
       public $balises;
       

       static $connection;

       // SQL
       public $reqGET;
       public $reqInsert;
       public $reqUpdate;
       public $reqExist;

       // Constructeur
       public function __construct($code = null) {
           
        static::$connection = connect();
        $this->code = $code;

        //
        // Requetes SQL préparées 
        $this->initSqls();


        // Récupération depuis la base de données si le code est spécifié
        if ($this->code != null)
            $this->get();

       }

       // Retourne un string: {balise}{balise2}{balise3}
       public function getBalisesFromContenu() {
            if(!empty($this->contenu)) {
                $retour = "";
                $split = explode("{", $this->contenu);
                for($i=0; $i<count($split); $i++) {
                    $balise = "";
                    $baliseFermer = false;
                    for($e=0; $e<strlen($split[$i]); $e++) {
                        if($split[$i][$e] == '}') {
                            $baliseFermer = true;
                            break;
                        }
                        $balise .= $split[$i][$e];
                    }
                    if($baliseFermer && !empty($balise)) {
                        $balise = '{' . $balise . '}';
                        $retour .= $balise . ' ';
                    }
                }
                return trim($retour);
            }
            return "";
       }

       private function get() {
           $resultat = $this->reqGET->execute();
        //    echo $this->reqGET->queryString;

           if ($donnee = $this->reqGET->fetch()) {
               
                $this->id = $donnee['id'];
                $this->code = $donnee['code'];
                $this->nom = $donnee['nom'];
                $this->contenu = $donnee['contenu'];
                $this->balises = $donnee['balises'];
           }
       }

       public function save() {
           if(empty($this->nom)) {
               return false;
           }
           $exist = false;
           $this->reqExist->execute();
            if ($donnee = $this->reqExist->fetch()) {
                $exist = $donnee['compte'] > 0;
            }
            if ($exist) {
                return $this->update();
            } else {
                return $this->insert();
            }
       }

       public function insert() {
           $baliseFromContenu = $this->getBalisesFromContenu();
           $this->reqInsert->bindParam(':balises', $baliseFromContenu);
            return $this->reqInsert->execute();
       }
       public function update() {
           $baliseFromContenu = $this->getBalisesFromContenu();
            $this->reqUpdate->bindParam(':balises', $baliseFromContenu);
            return $this->reqUpdate->execute();
       }

       private function initSqls() {
        //
        // Exist
        $this->reqExist = static::$connection->prepare('SELECT count(*) AS compte FROM configuration WHERE code=:code;');
        $this->reqExist->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        // Get
        $this->reqGET = static::$connection->prepare('SELECT * FROM configuration WHERE code=:code');
        $this->reqGET->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        // Update
        $this->reqUpdate = static::$connection->prepare('UPDATE configuration set code=:code, contenu=:contenu, nom=:nom, balises=:balises where id=:id');
        $this->reqUpdate->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        $this->reqUpdate->bindParam(':contenu', $this->contenu, PDO::PARAM_STR);
        $this->reqUpdate->bindParam(':nom', $this->nom, PDO::PARAM_STR);
        $this->reqUpdate->bindParam(':balises', $this->balises, PDO::PARAM_STR);
        $this->reqUpdate->bindParam(':id', $this->id, PDO::PARAM_INT);
        // Insert
        $this->reqInsert = static::$connection->prepare('INSERT into configuration (code, nom, contenu, balises) values (:code, :nom, :contenu, :balises)');
        $this->reqInsert->bindParam(':code', $this->code, PDO::PARAM_STR, 100);
        $this->reqInsert->bindParam(':contenu', $this->contenu, PDO::PARAM_STR);
        $this->reqInsert->bindParam(':nom', $this->nom, PDO::PARAM_STR);
        $this->reqInsert->bindParam(':balises', $this->balises, PDO::PARAM_STR);
       }

    }
?>