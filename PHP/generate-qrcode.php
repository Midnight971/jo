<?php
    
    session_start();

    // Inclusion des dépandances PHP
    require  "../vendor/autoload.php";

    // Définition du namespace de la classe que l'on souhaite utilisée
    use Endroid\QrCode\Color\Color;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel;
    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\Label\Label;
    use Endroid\QrCode\Logo\Logo;
    use Endroid\QrCode\RoundBlockSizeMode;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\Writer\ValidationException;

    function replaceWordCaseInsensitive($string, $search, $replace) {
        // Utiliser une expression régulière pour rechercher le mot de manière insensible à la casse
        $pattern = '/\b' . preg_quote($search, '/') . '\b/i';
        return preg_replace($pattern, $replace, $string);
    }

    // Vérifie que l'utilisateur est connecté
    if ((!isset($_SESSION['role']) && !isset($_SESSION['user_id'])) && $_SESSION['role'] !== "client") {
        header("Location: page-erreur-403.html");
        exit();
    }

    // Vérifie que l'on récupère l'id de la commande pour générer le qrcode
    if (!isset($_GET['id-commande'])) {
        header("Location: page-erreur-404.html");
        exit();
    }
    
    // base de données
    require_once ('connect-bd.php');

    $user_id = (int)$_SESSION['user_id'];
    $id_commande = (int)$_GET['id-commande'];

    $result = [];

    // récupérer la clée de commande avec celle de l'utilisateur
    $sql = "SELECT 
        commande.prix as commande_prix,
        paiement.cle as cle_commande,
        utilisateur.cle_client as cle_client
        FROM commande
        LEFT JOIN paiement
        ON commande.id = paiement.commande_id
        LEFT JOIN utilisateur
        ON commande.utilisateur_id = utilisateur.id
        WHERE 
        commande.id = :commande_id
        AND 
	    commande.utilisateur_id = :utilisateur_id
        LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('utilisateur_id', $user_id);
    $stmt->bindParam('commande_id', $id_commande);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Si la requête SQL renvoi un tableau vide cela signifie qu'il n'y à une erreur
    // Renvoi une erreur 403
    if (count($result) == 0) {
        header("Location: page-erreur-403.html");
        exit();
    }

    // Chaine de caractère à encodé dans le qrcode
    $cle_commande = $result[0]['cle_commande'];
    $cle_client = $result[0]['cle_client'];

    // Concatenation
    $data = $cle_client . $cle_commande;

    $qrCode = new QrCode(
        data: $data,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::Low,
        size: 300,
        margin: 10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin,
        foregroundColor: new Color(0, 0, 0),
        backgroundColor: new Color(255, 255, 255)
    );

    // Create generic label
    $label = new Label(
        text: 'Label',
        textColor: new Color(255, 0, 0)
    );

    $writer = new PngWriter();
    $qr = $writer->write($qrCode);

    // Save the QR code as a PNG file
    header('Content-Type: ' . $qr->getMimeType());
    echo $qr->getString();

?>
