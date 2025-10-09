

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
  AffichierClassementMatch($Filtre_SQL);
}

?>


<!--
**********************************************************************************************************
Affichage des resultats de chaque licencié
**********************************************************************************************************
-->

<?php
if ($date != "")
{
?>

<div  class="resultat_licencie">

  <table>
<?php

    $nb_parties=$conn_db->GetNbPartiesFromDate($date);

    $gestion_saison = new GestionSaison();
    $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

    $sql = "select Code, NOM, Prenom from licencies
    where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";

    $liste_licencies_req = $conn_db->RequeteSQL($sql);

    $liste_licencies_req->data_seek(0);
		// printing table rows
		while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
		{
		  $code = $row['Code'];
		  $nom = $row['NOM'];
		  $prenom = $row['Prenom'];
		  
      $sql = "select pour_1, contre_1, pour_2, contre_2, pour_3, contre_3,
       pour_4, contre_4, pour_5, contre_5, pour_6, contre_6, pour_7, contre_7, pour_8, contre_8 from `resultats`
       where (`resultats`.`Date` ='$date' and `resultats`.`Code` = '$code')";
      
    
      $res_db = $conn_db->RequeteSQL($sql);
      $present=false;
		  $pour=null;
		  $contre=null;
      
      // un resultat est présent, on le recupere
		  if ($res_db && $res_db->num_rows>0)
		  {
		    $present=true;
        $resultats_db = $res_db->fetch_array(MYSQLI_ASSOC);
		  }
		  
		  echo "<tr style='border:1px solid #ededed;'>";
		  echo "<td><input type='checkbox' disabled value='1'";
		  if ($present)
		  {
		    echo " checked";
		  }
		  echo "/></td>";
		  echo "<td>$nom $prenom</td>";
		  
      
		  for ($num_partie=0; $num_partie<$nb_parties; $num_partie++)
		  {
		    if ($present)
		    {
  		    $num_partie_db=$num_partie+1;
  		    $pour=$resultats_db['pour_'.$num_partie_db];
  		    $contre=$resultats_db['contre_'.$num_partie_db];
		    }
        if($pour==0 && $contre==0){
          echo "<td style='border-left:3px solid #ededed;text-align: center;'></td>";
          echo "<td style='border-left:1px solid #ededed;text-align: center;'></td>";
        }
        else{  
		      echo "<td style='border-left:3px solid #ededed;text-align: center;'>$pour</td>";
  		    echo "<td style='border-left:1px solid #ededed;text-align: center;'>$contre</td>";
        }
		  }
		  echo "</tr>";
		}
?>
  </table>
  
</div>

<?php
}
?>



