<?php
require_once "classDatabase.php";

class Gebruiker {
    private $pdo;
    private $tableNaam = 'gebruikers';

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    // Alle gebruikers ophalen
    public function getAlleGebruikers() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableNaam} ORDER BY gebruiker_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gebruiker ophalen op ID
    public function getGebruikerById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableNaam} WHERE gebruiker_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gebruiker toevoegen
    public function toevoegen($gebruikersnaam, $email, $wachtwoord, $rol) {
        $hashedWachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableNaam} (gebruikersnaam, email, wachtwoord, rol, aangemaakt_op)
            VALUES (:gebruikersnaam, :email, :wachtwoord, :rol, NOW())
        ");
        return $stmt->execute([
            'gebruikersnaam' => $gebruikersnaam,
            'email' => $email,
            'wachtwoord' => $hashedWachtwoord,
            'rol' => $rol
        ]);
    }

    // Gebruiker bijwerken
    public function bijwerken($id, $gebruikersnaam, $email, $rol) {
        $stmt = $this->pdo->prepare("
            UPDATE {$this->tableNaam}
            SET gebruikersnaam = :gebruikersnaam, email = :email, rol = :rol
            WHERE gebruiker_id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'gebruikersnaam' => $gebruikersnaam,
            'email' => $email,
            'rol' => $rol
        ]);
    }

    // Gebruiker verwijderen
    public function verwijderen($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tableNaam} WHERE gebruiker_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
