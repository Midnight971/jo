
<?php


// Vérifier si l'utilisateur est connecté
if ($_SESSION['user_id']) {
    // L'utilisateur est connecté
} else {
    // L'utilisateur n'est pas connecté
    header("Location: offre.php"); // Rediriger vers la page de connexion
    exit;
}


?>


