<?php
session_start();
require 'carromDB.php';

// Controllo accesso (Posto di blocco)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

// Se ho inviato il modulo di modifica
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $citta = $_POST['citta'];
    $desc = $_POST['descrizione'];
    
    $stmt = $pdo->prepare("UPDATE utenti SET citta = ?, descrizione = ? WHERE id = ?");
    $stmt->execute([$citta, $desc, $id]);
    echo "Profilo aggiornato!";
}

// Recupero dati attuali per precompilare il form
$stmt = $pdo->prepare("SELECT * FROM utenti WHERE id = ?");
$stmt->execute([$id]);
$utente = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - GameSAW</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Carrom_board_with_men.jpg/1200px-Carrom_board_with_men.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .profile-card {
            background: #FFEAD3;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 90%;
            max-width: 400px;
            text-align: center;
            border: 3px solid #9E3B3B;
        }

        h1 {
            color: #9E3B3B;
            font-size: 1.4rem;
            margin: 0 0 5px 0;
        }

        .level-info {
            font-size: 0.85rem;
            color: #5D2A2A;
            margin-bottom: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        

        .input-group {
            margin-bottom: 12px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 4px;
            color: #9E3B3B;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .input-group input, .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #D25353;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .input-group textarea {
            height: 80px;
            resize: none;
        }

        input:focus, textarea:focus {
            border-color: #c24101ff;
            outline: none;
            box-shadow: 0 0 5px rgba(194, 65, 1, 0.2);
        }

        .btn-save {
            background-color: #D25353;
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 5px;
        }

        .btn-save:hover {
            background-color: #9E3B3B;
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #9E3B3B;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="profile-card">
        <h1>Profilo Utente</h1>
        <div class="level-info">Livello: <?= htmlspecialchars($utente['livello'] ?? 'Novizio') ?></div>

        

        <form method="POST">
            <div class="input-group">
                <label>Città</label>
                <input type="text" name="citta" value="<?= htmlspecialchars($utente['citta'] ?? '') ?>" placeholder="La tua città...">
            </div>

            <div class="input-group">
                <label>Bio (About Me)</label>
                <textarea name="descrizione" placeholder="Raccontaci qualcosa di te..."><?= htmlspecialchars($utente['descrizione'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-save">Salva Modifiche</button>
        </form>

        <a href="dashboard.php" class="back-link">← Torna alla Dashboard</a>
    </div>

</body>
</html>