<?php

session_start();

session_unset();

header("location: connexion.php");
exit();

?>