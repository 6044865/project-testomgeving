<?php
require_once "classDatabase.php";
class Wereldwonder extends  Database{

    private $tableNaam = "wereldwonderen";

    
    
 
 

    // methode WonderToevoegen
    public function wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door, $locatie, $latitude, $longitude, $status, $tags){
        try{

            $query = "INSERT INTO " . $this->tableNaam . " (naam, beschrijving, bouwjaar, werelddeel, type, bestaat_nog, toegevoegd_door, locatie, latitude, longitude, status, tags) 
            VALUES (:naam, :beschrijving, :bouwjaar, :werelddeel, :type, :bestaat_nog, :toegevoegd_door, :locatie, :latitude, :longitude, :status, :tags)";


           

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
if ($statement->rowCount() > 0) {
    header("Location: wonderBeheer.php");
    exit();
}


        }catch(PDOException $e){
        $error = $e->getMessage();
        
            echo "Fout bij toevoegen wereldwonder: " . $e->getMessage();
        }

        }
    





// Wereldwonder verwijderen
    public function wonderVerwijderen($wonderId){
    try {
        // Eerst foto's verwijderen
        $this->pdo->prepare("DELETE FROM fotos WHERE wonder_id = :id")
                  ->execute([':id' => $wonderId]);

        // Daarna documenten verwijderen
        $this->pdo->prepare("DELETE FROM documenten WHERE wonder_id = :id")
                  ->execute([':id' => $wonderId]);

        // Daarna pas het wereldwonder zelf verwijderen
        $query = "DELETE FROM " . $this->tableNaam . " WHERE wonder_id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $wonderId, PDO::PARAM_INT);
        $statement->execute();

        header("location: wonderBeheer.php");
        exit();

    } catch(PDOException $e) {
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
   public function wonderUpdaten(
    $wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel, $type,
    $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags
) {
    try {
        $query = "UPDATE " . $this->tableNaam . "
                  SET naam = :naam,
                      beschrijving = :beschrijving,
                      bouwjaar = :bouwjaar,
                      werelddeel = :werelddeel,
                      type = :type,
                      bestaat_nog = :bestaat_nog,
                      locatie = :locatie,
                      latitude = :latitude,
                      longitude = :longitude,
                      status = :status,
                      tags = :tags
                  WHERE wonder_id = :id";

        $stmt = $this->pdo->prepare($query);

        // Bind waarden met NULL-checks voor lege velden
        $stmt->bindValue(':naam', $naam, PDO::PARAM_STR);
        $stmt->bindValue(':beschrijving', $beschrijving, PDO::PARAM_STR);

        $stmt->bindValue(':bouwjaar', ($bouwjaar !== null && $bouwjaar !== '') ? (int)$bouwjaar : null,
                         ($bouwjaar !== null && $bouwjaar !== '') ? PDO::PARAM_INT : PDO::PARAM_NULL);

        $stmt->bindValue(':werelddeel', $werelddeel, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);

        $stmt->bindValue(':bestaat_nog', ($bestaat_nog !== null && $bestaat_nog !== '') ? (int)$bestaat_nog : null,
                         ($bestaat_nog !== null && $bestaat_nog !== '') ? PDO::PARAM_INT : PDO::PARAM_NULL);

        $stmt->bindValue(':locatie', $locatie, PDO::PARAM_STR);

        $stmt->bindValue(':latitude', ($latitude !== null && $latitude !== '') ? (float)$latitude : null,
                         ($latitude !== null && $latitude !== '') ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt->bindValue(':longitude', ($longitude !== null && $longitude !== '') ? (float)$longitude : null,
                         ($longitude !== null && $longitude !== '') ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':tags', $tags, PDO::PARAM_STR);

        $stmt->bindValue(':id', (int)$wonderId, PDO::PARAM_INT);

        // Execute query
        $result = $stmt->execute();

        // Debug output
        // echo "<pre style='background:#f2f2f2;border:1px solid #ccc;padding:10px;'>";
        // echo "DEBUG: wonderUpdaten uitgevoerd voor ID: $wonderId\n";
        // echo "Query uitgevoerd: \n$query\n";
        // echo "Bind waarden: ";
        // print_r([
        //     'naam' => $naam,
        //     'beschrijving' => $beschrijving,
        //     'bouwjaar' => $bouwjaar,
        //     'werelddeel' => $werelddeel,
        //     'type' => $type,
        //     'bestaat_nog' => $bestaat_nog,
        //     'locatie' => $locatie,
        //     'latitude' => $latitude,
        //     'longitude' => $longitude,
        //     'status' => $status,
        //     'tags' => $tags,
        //     'id' => $wonderId
        // ]);
        // echo "\nResultaat execute: "; var_dump($result);
        // echo "\nRijen aangepast: " . $stmt->rowCount();
        // echo "</pre>";

        // return $result;
        header("location: wonderBeheer.php");

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij updaten: " . $e->getMessage() . "</p>";
        return false;
    }
}




// Wereldwonder volledig updaten, inclusief toegevoegde kolommen
public function wonderVolledigUpdaten(
    $wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog,
    $toegevoegd_door, $aangemaakt_op, $locatie, $latitude, $longitude, $status, $tags
) {
    try {
        $query = "UPDATE " . $this->tableNaam . "
                  SET naam = :naam,
                      beschrijving = :beschrijving,
                      bouwjaar = :bouwjaar,
                      werelddeel = :werelddeel,
                      type = :type,
                      bestaat_nog = :bestaat_nog,
                      toegevoegd_door = :toegevoegd_door,
                      aangemaakt_op = :aangemaakt_op,
                      locatie = :locatie,
                      latitude = :latitude,
                      longitude = :longitude,
                      status = :status,
                      tags = :tags
                  WHERE wonder_id = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':naam', $naam, PDO::PARAM_STR);
        $stmt->bindValue(':beschrijving', $beschrijving, PDO::PARAM_STR);
        $stmt->bindValue(':bouwjaar', $bouwjaar !== null ? (int)$bouwjaar : null, $bouwjaar !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':werelddeel', $werelddeel, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':bestaat_nog', $bestaat_nog !== null ? (int)$bestaat_nog : null, $bestaat_nog !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':toegevoegd_door', $toegevoegd_door, PDO::PARAM_INT);
        $stmt->bindValue(':aangemaakt_op', $aangemaakt_op, PDO::PARAM_STR);
        $stmt->bindValue(':locatie', $locatie, PDO::PARAM_STR);
        $stmt->bindValue(':latitude', $latitude !== null ? (float)$latitude : null, $latitude !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':longitude', $longitude !== null ? (float)$longitude : null, $longitude !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':tags', $tags, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$wonderId, PDO::PARAM_INT);

        $result = $stmt->execute();

        echo "<pre>DEBUG update uitgevoerd voor ID: $wonderId\n";
        echo "Bind waarden: "; print_r([
            'naam'=>$naam, 'beschrijving'=>$beschrijving, 'bouwjaar'=>$bouwjaar, 'werelddeel'=>$werelddeel,
            'type'=>$type, 'bestaat_nog'=>$bestaat_nog, 'toegevoegd_door'=>$toegevoegd_door,
            'aangemaakt_op'=>$aangemaakt_op, 'locatie'=>$locatie, 'latitude'=>$latitude,
            'longitude'=>$longitude, 'status'=>$status, 'tags'=>$tags, 'id'=>$wonderId
        ]);
        echo "\nResult execute: "; var_dump($result);
        echo "\nRijen aangepast: " . $stmt->rowCount() . "</pre>";
        

        header("location: wereldwonderenOverzicht.php");

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij volledig updaten: " . $e->getMessage() . "</p>";
        return false;
    }
}



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

    // public function haalAlleWonderen() {
    //     try {
    //         // $query = "SELECT w.*, f.bestandspad 
    //         //           FROM " . $this->tableNaam . " w
    //         //           LEFT JOIN fotos f ON w.wonder_id = f.wonder_id AND f.goedgekeurd = 1
    //         //           GROUP BY w.wonder_id"; 
    //                   $query = "SELECT w.*, f.bestandspad
    //       FROM " . $this->tableNaam . " w
    //       LEFT JOIN (
    //           SELECT wonder_id, MIN(bestandspad) AS bestandspad
    //           FROM fotos
    //           WHERE goedgekeurd = 1
    //           GROUP BY wonder_id
    //       ) f ON w.wonder_id = f.wonder_id";

                   

    //         $statement = $this->pdo->query($query);
    //         $wonderen = $statement->fetchAll();

    //         echo "<section id='wonderen_container'>";
    //         foreach ($wonderen as $wonder) {
    //             $id = $wonder['wonder_id'];

    //             // fallback als er geen foto is
    //             $foto = $wonder['bestandspad'] ? htmlspecialchars($wonder['bestandspad']) : "img/geen_foto.png";

    //             echo "<article class='wonder'>
    //                     <a href='wereldwonderInfo.php?id=$id'>
    //                         <img class='poster' src='" . $foto . "' alt='Foto van " . htmlspecialchars($wonder['naam']) . "' />
    //                         <h2>" . htmlspecialchars($wonder['naam']) . "</h2>
    //                         <p>" . htmlspecialchars(substr($wonder['beschrijving'], 0, 100)) . "...</p>
    //                     </a>
    //                   </article>";
    //         }
    //         echo "</section>";

    //     } catch(PDOException $e) {
    //         echo "Fout bij ophalen: " . $e->getMessage();
    //     }
    // }
    public function haalAlleWonderen() {
    try {
        $query = "SELECT w.wonder_id, w.naam, w.beschrijving, f.bestandspad
                  FROM " . $this->tableNaam . " w
                 LEFT JOIN fotos f ON f.foto_id = (
    SELECT f2.foto_id
    FROM fotos f2
    WHERE f2.wonder_id = w.wonder_id AND f2.goedgekeurd = 1
    ORDER BY f2.foto_id DESC
    LIMIT 1
)
";

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
                      </a>
                  </article>";
        }
        echo "</section>";

    } catch(PDOException $e) {
        echo "Fout bij ophalen: " . $e->getMessage();
    }
}


// data teruggeeft als array om langitude en latitude uit te halen
public function getWonderMetDetails($id) {
    try {
        $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $wonder = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$wonder) return null;

        // Voeg eventueel foto's toe
        $fotoStmt = $this->pdo->prepare("SELECT bestandspad FROM fotos WHERE wonder_id = :id AND goedgekeurd = 1");
        $fotoStmt->execute(['id' => $id]);
        $wonder['fotos'] = $fotoStmt->fetchAll(PDO::FETCH_ASSOC);

        // Voeg documenten toe
        $docStmt = $this->pdo->prepare("SELECT bestandspad, type FROM documenten WHERE wonder_id = :id");
        $docStmt->execute(['id' => $id]);
        $wonder['docs'] = $docStmt->fetchAll(PDO::FETCH_ASSOC);

        return $wonder;

    } catch(PDOException $e) {
        echo "Fout bij ophalen: " . $e->getMessage();
        return null;
    }
}



public function getAlleWonderen() {
    try {
        $query = "SELECT wonder_id, naam FROM " . $this->tableNaam . " ORDER BY naam ASC";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Fout bij ophalen van wonderen: " . $e->getMessage();
        return [];
    }
}


    // ✅ Wereldwonderen ophalen met zoek, filter en sorteer

public function haalAlleWonderenOverzicht($zoekterm, $werelddeel, $bestaatNog, $type, $sorteren) {
    try {
        $query = "SELECT w.*, 
                         (SELECT f.bestandspad 
                          FROM fotos f 
                          WHERE f.wonder_id = w.wonder_id AND f.goedgekeurd = 1 
                          ORDER BY f.foto_id ASC 
                          LIMIT 1) AS bestandspad
                  FROM " . $this->tableNaam . " w
                  WHERE 1=1";

        $params = [];

        if (!empty($zoekterm)) {
            $query .= " AND (w.naam LIKE :zoekterm OR w.beschrijving LIKE :zoekterm)";
            $params[':zoekterm'] = "%" . $zoekterm . "%";
        }
        if (!empty($werelddeel)) {
            $query .= " AND w.werelddeel = :werelddeel";
            $params[':werelddeel'] = $werelddeel;
        }
        if ($bestaatNog !== '') {
            $query .= " AND w.bestaat_nog = :bestaat_nog";
            $params[':bestaat_nog'] = $bestaatNog;
        }
        if (!empty($type)) {
            $query .= " AND w.type = :type";
            $params[':type'] = $type;
        }

        // sorteren
        switch($sorteren) {
            case 'naam_asc': $orderBy = "w.naam ASC"; break;
            case 'naam_desc': $orderBy = "w.naam DESC"; break;
            case 'bouwjaar_asc': $orderBy = "w.bouwjaar ASC"; break;
            case 'bouwjaar_desc': $orderBy = "w.bouwjaar DESC"; break;
            default: $orderBy = "w.naam ASC"; break;
        }
        $query .= " ORDER BY " . $orderBy;

        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        $wonderen = $statement->fetchAll();

        echo "<section id='wonderen_container'>";
        foreach ($wonderen as $wonder) {
            $id = $wonder['wonder_id'];
            $foto = $wonder['bestandspad'] ? htmlspecialchars($wonder['bestandspad']) : "img/geen_foto.png";

            echo "<article class='wonder_card'>
                    <a href='wereldwonderInfo.php?id=$id'>
                        <img class='poster' src='" . $foto . "' alt='Foto van " . htmlspecialchars($wonder['naam']) . "' />
                        <h2>" . htmlspecialchars($wonder['naam']) . "</h2>
                    </a>
                  </article>";
        }
        echo "</section>";

    } catch(PDOException $e) {
        echo "Fout bij ophalen: " . $e->getMessage();
    }
}

 }











