
<?php


include (WPINC . '/gestion-club/rencontres/lib_classement.php');


AffichierClassement("Dates.`Type` = 'Championnat'", "`Licencies`.NOM ASC, `Licencies`.Prenom ASC");
?>

