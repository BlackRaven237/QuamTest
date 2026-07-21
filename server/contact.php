<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

/**
 * ============================================================
 * FONCTIONNALITE 3 : FORMULAIRE DE CONTACT
 * ============================================================
 * Selon le cahier des charges, le formulaire de contact contient :
 * Nom, Email, Telephone, Sujet, Message
 *
 * Ce fichier recoit les donnees envoyees par le frontend (en POST),
 * les verifie (validation), puis les enregistre dans la table
 * "messages" de la base de donnees via une requete PREPAREE
 * (protection contre les injections SQL).
 * ============================================================
 */

// On indique que la reponse sera du JSON


// server/contact.php

// 1. On appelle le fichier de connexion créé juste au-dessus
require_once 'config.php'; 

// 2. On récupère et vérifie les données envoyées par le JavaScript
if (isset($_POST['Nom'], $_POST['Email'], $_POST['Message'])) {
    try {
        // La variable $pdo provient du fichier config.php inclus automatiquement
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, corps, date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$_POST['Nom'], $_POST['Email'], $_POST['Message']]);

        // On renvoie une réponse positive au JavaScript
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur de sauvegarde SQL : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données du formulaire manquantes ou incomplètes.']);
}


header('Content-Type: application/json; charset=utf-8');

// On inclut le fichier de connexion a la base de donnees
// (la variable $connexion devient disponible ici)
require("config.php");

// --- Etape 1 : on n'accepte que la methode POST ---
// (on envoie des donnees pour les enregistrer, donc POST et pas GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // 405 = methode non autorisee
    echo json_encode([
        "succes" => false,
        "message" => "Seule la methode POST est autorisee ici."
    ]);
    exit;
}

// --- Etape 2 : recuperation des donnees envoyees ---
// On accepte deux formats possibles pour faciliter les tests :
// 1) donnees envoyees en JSON (cas le plus courant avec fetch())
// 2) donnees envoyees en formulaire classique (x-www-form-urlencoded)
$corps_json = json_decode(file_get_contents("php://input"), true);

if (is_array($corps_json)) {
    // Cas 1 : les donnees arrivent en JSON
    $nom       = $corps_json['nom']       ?? '';
    $email     = $corps_json['email']     ?? '';
    $telephone = $corps_json['telephone'] ?? '';
    $sujet     = $corps_json['sujet']     ?? '';
    $message   = $corps_json['message']   ?? '';
} else {
    // Cas 2 : les donnees arrivent via un formulaire classique ($_POST)
    $nom       = $_POST['nom']       ?? '';
    $email     = $_POST['email']     ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $sujet     = $_POST['sujet']     ?? '';
    $message   = $_POST['message']   ?? '';
}

// --- Etape 3 : nettoyage des donnees (on enleve les espaces inutiles) ---
$nom       = trim($nom);
$email     = trim($email);
$telephone = trim($telephone);
$sujet     = trim($sujet);
$message   = trim($message);

// --- Etape 4 : validation des donnees ---
$erreurs = [];

if (empty($nom)) {
    $erreurs[] = "Le nom est obligatoire.";
}
if (empty($email)) {
    $erreurs[] = "L'email est obligatoire.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // filter_var verifie que le format de l'email est correct (ex: xxx@xxx.xxx)
    $erreurs[] = "Le format de l'email n'est pas valide.";
}
if (empty($sujet)) {
    $erreurs[] = "Le sujet est obligatoire.";
}
if (empty($message)) {
    $erreurs[] = "Le message est obligatoire.";
}
// Le telephone n'est pas obligatoire dans la table (peut etre NULL),
// donc pas de verification stricte dessus, juste un format simple si rempli
if (!empty($telephone) && !preg_match('/^[0-9+ ]{6,20}$/', $telephone)) {
    $erreurs[] = "Le format du telephone n'est pas valide.";
}

// Si on a trouve des erreurs, on arrete tout et on les renvoie
if (!empty($erreurs)) {
    http_response_code(400); // 400 = mauvaise requete (donnees invalides)
    echo json_encode([
        "succes"  => false,
        "message" => "Certaines donnees sont invalides.",
        "erreurs" => $erreurs
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// --- Etape 5 : insertion dans la base de donnees ---
try {
    // Requete preparee : les "?" seront remplaces par les vraies valeurs
    // de facon securisee (protection contre les injections SQL)
    $requete = $connexion->prepare(
        "INSERT INTO messages (nom, email, telephone, sujet, message)
         VALUES (?, ?, ?, ?, ?)"
    );

    // Si le champ telephone est vide, on enregistre NULL plutot qu'une chaine vide
    $telephone_a_enregistrer = empty($telephone) ? null : $telephone;

    // Execution de la requete avec les valeurs recues
    $requete->execute([$nom, $email, $telephone_a_enregistrer, $sujet, $message]);

    // On recupere l'identifiant du message qui vient d'etre cree
    $id_nouveau_message = $connexion->lastInsertId();

    // Reponse de succes
    http_response_code(201); // 201 = ressource creee avec succes
    echo json_encode([
        "succes"  => true,
        "message" => "Votre message a bien ete envoye.",
        "id"      => $id_nouveau_message
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $erreur) {
    // En cas d'erreur SQL (ex: table absente, colonne inconnue, etc.)
    http_response_code(500); // 500 = erreur serveur
    echo json_encode([
        "succes"  => false,
        "message" => "Erreur lors de l'enregistrement du message : " . $erreur->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
