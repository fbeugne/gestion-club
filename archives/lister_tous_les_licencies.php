
<?php

include (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();


$DonneesAAfficher = array(
  "NOM" => array("NOM"),
  "Prénom" => array("Prenom")
);

$gestion_saison = new GestionSaison();

$start=1987;
$end=$gestion_saison->GetAnneeEnCours();

$modulo_annee=10;

$sql_from = "Licencies WHERE FALSE";

for ($annee=$end-1; $annee>=$start; $annee--)
{
  $annee_sup = $annee + 1;
  $Title=substr("$annee", -2) . "-" . substr("$annee_sup", -2);

  $sql_from = "$sql_from OR (Licencies.`$annee/$annee_sup` is not null AND Licencies.`$annee/$annee_sup` != 'non')";
  
  $desc="IFNULL(Licencies.`$annee/$annee_sup`,'')";
  $desc="REPLACE($desc, 'non', '')";
  $desc="REPLACE($desc, 'OUI', 'X')";
  $desc="REPLACE($desc, 'Bureau', 'M')";
  $desc="REPLACE($desc, 'Vice-Trésorier', 'VT')";
  $desc="REPLACE($desc, 'Trésorier', 'T')";
  $desc="REPLACE($desc, 'Vice-Secrétaire', 'VS')";
  $desc="REPLACE($desc, 'Secrétaire', 'S')";
  $desc="REPLACE($desc, 'Vice-Président', 'VP')";
  $desc="REPLACE($desc, 'Président', 'P')";
  $desc="$desc AS ROLE_$annee";
  $DonneesAAfficher["$Title"] = array("$desc"); 
  
  
  if (((($end - $annee ) % $modulo_annee) == 0)
    || ($annee == $start))
  {
    $sql_from = "$sql_from ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";
    
    $conn_db->AfficherTable($DonneesAAfficher, $sql_from);
    
    $DonneesAAfficher = array(
      "NOM" => array("NOM"),
      "Prénom" => array("Prenom")
    );
    $sql_from = "Licencies WHERE FALSE";
    
    echo "<h1 style='page-break-before:always'></h1>";

  }    
}


?>

