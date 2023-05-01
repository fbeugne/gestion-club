

<!--
**********************************************************************************************************
Action lié au formualaire
- Sélection d'une date
**********************************************************************************************************
-->

<?php

  //selection de la date pour afficher les résultats
  
  if (array_key_exists('date', $_POST))
  {
    $date = htmlspecialchars($_POST['date']);
  }
  else
  {
    $date="";
  }
  
  include_once (gestion_club_dir_path() . '/common.php');

  
  $conn_db = new BaseDeDonnesPalet();
  
  
	$gestion_saison = new GestionSaison();
	$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();
	$annee1 = $gestion_saison->GetAnnee1Selectionnee();
	$annee2 = $gestion_saison->GetAnnee2Selectionnee();
  
  $sql = "select Date, Lieu, Type from dates
   where (dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') ORDER BY dates.`Type` ASC, dates.`Date` ASC";
  
  $liste_date = $conn_db->RequeteSQL($sql);
  
  
?>

  <form action='<?php echo get_permalink();?>' method="post">
    <select name='date' onchange="this.form.submit()">
      
      <option value=""> Date </option>
      <?php
      
        if ($liste_date)
        {
          while($info_date=$liste_date->fetch_array(MYSQLI_ASSOC))
          {
            $param="";
            if ($date == $info_date['Date'])
            {
              $param = "selected";
            }
            echo "<option value='" . $info_date['Date'] . "' " . $param . ">";
            echo $info_date['Type'] . " - " . $info_date['Date'] . " - " . $info_date['Lieu'] . "</option>";
          }
          $liste_date->free();
        }
      ?>
    </select>
  </form>
  
  <br>



<!--
**********************************************************************************************************
Affichage des resultats d'une date
**********************************************************************************************************
-->

<?php

include (gestion_club_dir_path() . '/rencontres/lib_classement.php');

if ($date != "")
{
  $Filtre_SQL="dates.`Date` = '$date'";
  $Tri_SQL="points DESC, assiduite DESC, `licencies`.NOM ASC, `licencies`.Prenom ASC";

  AffichierClassementMatch($Filtre_SQL);
}

?>



