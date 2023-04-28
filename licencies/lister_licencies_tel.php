
<?php

include (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$DonneesAAfficher = array(
  "NOM" => array("NOM"),
  "Prenom" => array("Prenom"),
  "N° Téléphone" => array("Fixe", " <br>", "Portable")
);


$sql_from = "licencies inner join adresse on licencies.Code=adresse.Code
 where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

