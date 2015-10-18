
<?php


include (WPINC . '/gestion-club/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

$annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
$annee2 = $gestion_saison->GetAnneeSelectionnee();

$DonneesAAfficher = array(
  "Date" => array("Date"),
  "Adversaire" => array("Lieu"),
  "Nb équipes" => array("nb_joueurs"),
  "Nb adversaires" => array("nb_adversaires"),
  "Résultats" => array("points_pour", " / ", "points_contre")
);

echo "Résultats des matchs de coupe à domicile  pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";


$sql_from = "Dates where ( (Dates.`Type` = 'Coupe') and Dates.`Dpct` = 0 and Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') ORDER BY Dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);


echo "Résultats des matchs de coupe en déplacement  pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";

$sql_from = "Dates where ( (Dates.`Type` = 'Coupe') and Dates.`Dpct` = -1 and Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') ORDER BY Dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

