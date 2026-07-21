<?php
session_start();
require 'connexion.php';

// Protection : redirige vers la page de connexion si non connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}
?>

<?php
// Récupération des services
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);

// Récupération des messages
$messages = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord administrateur</title>
</head>
<body>
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['admin_nom']) ?></h1>
    <a href="logout_admin.php">Se déconnecter</a>

    <h2>Gestion des services</h2>
    <a href="ajouter_service.php">+ Ajouter un service</a>
    <table border="1">
        <tr>
            <th>Nom</th><th>Description</th><th>Prix</th><th>Actions</th>
        </tr>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?= htmlspecialchars($service['nom']) ?></td>
                <td><?= htmlspecialchars($service['description']) ?></td>
                <td><?= htmlspecialchars($service['prix']) ?> €</td>
                <td>
                    <a href="modifier_service.php?id=<?= $service['id'] ?>">Modifier</a>
                    <a href="supprimer_service.php?id=<?= $service['id'] ?>" onclick="return confirm('Supprimer ce service ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Messages reçus</h2>
    <table border="1">
        <tr>
            <th>Nom</th><th>Email</th><th>Sujet</th><th>Message</th><th>Date</th>
        </tr>
        <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= htmlspecialchars($msg['nom']) ?></td>
                <td><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= htmlspecialchars($msg['sujet']) ?></td>
                <td><?= htmlspecialchars($msg['message']) ?></td>
                <td><?= htmlspecialchars($msg['date_envoi']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

