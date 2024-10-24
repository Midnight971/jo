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
    if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
        header("Location: page-erreur-403.html");
        exit();
    }

    
    // base de données
    require_once ('connect-bd.php');

    // Récupération du formulaire
    if (count($_POST) > 0) {

        // modifier offre
        if (isset($_POST['id-modify'])) {

            $id = (int)$_POST['id-modify'];
            $date = $_POST['date'];
            $formule = trim(replaceWordCaseInsensitive($_POST['name'], "Formule", ""));
            $prix = ((int)$_POST['price']) * 100;
            $user_id = (int)$_SESSION['user_id'];

            $sql_update = "UPDATE offres SET formule = :formule, date = :date, utilisateur_id = :utilisateur_id, prix = :prix WHERE id = :id";

            if (
                $id &&
                $date &&
                $formule &&
                $prix &&
                $user_id
            ) {
                $stmt = $pdo->prepare($sql_update);
                $stmt->bindParam(":formule", $formule);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":utilisateur_id", $user_id);
                $stmt->bindParam(":prix", $prix);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
            }

        }

        // supprimer offre
        else if (isset($_POST['id-delete'])) {

            $id = (int)$_POST['id-delete'];

            $sql_delete= "DELETE FROM offres WHERE id = :id";

            if (
                $id
            ) {
                $stmt = $pdo->prepare($sql_delete);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
            }
        }

        // ajouter offre
        else {

            $date = $_POST['date'];
            $formule = trim(replaceWordCaseInsensitive($_POST['name'], "Formule", ""));
            $prix = ((int)$_POST['price']) * 100;
            $user_id = (int)$_SESSION['user_id'];

            $sql_insert = "INSERT INTO offres (formule, date, utilisateur_id, prix) VALUES (:formule, :date, :utilisateur_id, :prix)";

            if (
                $date &&
                $formule &&
                $prix &&
                $user_id
            ) {
                $stmt = $pdo->prepare($sql_insert);
                $stmt->bindParam(":formule", $formule);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":utilisateur_id", $user_id);
                $stmt->bindParam(":prix", $prix);
                $stmt->execute();
            }
        }
    }

    // récupérer les offres
    $sql = "SELECT
        offres.id as id,
        offres.date as date,
        offres.formule as formule,
        offres.prix as prix,
        offres.utilisateur_id as utilisateur_id,
        GROUP_CONCAT(commande_sums.commande_id_sum) as commande_id_sums
        FROM offres
        LEFT JOIN (
            SELECT
                commandes_has_offres.offre_id,
                COUNT(commande.id) as commande_id_sum
            FROM commandes_has_offres
            JOIN commande
                ON commandes_has_offres.commande_id = commande.id
            GROUP BY commandes_has_offres.offre_id
        ) as commande_sums
            ON offres.id = commande_sums.offre_id
        GROUP BY offres.id, offres.date, offres.formule, offres.prix, offres.utilisateur_id;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page administrateur</title>


    <!--style css-->
    <link rel="stylesheet" href="CSS/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>


<body>
    <?php include_once './header.php' ?>
        

    <main class='main'>
        <section class="offers">
            <?php if (count($result) > 0) : ?>
                <?php foreach ($result as $rk => $r): ?>
                    <form action="" method="POST" class="ticket created-by-anniedotexe" id="form-offer-<?= ($rk + 1) ?>">
                        <input type="hidden" name="id-modify" value="<?= $r['id'] ?>" id="id-<?= ($rk + 1) ?>" />
                        <div class="left">
                            <div class="image">
                                <p class="admit-one">
                                    <span>ADMIT ONE</span>
                                    <span>ADMIT ONE</span>
                                    <span>ADMIT ONE</span>
                                </p>
                            </div>
                            <div class="ticket-info">
                                <div class="text-white">
                                    Ticket vendu : <?= $r["commande_id_sums"] ?? 0 ?>
                                </div>
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
                            <button type="button" onclick="editOffer(<?= ($rk + 1) ?>)">Modifier l'offre</button>
                            <button type="button" onclick="deleteOffer(<?= ($rk + 1) ?>)">Supprimer</button>
                        </div>
                    </form>
                <?php endforeach; ?>
                <form action="" method="POST" class="ticket created-by-anniedotexe">
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
                                <div class="june-29" id="date-solo-1"><input type="date" value="octobre-NaN-31" name="date" id="edit-date-1"></div> <!-- Date affichée -->
                            </div>
                            <div class="show-name">
                                <h1 id="nom-solo-1"><input type="text" value="Formule solo" name="name" id="edit-nom-1"></h1>
                                <h1 id="prix-solo-1"><input type="text" value="30 " name="price" id="edit-prix-1"></h1>
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
                    <div class="modifier">
                        <button>Sauvegarder</button>
                    </div>
                </form>
            <?php else: ?>
                <div>
                    Aucune n'offres n'à été trouvé...
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