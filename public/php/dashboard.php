<?php
session_start(); // 1. Risveglia la sessione tramite il cookie PHPSESSID

// 2. IL POSTO DI BLOCCO (Slide 38)
// Se non esiste l'ID dell'utente in sessione, significa che non ha fatto il login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Lo rispediamo al login
    exit(); // Blocchiamo il resto dello script per sicurezza
}

// Se siamo qui, l'utente è autorizzato!
$nomeUtente = $_SESSION['nome'];
$livelloUtente = $_SESSION['livello'];
?>
<!-- Questa è la dashboard dell'utente loggato -->
<!-- Mostra il benvenuto, livello e menu con link a gioco, profilo, classifica, home e logout -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GameSAW</title>
    <style>
        /* CSS per la dashboard */
        /* Stili per layout centrato con sfondo, contenitore e bottoni menu */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Carrom_board_with_men.jpg/1200px-Carrom_board_with_men.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh; /* Forza l'altezza dello schermo */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Impedisce lo scroll della pagina intera */
        }

        .container {
            background: #FFEAD3;
            padding: 40px; /* Ridotto da 40px */
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 90%;
            max-width: 500px; /* Più stretto */
            text-align: center;
            border: 3px solid #9E3B3B;
        }

        h1 {
            margin: 0 0 10px 0; /* Rimosso margine superiore */
            color: #9E3B3B;
            font-size: 1.8rem; /* Rimpicciolito */
        }

        .user-info {
            background: rgba(210, 83, 83, 0.05);
            padding: 10px; /* Più sottile */
            border-radius: 8px;
            margin-bottom: 20px; /* Ridotto spazio */
            border-left: 4px solid #D25353;
        }

        .level-badge {
            display: inline-block;
            background: #D25353;
            color: white;
            padding: 3px 12px;
            border-radius: 15px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px; /* Spazio tra bottoni ridotto */
        }

        .btn {
            padding: 12px; /* Ridotto da 15px */
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.2s ease;
            font-size: 0.95rem; /* Font più piccolo */
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-game {
            background: #9E3B3B;
            grid-column: span 2;
            font-size: 1.1rem;
            padding: 15px; /* Gioca ora rimane il più grande ma contenuto */
            border: 1px solid #b95828;
        }

        .btn-profile, .btn-rank { background: #D25353; }

        .btn-home { 
            background: #5D2A2A; 
            grid-column: span 2;
        }

        .btn-logout {
            background: none;
            color: #9E3B3B;
            border: 1.5px solid #9E3B3B;
            grid-column: span 2;
            margin-top: 5px;
            padding: 8px;
        }

        hr {
            border: 0;
            height: 1px;
            background: #D25353;
            margin: 10px 0;
            opacity: 0.2;
            grid-column: span 2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Ciao, <?= htmlspecialchars($nomeUtente); ?>!</h1>
        
        <!-- Informazioni utente con livello -->
        <div class="user-info">
            <div class="level-badge"><?= htmlspecialchars($livelloUtente); ?></div>
        </div>

        <!-- Menu con bottoni per navigare -->
        <div class="menu">
            <a href="game.php" class="btn btn-game">GIOCA ORA</a>
            
            <a href="profilo.php" class="btn btn-profile">Profilo</a>
            <a href="classifica.php" class="btn btn-rank">Classifica</a>
            
            <hr>
            
            <a href="index.php" class="btn btn-home">Home</a>
            <a href="logout.php" class="btn btn-logout">Esci</a>
        </div>
    </div>

</body>
</html>