
<?php

include (WPINC . '/gestion-club/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();



$conn_db->RequeteSQL("set @@GLOBAL.lc_time_names='fr_FR'");

$sql = "Licencies inner join Naissance on Licencies.Code=Naissance.Code
 where (Licencies.`$saison_selectionnee` !='non' and Licencies.`$saison_selectionnee` is not null) ORDER BY `Naissance`.`Mois`, `Naissance`.`Jour`, `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";

 
$DonneesAAfficher = array(
  "Anniversaire" => array("Jour", "  ", "DATE_FORMAT(DATE_ADD('2000-01-01', INTERVAL `Naissance`.`Mois` - 1 MONTH), '%M')"),
  "NOM" => array("NOM"),
  "Prenom" => array("Prenom")
);


$conn_db->AfficherTable($DonneesAAfficher, $sql);

?>

