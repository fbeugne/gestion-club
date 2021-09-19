

<!--
**********************************************************************************************************
Initialisation des variables liées au tye de match
**********************************************************************************************************
-->


<?php

  $title="Match";

  #quelques parametres de configuration
  if ($type == "Coupe")
  {
    $title="$title de coupe";
    $nb_partie = 6;
    $nb_point_max=13;
  }
  else if ($type == "Championnat")
  {
    $title="$title de championnat";
    $nb_partie = 6;
    $nb_point_max=11;
  }
  else if ($type == "Entrainement")
  {
    $title="$title d'entrainement";
    $nb_partie = 4;
    $nb_point_max=11;
  }
  else if ($type == "Amical")
  {
    $title="$title amical";
    $nb_partie = 6;
    $nb_point_max=11;
  }
  else if ($type == "Galette")
  {
    $title="Tournoi de la galette";
    $nb_partie = 4;
    $nb_point_max=13;
  }
  else
  {
    echo "Type de match $type non pris en compte";
    exit();
  }
  $title="$title - $date - $lieu";
  
  echo "$title";
  
  include_once (WPINC . '/gestion-club/common.php');
  
  
  $conn_db = new BaseDeDonnesPalet();
  
  $sql = "select Code, NOM, Prenom from Licencies
   where (Licencies.`$saison_selectionnee` !='non' and Licencies.`$saison_selectionnee` is not null) ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";
  
  $liste_licencies_req = $conn_db->RequeteSQL($sql);
  
  $sql = "select nb_joueurs, nb_adversaires, points_pour, points_contre from  `Dates` where (Dates.Date = '$date')";
  
  $resultats_match = $conn_db->RequeteSQL($sql);
  $points_contre=0;
  $points_pour=0;
  $nb_adversaires=0;
  $nb_joueurs=0;
  if ($resultats_match && ($info_match=$resultats_match->fetch_array(MYSQLI_ASSOC)))
  {
    $points_contre=$info_match['points_contre'];
    $points_pour=$info_match['points_pour'];
    $nb_adversaires=$info_match['nb_adversaires'];
    $nb_joueurs=$info_match['nb_joueurs'];
  }
?>


<!--
**********************************************************************************************************
Sauvegarde des donnees dans la base de donnees
**********************************************************************************************************
-->

<?php
  // sauvegarde des resultats dans la base de données
  if ($action == "modif_resultat_match_db")
  {
    include (WPINC . '/gestion-club/rencontres/modifier_resultat_match_db.php');
  }
?>

<?php
  // sauvegarde des resultats dans la base de données
  if ($action == "modif_resultat_db")
  {
    include (WPINC . '/gestion-club/rencontres/modifier_resultat_individuel_db.php');
  }
?>


<!--
**********************************************************************************************************
Affichage du formulaire de saisie de resultats du match
**********************************************************************************************************
-->

<?php 
if ( ($type != "Entrainement") && ($type != "Galette"))
{
?>
  
<form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'modif_resultat_match_db','date'=>$date),get_permalink()); ?>" method="post" name="saisie_resultat">
  <table>
    <tr>
      <td> 
        Nb joueurs : 
      </td>
      <td> 
        <input name='nb_joueurs' type="int" value="<?php echo "$nb_joueurs"?>" style='width:auto'>
      </td>
      <td>
        Nb adversaires : 
      </td>
      <td>
        <input name='nb_adversaires' type="int" value="<?php echo "$nb_adversaires"?>" style='width:auto'><br>
      </td>
    </tr>
    <tr>
      <td> 
        Points pour :
      </td>
      <td> 
        <input name='points_pour' type="int" value="<?php echo "$points_pour"?>" style='width:auto'>
      </td>
      <td>
        Points contre :
      </td>
      <td>
        <input name='points_contre' type="int" value="<?php echo "$points_contre"?>" style='width:auto'><br>
      </td>
    </tr>
    <tr>
        <td><input type='submit' value='Sauvegarder les résultats du match'></td><td></td><td></td><td></td>
    </tr>
  </table>
</form>

<hr>


<?php    
}
?>
  
<!--
**********************************************************************************************************
Affichage du formulaire de saisie de resultats de chaque licencié
**********************************************************************************************************
-->
<div  class="resultat_licencie">

<form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'modif_resultat_db','date'=>$date),get_permalink()); ?>" method="post" name="saisie_resultat">
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
       pour_4, contre_4, pour_5, contre_5, pour_6, contre_6 from `Résultats`
       where (`Résultats`.`Date` ='$date' and `Résultats`.`Code` = '$code')";
      
    
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