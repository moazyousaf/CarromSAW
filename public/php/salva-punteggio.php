<?php
session_start(); // Risveglia la sessione per identificare l'utente [cite: 185]
require 'carromDB.php'; // Connessione al database [cite: 251]

// Controllo di autorizzazione: solo chi è loggato può salvare punti [cite: 206, 217]
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit();
}

// Leggiamo il corpo della richiesta POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['punteggio'])) {
    $punti = (int)$data['punteggio'];
    $id_utente = $_SESSION['user_id']; // Recuperato dal server, non dal client! [cite: 192, 194]

    try {
        // Inserimento nel database (Accountability) [cite: 23, 24]
        $stmt = $pdo->prepare("INSERT INTO punteggi (id_utente, punteggio) VALUES (?, ?)");
        $stmt->execute([$id_utente, $punti]);
        
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>