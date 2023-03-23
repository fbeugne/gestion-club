
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


$sql_from = "Licencies inner join Adresse on Licencies.Code=Adresse.Code
 where (Licencies.`$saison_selectionnee` !='non' and Licencies.`$saison_selectionnee` is not null) ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

