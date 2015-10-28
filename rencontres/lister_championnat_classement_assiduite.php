
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Championnat'", "assiduite DESC, points DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

