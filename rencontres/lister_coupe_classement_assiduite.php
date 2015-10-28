
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Coupe'", "assiduite DESC, points DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

