<?php
require_once "classDatabase.php";

class Foto extends Database {

    private $tableNaam = "fotos";




//     // Foto toevoegen
    public function fotoToevoegen($wonderId, $bestandspad, $goedgekeurd = 0) {
    try {
        $query = "INSERT INTO " . $this->tableNaam . " (wonder_id, bestandspad, goedgekeurd) 
                  VALUES (:wonder_id, :bestandspad, :goedgekeurd)";
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            ':wonder_id' => $wonderId,
            ':bestandspad' => $bestandspad,
            ':goedgekeurd' => $goedgekeurd
        ]);

        // DEBUG
        echo "<pre style='background:#f2f2f2;border:1px solid #ccc;padding:10px;'>";
        echo "DEBUG: fotoToevoegen uitgevoerd voor wonder_id: $wonderId\n";
        echo "Pad: $bestandspad, goedgekeurd: $goedgekeurd\n";
        echo "Resultaat execute: "; var_dump($result);
        echo "</pre>";

        return $result;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij foto toevoegen: " . $e->getMessage() . "</p>";
        return false;
    }
}



// ✅ Foto toevoegen door beheerder (automatisch goedgekeurd)
    public function fotoToevoegenBeheerder($wonderId, $bestandspad, $toegevoegd_door) {
        try {
            $query = "INSERT INTO " . $this->tableNaam . " 
                      (wonder_id, bestandspad, goedgekeurd, toegevoegd_door) 
                      VALUES (:wonder_id, :bestandspad, 1, :toegevoegd_door)";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':wonder_id' => $wonderId,
                ':bestandspad' => $bestandspad,
                ':toegevoegd_door' => $toegevoegd_door
            ]);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ Fout bij foto toevoegen door beheerder: " . $e->getMessage() . "</p>";
            return false;
        }
    }

    // Foto verwijderen
public function fotoVerwijderen($fotoId) {
    try {
        // Haal bestandspad op
        $query = "SELECT bestandspad FROM " . $this->tableNaam . " WHERE foto_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $fotoId]);
        $foto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($foto) {
            $bestandspad = $foto['bestandspad'];

            // Eerst fysiek bestand verwijderen
            if (file_exists($bestandspad)) {
                unlink($bestandspad);
            }

            // Dan record verwijderen uit DB
            $query = "DELETE FROM " . $this->tableNaam . " WHERE foto_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $fotoId]);

            return true;
        }
        return false;

    } catch (PDOException $e) {
        echo "Fout bij foto verwijderen: " . $e->getMessage();
        return false;
    }
}


    // Foto goedkeuren
    public function fotoGoedkeuren($fotoId) {
        try {
            $query = "UPDATE " . $this->tableNaam . " SET goedgekeurd = 1 WHERE foto_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $fotoId]);
        } catch (PDOException $e) {
            echo "Fout bij goedkeuren foto: " . $e->getMessage();
        }
    }

    // Alle foto's per wonder ophalen
    public function getFotosPerWonder($wonderId) {
        $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $wonderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
