<?php
session_start();
require 'connexion.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'id passé dans l'URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Service introuvable.");
}

$erreur = '';

// Si le formulaire est soumis, on met à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);

    if (empty($nom) || empty($prix)) {
        $erreur = "Le nom et le prix sont obligatoires.";
    } else {
        $sql = "UPDATE services SET nom = :nom, description = :description, prix = :prix WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix,
            'id' => $id
        ]);

        header('Location: dashboard.php');
        exit;
    }
}

// Récupération des données actuelles du service pour pré-remplir le formulaire
$sql = "SELECT * FROM services WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Service introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un service</title>
</head>
<body>
    <h1>Modifier le service</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($service['nom']) ?>" required>

        <label>Description :</label>
        <textarea name="description"><?= htmlspecialchars($service['description']) ?></textarea>

        <label>Prix (€) :</label>
        <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($service['prix']) ?>" required>

        <button type="submit">Enregistrer</button>
    </form>

    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>