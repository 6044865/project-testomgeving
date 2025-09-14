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
        echo "<pre style='background:#f2f2f2;border:1px solid #ccc;padding:10px;'>";
        echo "DEBUG: documentToevoegen uitgevoerd voor wonder_id: $wonderId\n";
        echo "Pad: $bestandspad, type: $type, grootte: $grootte\n";
        echo "Resultaat execute: "; var_dump($result);
        echo "</pre>";

        return $result;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij document toevoegen: " . $e->getMessage() . "</p>";
        return false;
    }
}


    // Document verwijderen
    public function documentVerwijderen($docId) {
        try {
            $query = "DELETE FROM " . $this->tableNaam . " WHERE document_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $docId]);
        } catch (PDOException $e) {
            echo "❌ Fout bij document verwijderen: " . $e->getMessage();
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
}
