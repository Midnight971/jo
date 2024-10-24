

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!--style CSS-->
    <link rel="stylesheet" href="CSS/styles.css">

    <!---connexion a la base de donnée-->
    <?php include_once './connect-bd.php' ?>




    <?php
// register.php

// Contient les erreurs du formulaire
$errors = [];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
    }

    // Politique de sécurité du mot de passe
    // Regex - Expression régulière qui vérifie que le mot de passe comporte :
    // - Des chiffres et des lettres
    // - Minimum 8 caractères
    $pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
    if (!preg_match($pattern, $password)) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères alphanumérique !";
    }

    // Vérifié que l'utilisateur n'est pas enregistrée
    $stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE email = :email");
    $stmt->bindParam('email', $email);
    $stmt->execute();
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($user) > 0) $errors['email'] = "Cet email est déjà enregistré";

    if (count($errors) == 0) {
        // Hacher le mot de passe pour la sécurité de la base de donnée
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Définir le role de l'utilisateur
        $role = 'client';

        // Générer une clé client
        $cle_client = uniqid();

        // Préparer la requête pour insérer les données dans la base de données
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mdp, cle_client, role) VALUES (:nom, :prenom, :email, :mdp, :cle_client, :role)");
        $stmt->bindParam('nom', $name);
        $stmt->bindParam('prenom', $prenom);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('mdp', $hashed_password);
        $stmt->bindParam('role', $role);
        $stmt->bindParam('cle_client', $cle_client);
        $stmt->execute();

        header('Location: inscription-valide.php');
        exit();
    }
}
?>

</head>
    <body>
    <?php include_once './header.php' ?>


        <!--formulaire d'inscription-->
        <main class='main'>
                <div class="container">

                <div class="form-container">
                    <h2>Inscription</h2>

                    <form action="#" method="post">

                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" name="name" value="<?= $name ?? '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Prénom</label>
                            <input type="text" id="name" name="prenom" value="<?= $prenom ?? '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= $email ?? '' ?>" required>
                            <div class="small p-2">
                                <?= $errors['email'] ?? null ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" value="<?= $password ?? '' ?>" required>
                            <div class="small p-2">
                                <?= $errors['password'] ?? null ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Confirmer le mot de passe</label>
                            <input type="password" id="confirm-password" value="<?= $confirm_password ?? '' ?>" name="confirm-password" required>
                            <div class="small p-2">
                                <?= $errors['confirm_password'] ?? null ?>
                            </div>
                        </div>

                        <button type="submit">S'inscrire</button>
                    </form>
                </div>
            </div>

</main>
<?php include_once './footer.php' ?>
    

</body>
    </html>
