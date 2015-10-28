
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Championnat'", "points DESC, assiduite DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

