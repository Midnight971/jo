<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Validée</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include_once './header.php' ?>s
    
<main class='main'>
    <div class="success-message">
        <div class="message-content">
            <h1 class='inscri'>Connexion réussie !</h1>
            <p>Votre connexion a été validée avec succès. Bienvenue sur votre compte !</p>
            <a href="mon-panier.php" class="button">Accéder à votre panier</a>
        </div>
        <div class="message-image">
            <img src="https://i.ibb.co/fGwKwmD/valid.png" alt="valide">
        </div>
    </div>
</main>

    <?php include_once './footer.php' ?>
</body>
</html>