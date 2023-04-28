<?php

include (gestion_club_dir_path() . '/common.php');

$DonneesAAfficher = array(
  "NOM" => array("NOM"),
  "Prenom" => array("Prenom"),
  "Adresse" => array("Rue", " <br>", "CPostal", " <br>", "Ville"),
  "Contact" => array("email", " <br>", "Fixe", " <br>", "Portable"),
  "Date de naissance" => array("DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD('0000-01-01', INTERVAL `naissance`.`Annee` YEAR), INTERVAL `naissance`.`Mois` - 1 MONTH), INTERVAL `naissance`.`Jour` - 1 DAY), '%d / %m / %Y')")
);


$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$sql_from = "licencies inner join adresse on licencies.Code=adresse.Code inner join naissance on licencies.Code=naissance.Code
 where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";


$conn_db = new BaseDeDonnesPalet();

$result = $conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>