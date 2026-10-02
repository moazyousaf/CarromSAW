<?php
session_start(); // Inizia o recupera la sessione [cite: 185, 187]

// Controllo di autorizzazione: se la chiave 'user_id' non esiste, l'utente non è loggato [cite: 211, 322]
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Reindirizza al login [cite: 213, 324]
    exit(); // Blocca l'esecuzione della pagina [cite: 214, 325]
}
?>
<!-- Questa è la pagina del gioco Carrom -->
<!-- Include canvas per il gioco, punteggio, istruzioni e script JavaScript -->
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrom JS - Area Riservata</title>
    <link rel="stylesheet" href="../styles.css" />
  </head>
  <body>
    <!-- Navigazione con nome utente e link -->
    <nav style="background: #222; padding: 10px; text-align: right;">
        <span style="color: white; margin-right: 15px;">Giocatore: <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
        <a href="dashboard.php" style="color: #4CAF50; margin-right: 10px;">Dashboard</a>
        <a href="logout.php" style="color: #ff4444;">Logout</a>
    </nav>

    <h1>Carrom Board</h1>

    <!-- Contenitore del gioco con canvas e UI -->
    <div id="game-container">
      <canvas id="board" width="500" height="500"></canvas>

      <!-- Interfaccia utente con punteggio e istruzioni -->
      <div id="ui">
        <div id="current-score" style="font-size: 1.5rem; font-weight: bold; color: #4CAF50; margin-bottom: 10px;">
          Punti: 0
        </div>

        <p>
          1. Clicca sulla pedina <strong>GIALLA</strong>.<br />
          2. Trascina indietro per caricare la potenza.<br />
          3. Rilascia per tirare!
        </p>

        <button onclick="resetGame()">Nuova Partita</button>
      </div>
    </div>

    <script src="../script.js"></script>
  </body>
</html>