<?php
session_start();
// 1. CONNESSIONE AL DATABASE
require 'carromDB.php'; 

/**
 * NOTA: Assicurati che in carromDB.php la variabile della 
 * connessione si chiami $conn. Se si chiama $pdo, sostituisci 
 * tutti i $conn qui sotto con $pdo.
 */

// 2. PROTEZIONE: Solo gli admin possono entrare
if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    die("Accesso non autorizzato. Devi essere un amministratore.");
}

// 3. LOGICA BAN/SBAN (Eseguita al click del bottone)
if (isset($_POST['toggle_ban_id'])) {
    $id_utente = $_POST['toggle_ban_id'];
    $stato_attuale = $_POST['stato_attuale'];
    
    // Invertiamo lo stato: se è 0 (attivo) diventa 1 (bannato), e viceversa
    $nuovo_stato = ($stato_attuale == 0) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("UPDATE utenti SET bannato = ? WHERE id = ?");
        $stmt->execute([$nuovo_stato, $id_utente]);
        
        // Ricarichiamo la pagina per vedere l'aggiornamento
        header("Location: admin-panel.php");
        exit;
    } catch (PDOException $e) {
        die("Errore durante l'aggiornamento: " . $e->getMessage());
    }
}

// 4. RECUPERO LISTA UTENTI (Escludendo gli admin)
try {
    $stmt = $pdo->query("SELECT id, nome, bannato FROM utenti WHERE ruolo != 'admin'");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>
<!-- Questa è la pagina del pannello amministratore per GameSAW -->
<!-- Permette agli amministratori di visualizzare la lista dei giocatori e gestire i ban/sban -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pannello Amministrazione</title>
    <style>
        /* CSS per il pannello amministratore */
        /* Stili per il layout, tabella e bottoni */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; background-color: #f4f7f6; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #34495e; color: white; }
        tr:hover { background-color: #f9f9f9; }
        
        /* Classi per lo stato */
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
        .bannato { background-color: #ffdce0; color: #af233a; }
        .attivo { background-color: #dcffe4; color: #1e7e34; }
        
        button { cursor: pointer; padding: 8px 15px; border: none; border-radius: 4px; transition: 0.3s; font-weight: bold; }
        .btn-ban { background-color: #e74c3c; color: white; }
        .btn-ban:hover { background-color: #c0392b; }
        .btn-sban { background-color: #27ae60; color: white; }
        .btn-sban:hover { background-color: #219150; }
        
        .logout-link { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header con titolo e logout -->
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1>Gestione Giocatori</h1>
            <p>Benvenuto, <b><?= htmlspecialchars($_SESSION['nome']) ?></b> (Amministratore)</p>
        </div>
        <a href="logout.php" class="logout-link">Disconnetti</a>
    </header>

    <!-- Tabella per visualizzare e gestire gli utenti -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome Utente</th>
                <th>Stato</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($utenti)): ?>
                <tr><td colspan="4" style="text-align:center;">Nessun giocatore registrato.</td></tr>
            <?php else: ?>
                <?php foreach ($utenti as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td>
                        <?php if ($u['bannato']): ?>
                            <span class="status-badge bannato">⛔ BANNATO</span>
                        <?php else: ?>
                            <span class="status-badge attivo">✅ ATTIVO</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="toggle_ban_id" value="<?= $u['id'] ?>">
                            <input type="hidden" name="stato_attuale" value="<?= $u['bannato'] ?>">
                            
                            <?php if ($u['bannato'] == 0): ?>
                                <button type="submit" class="btn-ban">Blocca Utente</button>
                            <?php else: ?>
                                <button type="submit" class="btn-sban">Sblocca Utente</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>