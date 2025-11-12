-- CREATE Database 
CREATE DATABASE IF NOT EXISTS gachenti_db;

-- USE database
USE gachenti_db;

-- CREATE TABLE user_types
CREATE TABLE user_types (
    id_user_type INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(16)
);

-- CREATE TABLE users
CREATE TABLE users (
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
CREATE TABLE card_types (
    id_card_type INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(16),
    abbrev VARCHAR(4),
    description TEXT,
    color CHAR(6)
);

-- CREATE TABLE card_rarities
CREATE TABLE card_rarities (
    id_card_rarity INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    rarity VARCHAR(16),
    abbrev VARCHAR(4),
    description TEXT,
    probability INT
);

-- CREATE TABLE card_templates
CREATE TABLE card_templates (
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
CREATE TABLE cards (
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
CREATE TABLE user_cards (
    id_user_card INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_user INT UNSIGNED NOT NULL,
    id_card INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_card) REFERENCES cards(id_card)
);

-- CREATE TABLE logs
CREATE TABLE logs (
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
