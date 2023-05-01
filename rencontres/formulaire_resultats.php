
<!--
  Formulaire de sélection de la date
-->
<?php

include_once (gestion_club_dir_path() . '/common.php');

$conn_db = new BaseDeDonnesPalet();
  

if (array_key_exists('action', $_GET))
{
  $action=htmlspecialchars($_GET['action']);
}
else
{
  $action="";
}


if (array_key_exists('date', $_POST))
{
  $date = htmlspecialchars($_POST['date']);
}
else if (array_key_exists('date', $_GET))
{
  $date = htmlspecialchars($_GET['date']);
}
else 
{
  $date = "";
}

if ($date != "")
{
  $sql = "select * from `dates` where `Date`='$date'";
  $res = $conn_db->RequeteSQL($sql);
  
  if ($res)
  {
    $info_date=$res->fetch_array(MYSQLI_ASSOC);
    
    $type = $info_date['Type'];
    
    $res->free();
  }
}

$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();
$annee1 = $gestion_saison->GetAnnee1Selectionnee();
$annee2 = $gestion_saison->GetAnnee2Selectionnee();

$sql = "select Date, Lieu, Type from dates
    where (dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') ORDER BY dates.`Type` ASC, dates.`Date` ASC";


$liste_date = $conn_db->RequeteSQL($sql);

?>


<!--
**********************************************************************************************************
Formulaire de sélection d'une rencontre
**********************************************************************************************************
-->

<table>
  <tr>
    <td>
      <form action='<?php echo get_permalink();?>' method="post">
        <select name='date' onchange="this.form.submit()">
          
          <option value=""> - Rencontre - </option>
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
    </td>
  </tr>
</table>


<!--
**********************************************************************************************************
Initialisation des variables liées au tye de match
**********************************************************************************************************
-->


<?php


  if ($date == "")
  {
    echo "Aucune date sélectionnée";
    exit();
  }


  #quelques parametres de configuration
  if ($type == "Coupe")
  {
    $title="Match de coupe";
    $nb_partie = 6;
    $nb_point_max=13;
  }
  else if ($type == "Championnat")
  {
    $title="Match de championnat";
    $nb_partie = 6;
    $nb_point_max=11;
  }
  else if ($type == "Entrainement")
  {
    $title="Entrainement";
    $nb_partie = 4;
    $nb_point_max=11;
  }
  else if ($type == "Amical")
  {
    $title="Match amical";
    $nb_partie = 6;
    $nb_point_max=11;
  }
  else if ($type == "Tournoi")
  {
    $title="Tournoi interne";
    $nb_partie = 6;
    $nb_point_max=13;
  }
  else
  {
    echo "Type de match $type non pris en compte";
    exit();
  }
  
  $sql = "select Code, NOM, Prenom from licencies
   where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";
  
  $liste_licencies_req = $conn_db->RequeteSQL($sql);
  
?>


<!--
**********************************************************************************************************
Sauvegarde des donnees dans la base de donnees
**********************************************************************************************************
-->

<?php
  // sauvegarde des resultats dans la base de données
  if ($action == "modif_resultat_db")
  {
    include (gestion_club_dir_path() . '/rencontres/modifier_resultat_individuel_db.php');
  }
?>

  
<!--
**********************************************************************************************************
Affichage du formulaire de saisie de resultats de chaque licencié
**********************************************************************************************************
-->
<div  class="resultat_licencie">

<form action="<?php echo add_query_arg(array('action'=>'modif_resultat_db','date'=>$date),get_permalink()); ?>" method="post" name="saisie_resultat">
  <table>
<?php

    $liste_licencies_req->data_seek(0);
		// printing table rows
		while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
		{
		  $code = $row['Code'];
		  $nom = $row['NOM'];
		  $prenom = $row['Prenom'];
		  
      $sql = "select pour_1, contre_1, pour_2, contre_2, pour_3, contre_3,
       pour_4, contre_4, pour_5, contre_5, pour_6, contre_6 from `resultats`
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
		  echo "<td><input type='checkbox' name='presence_$code' id='presence_$code' value='1' onclick='reset_resultat(\"$code\", $nb_partie)'";
		  if ($present)
		  {
		    echo " checked";
		  }
		  echo "/></td>";
		  echo "<td>$nom</td>";
		  echo "<td>$prenom</td>";
		  
      
		  for ($num_partie=0; $num_partie<$nb_partie; $num_partie++)
		  {
		    if ($present)
		    {
  		    $num_partie_db=$num_partie+1;
  		    $pour=$resultats_db['pour_'.$num_partie_db];
  		    $contre=$resultats_db['contre_'.$num_partie_db];
		    }
		    echo "<td style='border-left:1px solid #ededed;'></td>";
  		  echo "<td><select name='".$num_partie."_gagne_".$code."' id='".$num_partie."_gagne_".$code."' onChange='set_resultat_oppose(this, \"$num_partie\", \"$code\", $nb_point_max)'>";
  		  echo "<option value='-1'>  </option>";
  		  for ($i=0; $i<$nb_point_max;$i++)
  		  {
  		    echo "<option value='$i'";
  		    if ($pour!=null && $pour==$i) echo " selected";
  		    echo ">$i</option>";
  		  }
  		  echo "<option hidden value='$nb_point_max'";
		    if ($pour!=null && $pour==$nb_point_max) echo " selected";
  		  echo ">$nb_point_max</option>";
  		  echo "</select></td>";
  		  
  		  echo "<td><select name='".$num_partie."_perdu_".$code."' id='".$num_partie."_perdu_".$code."' onChange='set_resultat_oppose(this, \"$num_partie\", \"$code\", $nb_point_max)'>";
  		  echo "<option value='-1'>  </option>";
  		  for ($i=0; $i<$nb_point_max;$i++)
  		  {
  		    echo "<option value='$i'";
  		    if ($contre!=null && $contre==$i) echo " selected";
  		    echo ">$i</option>";
  		  }
  		  echo "<option hidden value='$nb_point_max'";
		    if ($contre!=null && $contre==$nb_point_max) echo " selected";
  		  echo ">$nb_point_max</option>";
  		  echo "</select></td>";
		  }
		  echo "</tr>";
		}
?>
  </table>
  
  <input type="submit" value="Sauvegarder les résultats individuels"/>
</form>
</div>



<!--
**********************************************************************************************************
Scripts permettant de facilité la rentré des résultats
**********************************************************************************************************
-->

<script>
  function set_resultat_oppose(element, num_partie, code, nb_point_max)
  {
    if (element.id==(num_partie+"_gagne_"+code))
    {
      if(element.value == -1)
      {
        document.getElementById(num_partie+"_perdu_"+code).value=-1;
      }
      else
      {
        document.getElementById(num_partie+"_perdu_"+code).value=nb_point_max;
        document.getElementById("presence_"+code).checked=true;
      }
    }
    else
    {
      if(element.value == -1)
      {
        document.getElementById(num_partie+"_gagne_"+code).value=-1;
      }
      else
      {
        document.getElementById(num_partie+"_gagne_"+code).value=nb_point_max;
        document.getElementById("presence_"+code).checked=true;
      }
    }
  }
  function reset_resultat(code, nb_parties)
  {
      if (document.getElementById("presence_"+code).checked==false)
      {
        for (i = 0; i < nb_parties; i++) {
          document.getElementById(i+"_gagne_"+code).value=-1;
          document.getElementById(i+"_perdu_"+code).value=-1
        }
      }
  }
</script>


<?php

  $liste_licencies_req->free();
?>