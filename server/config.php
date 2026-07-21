<?php
/**
 * ============================================================
 * FICHIER DE CONNEXION A LA BASE DE DONNEES
 * ============================================================
 * Ce fichier centralise la connexion MySQL grace a PDO.
 * On l'inclut (require) dans tous les autres fichiers PHP
 * qui ont besoin de communiquer avec la base de donnees.
 *
 * PDO = PHP Data Objects : une facon standard et securisee
 * de se connecter a une base de donnees en PHP (protege
 * automatiquement contre les injections SQL si on utilise
 * des requetes preparees, ce qu'on fait plus loin).
 * ============================================================
 */

// --- Parametres de connexion a adapter selon ton environnement ---

// server/config.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autorise la communication avec le dossier src

    $hote       = "localhost";       // adresse du serveur MySQL (souvent localhost en local)
    $nom_base   = "site_freelance";  // nom de la base de donnees (voir database.sql)
    $utilisateur = "freelance_user";           // utilisateur MySQL (par defaut "root" avec WAMP/XAMPP/MAMP)
    $mot_de_passe = "Freelance@123";              // mot de passe MySQL (souvent vide en local avec XAMPP)

    try {
        // Creation de la connexion PDO vers MySQL en utf8mb4 (pour gerer accents, emojis, etc.)
        $connexion = new PDO(
            "mysql:host=$hote;dbname=$nom_base;charset=utf8mb4",
            $utilisateur,
            $mot_de_passe
        );

        // On demande a PDO de nous avertir avec des exceptions en cas d'erreur SQL
        // (plus facile a debugger que les erreurs silencieuses)
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $erreur) {
        // Si la connexion echoue, on arrete tout et on renvoie une erreur claire en JSON
        header('Content-Type: application/json');
        http_response_code(500); // 500 = erreur serveur
        echo json_encode([
            "succes" => false,
            "message" => "Erreur de connexion a la base de donnees : " . $erreur->getMessage()
        ]);
        exit; // on arrete l'execution du script ici
    }

    // A partir d'ici, la variable $connexion est disponible dans tout
    // fichier qui fait un require("config.php")
    ?>
