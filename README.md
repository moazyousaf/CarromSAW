# GameSAW - Carrom Online 🎯

Benvenuto in **GameSAW**, una trasposizione web interattiva del celebre gioco da tavolo Carrom! Questo progetto unisce una simulazione fisica custom in HTML5 Canvas con un solido backend PHP per la gestione degli utenti, dei punteggi e delle classifiche.

## 🌟 Funzionalità

- **Fisica Custom in Canvas:** Collisioni, attrito, rimbalzi e sistema di tiro a "fionda".
- **Sistema di Punteggio e Combo:** Calcolo dei punti in base alle pedine imbucate e moltiplicatori combo per tiri consecutivi.
- **Autenticazione Sicura:** Registrazione e Login con password criptate (Bcrypt).
- **Dashboard Utente:** Gestione del profilo personalizzato e visualizzazione del livello.
- **Classifica Globale (Top 10):** Sfida gli altri giocatori per raggiungere la vetta della leaderboard.
- **Pannello di Amministrazione:** Strumento esclusivo per gli admin per bannare o sbloccare i giocatori.

## 🛠️ Tecnologie Utilizzate

- **Frontend:** HTML5, CSS3, Vanilla JavaScript (Canvas API)
- **Backend:** PHP 7.4+ (con sessioni)
- **Database:** MySQL (interfacciato tramite PDO)

## 🚀 Guida all'Installazione

Per eseguire questo progetto in locale, avrai bisogno di un ambiente server come **XAMPP**, **MAMP** o **LAMP**.

### 1. Preparazione dell'ambiente
1. Clona o scarica questo repository.
2. Sposta l'intera cartella del progetto all'interno della directory root del tuo server locale (es. `htdocs` per XAMPP).

### 2. Configurazione del Database
1. Apri **phpMyAdmin** (solitamente `http://localhost/phpmyadmin`).
2. Crea un nuovo database vuoto e chiamalo `carrom`.
3. Importa il file `config/carrom.sql` all'interno del database per creare le tabelle di base.
4. **IMPORTANTE:** Per abilitare il sistema di admin e i ban, esegui queste due query SQL aggiuntive nella scheda "SQL" di phpMyAdmin:
   ```sql
   ALTER TABLE utenti ADD COLUMN ruolo VARCHAR(20) DEFAULT 'user';
   ALTER TABLE utenti ADD COLUMN bannato TINYINT(1) DEFAULT 0;
   ```

### 3. Avvio del Gioco
1. Assicurati che i servizi Apache e MySQL siano avviati nel tuo pannello di controllo (es. XAMPP).
2. Apri il browser e naviga verso l'entry point dell'applicazione (sostituisci `nome-cartella` con il nome della cartella del progetto):
   ```text
   http://localhost/nome-cartella/public/php/index.php
   ```

## 👑 Come diventare Amministratore

Per motivi di sicurezza, non è possibile registrarsi direttamente come amministratore. Per testare il pannello admin:
1. Registra un normale account dal sito.
2. Vai su phpMyAdmin, apri la tabella `utenti` e trova il tuo account.
3. Modifica il campo `ruolo` da `user` a `admin`.
4. Effettua nuovamente il login dal sito per accedere all'area riservata.

## 🎮 Come si gioca

1. **Posizionamento:** Lo "Striker" (la pedina gialla) parte dalla base.
2. **Carica il tiro:** Clicca (o tocca) lo Striker, tieni premuto e trascina il mouse verso il basso o nella direzione opposta a cui vuoi tirare (come una fionda).
3. **Spara:** Rilascia il click per colpire le pedine e mandarle in buca.
4. **Punteggio:** Le pedine chiare valgono 20 punti, quelle scure 10. La pedina rossa (Regina) vale 50 punti.
5. **Attenzione ai tiri:** Hai 15 tiri gratuiti. Oltre questa soglia, riceverai una penalità sul punteggio finale! Se imbuchi lo striker per sbaglio, perderai 20 punti base.

---
*Progetto realizzato per il Corso di Sviluppo di Applicazioni Web (SAW) - Università degli Studi di Genova.*