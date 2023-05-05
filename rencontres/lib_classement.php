
<?php

function AffichierClassement($filtre_match, $classement)
{
  include_once (gestion_club_dir_path() . '/common.php');

  $conn_db = new BaseDeDonnesPalet();

  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  $annee1 = $gestion_saison->GetAnnee1Selectionnee();
  $annee2 = $gestion_saison->GetAnnee2Selectionnee();

  $DonneesAAfficher = array(
    ""  => array("num_ligne"),
    "Nom" => array("`licencies`.NOM", "  ", "`licencies`.Prenom"),
    "Points" => array("SUM(`resultats`.`Resultat`) as points"),
    "Assiduité" => array("COUNT(`resultats`.Code) as assiduite"),
    "Moyenne" => array("AVG(`resultats`.`Resultat`) as moyenne")
  );
  
  
  $sql_from = "`resultats` INNER JOIN `dates` on `dates`.Date=`resultats`.Date 
  and ( (" . $filtre_match . ") and dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') 
  RIGHT JOIN `licencies` on `resultats`.Code=`licencies`.Code
  WHERE `licencies`.`$saison_selectionnee` is not null and `licencies`.`$saison_selectionnee` != 'non'
  GROUP BY `licencies`.Code 
  ORDER BY " . $classement;
  
  $conn_db->AfficherTable($DonneesAAfficher, $sql_from);
}

function AffichierClassementMatch($filtre_match)
{
  include_once (gestion_club_dir_path() . '/common.php');

  $conn_db = new BaseDeDonnesPalet();

  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  $annee1 = $gestion_saison->GetAnnee1Selectionnee();
  $annee2 = $gestion_saison->GetAnnee2Selectionnee();

  $DonneesAAfficher = array(
    ""  => array("num_ligne"),
    "Nom" => array("`licencies`.NOM", "  ", "`licencies`.Prenom"),
    "Points" => array("SUM(`resultats`.`Resultat`) as points")
  );
  
  
  $sql_from = "`resultats` INNER JOIN `dates` on `dates`.Date=`resultats`.Date 
  and ( (" . $filtre_match . ") and dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') 
  RIGHT JOIN `licencies` on `resultats`.Code=`licencies`.Code
  WHERE `licencies`.`$saison_selectionnee` is not null and `licencies`.`$saison_selectionnee` != 'non'
  GROUP BY `licencies`.Code 
  ORDER BY points DESC, `licencies`.NOM ASC, `licencies`.Prenom ASC";
  
  $conn_db->AfficherTable($DonneesAAfficher, $sql_from);
}
?>

