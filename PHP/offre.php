
<?php

    session_start();

    function relative_date($mysqlDate) {
        // Convertir la date MySQL en un objet DateTime
        $date = new DateTime($mysqlDate);

        // Définir le format de la date
        $formattedDate = $date->format('l j F Y');

        // Convertir les noms de jours et de mois en français
        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $daysFr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $monthsFr = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

        $formattedDate = str_replace($days, $daysFr, $formattedDate);
        $formattedDate = str_replace($months, $monthsFr, $formattedDate);

        return $formattedDate;
    }
?>

<?php
    include 'connect-bd.php';

    // récupérer les offres
    $sql = "SELECT * FROM offres";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['cart'])) {
        if (isset($_SESSION['cart'])) {
            if (!in_array((int)$_POST['cart'], $_SESSION['cart'])) {
                $_SESSION['cart'][] = (int) $_POST['cart'];
            }
        } else {
            $_SESSION['cart'][] = (int) $_POST['cart'];
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Offres</title>


        <!--style css-->
        <link rel="stylesheet" href="CSS/styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>


    <body>

        <?php include_once './header.php' ?>
        

        <main class='main'>
            <h2 class="display-3 text-white">Tickets en vente</h2>
            <section class="offers">
                <?php if (count($result) > 0) : ?>
                    <?php foreach ($result as $rk => $r): ?>
                        <form action="" method="POST" class="ticket created-by-anniedotexe" id="form-offer-<?= ($rk + 1) ?>">
                            <input type="hidden" name="cart" value="<?= $r['id'] ?>" id="id-<?= ($rk + 1) ?>" />
                            <div class="left">
                                <div class="image">
                                    <p class="admit-one">
                                        <span>ADMIT ONE</span>
                                        <span>ADMIT ONE</span>
                                        <span>ADMIT ONE</span>
                                    </p>
                                </div>
                                <div class="ticket-info">
                                    <div class="date">
                                        <div class="june-29" id="date-solo-<?= ($rk + 1) ?>">
                                            <?= relative_date($r['date']); ?>
                                        </div> <!-- Date affichée -->
                                    </div>
                                    <div class="show-name">
                                        <h1 id="nom-solo-<?= ($rk + 1) ?>">Formule <?= $r['formule']; ?></h1>
                                        <h1 id="prix-solo-<?= ($rk + 1) ?>"><?= ($r['prix'] / 100); ?> €</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <p class="admit-one">
                                    <span>ADMIT ONE</span>
                                    <span>ADMIT ONE</span>
                                    <span>ADMIT ONE</span>
                                </p>
                                <div class="right-info-container">
                                    <div class="time">
                                        <p>8:00 PM <span>TO</span> 11:00 PM</p>
                                        <p>DOORS <span>@</span> 7:00 PM</p>
                                    </div>
                                    <p class="ticket-number">#20030220</p>
                                </div>
                            </div>
                            <!--bouton modifier et supprimer-->
                            <div class='modifier'>
                                <button type="submit">Panier</button>
                            </div>
                        </form>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>
                        Aucune n'offres n'à été trouvé...
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <?php include_once './footer.php' ?>
    </body>
</html>