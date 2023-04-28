
<?php

include_once (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();

$annee1 = $gestion_saison->GetAnnee1Selectionnee();
$annee2 = $gestion_saison->GetAnnee2Selectionnee();

$DonneesAAfficher = array(
  "Date" => array("DATE_FORMAT(Date, '%d/%m/%Y')"),
  "Adversaire" => array("Lieu"),
  "Déplacement" => array(" <i>", "CONVERT(REPLACE(REPLACE(Dpct,-1,'En déplacement'),0,'A domicile'),CHAR(20))"),
  "Nb joueurs" => array("nb_joueurs"),
  "Nb adversaires" => array("nb_adversaires"),
  "Résultats" => array("points_pour", " / ", "points_contre")
);

echo "Résultats des matchs de championnat pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";


$sql_from = "dates where ( (dates.`Type` = 'Championnat' or dates.`Type` = 'Amical') and dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') ORDER BY dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

