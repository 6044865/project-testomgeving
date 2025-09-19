<?php
require_once "classDatabase.php";

class Foto extends Database {

    private $tableNaam = "fotos";




//     // Foto toevoegen onderzoeker
 public function fotoToevoegenOnderzoeker($wonderId, $bestandspad, $gebruikerId) {
    try {
        $stmt = $this->pdo->prepare("
            INSERT INTO fotos (wonder_id, bestandspad, goedgekeurd, toegevoegd_door)
            VALUES (:wonder_id, :bestandspad, 0, :toegevoegd_door)
        ");
        $stmt->execute([
            ':wonder_id' => $wonderId,
            ':bestandspad' => $bestandspad,
            ':toegevoegd_door' => $gebruikerId
        ]);

        return true;
    }catch (PDOException $e) {
    $msg = addslashes($e->getMessage()); // escape quotes voor JS
    echo "<script>alert('❌ Fout bij fotoToevoegenOnderzoeker: $msg');</script>";
    return false;
}

}

// onderzoeker kan eigen toegevoegde fotos zien zelfs het is nog niet goedgekeurd en het is nog onzichtbaar voor publiek
public function getFotosForOnderzoeker($wonderId, $gebruikerId) {
    $stmt = $this->pdo->prepare("
        SELECT * FROM fotos
        WHERE wonder_id = :wonder_id
        AND (goedgekeurd = 1 OR toegevoegd_door = :gebruiker_id)
    ");
    $stmt->execute([
        ':wonder_id' => $wonderId,
        ':gebruiker_id' => $gebruikerId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// ✅ Foto toevoegen door beheerder (automatisch goedgekeurd)
   // Beheerder voegt foto toe
  
public function fotoToevoegenBeheerder($wonderId, $bestandspad, $gebruikerId) {
    try {
        $stmt = $this->pdo->prepare("
            INSERT INTO fotos (wonder_id, bestandspad, goedgekeurd, toegevoegd_door)
            VALUES (:wonder_id, :bestandspad, 1, :toegevoegd_door)
        ");
        $stmt->execute([
            ':wonder_id' => $wonderId,
            ':bestandspad' => $bestandspad,
            ':toegevoegd_door' => $gebruikerId
        ]);

        return true; // ✅ altijd true bij succes
    } catch (PDOException $e) {
        $msg = addslashes($e->getMessage());
        echo "<script>alert('❌ Fout bij fotoToevoegenBeheerder: $msg');</script>";
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
    // public function getFotosPerWonder($wonderId) {
    //     $query = "SELECT * FROM " . $this->tableNaam . " WHERE wonder_id = :id";
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute([':id' => $wonderId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
        // Foto's ophalen bij een wereldwonder
    public function getFotosByWonder($wonderId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM fotos 
            WHERE wonder_id = :id AND goedgekeurd = 1
        ");
        $stmt->execute([':id' => $wonderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


// voor redacteur
public function getOngekeurdeFotos() {
    $stmt = $this->pdo->prepare("SELECT * FROM fotos WHERE goedgekeurd = 0 ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function updateStatus($fotoId, $status) {
    try {
        $stmt = $this->pdo->prepare("UPDATE fotos SET goedgekeurd = :status WHERE foto_id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $fotoId
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

    
}
