CREATE DATABASE IF NOT EXISTS Società_Turismo;
USE Società_Turismo;

-- Tabella delle visite turistiche
CREATE TABLE visite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    durata_media TIME NOT NULL,
    luogo VARCHAR(255) NOT NULL
);

-- Tabella degli eventi collegati a visite
CREATE TABLE eventi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prezzo DECIMAL(10, 2) NOT NULL,
    minimo_partecipanti INT NOT NULL,
    massimo_partecipanti INT NOT NULL,
    ora_inizio DATETIME NOT NULL,
    id_visita INT,
    FOREIGN KEY (id_visita) REFERENCES visite(id)
);

-- Tabella delle guide turistiche
CREATE TABLE guida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    data_nascita DATE NOT NULL,
    titolo_di_studio VARCHAR(255) NOT NULL
);

-- Tabella delle competenze linguistiche delle guide
CREATE TABLE competenze_linguistiche (
    id_guida INT,
    lingua VARCHAR(255) NOT NULL,
    livello VARCHAR(255) NOT NULL, -- normale, avanzato, madrelingua
    PRIMARY KEY (id_guida, lingua),
    FOREIGN KEY (id_guida) REFERENCES guida(id)
);

-- Tabella login (principale, da cui derivano i ruoli)
CREATE TABLE login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    ruolo ENUM('visitatore', 'admin') NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

drop table visitatori;
-- Tabella visitatori collegata a login
CREATE TABLE visitatori (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Aggiungi un ID univoco
    id_credenziali INT NOT NULL, -- Riferimento alla tabella login
    nome VARCHAR(255) NOT NULL,
    lingua_base VARCHAR(255) NOT NULL,
    recapito VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_credenziali) REFERENCES login(id)
);



-- Inserimento dati iniziali per le guide
INSERT INTO guida (cognome, nome, data_nascita, titolo_di_studio)
VALUES 
('Rossi', 'Marco', '1985-04-15', 'Laurea in Lettere'),
('Bianchi', 'Sara', '1990-08-22', 'Laurea in Storia dell’Arte'),
('Verdi', 'Luca', '1982-12-05', 'Diploma di Maturità Scientifica');

-- Inserimento competenze linguistiche
INSERT INTO competenze_linguistiche (id_guida, lingua, livello)
VALUES 
(1, 'Inglese', 'Madrelingua'),
(1, 'Francese', 'Avanzato'),
(2, 'Inglese', 'Avanzato'),
(2, 'Tedesco', 'Normale'),
(3, 'Spagnolo', 'Madrelingua'),
(3, 'Inglese', 'Normale');
CREATE TABLE IF NOT EXISTS prenotazioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_evento INT NOT NULL,
    id_visitatore INT NOT NULL,
    data_prenotazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_pagamento DATETIME NULL,
    stato_pagamento ENUM('in attesa', 'completato', 'annullato') DEFAULT 'in attesa',
    FOREIGN KEY (id_evento) REFERENCES eventi(id),
    FOREIGN KEY (id_visitatore) REFERENCES login(id)
);
CREATE TABLE biglietto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_visitatore INT NOT NULL,  -- Usa id_visitatore come riferimento
    data_stampa DATE NOT NULL DEFAULT CURRENT_DATE,
    evento_id INT NOT NULL,
    FOREIGN KEY (id_visitatore) REFERENCES visitatori(id),  -- Foreign key riferito all'ID visitatore
    FOREIGN KEY (evento_id) REFERENCES eventi(id)
);



-- Verifica contenuto tabelle (facoltativo)
SELECT * FROM guida;
SELECT * FROM login;
SELECT * FROM visitatori;
