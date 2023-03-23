
<?php


include_once (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
$annee2 = $gestion_saison->GetAnneeSelectionnee();

$DonneesAAfficher = array(
  "Date" => array("Date"),
  "Nb joueurs" => array("nb_joueurs")
);

echo "Liste des entrainements pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";


$sql_from = "Dates where ( (Dates.`Type` = 'Entrainement') and Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') ORDER BY Dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

