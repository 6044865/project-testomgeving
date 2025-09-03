<?php
require_once "classDatabase.php";
require_once "classGebruiker.php";

class Klant extends Gebruiker
{
    // private $tableNaam ;






    public function Registreren($username, $wachtwoord_geb, $email)
    {

        $username = $_POST["username"];
        $wachtwoord_geb = $_POST["wachtwoord"];
        $email = $_POST["email"];


        $option = [
            'cost' => 12
        ];
        $hashedpassword = password_hash($wachtwoord_geb, PASSWORD_BCRYPT, $option);

        try {
            $query = "INSERT INTO klant (klant_naam, klant_email,klant_wachtwoord) VALUES (:username, :email_adres , :password)";

            $statement =  $this->pdo->prepare($query);

            $statement->bindParam(':username', $username);
            $statement->bindParam(':email_adres', $email);
            $statement->bindParam(':password', $hashedpassword);


            $statement->execute();

            session_start();
            $_SESSION['username'] = $username;
            $klantNaam =  $_SESSION['username'];

            if ($statement) {


                // test
                // echo " gebruiker is toegevoegd.";
                header("location: klantDashboard.php");
            } else {
                echo " Fout: gebruiker is niet toegevoegd";
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();

            echo "query is niet gelukt: " . $error;
        }
    }

    public function  inloggen($gebruikersnaam, $wachtwoord)
    {

        try {
            $query = "SELECT*FROM klant WHERE klant_naam=:username";



            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':username', $gebruikersnaam);
            // $statement->bindParam(':password',$wachtwoord_geb);

            $statement->execute();

            // $results =  $statement->fetchAll();
            $resluts = $statement->fetch(PDO::FETCH_ASSOC);

            if ($resluts) {



                $passHashDb = $resluts["klant_wachtwoord"];


                if (password_verify($wachtwoord, $passHashDb)) {


                    // Login is succesvol
                    // ik ga de medewerker naam in eeen session varibele bewaren zodat ik die in dashboard pagina kan gebruiken
                    $_SESSION['username'] = $resluts["klant_naam"];
                    $_SESSION['user_role'] = 'klant';
                    $_SESSION['isIngelogd'] = true;
                    //   $_SERVER['rol'] = "medewerker";
                    header("location:klantDashboard.php");
                    exit();
                } else {

                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();

            echo "Query is niet gelukt: " . $error;
        }
    }
}
