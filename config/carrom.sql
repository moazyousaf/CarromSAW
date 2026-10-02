-- Tabella Utenti
CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- La password sarà cifrata (hash)
    citta VARCHAR(50),
    descrizione TEXT,
    livello VARCHAR(20) DEFAULT 'Beginner', -- Beginner, Intermediate, Expert
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabella Punteggi (per la Classifica)
CREATE TABLE punteggi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT,
    punteggio INT,
    data_gioco TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utente) REFERENCES utenti(id)
);