<?php

include (gestion_club_dir_path() . '/common.php');

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

echo "Composition du bureau pour la saison $saison_selectionnee";
echo "<br>";
echo "<br>";

$ListeRole=array(
"Président",
"Vice-Président",
"Secrétaire",
"Vice-Secrétaire",
"Trésorier",
"Vice-Trésorier");


$conn_db = new BaseDeDonnesPalet();

echo "<table>";
foreach ($ListeRole as $role)
{
  $sql = "select NOM, Prenom from licencies where (licencies.`$saison_selectionnee` = '$role') ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";
  
  $result = $conn_db->RequeteSQL($sql); 
  
  // Affichage des rôles uniques
  if ( ($result) && ($info_bureau = $result->fetch_row()) )
  {
    $nom=$info_bureau[0];
    $prenom=$info_bureau[1];
    
    echo "<tr>";
    echo "<td><b>$role</td>";
    echo "<td>$nom $prenom</td>";
    echo "</tr>"; 
  }
}

// Listing des membres du bureau
$sql = "select NOM, Prenom from licencies where (licencies.`$saison_selectionnee` = 'Bureau') ORDER BY `licencies`.`NOM` ASC";

$result = $conn_db->RequeteSQL($sql); 

if ($result)
{
  while($info_bureau = $result->fetch_row())
  {
    $nom=$info_bureau[0];
    $prenom=$info_bureau[1];
    
    echo "<tr>";
    echo "<td><b>Membre</td>";
    echo "<td>$nom $prenom</td>";
    echo "</tr>"; 
  }
}
  
echo "</table>";

?>