<?php
class email {

    public $sujet;
    public $message;
    public $destinataires;
    public $destinataires_tab;
    public $gabarit;
    public $resultat;
    public $mail;
	public $connection;
	
    //Constructeur 
    public function __construct ($id=0) {    
    	 $this->connection = connect();
    	 
    	 $this->reqAjout = $this->connection->prepare("INSERT INTO  `emails` 
		  (`id`, `sujet`,`destinataires`, `message`) 
		  VALUES 
		  ('',  :sujet, :destinataires_tab, :message);");
	
		 $this->reqAjout->bindParam(':sujet', $this->sujet,PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':destinataires_tab', $this->destinataires_tab, PDO::PARAM_STR, 250000);
		 $this->reqAjout->bindParam(':message', $this->message, PDO::PARAM_STR, 250000);
		
		
	}
	
	
	// Destinataire
	public function to ($to,$to_name='') {
		$i = count($this->destinataires)+1;
		$this->destinataires[i]['to'] = $to;
		$this->destinataires[i]['to_name'] = $to_name;
	
	}
	
	
	// Envoyer
	public function envoyer () {
		
			try {
				   	 $this->destinataires_tab = serialize($this->destinataires);
  					 $this->message = str_replace('border="0"','border="1"',$this->message);
					$this->message = str_replace('<td ','<td align="left" ',$this->message);
			
  					 $resultat = $this->reqAjout->execute();
   
  					 if( $resultat===true ) {
    				
    					return $retour;
    								
 					 } else return $this->reqAjout->errorInfo();
  
				} catch( Exception $e ) {
				  	return "Erreur d'enregistrement : ". $e->getMessage();
				}
				
	}


}
?>