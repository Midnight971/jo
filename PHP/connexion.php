<!--gestion de la connection-->

<?php
session_start();
?>

<?php include_once './connect-bd.php' ?>


<?php

// Variable contenant les erreurs
$errors;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Préparer la requête pour vérifier les informations de connexion
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if ($user && password_verify($password, $user['mdp'])) {
        // Connexion réussie
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];

        if (isset($_GET['redirect'])) {
            header('Location: ' . $_GET['redirect']);
        } else {
            if ($user['role'] == "admin") {
                header('Location: offre-admin.php');
            } else {
                header('Location: offre-client.php');
            }
        }
        exit();
    } else {
        // Connexion échouée
        $errors = "Email ou mot de passe incorrect.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>


    <?php include_once './header.php' ?>


    <!-- formulaire de connexion -->

    <main class='main'>
    <div class="container">
        <div class="login-container">
            <h2>Connexion</h2>
            <form action="#" method="post">
                <div>
                    <?= $errors ?? '' ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <a href="page_destination.html">
                <button type="submit">Se connecter</button>

                <div class="liens-auth mt-3">
                    <a href="formulaire-inscription.php">Inscription</a>
                </div>

            </form>
        </div>
    </div>
    </main>

<?php include_once './footer.php' ?>
</body>
</html>