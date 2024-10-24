<?php
    session_start();

    // inclusion BDD
    require_once './connect-bd.php';

    // contient les produits du panier
    $result = [];

    // cout commande total
    $total_price = 0;

    // Supprimer un produit du panier
    if (isset($_GET['id-delete'])) {
        $cart_arr = $_SESSION['cart'];
        foreach ($cart_arr as $k_cart => $cart) {
            if ($cart == (int)$_GET['id-delete']) {
                unset($cart_arr[$k_cart]);
            }
        }

        $_SESSION['cart'] = array_values($cart_arr);
    }


    // Récupérer des infos du produit du panier
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));

        $sql = "SELECT * FROM offres WHERE id IN (" . $placeholders . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($_SESSION['cart']);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panier d'achat</title>
        <link rel="stylesheet" href="styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>

        <?php include_once './header.php' ?>

        <main class='main'>
            <h2 class="display-3 text-white">Mon Panier</h2>
            <section class="panier">
                <?php if (count($result) > 0): ?>
                    <?php foreach ($result as $r): ?>
                        <?php $total_price += (int) $r['prix'] ?>
                        <form method="GET" class="cart-item">
                            <input type="hidden" name="id-delete" value="<?= $r['id']; ?>" />
                            <img src="https://i.ibb.co/jvyqXyT/logo.png" alt="Produit 1" />
                            <div class="cart-item-details">
                                <h2>Formule <?= $r['formule']; ?></h2>
                                <p>Prix : <?= $r['prix'] / 100 ?> €</p>
                            </div>
                            <button class="remove-btn">Supprimer</button>
                        </form>
                    <?php endforeach; ?>

                    <section class="cart-summary">
                        <h2>Résumé de la commande</h2>
                        <p>Total : <?= ($total_price / 100); ?> €</p>
                        <a href="formule-achat.php" class="checkout-btn">Passer à la caisse</a>
                    </section>
                <?php else: ?>
                    <div class="display-4 p-3 text-center">
                        Panier vide...
                    </div>
                <?php endif; ?>
            </section>


        </main>

        <?php include_once './footer.php' ?>
        
    </body>
</html>