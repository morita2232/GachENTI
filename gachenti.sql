-- CREATE Database 
CREATE DATABASE IF NOT EXISTS gachenti_db;
USE gachenti_db;

-- CREATE TABLE user_types
CREATE TABLE IF NOT EXISTS user_types (
    id_user_type INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(16)
);

-- CREATE TABLE users
CREATE TABLE IF NOT EXISTS users (
    id_user INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(24),
    surname VARCHAR(24),
    username VARCHAR(16),
    email VARCHAR(32),
    password CHAR(32),
    birthdate DATE,
    funds DECIMAL(8,2),
    registered DATETIME,
    status INT,
    id_user_type INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_user_type) REFERENCES user_types(id_user_type)
);

-- CREATE TABLE card_types
CREATE TABLE IF NOT EXISTS card_types (
    id_card_type INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(16),
    abbrev VARCHAR(4),
    description TEXT,
    color CHAR(6)
);

-- CREATE TABLE card_rarities
CREATE TABLE IF NOT EXISTS card_rarities (
    id_card_rarity INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    rarity VARCHAR(16),
    abbrev VARCHAR(4),
    description TEXT,
    probability INT
);

-- CREATE TABLE card_templates
CREATE TABLE IF NOT EXISTS card_templates (
    id_card_template INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    card VARCHAR(32),
    initial_price DECIMAL(6,2),
    description TEXT NULL,
    image VARCHAR(32) NULL,
    id_card_type INT UNSIGNED NOT NULL,
    id_card_rarity INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_card_type) REFERENCES card_types(id_card_type),
    FOREIGN KEY (id_card_rarity) REFERENCES card_rarities(id_card_rarity)
);

-- CREATE TABLE cards
CREATE TABLE IF NOT EXISTS cards (
    id_card INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(6,2),
    discount INT,
    on_sale BOOL,
    state INT,
    creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_card_template INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_card_template) REFERENCES card_templates(id_card_template)
);

-- CREATE TABLE user_cards
CREATE TABLE IF NOT EXISTS user_cards (
    id_user_card INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_user INT UNSIGNED NOT NULL,
    id_card INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_card) REFERENCES cards(id_card)
);

-- CREATE TABLE logs
CREATE TABLE IF NOT EXISTS logs (
    id_log INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(6,2),
    discount INT,
    transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    state INT,
    id_user_seller INT UNSIGNED NOT NULL,
    id_user_buyer INT UNSIGNED NOT NULL,
    id_card INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_user_seller) REFERENCES users(id_user),
    FOREIGN KEY (id_user_buyer) REFERENCES users(id_user),
    FOREIGN KEY (id_card) REFERENCES cards(id_card)
);

-- INSERTS básicos
-- user_types: 1 => root, 2 => user
INSERT INTO user_types (id_user_type, type) VALUES
(1, 'root'),
(2, 'user');

-- Usuario root (password 'root' en md5)
-- md5('root') = 63a9f0ea7bb98050796b649e85481845
INSERT INTO users (id_user, name, surname, username, email, password, birthdate, funds, registered, status, id_user_type)
VALUES (1, 'Super', 'Admin', 'root', 'root@example.com', '63a9f0ea7bb98050796b649e85481845', '1990-01-01', 1000.00, NOW(), 1, 1);

-- Tipos de carta
INSERT INTO card_types (id_card_type, type, abbrev, description, color) VALUES
(1, 'Profesor', 'PROF', 'Carta de profesor', 'FFCC00'),
(2, 'Alumno', 'ALUM', 'Carta de alumno', '00CCFF'),
(3, 'Staff', 'STAF', 'Carta del staff', 'CC00FF');

-- Rarezas
INSERT INTO card_rarities (id_card_rarity, rarity, abbrev, description, probability) VALUES
(1, 'Común', 'C', 'Carta común', 7000),
(2, 'Rara', 'R', 'Carta rara', 2500),
(3, 'Épica', 'E', 'Carta épica', 400),
(4, 'Legendaria', 'L', 'Carta legendaria', 100);

-- Algunas plantillas de cartas
INSERT INTO card_templates (id_card_template, card, initial_price, description, image, id_card_type, id_card_rarity) VALUES
(1, 'Profesor Alfa', 5.00, 'Profesor con mucha experiencia', NULL, 1, 2),
(2, 'Profesor Beta', 7.50, 'Profesor exigente y carismático', NULL, 1, 3),
(3, 'Alumno Gamma', 1.00, 'Alumno novato', NULL, 2, 1),
(4, 'Jefe de Departamento', 15.00, 'Miembro del staff con influencia', NULL, 3, 4);

-- Algunas cartas concretas a partir de las plantillas
INSERT INTO cards (id_card, price, discount, on_sale, state, id_card_template) VALUES
(1, 5.00, 0, TRUE, 1, 1),
(2, 7.50, 10, TRUE, 1, 2),
(3, 1.00, 0, FALSE, 1, 3),
(4, 15.00, 20, TRUE, 1, 4);

-- fin

