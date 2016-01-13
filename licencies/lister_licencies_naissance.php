
<?php

include (WPINC . '/gestion-club/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

$sql = "Licencies inner join Naissance on Licencies.Code=Naissance.Code
 where (Licencies.`$saison_selectionnee` !='non' and Licencies.`$saison_selectionnee` is not null) ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";

 
$DonneesAAfficher = array(
  "NOM" => array("NOM"),
  "Prenom" => array("Prenom"),
  "Date de naissannce" => array("DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD('0000-01-01', INTERVAL `Naissance`.`Année` YEAR), INTERVAL `Naissance`.`Mois` - 1 MONTH), INTERVAL `Naissance`.`Jour` - 1 DAY), '%d / %m / %Y')")
);


$conn_db->AfficherTable($DonneesAAfficher, $sql);

?>

