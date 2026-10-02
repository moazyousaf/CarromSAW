<?php
// Avviamo la sessione per capire se l'utente è loggato
// Questo permette di mantenere lo stato dell'utente tra le pagine
session_start();
?>
<!-- Questa è la pagina principale (homepage) del sito GameSAW, un gioco di Carrom online -->
<!-- Include navigazione, sezione hero, informazioni sul gioco e contatti -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameSAW - Carrom Online</title>
    <style>
        /* CSS Specifico per la Homepage */
        /* Stili per il layout responsive e colori del tema */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; /* Rimuove margini di default */
            padding: 0; /* Rimuove padding di default */
            color: black;
            line-height: 1.6; /* Migliora la leggibilità del testo */
            
        }

        /* Navigazione */
        nav {
            background-color: #F0D17499;
            color: white;
            padding: 1rem 2rem; /* Spaziatura interna */
            display: flex;
            justify-content: space-between; /* Spazio tra logo e link automaticamente distribuiti */
            align-items: center; /* Allinea verticalmente */
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #9E3B3B; /* Colore del logo */
        }

        /* Navigazione*/
        .nav-links {
            list-style: none; /* Rimuove i pallini dalle liste */
            display: flex; /* Dispone i link in orizzontale */
            gap: 20px; /* Spaziatura tra i link */
        }

        .nav-links a { /* Stile per i link di navigazione */
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #9E3B3B;
        }

        .btn-highlight { /* Stile speciale per i bottoni evidenziati */
            background-color: #D25353;
            padding: 8px 15px; /* Spaziatura interna */
            border-radius: 5px;
        }

        .btn-highlight:hover {
            background-color: #9E3B3B;
            color: white !important; /* Forza il colore bianco al passaggio del mouse */
        }

        /* Hero Section (Copertina) */
        header.hero {
            background: linear-gradient(#F0D17499, #00000099);
            height: 90vh; /* Altezza della viewport */
            display: flex; 
            flex-direction: column; /* Dispone gli elementi in verticale */
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        header.hero h1 {
            font-size: 3.5rem;
            margin-bottom: 5px;/* Spaziatura sotto il titolo */
        }

        header.hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin-bottom: 30px;
        }

       
        .cta-button { /* Stile per il pulsante call-to-action */
            background-color: #D25353;
            color: white;
            padding: 15px 30px;
            text-decoration: none;/* Rimuove la sottolineatura perchè il bottone è un link */
            font-size: 1.2rem;
            border-radius: 5px;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .cta-button:hover {
            transform: scale(1.05);
            background-color: #9E3B3B;
        }

        /* Sezioni Info */
        section {
            padding: 70px 20px; /* Spaziatura interna delle sezioni */
            max-width: 1000px; /* Larghezza massima per grandi schermi */
            margin: 0 auto; /* Centra le sezioni orizzontalmente*/
            text-align: center;
        }

        .features { /* Contenitore per le caratteristiche (box) del gioco */
            display: flex;
            justify-content: space-around; /* Spazio uniforme tra i box */
            flex-wrap: wrap; /* Permette di andare a capo su schermi piccoli */
            gap: 20px; /* Spaziatura tra i box */
        }

        .feature-box { /* Stile per ogni box delle caratteristiche */
            flex: 1; /* Occupa spazio uguale */
            min-width: 250px; /* Larghezza minima per i box */
            padding: 20px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 { color: #9E3B3B; margin-bottom: 20px; }

        footer {
            background-color: #222;
            color: #aaa;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">GameSAW</div> <!-- Logo del sito -->
        <ul class="nav-links"> <!-- menù di navigazione con i link -->
            <li><a href="#chi-siamo">Chi Siamo</a></li>
            <li><a href="#contatti">Contatti</a></li>

            <?php if(isset($_SESSION['user_id'])): ?> <!-- se l'utente è loggato mostra un determinato menù -->
                <!-- Menu per utenti loggati: dashboard, profilo, gioca, logout -->
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profilo.php">Profilo</a></li>
                <li><a href="game.php" class="btn-highlight">GIOCA ORA</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <!-- Menu per utenti non loggati: accedi, registrati -->
                <li><a href="login.php">Accedi</a></li>
                <li><a href="register.php" class="btn-highlight">Registrati</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="hero">
        <!-- Sezione hero con immagine di sfondo e call-to-action -->
        <h1>Benvenuto su GameSAW</h1>
        <p>Un'esperienza di Carrom online. Sfida il gioco e scala la classifica</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Se loggato, pulsante per tornare al gioco -->
            <a href="game.php" class="cta-button">Torna a Giocare</a>
        <?php else: ?>
            <!-- Se non loggato, pulsante per registrarsi -->
            <a href="register.php" class="cta-button">Inizia a Giocare Gratuitamente</a>
        <?php endif; ?>
    </header>

    <section id="chi-siamo">
        <!-- Sezione informativa su cosa è GameSAW -->
        <h2>Cos'è GameSAW?</h2>
        <div class="features">
            <!-- Box delle caratteristiche del gioco -->
            <div class="feature-box">
                <h3>🎮 Gameplay Realistico</h3>
                <p>Un'esperienza di gioco realistica</p>
            </div>
            <div class="feature-box">
                <h3>🏆 Classifiche</h3>
                <p>Salva i tuoi punteggi e confrontati con gli altri giocatori nella Top 10.</p>
            </div>
            <div class="feature-box">
                <h3>👤 Profilo Utente</h3>
                <p>Personalizza il tuo profilo e imposta il tuo livello</p>
            </div>
        </div>
    </section>

    <section id="contatti" style="background-color: #f9f9f9;">
        <!-- Sezione contatti -->
        <h2>Contatti</h2>
        <p>Email: <strong>info@gamesaw.it</strong></p>
        <p>Sede: Università degli Studi di Genova, Corso di Sviluppo di Applicazioni Web</p>
        <br>
    </section>

    <footer>
        <!-- Footer con copyright dinamico -->
        <p>&copy; <?php echo date("Y"); ?> GameSAW Project. Tutti i diritti riservati.</p>
    </footer>

</body>
</html>