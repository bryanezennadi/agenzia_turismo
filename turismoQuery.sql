CREATE DATABASE Società_Turismo;
USE Società_Turismo;

CREATE TABLE visite (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titolo` VARCHAR(255) NOT NULL,
    `durata_media` TIME NOT NULL,
    `luogo` VARCHAR(255) NOT NULL
);

CREATE TABLE eventi (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `prezzo` DECIMAL(10, 2) NOT NULL,
    `minimo_partecipanti` INT NOT NULL,
    `massimo_partecipanti` INT NOT NULL,
    `ora_inizio` DATETIME NOT NULL,
    `id_visita` INT,
    FOREIGN KEY (`id_visita`) REFERENCES `visite`(`id`)
);

CREATE table guida (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cognome` VARCHAR(255) NOT NULL,
    `nome` VARCHAR(255) NOT NULL,
    `data_nascita` DATE NOT NULL,
    `titolo_di_studio` VARCHAR(255) NOT NULL
);

CREATE TABLE competenze_linguistiche (
    `id_guida` INT,
    `lingua` VARCHAR(255) NOT NULL,
    `livello` VARCHAR(255) NOT NULL,  -- livello: normale, avanzato, madrelingua
    PRIMARY KEY (`id_guida`, `lingua`),
    FOREIGN KEY (`id_guida`) REFERENCES `guida`(`id`)
);
CREATE TABLE visitatori (
    `id_utente` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `nazionalita` VARCHAR(255) NOT NULL,
    `lingua_base` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,  -- email come identificativo unico
    `password` VARCHAR(255) NOT NULL,  -- password cifrata
    `recapito` VARCHAR(255) NOT NULL
);


CREATE TABLE login (
    `id_utente` INT,
    `ruolo` ENUM('visitatore', 'admin') NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,  -- La password crittografata
    PRIMARY KEY (`id_utente`),
    FOREIGN KEY (`id_utente`) REFERENCES `visitatori`(`id_utente`)
);


INSERT INTO guida (`cognome`, `nome`, `data_nascita`, `titolo_di_studio`)
VALUES 
('Rossi', 'Marco', '1985-04-15', 'Laurea in Lettere'),
('Bianchi', 'Sara', '1990-08-22', 'Laurea in Storia dell’Arte'),
('Verdi', 'Luca', '1982-12-05', 'Diploma di Maturità Scientifica');
INSERT INTO `competenze_linguistiche` (`id_guida`, `lingua`, `livello`)
VALUES 
-- Per la guida con id 1 (Marco Rossi)
(1, 'Inglese', 'Madrelingua'),
(1, 'Francese', 'Avanzato'),

-- Per la guida con id 2 (Sara Bianchi)
(2, 'Inglese', 'Avanzato'),
(2, 'Tedesco', 'Normale'),

-- Per la guida con id 3 (Luca Verdi)
(3, 'Spagnolo', 'Madrelingua'),
(3, 'Inglese', 'Normale');

select * from società_turismo.guida;