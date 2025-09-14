<?php
require_once "classDatabase.php";

class Wereldwonder extends Database {

    private $tableNaam = "wereldwonderen";

    // ✅ Voeg een nieuw wonder toe
    public function wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door, $locatie, $latitude, $longitude, $status, $tags){
        // Check rechten
        if (empty($_SESSION['rechten']['kan_wonder_toevoegen'])) {
            die("❌ Je hebt geen toestemming om een wereldwonder toe te voegen.");
        }

        try {
            $query = "INSERT INTO " . $this->tableNaam . " 
                      (naam, beschrijving, bouwjaar, werelddeel, type, bestaat_nog, toegevoegd_door, locatie, latitude, longitude, status, tags) 
                      VALUES (:naam, :beschrijving, :bouwjaar, :werelddeel, :type, :bestaat_nog, :toegevoegd_door, :locatie, :latitude, :longitude, :status, :tags)";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':naam' => $naam,
                ':beschrijving' => $beschrijving,
                ':bouwjaar' => $bouwjaar,
                ':werelddeel' => $werelddeel,
                ':type' => $type,
                ':bestaat_nog' => $bestaat_nog,
                ':toegevoegd_door' => $toegevoegd_door,
                ':locatie' => $locatie,
                ':latitude' => $latitude,
                ':longitude' => $longitude,
                ':status' => $status,
                ':tags' => $tags
            ]);

            header("Location: wereldwonderenOverzicht.php");
            exit();
        } catch(PDOException $e) {
            echo "Fout bij toevoegen wereldwonder: " . $e->getMessage();
        }
    }

    // ✅ Update een bestaand wonder
    // public function wonderUpdaten($wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags) {
    //     try {
    //         // Haal eigenaar op
    //         $stmtCheck = $this->pdo->prepare("SELECT toegevoegd_door FROM wereldwonderen WHERE wonder_id = :id");
    //         $stmtCheck->execute([':id' => $wonderId]);
    //         $result = $stmtCheck->fetch();

    //         if (!$result) die("❌ Wereldwonder niet gevonden.");
    //         $toegevoegdDoor = $result['toegevoegd_door'];

    //         // Alleen eigen toevoegingen of gebruikers met bewerk-recht
    //         if ($_SESSION['gebruiker_id'] != $toegevoegdDoor && empty($_SESSION['rechten']['kan_wonder_bewerken'])) {
    //             die("❌ Je hebt geen toestemming om dit wonder te bewerken.");
    //         }

    //         $query = "UPDATE " . $this->tableNaam . "
    //                   SET naam = :naam,
    //                       beschrijving = :beschrijving,
    //                       bouwjaar = :bouwjaar,
    //                       werelddeel = :werelddeel,
    //                       type = :type,
    //                       bestaat_nog = :bestaat_nog,
    //                       locatie = :locatie,
    //                       latitude = :latitude,
    //                       longitude = :longitude,
    //                       status = :status,
    //                       tags = :tags
    //                   WHERE wonder_id = :id";

    //         $stmt = $this->pdo->prepare($query);
    //         $stmt->execute([
    //             ':naam' => $naam,
    //             ':beschrijving' => $beschrijving,
    //             ':bouwjaar' => $bouwjaar,
    //             ':werelddeel' => $werelddeel,
    //             ':type' => $type,
    //             ':bestaat_nog' => $bestaat_nog,
    //             ':locatie' => $locatie,
    //             ':latitude' => $latitude,
    //             ':longitude' => $longitude,
    //             ':status' => $status,
    //             ':tags' => $tags,
    //             ':id' => $wonderId
    //         ]);

    //         header("Location: wereldwonderBewerken.php?wonder_id=" . $wonderId);
    //         exit();

    //     } catch(PDOException $e) {
    //         echo "Fout bij updaten: " . $e->getMessage();
    //     }
    // }
    public function wonderUpdaten($wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags) {
    echo "<pre>DEBUG: wonderUpdaten gestart\n";
    echo "Sessiedata:\n";
    print_r($_SESSION);
    echo "Parameters ontvangen:\n";
    print_r(compact('wonderId','naam','beschrijving','bouwjaar','werelddeel','type','bestaat_nog','locatie','latitude','longitude','status','tags'));

    try {
        // Haal eigenaar op
        $stmtCheck = $this->pdo->prepare("SELECT toegevoegd_door FROM wereldwonderen WHERE wonder_id = :id");
        $stmtCheck->execute([':id' => $wonderId]);
        $result = $stmtCheck->fetch();

        if (!$result) {
            die("❌ Wereldwonder niet gevonden.");
        }
        $toegevoegdDoor = $result['toegevoegd_door'];
        echo "Toegevoegd door: $toegevoegdDoor\n";

        // Rechtencheck
        $magBewerken = false;
        if ($_SESSION['gebruiker_id'] == $toegevoegdDoor) {
            $magBewerken = true;
            echo "Gebruiker is eigenaar van het wonder.\n";
        }
        if (!empty($_SESSION['rechten']['kan_wonder_bewerken'])) {
            $magBewerken = true;
            echo "Gebruiker heeft bewerk-recht.\n";
        }

        if (!$magBewerken) {
            die("❌ Je hebt geen toestemming om dit wonder te bewerken.");
        }

        // Update query
        $query = "UPDATE wereldwonderen
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
        $stmt->execute([
            ':naam' => $naam,
            ':beschrijving' => $beschrijving,
            ':bouwjaar' => $bouwjaar,
            ':werelddeel' => $werelddeel,
            ':type' => $type,
            ':bestaat_nog' => $bestaat_nog,
            ':locatie' => $locatie,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':status' => $status,
            ':tags' => $tags,
            ':id' => $wonderId
        ]);

        echo "✅ Wereldwonder succesvol bijgewerkt!\n";
        print_r($stmt->rowCount());

    } catch(PDOException $e) {
        echo "❌ Fout bij updaten: " . $e->getMessage();
    }

    echo "</pre>";
}


    // ✅ Verwijder een wonder
    public function wonderVerwijderen($wonderId) {
        if (empty($_SESSION['rechten']['kan_wonder_verwijderen'])) {
            die("❌ Je hebt geen toestemming om dit wonder te verwijderen.");
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM " . $this->tableNaam . " WHERE wonder_id = :id");
            $stmt->execute([':id' => $wonderId]);
            header("Location: wereldwonderenOverzicht.php");
            exit();
        } catch(PDOException $e) {
            echo "Fout bij verwijderen: " . $e->getMessage();
        }
    }

    // ✅ Goedkeuren door redacteur
    public function goedkeurWonder($wonderId) {
        if (empty($_SESSION['rechten']['kan_wonder_goedkeuren'])) {
            die("❌ Je hebt geen toestemming om dit wonder goed te keuren.");
        }

        $stmt = $this->pdo->prepare("UPDATE " . $this->tableNaam . " SET status = 'goedgekeurd' WHERE wonder_id = :id");
        $stmt->execute([':id' => $wonderId]);
    }

    // ✅ Haal informatie van één wonder
    public function wonderInfoOphalen($wonderId){
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id");
            $stmt->execute([':id' => $wonderId]);
            return $stmt->fetch();
        } catch(PDOException $e) {
            echo "Fout bij ophalen: " . $e->getMessage();
        }
    }

    // ✅ Haal alle wonderen (kort overzicht)
    public function haalAlleWonderen() {
        try {
            $query = "SELECT w.wonder_id, w.naam, w.beschrijving, f.bestandspad
                      FROM " . $this->tableNaam . " w
                      LEFT JOIN fotos f ON f.foto_id = (
                          SELECT f2.foto_id FROM fotos f2
                          WHERE f2.wonder_id = w.wonder_id AND f2.goedgekeurd = 1
                          ORDER BY f2.foto_id DESC
                          LIMIT 1
                      )";
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            echo "Fout bij ophalen: " . $e->getMessage();
            return [];
        }
    }

    // ✅ Haal alle wonderen met filters en zoek
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
                $params[':zoekterm'] = "%$zoekterm%";
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

            // Sortering
            $orderBy = match($sorteren) {
                'naam_asc' => "w.naam ASC",
                'naam_desc' => "w.naam DESC",
                'bouwjaar_asc' => "w.bouwjaar ASC",
                'bouwjaar_desc' => "w.bouwjaar DESC",
                default => "w.naam ASC",
            };
            $query .= " ORDER BY $orderBy";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            echo "Fout bij ophalen: " . $e->getMessage();
            return [];
        }
    }

    // ✅ Haal wonder met details (foto's, documenten)
    public function getWonderMetDetails($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id");
            $stmt->execute([':id' => $id]);
            $wonder = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$wonder) return null;

            $fotoStmt = $this->pdo->prepare("SELECT bestandspad FROM fotos WHERE wonder_id = :id AND goedgekeurd = 1");
            $fotoStmt->execute([':id' => $id]);
            $wonder['fotos'] = $fotoStmt->fetchAll(PDO::FETCH_ASSOC);

            $docStmt = $this->pdo->prepare("SELECT bestandspad, type FROM documenten WHERE wonder_id = :id");
            $docStmt->execute([':id' => $id]);
            $wonder['docs'] = $docStmt->fetchAll(PDO::FETCH_ASSOC);

            return $wonder;
        } catch(PDOException $e) {
            echo "Fout bij ophalen: " . $e->getMessage();
            return null;
        }
    }
}
?>
