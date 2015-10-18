<?php

include (WPINC . '/gestion-club/common.php');

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();


$ListeRole=array(
"Président",
"Vice-Président",
"Secrétaire",
"Vice-Secrétaire",
"Trésorier",
"Vice-Trésorier",
"Bureau");


$DonneesAAfficher = array(
  "NOM" => array("NOM"),
  "Prenom" => array("Prenom"),
);


$conn_db = new BaseDeDonnesPalet();

echo "<table>";
foreach ($ListeRole as $role)
{
  $sql = "select NOM, Prenom from Licencies where (Licencies.`$saison_selectionnee` = '$role') ORDER BY `Licencies`.`NOM` ASC";
  
  $result = $conn_db->RequeteSQL($sql); 
  
  if ($result)
  {
    
    while($info_bureau = $result->fetch_row())
    {
      $nom=$info_bureau[0];
      $prenom=$info_bureau[1];
      
      echo "<tr>";
      echo "<td><b>$role</td>";
      echo "<td>$nom</td>";
      echo "<td>$prenom</td>";
      echo "</tr>"; 
    }
  }
}
echo "</table>";

?>