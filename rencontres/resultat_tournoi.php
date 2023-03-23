
<?php
include (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

$annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
$annee2 = $gestion_saison->GetAnneeSelectionnee();

$DonneesAAfficher = array(
  ""  => array("num_ligne"),
  "NOM" => array("`Licencies`.NOM"),
  "Prénom" => array("`Licencies`.Prenom"),
  "Partie1" => array("CONCAT(`Résultats`.pour_1, ' / ',`Résultats`.contre_1)"),
  "Partie2" => array("CONCAT(`Résultats`.pour_2, ' / ',`Résultats`.contre_2)"),
  "Partie3" => array("CONCAT(`Résultats`.pour_3, ' / ',`Résultats`.contre_3)"),
  "Partie4" => array("CONCAT(`Résultats`.pour_4, ' / ',`Résultats`.contre_4)"),
  "Points" => array("SUM(`Résultats`.`Résultat`) as points")
);


$sql_from = "`Résultats` INNER JOIN `Dates` on `Dates`.Date=`Résultats`.Date 
and ( (Dates.`Type` = 'Tournoi') and Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') 
RIGHT JOIN `Licencies` on `Résultats`.Code=`Licencies`.Code
WHERE `Licencies`.`$saison_selectionnee` is not null and `Licencies`.`$saison_selectionnee` != 'non'
GROUP BY `Licencies`.Code 
ORDER BY points DESC";

$conn_db->AfficherTable($DonneesAAfficher, $sql_from);

?>
