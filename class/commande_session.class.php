<?php
class Commande_session {

    public $id;
    public $id_session;
    public $numero;
    public $session;
    public $date;
  
	// SQL
	public $reqAjout; 
	public $reqSupprime; 
	public $reqCharge; 
	
    //Constructeur 
   public function __construct ($id=0) {    
    	 
    	  
    	  //
    	  // Initialisation
    	  //
 	 
    	  $this->connection = connect();
    	  
	
    	  //
    	  // Requetes SQL préparées 
    	  //


    	  // Sauve 
    	   $this->reqAjout = $this->connection->prepare("INSERT INTO  `commerce_sessions` 
		  (`id`, `id_session`, `numero`, `session`,`date`)
		  VALUES 
		  ('', :id_session,  :numero, :session,  NOW() );");
	
		 $this->reqAjout->bindParam(':id_session', $this->id_session, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':numero', $this->numero, PDO::PARAM_STR, 255);
		 $this->reqAjout->bindParam(':session', $this->session, PDO::PARAM_STR, 25500);
		  
		 
		// Chargement  
		$this->reqCharge = $this->connection->prepare('
		SELECT 
			commerce_sessions.id , 
			commerce_sessions.id_session, 
			commerce_sessions.numero, 
			commerce_sessions.session
		FROM commerce_sessions 
		WHERE commerce_sessions.id_session = :id_session
		');
		$this->reqCharge->bindParam(':id_session', $this->id_session, PDO::PARAM_INT, 11);
		
		
		// Supprimer 
		$this->reqSupprimer = $this->connection->prepare("DELETE FROM  `commerce_sessions`  WHERE id_session = :id_session ");
		$this->reqSupprimer->bindParam(':id_session', $this->id_session, PDO::PARAM_STR, 255); 

		// Chargement
	
		if (!empty($id)) {
			$this->id_session = $id;
			$this->load_session();
		}
	}
	
	
	
	// Récupère la session
	private function load_session () {
		{
				// Chargement des informations de base
				try {
  					$this->reqCharge->execute();
   	 				while( $enregistrement = $this->reqCharge->fetch(PDO::FETCH_ASSOC)){
    					foreach ($enregistrement as $cle=>$val) {	
    						if(property_exists('commande_session', $cle)) 
								{
									$this->{$cle} = $val;
								}	
    					}
 					}
 					
 					
 					
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}

		}
	}
	
	public function charge() {
		// Restauration de la session
 		if (!empty($this->session)) session_decode($this->session);
	}
	
	// Enregistre
	public function sauve () {
		{
				$this->numero = time();
				$this->id_session = session_id();
				$this->session = session_encode();
				
				try {
  					$resultat = $this->reqAjout->execute();
   
  					if( $resultat===true ) return $retour;
    				else return $this->reqAjout->errorInfo();
    				
				} catch( Exception $e ) {
				  	echo "Erreur d'enregistrement : ", $e->getMessage();
				}

		}
	}
	
	public function supprime() {
			return $this->reqSupprimer->execute();
	}
}
?>