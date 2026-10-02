<?php
session_start(); // Avviamo la sessione (utile per il menu o tasto 'indietro')
require 'carromDB.php'; // Connessione al database

// Query per ottenere la TOP 10
// JOIN: Uniamo la tabella 'punteggi' con 'utenti' per avere i nomi
// ORDER BY: Ordinare dal più alto al più basso (DESC)
// LIMIT 10: Prendiamo solo i primi 10
$sql = "SELECT utenti.nome, utenti.cognome, punteggi.punteggio, punteggi.data_gioco 
        FROM punteggi 
        JOIN utenti ON punteggi.id_utente = utenti.id 
        ORDER BY punteggi.punteggio DESC 
        LIMIT 10";

// Esecuzione query con PDO
try {
    $stmt = $pdo->query($sql);
    $classifica = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nel recupero della classifica: " . $e->getMessage());
}
?>
<!-- Questa è la pagina della classifica dei migliori giocatori di Carrom -->
<!-- Mostra la top 10 dei punteggi con nomi, punteggi e date -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Top 10 - GameSAW</title>
    <style>
        /* CSS per la pagina classifica */
        /* Stili per layout centrato, tabella e bottoni */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 { color: #4CAF50; margin-bottom: 20px; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        
        /* Evidenzia il primo posto */
        tr:nth-child(1) td {
            font-weight: bold;
            color: #d4af37; /* Oro */
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover { background: #1976D2; }
    </style>
</head>
<body>

    <!-- Contenitore principale con titolo e tabella classifica -->
    <div class="container">
        <h1>🏆 Top 10 Giocatori</h1>
        <p>I migliori maestri di Carrom della scuola.</p>

        <table>
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Giocatore</th>
                    <th>Punteggio</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($classifica) > 0): ?>
                    <?php
                    $pos = 1; // Contatore per la posizione
                    foreach ($classifica as $riga): // Ciclo per ogni riga della classifica
                    ?>
                        <tr>
                            <td>#<?php echo $pos++; ?></td>
                            <td>
                                <?php echo htmlspecialchars($riga['nome'] . " " . $riga['cognome']); ?>
                            </td>
                            <td><strong><?php echo $riga['punteggio']; ?></strong></td>
                            <td style="font-size: 0.85em; color: #666;">
                                <?php echo date("d/m/Y H:i", strtotime($riga['data_gioco'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Nessuna partita giocata ancora!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <!-- Link per tornare alla dashboard e giocare ancora -->
        <a href="dashboard.php" class="btn">⬅ Torna alla Dashboard</a>
        <a href="game.php" class="btn" style="background-color: #4CAF50;">🎮 Gioca Ancora</a>
    </div>

</body>
</html>