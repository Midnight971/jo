<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Validée</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>



<?php include_once './header.php' ?>

<main class='main'>
    <div class="success-message">
        <div class="message-content">
            <h1 class='inscri'>Inscription réussie !</h1>
            <p>Votre inscription a été validée avec succès. Vous pouvez vous connecter !</p>
            <a href="connexion.php" class="button">se connecter</a>
        </div>
        <div class="message-image">
            <img src="https://i.ibb.co/fGwKwmD/valid.png" alt="valide">
        </div>
    </div>
</main>
    <?php include_once './footer.php' ?>
</body>
</html>