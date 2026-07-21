<?php
session_start();
require 'connexion.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<?php
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);

    if (empty($nom) || empty($prix)) {
        $erreur = "Le nom et le prix sont obligatoires.";
    } else {
        $sql = "INSERT INTO services (nom, description, prix) VALUES (:nom, :description, :prix)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix
        ]);

        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un service</title>
</head>
<body>
    <h1>Ajouter un service</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Description :</label>
        <textarea name="description"></textarea>

        <label>Prix (€) :</label>
        <input type="number" step="0.01" name="prix" required>

        <button type="submit">Ajouter</button>
    </form>

    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
