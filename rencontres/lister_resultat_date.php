

<!--
**********************************************************************************************************
Action lié au formualaire
- Sélection d'une date
- Sélection d'un classement
**********************************************************************************************************
-->

<?php

  //selection de la date pour afficher les résultats
  $date = htmlspecialchars($_POST['date']);
  if($date=="")
  {
    $date = htmlspecialchars($_GET['date']);
  }
  
  include_once (gestion_club_dir_path() . '/common.php');

  
  $conn_db = new BaseDeDonnesPalet();
  
  
	$gestion_saison = new GestionSaison();
	$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();
	$annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
	$annee2 = $gestion_saison->GetAnneeSelectionnee();
  
  $sql = "select Date, Lieu, Type from Dates
   where (Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') ORDER BY Dates.`Date` ASC";
  
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
<?

include (gestion_club_dir_path() . '/rencontres/lib_classement.php');

if ($date != "")
{
  $Filtre_SQL="Dates.`Date` = '$date'";
  $Tri_SQL="points DESC, assiduite DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC";

  AffichierClassementMatch($Filtre_SQL);
}

?>



