-- 1. Créer et sélectionner la base
CREATE DATABASE IF NOT EXISTS site_actualite CHARACTER SET utf8mb4;
USE site_actualite;

-- 2. Table categories
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

-- 4. Table articles
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

-- Insertion des catégories
INSERT INTO categories (nom, description) VALUES
('Politique', 'Actualités politiques nationales et internationales'),
('Sport', 'Toute l''actualité sportive : football, basket, tennis...'),
('Technologie', 'Innovations, high-tech, intelligence artificielle'),
('Culture', 'Cinéma, musique, littérature, arts'),
('Économie', 'Finance, entreprises, marchés'),
('Santé', 'Médecine, bien-être, recherches médicales');

-- Insertion des articles (liés aux catégories et utilisateurs existants)
INSERT INTO articles (titre, description_courte, contenu, id_categorie, id_auteur) VALUES
('Nouvelle loi sur le climat', 'Le parlement adopte une loi historique pour l''environnement', 'Après des débats houleux, le parlement a adopté hier soir la loi sur le climat. Cette législation prévoit une réduction de 40% des émissions de CO2 d''ici 2030...', 1, 1),

('Le PSG remporte la ligue des champions', 'Victoire historique du club parisien en finale', 'Dans un match haletant, le PSG s''est imposé 3-2 face au Bayern Munich. Mbappé, auteur d''un doublé, a été élu homme du match...', 2, 2),

('iPhone 15 : les premières images fuient', 'Des photos du prochain smartphone Apple circulent sur la toile', 'À deux mois de la sortie officielle, des images du futur iPhone 15 ont été publiées sur les réseaux sociaux. Le design semble évoluer avec un nouveau module photo...', 3, 3);


-- Insertion des utilisateurs (mots de passe en clair pour la simulation)
INSERT INTO utilisateurs (nom, prenom, login, mot_de_passe, role) VALUES
('Diouf', 'Falilou', 'falilou.d', 'passer123', 'administrateur'),
('Dubois', 'Pierre', 'pierre.d', 'azerty456', 'editeur'),
('Bernard', 'Claire', 'claire.b', 'claire789', 'editeur');