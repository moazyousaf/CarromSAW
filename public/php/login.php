<?php
session_start(); // Avvia la sessione per gestire il login

// Include il file di connessione al database
require 'carromDB.php';

// Verifica se il form è stato inviato via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera email e password dal form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepara e esegue la query per trovare l'utente con l'email
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifica la password hashata
    if ($user && password_verify($password, $user['password'])) {

        // --- NUOVO CONTROLLO BAN ---
        // Controlla se l'utente è bannato
        if ($user['bannato'] == 1) {
            die("ACCESSO NEGATO: Il tuo account è stato sospeso dagli amministratori.");
        }

        // Login OK: Salviamo i dati in sessione
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['livello'] = $user['livello'];
        $_SESSION['ruolo'] = $user['ruolo'];
        
        // Reindirizzamento intelligente basato sul ruolo
        if ($user['ruolo'] === 'admin') {
            header("Location: admin-panel.php"); // Vai all'area admin
        } else {
            header("Location: dashboard.php"); // Vai al gioco
        }
        exit;
    } else {
        echo "Credenziali errate!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi - GameSAW</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            /* Sfondo simile alla Hero della index */
            background: linear-gradient(rgba(240, 209, 116, 0.6),rgba(0,0,0,0.6)), url('https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Carrom_board_with_men.jpg/1200px-Carrom_board_with_men.jpg');

            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #FFEAD3;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo-login {
            font-size: 2rem;
            font-weight: bold;
            color: #9E3B3B;
            margin-bottom: 20px;
            display: block;
            text-decoration: none;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #9E3B3B;
            font-size: 0.9rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #D25353;
            border-radius: 8px;
            box-sizing: border-box; /* Importante per il padding */
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #c24101ff;
            outline: none;
        }

        .btn-login {
            background-color: #D25353;
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #9E3B3B;
            transform: translateY(-2px);
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid #ffcdd2;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 0.9rem;
            color: #777;
        }

        .footer-links a {
            color: #9E3B3B;
            text-decoration: none;
            font-weight: bold;
        }

        .back-home {
            display: inline-block;
            margin-top: 15px;
            color: #aaa;
            text-decoration: none;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo che linka alla homepage -->
        <a href="index.php" class="logo-login">GameSAW</a>

        <!-- Form di login -->
        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="email">Indirizzo Email</label>
                <input type="email" id="email" name="email" placeholder="esempio@mail.com" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Accedi al Gioco</button>
        </form>

        <!-- Link per registrarsi se non si ha account -->
        <div class="footer-links">
            Non hai un account? <a href="register.php">Registrati ora</a>
        </div>

        <!-- Link per tornare alla homepage -->
        <a href="index.php" class="back-home">← Torna alla homepage</a>
    </div>

</body>
</html>