
<?php


include_once (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

$annee1 = $gestion_saison->GetAnnee1Selectionnee();
$annee2 = $gestion_saison->GetAnnee2Selectionnee();

$DonneesAAfficher = array(
  "Date" => array("DATE_FORMAT(Date, '%d/%m/%Y')"," <i>", "CONVERT(REPLACE(REPLACE(Dpct,-1,'en déplacement'),0,'à domicile'),CHAR(20))"),
  "Adversaire" => array("Lieu"),
  "Effectifs" => array(" <b>", "nb_joueurs", " </b>", " / ", "nb_adversaires"),
  "Résultats" => array(" <b>", "points_pour", " </b>", " / ", "points_contre")
);

echo "Résultats des matchs de coupe pour la saison $annee1 / $annee2";
echo "<br>";
echo "<br>";

/* Dans le cas de l'année en cours on affiche que les dates jusqu'à maintenant */
if ($annee1 == $gestion_saison->GetAnneeEnCours())
{
  $date_fin = date('Y-m-d');
}
else
{
  $date_fin = "$annee2-08-01";
}
$sql_from = "dates where ( (dates.`Type` = 'Coupe') and dates.`Date` > '$annee1-08-01' and dates.`Date` <= '$date_fin') ORDER BY dates.`Date` ASC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>

