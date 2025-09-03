<?php
require_once "classDatabase.php";



abstract class Gebruiker extends Database{

   

    abstract  function inloggen($gebruikersnaam, $wachtwoord);
  
   

}


?>