CREATE DATABASE IF NOT EXISTS gachenti;
USE gachenti;

CREATE TABLE user_types (
	id_user_type INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	type VARCHAR(16) NOT NULL,
	PRIMARY KEY (id_user_type)
);

CREATE TABLE users (
	id_user INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	name VARCHAR(24) NOT NULL,
	surname VARCHAR(24) NOT NULL,
	username VARCHAR(16) NOT NULL UNIQUE,
	email VARCHAR(32) NOT NULL UNIQUE,
	password CHAR(32) NOT NULL,
	birthdate DATE NOT NULL,
	funds DECIMAL(8,2) NOT NULL,
	registered DATETIME NOT NULL,
	status INT,
	id_user_type INT UNSIGNED NOT NULL,
	PRIMARY KEY (id_user),
	INDEX (id_user_type),
	FOREIGN KEY (id_user_type) REFERENCES user_types(id_user_type)
		ON UPDATE CASCADE
		ON DELETE SET NULL
);

CREATE TABLE types (
	id_type INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	type VARCHAR(16) NOT NULL,
	description TEXT NOT NULL,
	abbreviation VARCHAR(8) NOT NULL,
	color HEX(6) NOT NULL,
	PIMRY KEY (id_type)
);

CREATE TABLE rarities (
	id_rarity INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	rarity VARCHAR(16) NOT NULL,
	description TEXT NOT NULL,
	abbreviation VARCHAR(8) NOT NULL,
	color HEX(6) NOT NULL,
	probability INT NOT NULL,
	PRIMARY KEY (id_rarity)
);

CREATE TABLE card_templates (
	id_card_template INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,
	starting_price DECIMAL(6,2) NOT NULL,
	description TEXT NOT NULL,
	image VARCHAR(32) NOT NULL,
	id_type INT UNSIGNED NOT NULL,
	id_rarity INT UNSIGNED NOT NULL,
	PRIMARY KEY (id_card_template),
	INDEX (id_type),
	INDEX (id_rarity),
	FOREIGN KEY (id_type) REFERENCES types(id_type)
		ON UPDATE CASCADE
		ON DELETE SET NULL,
	FOREIGN KEY (id_rarity) REFRENCES rarities(id_rarity)
		ON UPDATE CASCADE
		ON DELETE SET NULL
);

CREATE TABLE cards (
	id_card INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	starting_price DECIMAL(6,2) NOT NULL,
	description TEXT NOT NULL,
	image VARCHAR(32) NOT NULL,
	id_type INT UNISGNED NOT NULL,
	id_rarity INT UNSIGNED NOT NULL,
	id_card_template INT UNSIGNED NOT NULL,
	PRIMARY KEY (id_card),
	INDEX (id_type),
	INDEX (id_rarity),
	INDEX (id_card_template),
	FOREIGN KEY (id_type) REFERENCES types(id_type)
		ON UPDATE CASCADE
		ON DELETE SET NULL,
	FOREIGN KEY (id_rarity) REFERENCES rarities(id_rarity)
		ON UPDATE CASCADE
		ON DELETE SET NULL,
	FOREIGN KEY (id_card_template) REFERENCES card_templates(id_card_template)
		ON UPDATE CASCADE
		ON DELETE SET NULL	
);

CREATE TABLE cards_users (
	id_cards_users INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	user_id INT UNSIGNED NOT NULL UNIQUE,
	card_id INT UNSIGNED NOT NULL UNIQUE,
	PRIMARY KEY (id_cards_users),
	INDEX (user_id),
	INDEX (card_id),
	FOREIGN KEY (user_id) REFERENCES users(id_user)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	FOREIGN KEY (card_id) REFERENCES cards(id_card)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE logs (
	id_log INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
	selling_price DECIMAL(6,2) NOT NULL,
	discount INT,
	sale_date DATETIME NOT NULL,
	state INT NOT NULL,
	selling_user_id INT UNSIGNED NOT NULL,
	buying_user_id INT UNSIGNED NOT NULL,
	sold_card_id INT UNSIGNED NOT NULL,
	PRIMARY KEY (id_log),
	INDEX (selling_user_id),
	INDEX (buying_user_id),
	INDEX (sold_card_id),
	FOREIGN KEY (selling_user_id) REFERENCES users(id_user)
		ON UPDATE CASCADE
		ON DELETE SET NULL,
	FOREIGN KEY (buying_user_id) REFERENCES users(id_user)
		ON UPDATE CASCADE
		ON DELETE SET NULL,
	FOREIGN KEY (sold_card_id) REFERENCES cards(id_card)
		ON UPDATE CASCADE
		ON DELETE SET NULL
);

Holi
