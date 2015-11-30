
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
  "Date de naissannce" => array("Jour", " / ", "Mois", " / ", "Année")
);


$conn_db->AfficherTable($DonneesAAfficher, $sql);

?>

