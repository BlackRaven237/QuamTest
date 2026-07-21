<?php
require 'connexion.php';

$sql = "SELECT *FROM services";
$stmt = $pdo->query($sql);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE htlml>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Nos Services</title>
    </head>
    <body>
        <h1>Nos Services</h1>

        <?php if (count($services)===0):?>
            <p>Aucun service disponible pour le moment.</p>
        <?php else:?>
            <?php foreach($services as $services):?>
                <div class="service-card">
                    <h2> <?=htmlspecialchars($services['nom'])?> </h2>
                    <p> <?=htmlspecialchars($services['description'])?> <p>
                    <p><strong> <?=htmlspecialchars($services['prix'])?>€</strong><p>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        </body>
</html>