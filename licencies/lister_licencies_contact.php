<?php

include (gestion_club_dir_path() . '/common.php');

$DonneesAAfficher = array(
  "Nom" => array("NOM", "  ", "Prenom"),
  "Adresse" => array("Rue", " <br>", "CPostal", " <br>", "Ville"),
  "Contact" => array("email", " <br>", "Fixe", " <br>", "Portable")
);


$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$sql_from = "licencies inner join adresse on licencies.Code=adresse.Code inner join naissance on licencies.Code=naissance.Code
 where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";


$conn_db = new BaseDeDonnesPalet();

$result = $conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>