
<?php


include_once (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$annee1 = $gestion_saison->GetAnnee1Selectionnee();
$annee2 = $gestion_saison->GetAnnee2Selectionnee();

$DonneesAAfficher = array(
  "Date" => array("Date"),
  "Nb joueurs" => array("nb_joueurs")
);

echo "Liste des entrainements pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";


$sql_from = "dates where ( (dates.`Type` = 'Entrainement') and dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') ORDER BY dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

