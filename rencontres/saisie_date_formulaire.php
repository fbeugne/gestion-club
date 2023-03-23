

<!--
**********************************************************************************************************
Action lié au formualaire
- Modification d'une date
- Suppression d'une date
- Selection d'une date
**********************************************************************************************************
-->

<?php

  //selection de la date pour saisir les résultats

  $type=htmlspecialchars($_POST['type']);
  $action=htmlspecialchars($_GET['action']);
  
  if (isset($_POST["type_filtre"]))
  {
    $type_filtre=htmlspecialchars($_POST['type_filtre']);
    $_SESSION["type_filtre"] = $type_filtre;
  }
  else if (isset($_SESSION["type_filtre"]))
  {
    $type_filtre = $_SESSION["type_filtre"];
  }
  else
  {
    $type_filtre = "";
  }
  
  $date="";
  $lieu="";
  $dpct=0;
  
  include_once (gestion_club_dir_path() . '/common.php');

  
  $conn_db = new BaseDeDonnesPalet();
  
  //modification d'une date
  if ($action == "modif_date_db" )
  {
  
    $type = htmlspecialchars($_POST['type']);
    $date = htmlspecialchars($_POST['date']);
    $lieu = htmlspecialchars($_POST['lieu']);
    $dpct = htmlspecialchars($_POST['dpct']);
    
    
    $sql="INSERT IGNORE INTO `Dates` SET `Date`='$date'";
    $conn_db->RequeteSQL($sql);

    
    $sql = "update `Dates` set `Type` = '$type' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "update `Dates` set `Lieu` = '$lieu' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "update `Dates` set `Dpct` = '$dpct' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    
  }
  //suppression d'une date
  else if ($action == "delete_date_db" )
  {
    $date = htmlspecialchars($_POST['date']);
    $sql = "delete from `Dates` where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "delete from `Résultats` where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $date="";
  }
  //selection d'une date
  else
  {
    $date = htmlspecialchars($_POST['date']);
    if($date=="")
    {
      $date = htmlspecialchars($_GET['date']);
    }
    
    if ($date != "")
    {
      $sql = "select * from `Dates` where `Date`='$date'";
      $res = $conn_db->RequeteSQL($sql);
      
      if ($res)
      {
        $info_date=$res->fetch_array(MYSQLI_ASSOC);
        
        $type = $info_date['Type'];
        $lieu = $info_date['Lieu'];
        $dpct = $info_date['Dpct'];
        
        $res->free();
      }
    }
  }
  
	$gestion_saison = new GestionSaison();
	$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();
	$annee1 = $gestion_saison->GetAnneeSelectionneePrecedente();
	$annee2 = $gestion_saison->GetAnneeSelectionnee();
  
  if ($type_filtre == "")
  {
    $sql = "select Date, Lieu, Type from Dates
     where (Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01') ORDER BY Dates.`Date` ASC";
  }
  else
  { 
    $sql = "select Date, Lieu, Type from Dates
     where (Dates.`Date` > '$annee1-08-01' and Dates.`Date` < '$annee2-08-01' and Dates.`Type` = '$type_filtre') ORDER BY Dates.`Date` ASC";
  }
  
  $liste_date = $conn_db->RequeteSQL($sql);
  
  
?>


<!--
**********************************************************************************************************
Formulaire de gestion des dates
**********************************************************************************************************
-->


<table>
  <tr>
    
   
    <td>
      Filtrer les dates :
      <form action='<?php echo get_permalink();?>' method="post">
        <select name="type_filtre" onchange="this.form.submit()">
          <option value=""> - Type de competition - </option>
          <option value="Championnat" <?php  if ($type_filtre == "Championnat") echo "selected";?>>Championnat</option>
          <option value="Coupe" <?php  if ($type_filtre == "Coupe") echo "selected";?>>Coupe</option>
          <option value="Entrainement" <?php  if ($type_filtre == "Entrainement") echo "selected";?>>Entrainement</option>
          <option value="Amical" <?php  if ($type_filtre == "Amical") echo "selected";?>>Amical</option>
          <option value="Tournoi" <?php  if ($type_filtre == "Tournoi") echo "selected";?>>Tournoi interne</option>
        </select>
      </form>
    </td>
    
	
    <td>
      Selectionner une date :
      
      
      <form action='<?php echo add_query_arg(array('action'=>'selection_date'),get_permalink());?>' method="post">
        <select name='date' onchange="this.form.submit()">
          
          <option value=""> Nouvelle date </option>
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
                echo $info_date['Date'] . " - " . $info_date['Lieu'] . "</option>";
              }
              $liste_date->free();
            }
          ?>
        </select>
      </form>
      
    </td>
    <td>
     
      <?php
      if ($date != "")
      {
        echo "Modification d'une date pour la saison $saison_selectionnee";
      }
      else
      {
        echo "Ajout d'une date pour la saison $saison_selectionnee";
      }
      ?>
      
      
      <form action='<?php echo add_query_arg(array('action'=>'modif_date_db'),get_permalink());?>' method="post">
        <table>
          <tr>
            <td>Type : </td>
            <td>
              <select name='type' style='width:auto'>
                <option disabled> - Type de competition - </option>
                <option value="Championnat" <?php  if ($type == "Championnat") echo "selected";?>>Championnat</option>
                <option value="Coupe" <?php  if ($type == "Coupe") echo "selected";?>>Coupe</option>
                <option value="Entrainement" <?php  if ($type == "Entrainement") echo "selected";?>>Entrainement</option>
                <option value="Amical" <?php  if ($type == "Amical") echo "selected";?>>Amical</option>
                <option value="Tournoi" <?php  if ($type == "Tournoi") echo "selected";?>>Tournoi interne</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Date : </td>
            <td>
              <input name='date' type="date" value="<?php echo "$date"?>" style='width:auto'><br>
            </td>
          </tr>
          <tr>
            <td>Lieu : </td>
            <td>
              <input name='lieu' type="text" value="<?php echo "$lieu"?>" ><br>
            </td>
          </tr>
          <tr>
            <td>
              <input type="radio" name="dpct" id="dpct" value="0" <?php  if ($dpct != "-1") echo "checked";?> > A domicile
            </td>
            <td>
              <input type="radio" name="dpct" id="dpct" value ="-1" <?php  if ($dpct == "-1") echo "checked";?> > En déplacement
            </td>
          </tr>
          <tr>
            <td><input type='submit' value='Sauvegarder la date'>
            </td>
            <td><input type='submit' value='Supprimer la date' formmethod='post' formaction='<?php echo get_permalink();?>&action=delete_date_db'></td>
          </tr>
        </table>
      </form>
    </td>

  </tr>
</table>

