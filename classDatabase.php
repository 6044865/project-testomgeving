<?php

abstract class Database{

    // connectie met database   / protected zodat de database connectie blijft beschremd en beveligd  
    // en alleen binnen de calss en subclass wordt gebruikt..
    protected $pdo;

    private $server ;
    private $dbNaam ;
    private $gebruiker ;
    private $wachtwoord ;
    
    // binnen de construct intailize db
    public function __construct(){
    
       
        try{
            // intialize data base 
            $this->server  = "localhost" ;
            $this->dbNaam  ="mbo_cinemas";
            $this->gebruiker ="root";
            $this->wachtwoord ="" ;

            $this->pdo = new PDO("mysql:host=$this->server;dbname=$this->dbNaam",$this->gebruiker,$this->wachtwoord);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_BOTH);

        }catch(PDOException $e){
            $error = $e->getMessage();
            
            echo "Database verbinding is niet gelukt: ".$error;
        }
 
    }

    // deze functie moet in subclassen zijn zodat ik kan van de ene table naar de andere kan wisselen
  


}



?>