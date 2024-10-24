<?php
session_start();
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    

<!-- intégration des css -->
<link rel="stylesheet" href="CSS/styles.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>


<body>
<?php include_once './header.php' ?>

    <main class='main1'>



                                
                <h1 style='color: white; font-size: 60px; font-style: italic; font-family: serif; margin-bottom: 50px;'>Jeux Olympiques </h1>
                    <div style='text-align:center'>
                    <a style='color: white; font-size: 30px; font-style: italic; font-family: serif;' >Les Jeux olympiques (JO), aussi appelés Jeux olympiques modernes,
                        puisqu'ils prolongent la tradition des jeux olympiques de la Grèce antique, 
                        sont des événements sportifs internationaux majeurs,regroupant les sports d’été ou d’hiver, 
                        auxquels des milliers d’athlètes participent à travers 
                        différentes compétitions tous les quatre ans, pour chaque olympiade moderne.

                    </a>

                    </div>

                    <div class='row justify-content-between' style='width: 1400px; margin-top:50px'>
                    
                                <div class='col-lg-3 text-center'>
                                    
                                        <img src="https://i.ibb.co/Rbc2jVq/natation.jpg" alt="duo">

                                        <p style='color: white; margin-top:20px'>
                                            La natation est une discipline historique des Jeux Olympiques de l’ère moderne. 
                                            Si les premières courses olympiques se déroulaient en environnement naturel, dès les Jeux de Londres en 1908, 
                                            les épreuves ont pris place dans une piscine, ce qui a donné lieu à la création de la Fédération Internationale de Natation (FINA). 
                                            La nage libre et la brasse sont les seules épreuves présentes aux Jeux d’Athènes en 1896, 
                                            le dos est ensuite ajouté en 1904, puis le papillon apparaît en 1956 aux Jeux de Melbourne.
                                        </p>

                                </div>

                                <div class='col-lg-3 text-center'>
                                        <img src="https://i.ibb.co/RYWsykT/athletisme.png" alt="duo">

                                        <p style='color: white; margin-top:20px'>
                                            Avec 46 titres olympiques, l'athlétisme sera l'un des sports majeurs de ces Jeux Olympiques. 
                                            L'équipe de France voudra faire mieux qu'aux Jeux Olympiques de 2020 où la délégation tricolore n'avait ramené qu'une seule médaille. 
                                            La délégation française est en confiance après des championnats d'Europe réussis où les Bleus ont ramené 16 médailles, dont quatre en or. 
                                            Cyréna Samba-Mayela, Gabriel Tual, Alexis Miellet ou encore Alice Finot font partie des meilleures chances de médailles pour la France. 
                                            Kevin Mayer devrait bien être présent aux JO de Paris 2024. Cependant, le français s'est blessé à la cuisse au début du mois de juillet et arrivera aux JO diminué.
                                        </p>

                                </div>
                    </div>                        


                                <!--Animation JS-->
                            <!-- <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const body = document.body;
                                    let opacity = 1;
                                    let direction = -0.01;

                                    function animateBackground() {
                                        opacity += direction;
                                        if (opacity <= 0.2 || opacity >= 1) {
                                            direction = -direction;
                                        }
                                        body.style.opacity = opacity;
                                        requestAnimationFrame(animateBackground);
                                    }

                        animateBackground();
                        });
                            </script>     -->


                        <!-- <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                function lines() {
                                    let e = document.createElement('div');
                                    e.setAttribute('class', 'circle');
                                    e.style.left = Math.random() * 100 + 'vw'; // Position aléatoire horizontale
                                    document.body.appendChild(e);

                                    setTimeout(function() {
                                        document.body.removeChild(e);
                                    }, 5000);
                                }

                                setInterval(function() {
                                    lines();
                                }, 200);
                        });
                            </script> -->

                

                    </main>

                    <?php include_once './footer.php' ?>
                            


                            
                        </body>

                    <footer> </footer>
                    
</html>