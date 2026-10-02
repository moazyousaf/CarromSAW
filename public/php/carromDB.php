<?php
// Questo file gestisce la connessione al database MySQL per il progetto Carrom
// Utilizza PDO per una connessione sicura e orientata agli oggetti

// Configurazione del database
$host = 'localhost';  // Host del server MySQL (localhost per XAMPP)
$db   = 'carrom';     // Nome del database
$user = 'root';       // Username per l'accesso al database
$pass = '';           // Password per l'accesso (vuota in XAMPP di default)

try {
    // Creazione della connessione PDO al database
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Impostazione della modalità di errore per lanciare eccezioni in caso di problemi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // In caso di errore nella connessione, termina lo script e mostra il messaggio di errore
    die("Errore connessione database: " . $e->getMessage());
}
?>