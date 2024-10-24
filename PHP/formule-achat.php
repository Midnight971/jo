<?php

  session_start ();

  require_once "./connect-bd.php";


  // Vérification du panier
  if (!isset($_SESSION['cart']) && count($_SESSION['cart']) == 0) {
    header("Location: mon-panier.php");
    exit();
  }

  // Si l'utilisateur est admin le bloquer
  if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: page-erreur-403.html");
    exit();
  }

  // Vérifier si l'utilisateur n'est pas connecté
  if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php?redirect=formule-achat.php");
    exit;
  }

  // Traitement de la commande et du paiement
  if (isset($_POST) && count($_POST) > 0) {
    $total = (int) $_POST['total-price'];
    $user_id = $_SESSION['user_id'];

    // insert commandes
    $sql_commande = "INSERT INTO commande (prix, utilisateur_id) VALUES (:prix, :utilisateur_id)";
    $stmt = $pdo->prepare($sql_commande);
    $stmt->bindParam("prix", $total);
    $stmt->bindParam("utilisateur_id", $user_id);
    $stmt->execute();
    $lastInsertId = (int)$pdo->lastInsertId();


    // récupération des offres
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));

    $sql_offers = "SELECT id FROM offres WHERE id IN (" . $placeholders . ")";
    $stmt = $pdo->prepare($sql_offers);
    $stmt->execute($_SESSION['cart']);
    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Insert Commandes has Offres
    $sql_commandes_offer = "INSERT INTO commandes_has_offres (commande_id, offre_id) VALUES (:commande_id, :offre_id)";
    $stmt = $pdo->prepare($sql_commandes_offer);

    $pdo->beginTransaction();

    foreach ($offers as $o) {
        $oId = (int) $o['id'];
        $stmt->bindParam("offre_id", $oId);
        $stmt->bindParam("commande_id", $lastInsertId);
        $stmt->execute();
    }

    $pdo->commit();

    // Insert Paiement
    $cle = uniqid();

    $sql_paiement = "INSERT INTO paiement (cle, commande_id) VALUES (:cle, :commande_id)";
    $stmt = $pdo->prepare($sql_paiement);
    $stmt->bindParam("cle", $cle);
    $stmt->bindParam("commande_id", $lastInsertId);
    $stmt->execute();

    // Suppression produit dans le panier
    $_SESSION['cart'] = [];


    header("Location: offre-client.php");
    exit;
  }

  // contient les produits du panier
  $result = [];

  // cout commande total
  $total_price = 0;

  // Récupérer des infos du produit du panier
  $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));

  $sql = "SELECT sum(prix) as total_price FROM offres WHERE id IN (" . $placeholders . ")";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($_SESSION['cart']);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>paiement</title>

  <link rel="stylesheet" href="CSS/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
    <body>
    <?php include_once './header.php' ?>


  <!--formulaire d'inscription-->
  <main class='main'>
            
    <form id="paiement" method="POST">
      <div class="fs-5 mb-3">
        Achat de <?= (count($_SESSION['cart'])) ?> produit(s) pour <?= (((int)$result[0]['total_price']) / 100) ?> €
      </div>
      <input type="hidden" name="total-price" value="<?= ((int)$result[0]['total_price']); ?>" />

      <fieldset>
        <legend>Votre identité</legend>

        <ol>
          <li>
            <label for=nom>Nom</label>
            <input id=nom name=nom type=text placeholder="Prénom et nom" required autofocus>
          </li>
          <li>
            <label for=email>Email</label>
            <input id=email name=email type=email placeholder="exemple@domaine.com" required>
          </li>
          <li>
            <label for=telephone>Téléphone</label>
            <input id=telephone name=telephone type=tel placeholder="par ex&nbsp;: +3375500000000" required>
          </li>
        </ol>
      </fieldset>

      <fieldset>
        <legend>Adresse de facturation</legend>
        <ol>
          <li>
            <label for=adresse>Adresse</label>
            <textarea id=adresse name=adresse rows=5 required></textarea>
          </li>
          <li>
            <label for=codepostal>Code postal</label>
            <input id=codepostal name=codepostal type=text required>
          </li>
            <li>
            <label for=pays>Pays</label>
            <input id=pays name=pays type=text required>
          </li>
        </ol>
      </fieldset>
      
      <fieldset>
        <legend>Informations CB</legend>
        <ol>
          <li>
            <fieldset>
              <legend>Type de carte bancaire</legend>
              <ol>
                <li>
                  <input id=visa name=type_de_carte type=radio>
                  <label for=visa>VISA</label>
                </li>
                <li>
                  <input id=amex name=type_de_carte type=radio>
                  <label for=amex>AmEx</label>
                </li>
                <li>
                  <input id=mastercard name=type_de_carte type=radio>
                  <label for=mastercard>Mastercard</label>
                </li>
              </ol>
            </fieldset>
          </li>
          <li>
            <label for=numero_de_carte>N° de carte</label>
            <input id=numero_de_carte name=numero_de_carte type=number required>
          </li>
          <li>
            <label for=securite>Code sécurité</label>
            <input id=securite name=securite type=number required>
          </li>
          <li>
            <label for=nom_porteur>Nom du porteur</label>
            <input id=nom_porteur name=nom_porteur type=text placeholder="Même nom que sur la carte" required>
          </li>
        </ol>
      </fieldset>

      <fieldset>
        <button type=submit>J'achète !</button>
      </fieldset>
    </form>
  </main>

  <?php include_once './footer.php' ?>
    

  </body>
</html>
