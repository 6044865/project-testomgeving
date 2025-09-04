<?php 
// niet absract calss want ik geen child classen gebuikt heb zoals onderzoekers ,beheerder... ict dat(rollen) saat in gebuikers table in my data base
class Database {

    protected $pdo;

    private $server;
    private $dbNaam;
    private $gebruiker;
    private $wachtwoord;

    public function __construct() {
        try {
            $this->server = "localhost";
            $this->dbNaam = "wereldwonderen_db";  
            $this->gebruiker = "root";
            $this->wachtwoord = "";

            $this->pdo = new PDO(
                "mysql:host=$this->server;dbname=$this->dbNaam;charset=utf8mb4", 
                $this->gebruiker,
                $this->wachtwoord
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 

        } catch (PDOException $e) {
            die("❌ Database verbinding is niet gelukt: " . $e->getMessage()); 
        }
    }
    public function getConnection() {
        return $this->pdo;
    }
}


?>