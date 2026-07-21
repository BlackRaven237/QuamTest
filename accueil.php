<?php
/**
 * ============================================================
 * FONCTIONNALITE 1 : PAGE D'ACCUEIL
 * ============================================================
 * Selon le cahier des charges, cette fonctionnalite doit fournir :
 * - Nom du freelance
 * - Son domaine d'activite
 * - Ses competences
 * - Ses coordonnees
 *
 * IMPORTANT : le cahier des charges ne prevoit PAS de table
 * dediee a ces informations dans la base de donnees (seules les
 * tables "administrateur", "services" et "messages" existent).
 * On considere donc ces informations comme des donnees FIXES
 * du site (elles ne changent pas souvent), stockees directement
 * dans le code PHP sous forme de tableau. C'est une solution
 * simple, adaptee a un projet qui doit "rester simple".
 *
 * Ce fichier fonctionne comme une petite API : quand on l'appelle
 * en GET, il renvoie les informations du freelance au format JSON.
 * Le frontend (HTML/JS) pourra ensuite recuperer ce JSON avec
 * "fetch()" et l'afficher sur la page d'accueil.
 * ============================================================
 */

// On indique que la reponse renvoyee sera du JSON
header('Content-Type: application/json; charset=utf-8');

// On autorise uniquement la methode GET pour cette page
// (car on ne fait que lire/consulter des informations, pas les modifier)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // 405 = methode non autorisee
    echo json_encode([
        "succes" => false,
        "message" => "Seule la methode GET est autorisee ici."
    ]);
    exit;
}

// --- Informations du freelance (a personnaliser selon le projet) ---
$infos_freelance = [
    "nom"            => "Nom Prenom du Freelance",
    "domaine"        => "Genie Logiciel - Developpement Web & Mobile",
    "competences"    => [
        "Developpement Web (HTML, CSS, JavaScript, PHP)",
        "Developpement Mobile",
        "Developpement de logiciels",
        "Maintenance et support technique"
    ],
    "coordonnees"    => [
        "email"     => "contact@freelance-exemple.com",
        "telephone" => "+237 6XX XXX XXX",
        "adresse"   => "Yaounde, Cameroun"
    ]
];

// On renvoie la reponse au format JSON avec un statut de succes
http_response_code(200); // 200 = OK
echo json_encode([
    "succes"  => true,
    "donnees" => $infos_freelance
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// JSON_UNESCAPED_UNICODE : pour que les accents s'affichent correctement
// JSON_PRETTY_PRINT : pour que le JSON soit lisible pendant les tests
?>
