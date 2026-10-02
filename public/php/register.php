<?php
require 'carromDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if ($pass1 === $pass2) {
        // Cifriamo la password
        $passHash = password_hash($pass1, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $cognome, $email, $passHash]);
            header("Location: login.php"); // Vai al login dopo la registrazione
            exit;
        } catch (PDOException $e) {
            echo "Errore: Email già usata.";
        }
    } else {
        echo "Le password non coincidono!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati - GameSAW</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(240, 209, 116, 0.4),rgba(0,0,0,0.7)), 
                        url('https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Carrom_board_with_men.jpg/1200px-Carrom_board_with_men.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .register-card {
            background: #FFEAD3;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo-register {
            font-size: 2rem;
            font-weight: bold;
            color: #9E3B3B;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }

        h2 {
            color: #5D2A2A;
            margin-bottom: 25px;
            font-size: 1.2rem;
        }

        .input-row {
            display: flex;
            gap: 15px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
            flex: 1;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #9E3B3B;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #D25353;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 0.95rem;
            transition: all 0.3s;
            background-color: white;
        }

        .input-group input:focus {
            border-color: #c24101ff;
            box-shadow: 0 0 8px rgba(194, 65, 1, 0.2);
            outline: none;
        }

        .btn-register {
            background-color: #D25353;
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .btn-register:hover {
            background-color: #9E3B3B;
            transform: translateY(-2px);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid #f5c6cb;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 0.9rem;
            color: #5D2A2A;
        }

        .footer-links a {
            color: #9E3B3B;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <a href="index.php" class="logo-register">GameSAW</a>

        <form method="POST" action="register.php">
            <div class="input-row">
                <div class="input-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Mario" required>
                </div>
                <div class="input-group">
                    <label for="cognome">Cognome</label>
                    <input type="text" id="cognome" name="cognome" placeholder="Rossi" required>
                </div>
            </div>

            <div class="input-group">
                <label for="email">Indirizzo Email</label>
                <input type="email" id="email" name="email" placeholder="mario.rossi@email.it" required>
            </div>

            <div class="input-group">
                <label for="pass1">Password</label>
                <input type="password" id="pass1" name="pass1" placeholder="••••••••" required>
            </div>

            <div class="input-group">
                <label for="pass2">Conferma Password</label>
                <input type="password" id="pass2" name="pass2" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-register">Registrati</button>
        </form>

        <div class="footer-links">
            Hai già un account? <a href="login.php">Accedi qui</a>
        </div>
    </div>

</body>
</html>