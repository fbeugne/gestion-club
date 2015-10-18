<?php

$code=htmlspecialchars($_GET['id']);



include_once (WPINC . '/gestion-club/common.php');

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$sql="UPDATE Licencies SET `$saison_selectionnee` = 'non' WHERE Code='$code'";

$conn_db = new BaseDeDonnesPalet();
$conn_db->RequeteSQL($sql);

echo "<i>$code supprimer de la saison $saison_selectionnee</i><br><br>";

?>