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

    function replaceWordCaseInsensitive($string, $search, $replace) {
        // Utiliser une expression régulière pour rechercher le mot de manière insensible à la casse
        $pattern = '/\b' . preg_quote($search, '/') . '\b/i';
        return preg_replace($pattern, $replace, $string);
    }

    // Vérifie que l'utilisateur connectée à le role admin
    if ((!isset($_SESSION['role']) && !isset($_SESSION['user_id'])) || $_SESSION['role'] == "admin") {
        header("Location: page-erreur-403.html");
        exit();
    }

    
    // base de données
    require_once ('connect-bd.php');

    $user_id = (int)$_SESSION['user_id'];

    // récupérer les offres
    $sql = "SELECT commande.id as commande_id, commande.prix as commande_prix, CONCAT('[', GROUP_CONCAT( JSON_OBJECT( 'date', offres.date, 'formule', offres.formule, 'prix', offres.prix ) ), ']') as offres_json FROM commande LEFT JOIN commandes_has_offres ON commandes_has_offres.commande_id = commande.id LEFT JOIN offres ON offres.id = commandes_has_offres.offre_id WHERE commande.utilisateur_id = :utilisateur_id GROUP BY commande.id, commande.prix;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('utilisateur_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>

    <style>
        .order-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: left;
        }
        .order-item h5 {
            margin-bottom: 10px;
        }
        .order-item p {
            margin: 5px 0;
        }
        .order-item .btn {
            margin-top: 10px;
        }

        .order-item ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }
    </style>

    <!--style css-->
    <link rel="stylesheet" href="CSS/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>


<body>
    <?php include_once './header.php' ?>
        

    <main class='main'>
        <section class="container">
            <?php if (count($result) > 0) : ?>
                <h2 class="text-center mb-4">Liste des Commandes</h2>
                <div class="row">
                    <?php foreach ($result as $rk => $r): ?>
                        <div class="">
                            <div class="order-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Commande #<?= $r['commande_id'] ?></h5>
                                        <p><strong>Montant total:</strong> <?= (((int)$r['commande_prix']) / 100) ?> €</p>
                                    </div>
                                    <div>
                                    <small>e-ticket</small>
                                    <a href="generate-qrcode.php?id-commande=<?= $r['commande_id'] ?>" class="p-3 bg-light border rounded d-inline-block">
                                        <i class="bi bi-qr-code"></i>
                                    </a>
                                    </div>
                                </div>
                                <p><strong>Produits:</strong></p>
                                <ul>
                                    <?php
                                        $offers = json_decode($r['offres_json'], true);
                                        foreach ($offers as $o):
                                    ?>
                                        <li>
                                            <ul class="row border-top align-items-center py-3">
                                                <li class="col-md-3">
                                                    <a href="/offre.php">
                                                        Formule <?= $o['formule'] ?? "Supprimé..." ?>
                                                    </a>
                                                </li>
                                                <li class="col-md-5 small">
                                                    <?= $o['date'] ? relative_date($o['date']) : null ?>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div>
                    Aucune commande n'à été trouvé...
                </div>
            <?php endif;?>

    </section>
    </main>

    <script>
    // Fonction pour formater une date en format personnalisé (ex. : "June 29TH 2021")
    function formatDate(dateStr) {
        const months = [
            "JANVIER", "FEVRIER", "MARS", "AVRIL", "MAI", "JUIN", "JUILLET", "AOUT", "SEPTEMBRE", "OCTOBRE", "NOVEMBRE", "DECEMBRE"
        ];
        const date = new Date(dateStr);
        const month = months[date.getMonth()];
        const day = date.getDate();
        const year = date.getFullYear();
        return `${month} ${day}TH ${year}`;
    }

    // Fonction pour activer la modification
    function editOffer(type) {
        const dateElem = document.getElementById(`date-solo-${type}`);
        const nom = document.getElementById(`nom-solo-${type}`);
        const prix = document.getElementById(`prix-solo-${type}`);

        // Convertir la date actuelle en un format utilisable par <input type="date">
        const dateText = dateElem.innerText.trim();
        const dateParts = dateText.split(' '); // Ex: ["JUNE", "29TH", "2021"]
        console.log(dateParts);
        const year = dateParts[3];
        const month = new Date(Date.parse(dateParts[2] +" 1, 2012")).getMonth() + 1; // Convertir le mois en nombre
        const day = dateParts[1].replace('TH', ''); // Enlever "TH" du jour

        const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.padStart(2, '0')}`;

        // Remplacer l'affichage de la date par un input type="date"
        dateElem.innerHTML = `<input type="date" value="${formattedDate}" name="date" id="edit-date-${type}" />`;
        nom.innerHTML = `<input type="text" value="${nom.innerText}" name="name" id="edit-nom-${type}" />`;
        prix.innerHTML = `<input type="text" value="${prix.innerText.replace('€', '')}" name="price" id="edit-prix-${type}" />`;

        // Changer le bouton pour sauvegarder
        const button = document.querySelector(`button[onclick="editOffer(${type})"]`);
        button.innerText = "Sauvegarder";
        button.removeAttribute("onclick");

        setTimeout (() => {
            button.removeAttribute("type");
        }, 1000)
    }

    // Fonction pour sauvegarder les modifications
    function saveOffer(type) {
        const dateInput = document.getElementById(`edit-date-${type}`).value;
        const nomInput = document.getElementById(`edit-nom-${type}`).value;
        const prixInput = document.getElementById(`edit-prix-${type}`).value;

        // Sauvegarder les nouvelles valeurs
        document.getElementById(`date-${type}`).innerText = formatDate(dateInput);
        document.getElementById(`nom-${type}`).innerText = nomInput;
        document.getElementById(`prix-${type}`).innerText = prixInput + '€';

        // Changer le bouton pour modifier
        const button = document.querySelector(`button[onclick="saveOffer('${type}')"]`);
        button.innerText = "Modifier l'offre";
        button.setAttribute("onclick", `editOffer('${type}')`);
    }

    // Fonction pour supprimer une offre
    function deleteOffer(type) {
        const idModify = document.querySelector("#id-" + type)
        const form = document.querySelector("#form-offer-" + type)
        idModify.setAttribute("name", "id-delete")
        form.submit()
    }
    </script>


    <?php include_once './footer.php' ?>
        
</body>
</html>