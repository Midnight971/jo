

<?php include_once './connect-bd.php' ?>

<link rel="stylesheet" href="CSS/styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<header class="header">

<nav class="navbar">
    <a href="/">
        <img src="https://i.ibb.co/wh5zsmH/logo.png" alt="Logo" class="logo">   
    </a>

        <div class='bouton-header'>
            <a href="offre.php" class="btn">Offre</a>    <!-- Redirection vers la page des offres -->
            <!-- <a href="contact.php" class="btn">Contact</a>  -->

            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="connexion.php" class="btn">Connexion</a> <!-- Redirection vers la page de connexion -->
            <?php endif; ?>
            
            
            <a href="mon-panier.php" class="btn panier-btn">
                <img class="icon-panier" src="https://i.ibb.co/99XkvCg/panier-dachat.png" alt="panier" /> 
                Mon panier
                <span class="cart-count">
                    <?= isset($_SESSION['cart']) ? "(" . count($_SESSION['cart']) . ")" : null ?>
                </span>
            </a>

            <div class="user-info d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])): ?>
                        <a class="user-name text-white text-decoration-none btn" href="<?= $_SESSION['role'] === 'admin' ? 'offre-admin.php' : 'offre-client.php' ?>">
                            <?= $_SESSION['role'] === 'admin' ? "<i class='bi bi-person-fill-gear'></i>" : "<i class='bi bi-person-fill'></i>" ?>
                            <?php echo htmlspecialchars($_SESSION['nom'] . ' '. $_SESSION['prenom']); ?><br/>
                        </a>
                    <div class="small">
                        <a href="/deconnexion.php" class="btn text-white">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
</nav>


    </header>
