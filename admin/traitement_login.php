<?php
session_start();
require 'connexion.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM administrateur WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>


