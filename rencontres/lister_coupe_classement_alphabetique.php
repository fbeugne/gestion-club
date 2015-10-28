
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Coupe'", "`Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

