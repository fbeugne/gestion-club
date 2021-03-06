<?php

include (WPINC . '/gestion-club/common.php');

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
  $sql = "select NOM, Prenom from Licencies where (Licencies.`$saison_selectionnee` = '$role') ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";
  
  $result = $conn_db->RequeteSQL($sql); 
  
  // Affichage des rôles uniques
  echo "<tr>";
  if ( ($result) && ($info_bureau = $result->fetch_row()) )
  {
    $nom=$info_bureau[0];
    $prenom=$info_bureau[1];
    
    echo "<td><b>$role</td>";
    echo "<td>$nom</td>";
    echo "<td>$prenom</td>";
    echo "</tr>"; 
  }
  else
  {
      echo "<tr>";
      echo "<td><b>$role</td>";
      echo "<td><i>Inconnu</td>";
      echo "<td><i>Inconnu</td>";
      echo "</tr>"; 
  }
}

// Listing des membres du bureau
$sql = "select NOM, Prenom from Licencies where (Licencies.`$saison_selectionnee` = 'Bureau') ORDER BY `Licencies`.`NOM` ASC";

$result = $conn_db->RequeteSQL($sql); 

if ($result)
{
  while($info_bureau = $result->fetch_row())
  {
    $nom=$info_bureau[0];
    $prenom=$info_bureau[1];
    
    echo "<tr>";
    echo "<td><b>Membre</td>";
    echo "<td>$nom</td>";
    echo "<td>$prenom</td>";
    echo "</tr>"; 
  }
}
else
{
    echo "<tr>";
    echo "<td><b>Membre</td>";
    echo "<td><i>Inconnu</td>";
    echo "<td><i>Inconnu</td>";
    echo "</tr>";
}
  
echo "</table>";

?>