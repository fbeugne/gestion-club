
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Coupe'", "points DESC, assiduite DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

