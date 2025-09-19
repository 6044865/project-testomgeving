<?php
require_once "classDatabase.php";

class Document extends Database {

    private $tableNaam = "documenten";

    // Document toevoegen
    public function documentToevoegen($wonderId, $bestandspad, $type, $grootte, $toegevoegd_door, $toegevoegd_naam) {
    try {
        $query = "INSERT INTO " . $this->tableNaam . " 
                  (wonder_id, bestandspad, type, grootte, toegevoegd_door, toegevoegd_naam) 
                  VALUES (:wonder_id, :bestandspad, :type, :grootte, :toegevoegd_door, :toegevoegd_naam)";
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            ':wonder_id' => $wonderId,
            ':bestandspad' => $bestandspad,
            ':type' => $type,
            ':grootte' => $grootte,
            ':toegevoegd_door' => $toegevoegd_door,
            ':toegevoegd_naam' => $toegevoegd_naam
        ]);

        // DEBUG
        // echo "<pre style='background:#f2f2f2;border:1px solid #ccc;padding:10px;'>";
        // echo "DEBUG: documentToevoegen uitgevoerd voor wonder_id: $wonderId\n";
        // echo "Pad: $bestandspad, type: $type, grootte: $grootte\n";
        // echo "Resultaat execute: "; var_dump($result);
        // echo "</pre>";

        return $result;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij document toevoegen: " . $e->getMessage() . "</p>";
        return false;
    }
}


// 
// Document toevoegen met groottecontrole
public function documentToevoegenBeheerder($wonderId, $bestandspad, $type, $grootte, $toegevoegd_door, $toegevoegd_naam, $maxGrootteMB = 5) {
    try {
        // Controleer bestandsgrootte
        if (!$this->checkBestandsgrootte($grootte, $maxGrootteMB * 1024 * 1024)) {
            throw new Exception("Bestand te groot! Maximaal {$maxGrootteMB} MB toegestaan.");
        }

        $query = "INSERT INTO " . $this->tableNaam . " 
                  (wonder_id, bestandspad, type, grootte, toegevoegd_door, toegevoegd_naam) 
                  VALUES (:wonder_id, :bestandspad, :type, :grootte, :toegevoegd_door, :toegevoegd_naam)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':wonder_id' => $wonderId,
            ':bestandspad' => $bestandspad,
            ':type' => $type,
            ':grootte' => $grootte,
            ':toegevoegd_door' => $toegevoegd_door,
            ':toegevoegd_naam' => $toegevoegd_naam
        ]);

        return true;

    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ " . $e->getMessage() . "</p>";
        return false;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij toevoegen document: " . $e->getMessage() . "</p>";
        return false;
    }
}


    // DocumentClass.php
public function documentVerwijderen($docId) {
    try {
        // Haal bestandspad op
        $query = "SELECT bestandspad FROM " . $this->tableNaam . " WHERE document_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $docId]);
        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($document) {
            $bestandspad = $document['bestandspad'];

            // Verwijder fysiek bestand als het bestaat
            if (file_exists($bestandspad)) {
                unlink($bestandspad);
            } else {
                // Bestand bestaat niet, kan zijn verwijderd buiten de app
                // Log dit evt, maar verwijder record alsnog
            }

            // Record verwijderen
            $query = "DELETE FROM " . $this->tableNaam . " WHERE document_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $docId]);

            return true; // Altijd true als record verwijderd is
        }
        return false; // Document niet gevonden
    } catch (PDOException $e) {
        echo "❌ Fout bij document verwijderen: " . $e->getMessage();
        return false;
    }
}


    // Alle documenten per wonder ophalen
    public function getDocumentenPerWonder($wonderId) {
        try {
            $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id ORDER BY document_id DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $wonderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "❌ Fout bij ophalen documenten: " . $e->getMessage();
            return [];
        }
    }

    // Één document ophalen
    public function getDocument($docId) {
        try {
            $query = "SELECT * FROM " . $this->tableNaam . " WHERE document_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $docId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "❌ Fout bij ophalen document: " . $e->getMessage();
            return null;
        }
    }

    // Check of bestandsgrootte binnen ingestelde limiet valt
    public function checkBestandsgrootte($grootte, $maxToegestaan) {
        return $grootte <= $maxToegestaan;
    }




    public function getOngekeurdeDocs() {
    try {
        $stmt = $this->pdo->query("SELECT * FROM documenten WHERE status_toevoeging = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

public function updateStatus($docId, $status) {
    try {
        $stmt = $this->pdo->prepare("UPDATE documenten SET status_toevoeging = :status WHERE document_id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $docId
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

}
