-- 1. Créer et sélectionner la base
CREATE DATABASE IF NOT EXISTS site_actualite CHARACTER SET utf8mb4;
USE site_actualite;
-- 2. Table categories (avant articles car articles la référence)
CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(100) NOT NULL UNIQUE,
description TEXT
);
-- 3. Table utilisateurs
CREATE TABLE utilisateurs (
id INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
prenom VARCHAR(100) NOT NULL,
login VARCHAR(100) NOT NULL UNIQUE,
mot_de_passe VARCHAR(255) NOT NULL,
role ENUM('editeur','administrateur') NOT NULL,
date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- 4. Table articles (dépend des deux tables précédentes)
CREATE TABLE articles (
id INT AUTO_INCREMENT PRIMARY KEY,
titre VARCHAR(255) NOT NULL,
description_courte VARCHAR(500) NOT NULL,
contenu TEXT NOT NULL,
date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
id_categorie INT NOT NULL,
id_auteur INT NOT NULL,
FOREIGN KEY (id_categorie) REFERENCES categories(id) ON DELETE RESTRICT,
FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id) ON DELETE RESTRICT
);
