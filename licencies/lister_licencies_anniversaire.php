
<?php

include (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$conn_db->RequeteSQL("set lc_time_names='fr_FR'");


$sql = "licencies inner join naissance on licencies.Code=naissance.Code
 where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `naissance`.`Mois`, `naissance`.`Jour`, `licencies`.`NOM` ASC, `licencies`.Prenom ASC";

 
$DonneesAAfficher = array(
  "Anniversaire" => array("Jour", "  ", "DATE_FORMAT(DATE_ADD('2000-01-01', INTERVAL `naissance`.`Mois` - 1 MONTH), '%M')"),
  "Qui" => array("Prenom", "  ", "NOM")
);


$conn_db->AfficherTable($DonneesAAfficher, $sql);

?>

