
<?php

function AffichierClassement($filtre_match, $classement)
{
  include (WPINC . '/gestion-club/common.php');

  $conn_db = new BaseDeDonnesPalet();

  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  $annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
  $annee2 = $gestion_saison->GetAnneeSelectionnee();

  $DonneesAAfficher = array(
    "NOM" => array("`Licencies`.NOM"),
    "Prénom" => array("`Licencies`.Prenom"),
    "Points" => array("SUM(`Résultats`.`Résultat`) as points"),
    "Assiduité" => array("COUNT(`Résultats`.Code) as assiduite")
  );

  
  
  $sql_from = "`Résultats` INNER JOIN `Dates` on `Dates`.Date=`Résultats`.Date 
  and ( (" . $filtre_match . ") and Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') 
  RIGHT JOIN `Licencies` on `Résultats`.Code=`Licencies`.Code
  WHERE `Licencies`.`$saison_selectionnee` is not null and `Licencies`.`$saison_selectionnee` != 'non'
  GROUP BY `Licencies`.Code 
  ORDER BY " . $classement;
    

  $conn_db->AfficherTable($DonneesAAfficher, $sql_from);
}
?>

