-- ============================================================
-- SCRIPT DE CREATION DE LA BASE DE DONNEES
-- Projet : Site web freelance - Genie Logiciel
-- Partie concernee : Fonctionnalite 1 (page d'accueil) et
--                     Fonctionnalite 3 (formulaire de contact)
-- ============================================================
-- NOTE : les tables "administrateur" et "services" sont gerees
-- par tes coequipiers (fonctionnalites 2 et 4). Ici on cree
-- uniquement la base et la table "messages" dont tu as besoin.
-- Si la base existe deja (creee par un coequipier), tu peux
-- ignorer la ligne CREATE DATABASE et juste executer le reste.
-- ============================================================

-- 1. Creation de la base de donnees (si elle n'existe pas encore)
CREATE DATABASE IF NOT EXISTS site_freelance
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- 2. On selectionne la base pour travailler dedans
USE site_freelance;

-- 3. Creation de la table "messages" (fonctionnalite 3 : formulaire de contact)
-- Champs demandes par le cahier des charges :
-- id, nom, email, telephone, sujet, message, date_envoi
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- identifiant unique auto-incremente
    nom VARCHAR(100) NOT NULL,              -- nom du visiteur
    email VARCHAR(150) NOT NULL,            -- email du visiteur
    telephone VARCHAR(20) DEFAULT NULL,     -- telephone (optionnel)
    sujet VARCHAR(150) NOT NULL,            -- sujet du message
    message TEXT NOT NULL,                  -- contenu du message
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP  -- date/heure d'envoi automatique
) ENGINE=InnoDB;

-- 4. (Optionnel) Quelques donnees de test pour verifier que tout marche
-- Tu peux commenter ces lignes si tu ne veux pas de fausses donnees
INSERT INTO messages (nom, email, telephone, sujet, message)
VALUES
('Jean Dupont', 'jean.dupont@example.com', '699112233', 'Demande de devis', 'Bonjour, je voudrais un devis pour un site web.'),
('Marie Curie', 'marie.curie@example.com', NULL, 'Question technique', 'Faites-vous de la maintenance apres livraison ?');

-- 5. Verification rapide : afficher le contenu de la table
SELECT * FROM messages;
