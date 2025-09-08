<?php
require_once "classDatabase.php";
class Wereldwonder extends  Database{

    private $tableNaam = "wereldwonderen";

    
    
 
 

    // methode WonderToevoegen
    public function wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door, $locatie, $latitude, $longitude, $status, $tags){
        try{

            $query = "INSERT INTO " . $this->tableNaam . " (naam, beschrijving, bouwjaar, werelddeel, type, bestaat_nog, toegevoegd_door, locatie, latitude, longitude, status, tags) 
            VALUES (:naam, :beschrijving, :bouwjaar, :werelddeel, :type, :bestaat_nog, :toegevoegd_door, :locatie, :latitude, :longitude, :status, :tags)";


              $statement =  $this->pdo->prepare($query);

            $statement = $this->pdo->prepare($query);

            $statement->bindParam(':naam', $naam);
            $statement->bindParam(':beschrijving', $beschrijving);
            $statement->bindParam(':bouwjaar', $bouwjaar);
            $statement->bindParam(':werelddeel', $werelddeel);
            $statement->bindParam(':type', $type);
            $statement->bindParam(':bestaat_nog', $bestaat_nog);
            $statement->bindParam(':toegevoegd_door', $toegevoegd_door);
            $statement->bindParam(':locatie', $locatie);
            $statement->bindParam(':latitude', $latitude);
            $statement->bindParam(':longitude', $longitude);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':tags', $tags);

          $statement->execute();

          if($statement){
            // echo " Film is toegevoegd, query is gelukt";

            header("location: wereldwonderenOverzicht.php");
        
        }

        }catch(PDOException $e){
        $error = $e->getMessage();
        
            echo "Fout bij toevoegen wereldwonder: " . $e->getMessage();
        }

        }
    





// Wereldwonder verwijderen
    public function wonderVerwijderen($wonderId){
      
      try{

        $query = "DELETE FROM " . $this->tableNaam . " WHERE wonder_id = :id";
        $statement =  $this->pdo->prepare($query);

            $statement->bindParam(':id', $wonderId);
        


        $statement->execute();
    
          header("location: wereldwonderenOverzicht.php");
            exit();
        
        
        }catch(PDOException $e){
            
            
            echo "Fout bij verwijderen: " . $e->getMessage();
        }

    }








 // Wereldwonder ophalen voor aanpassen
// stap 1 : wonderAanpassen/  wonder info ophalen
    public function wonderInfoOphalen($wonderId){

       
        
      try{
        // ik wil de info ophalen van de gekozen wonder met wonderId 
                  $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";$this->pdo->prepare($query);
         $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $wonderId]);
        
        $film = $statement->fetch();
      
       
        // ik wil zeker weten if de wonderId bestaat in db of niet
        if($statement){
          
            return $film;
            

          
        }else {
            echo "Helaas! wonder  is niet gevonden!";
        }
      }catch(PDOException $e){
         
          
          echo "Fout bij ophalen: " . $e->getMessage();
      }
    }


    // methode voor wonder info updaten/wonderAanpassen stap2

    public function  wonderUpdaten($wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags) {

        try{
          $query = "UPDATE " . $this->tableNaam . " 
            SET naam = :naam, beschrijving = :beschrijving, bouwjaar = :bouwjaar, werelddeel = :werelddeel, 
            type = :type, bestaat_nog = :bestaat_nog, locatie = :locatie, latitude = :latitude, longitude = :longitude, 
            status = :status, tags = :tags 
            WHERE wonder_id = :id";
              
           
            $statement =  $this->pdo->prepare($query);

           $statement->bindParam(':naam', $naam);
            $statement->bindParam(':beschrijving', $beschrijving);
            $statement->bindParam(':bouwjaar', $bouwjaar);
            $statement->bindParam(':werelddeel', $werelddeel);
            $statement->bindParam(':type', $type);
            $statement->bindParam(':bestaat_nog', $bestaat_nog);
            $statement->bindParam(':locatie', $locatie);
            $statement->bindParam(':latitude', $latitude);
            $statement->bindParam(':longitude', $longitude);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':tags', $tags);
            $statement->bindParam(':id', $wonderId);

            $statement->execute();
          
                  // vergeet dit nooit meer !!!!!!!!!!!!!!!!
           

            

            if($statement){
           
                // test
            
                // stuur de gebuiker weer naar wonderInfo pagina zodat hij de aanpassingen kan zien.
                header("location: wonderInfo.php?id=$wonderId");
             
           
                
            
            }

        }catch(PDOException $e){
        echo "Fout bij updaten: " . $e->getMessage();
        }
    }




   // Alle wereldwonderen ophalen 
    // public function haalAlleWonderen(){
         
    //                    try {
    //         $query = "SELECT * FROM " . $this->tableNaam;
    //         $statement = $this->pdo->query($query);
    //         $wonderen = $statement->fetchAll();

                
                

    //                         if($statement){
                               
                               
    //                           echo " <section id='wonderen_container'>";
    //                            foreach ($wonderen as $wonder) {
    //             $id = $wonder['wonder_id'];
                                 
    //                            echo "<article class='wonder'>
    //                     <a href='wereldwonderInfo.php?id=$id'>
    //                     <h2>" . htmlspecialchars($wonder['naam']) . "</h2>
    //                     <p>" . htmlspecialchars(substr($wonder['beschrijving'], 0, 100)) . "...</p>
    //                     </a>
    //                   </article>";
    //                      }
    //         echo "</section>";
    //                             //   HTML-injecties htmlspecialchars() voorkomen
                                 
    //                             //   echo "<img class='poster' src='" . htmlspecialchars($film['poster']) . "' alt='Film poster' />";
    //                             //   echo "<h1>Film Title: " . htmlspecialchars($film['film_title']) . "</h1>";
                               
    //                             //   echo " </a>  ";
                                  
    //                             //   echo "</article> ";


                                  
                                
                                
    //                             //   van 0 tot....
                                
                                
                           
                              
                               
                
    //                         }


    //                     }catch(PDOException $e){
    //                       echo "Fout bij ophalen: " . $e->getMessage();
    //                     }
    // }



    // Specifiek wereldwonder info tonen
    // public function toonInfoPerWonder($id){
        
    //                     try{
    //                        $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";
    //         $statement = $this->pdo->prepare($query);
    //         $statement->bindParam(':id', $id);
    //         $statement->execute();
    //         $wonder = $statement->fetch();

                
                

    //                          if ($wonder) {
    //             echo "<section id='wonder_box'>";
    //             echo "<h1>" . htmlspecialchars($wonder['naam']) . "</h1>";
    //             echo "<p>" . htmlspecialchars($wonder['beschrijving']) . "</p>";
    //             echo "<p><strong>Locatie:</strong> " . htmlspecialchars($wonder['locatie']) . "</p>";
    //             echo "<p><strong>Werelddeel:</strong> " . htmlspecialchars($wonder['werelddeel']) . "</p>";
    //             echo "<p><strong>Bestaat nog:</strong> " . ($wonder['bestaat_nog'] ? 'Ja' : 'Nee') . "</p>";
    //             echo "<p><strong>Tags:</strong> " . htmlspecialchars($wonder['tags']) . "</p>";
    //             echo "</section>";
    //         } else {
    //             echo "Geen wonder gevonden.";
    //         }


    //                     }catch(PDOException $e){
    //                      echo "Fout bij ophalen: " . $e->getMessage();
    //                     }
    // }





    // Specifiek wereldwonder info tonen + alle foto's en documenten
public function toonInfoPerWonder($id) {
    try {
        // 1. Haal de basisinfo van het wonder op
        $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $wonder = $statement->fetch();

        if ($wonder) {
            echo "<section id='wonder_box'>";
            echo "<h1>" . htmlspecialchars($wonder['naam']) . "</h1>";
            echo "<p>" . htmlspecialchars($wonder['beschrijving']) . "</p>";
            echo "<p><strong>Bouwjaar:</strong> " . htmlspecialchars($wonder['bouwjaar']) . "</p>";
            echo "<p><strong>Locatie:</strong> " . htmlspecialchars($wonder['locatie']) . "</p>";
            echo "<p><strong>Werelddeel:</strong> " . htmlspecialchars($wonder['werelddeel']) . "</p>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($wonder['type']) . "</p>";
            echo "<p><strong>Bestaat nog:</strong> " . ($wonder['bestaat_nog'] ? 'Ja' : 'Nee') . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($wonder['status']) . "</p>";
            echo "<p><strong>Tags:</strong> " . htmlspecialchars($wonder['tags']) . "</p>";

            // 2. Haal alle bijbehorende foto's op
            $fotoQuery = "SELECT bestandspad 
                          FROM fotos
                          WHERE wonder_id = :id AND goedgekeurd = 1";
            $fotoStmt = $this->pdo->prepare($fotoQuery);
            $fotoStmt->bindParam(':id', $id);
            $fotoStmt->execute();
            $fotos = $fotoStmt->fetchAll();

            if ($fotos) {
                echo "<div class='wonder_fotos'>";
                echo "<h2>Foto's:</h2>";
                foreach ($fotos as $foto) {
                    echo "<img class='wonder_foto' src='" . htmlspecialchars($foto['bestandspad']) . "' alt='Foto van " . htmlspecialchars($wonder['naam']) . "' />";
                }
                echo "</div>";
            } else {
                echo "<p><em>Geen foto's beschikbaar.</em></p>";
            }

            // 3. Haal alle bijbehorende documenten op
            $docQuery = "SELECT bestandspad, type 
                         FROM documenten 
                         WHERE wonder_id = :id";
            $docStmt = $this->pdo->prepare($docQuery);
            $docStmt->bindParam(':id', $id);
            $docStmt->execute();
            $docs = $docStmt->fetchAll();

            if ($docs) {
                echo "<div class='wonder_docs'>";
                echo "<h2>Documenten:</h2>";
                echo "<ul>";
                foreach ($docs as $doc) {
                    $pad = htmlspecialchars($doc['bestandspad']);
                    $type = htmlspecialchars($doc['type']);
                    echo "<li><a href='" . $pad . "' target='_blank'>Document (" . $type . ")</a></li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                echo "<p><em>Geen documenten beschikbaar.</em></p>";
            }

            echo "</section>";
        } else {
            echo "Geen wonder gevonden.";
        }

    } catch(PDOException $e) {
        echo "Fout bij ophalen: " . $e->getMessage();
    }
}






        // ✅ Alle wereldwonderen ophalen met foto

    public function haalAlleWonderen() {
        try {
            $query = "SELECT w.*, f.bestandspad 
                      FROM " . $this->tableNaam . " w
                      LEFT JOIN fotos f ON w.wonder_id = f.wonder_id AND f.goedgekeurd = 1
                      GROUP BY w.wonder_id"; 

            $statement = $this->pdo->query($query);
            $wonderen = $statement->fetchAll();

            echo "<section id='wonderen_container'>";
            foreach ($wonderen as $wonder) {
                $id = $wonder['wonder_id'];

                // fallback als er geen foto is
                $foto = $wonder['bestandspad'] ? htmlspecialchars($wonder['bestandspad']) : "img/geen_foto.png";

                echo "<article class='wonder'>
                        <a href='wereldwonderInfo.php?id=$id'>
                            <img class='poster' src='" . $foto . "' alt='Foto van " . htmlspecialchars($wonder['naam']) . "' />
                            <h2>" . htmlspecialchars($wonder['naam']) . "</h2>
                            <p>" . htmlspecialchars(substr($wonder['beschrijving'], 0, 100)) . "...</p>
                        </a>
                      </article>";
            }
            echo "</section>";

        } catch(PDOException $e) {
            echo "Fout bij ophalen: " . $e->getMessage();
        }
    }





    // ✅ Wereldwonderen ophalen met zoek, filter en sorteer
public function haalAlleWonderenOverzicht($zoekterm, $werelddeel, $bestaatNog, $type, $sorteren) {
    try {
        // Basisquery
        $query = "SELECT w.*, f.bestandspad 
                  FROM " . $this->tableNaam . " w
                  LEFT JOIN fotos f ON w.wonder_id = f.wonder_id AND f.goedgekeurd = 1
                  WHERE 1=1"; // 1=1 zodat we makkelijk AND's kunnen toevoegen

        // Parameters array voor prepared statement
        $params = [];

        // 🔍 Zoekterm (naam + beschrijving)
        if (!empty($zoekterm)) {
            $query .= " AND (w.naam LIKE :zoekterm OR w.beschrijving LIKE :zoekterm)";
            $params[':zoekterm'] = "%" . $zoekterm . "%";
        }

        // 🌍 Filter werelddeel
        if (!empty($werelddeel)) {
            $query .= " AND w.werelddeel = :werelddeel";
            $params[':werelddeel'] = $werelddeel;
        }

        // ✅ Filter bestaat_nog
        if ($bestaatNog !== '') { // kan 0 of 1 zijn
            $query .= " AND w.bestaat_nog = :bestaat_nog";
            $params[':bestaat_nog'] = $bestaatNog;
        }

        // 🏛️ Filter type
        if (!empty($type)) {
            $query .= " AND w.type = :type";
            $params[':type'] = $type;
        }

        // 📊 Sorteeropties
        $allowedSort = ['naam', 'bouwjaar', 'werelddeel'];
        if (in_array($sorteren, $allowedSort)) {
            $query .= " ORDER BY w." . $sorteren . " ASC";
        } else {
            $query .= " ORDER BY w.naam ASC"; // fallback
        }

        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        $wonderen = $statement->fetchAll();

        // HTML tonen
        echo "<section id='wonderen_container'>";
        foreach ($wonderen as $wonder) {
            $id = $wonder['wonder_id'];
            $foto = $wonder['bestandspad'] ? htmlspecialchars($wonder['bestandspad']) : "img/geen_foto.png";

            echo "<article class='wonder'>
                    <a href='wereldwonderInfo.php?id=$id'>
                        <img class='poster' src='" . $foto . "' alt='Foto van " . htmlspecialchars($wonder['naam']) . "' />
                        <h2>" . htmlspecialchars($wonder['naam']) . "</h2>
                        <p>" . htmlspecialchars(substr($wonder['beschrijving'], 0, 100)) . "...</p>
                    </a>
                  </article>";
        }
        echo "</section>";

    } catch(PDOException $e) {
        echo "Fout bij ophalen: " . $e->getMessage();
    }
}



    
    // public function toonInfoPerFilmMedewerker($id){
    //   try{
    //     $query = "SELECT*FROM films where films_id= :id ";


    //     $statement =  $this->pdo->prepare($query);

      
    //     $statement->bindParam(':id', $id);
            
    //     $statement->execute();

      
    //     // ik ga hier fetch gebruiken omdat ik een row wil printen niet de alle rows
    //     $film= $statement->fetch();


    //    if($film){
  
    //   echo " <section id='film_box'>";
      
    //           //   HTML-injecties htmlspecialchars() voorkomen
    //           echo "<article >";
    //               echo "<h1>Film Title: " . htmlspecialchars($film['film_title']) . "</h1>";
    //               echo "<img src='" . htmlspecialchars($film['poster']) . "' alt='Film poster' class='poster'  />";
    //           echo "</article> ";




    //           echo "<article>";

    //                       // verwijderen en aanpassen knopjes
    //                       echo "<div class='buttons'>";

    //                       // Aanpassen formulier  \ singel '' of dubbel
    //                       echo "<form action='filmAanpassen.php' method='post' id='formAanpassen" . $id . "'  onsubmit=\"return confirm('Weet je zeker dat je deze film wilt aanpassen? of Ja klik OK of nee klik Cancel')\">";
    //                       echo "<input type='hidden' name='film_id' value='" . $id . "' />";
    //                       echo "<button type='submit'>Aanpassen</button>";
    //                       echo "</form>";

    //                       // Verwijderen formulier
    //                       echo "<form action='filmVerwijderen.php' method='post' id='formVerwijderen" . $id . "' onsubmit=\"return confirm('Weet je zeker dat je deze film wilt verwijderen?  of Ja klik OK of nee druk Cancel')\">";
    //                       echo "<input type='hidden' name='film_id' value='" . $id . "' />";
    //                       echo "<button type='submit'>Verwijderen</button>";
    //                       echo "</form>";

    //                       echo "</div>";







              // film info
//                           echo "<h1>Film Genre: </h1> <p>" . htmlspecialchars($film['film_gener']) . "</p>";
//                           echo "<h1>Film tijd: </h1> <p>" . htmlspecialchars($film['film_duration']) . "</p>";
//                           echo "<h1>Film omschrijving: </h1> <p>" . htmlspecialchars($film['film_beschrijving']) . "</p>";
//                           echo "<h1>Film lanceer datum: </h1> <p>" . htmlspecialchars($film['film_lanceer_datum']) . "</p>";
//                           echo "<h1>Film trailer: </h1> <iframe  src='".htmlspecialchars($film['film_treil'])."' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                
//               echo "</article> ";

        





//       echo "</section";
//     }


//   } catch(PDOException $e){
//       $error = $e->getMessage();
      
//       echo "query is niet gelukt: ".$error;
//   }
//       }


//     public function toonInfoPerFilmKlant($id){
                         
//         try{
//           $query = "SELECT*FROM films where films_id= :id ";


//           $statement =  $this->pdo->prepare($query);

        
//           $statement->bindParam(':id', $id);
              
//           $statement->execute();

//           //    $films= $statement->fetchAll();
//           // ik ga hier fetch gebruiken omdat ik een row wil printen niet de alle rows
//           $film= $statement->fetch();


//           if($film){
   
//              echo " <section id='film_box'>";
      
//                 //   HTML-injecties htmlspecialchars() voorkomen
//                 echo "<article  >";
//                     echo "<h1>Film Title: " . htmlspecialchars($film['film_title']) . "</h1>";
//                     echo "<img src='" . htmlspecialchars($film['poster']) . "' alt='Film poster' class='poster'  />";
//                 echo "</article> ";
                


//               // film info
//               echo "<article> ";
//                           echo "<h1>Film Genre: </h1> <p>" . htmlspecialchars($film['film_gener']) . "</p>";
//                           echo "<h1>Film tijd: </h1> <p>" . htmlspecialchars($film['film_duration']) . "</p>";
//                           echo "<h1>Film omschrijving: </h1> <p>" . htmlspecialchars($film['film_beschrijving']) . "</p>";
//                           echo "<h1>Film lanceer datum: </h1> <p>" . htmlspecialchars($film['film_lanceer_datum']) . "</p>";
//                           echo "<h1>Film trailer: </h1> <iframe  src='".htmlspecialchars($film['film_treil'])."' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                 
//               echo "</article> ";

        





//             echo "</section";
//       }


//   } catch(PDOException $e){
//       $error = $e->getMessage();
      
//       echo "query is niet gelukt: ".$error;
//   }
//       }

 }











