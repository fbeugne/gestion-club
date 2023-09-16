

<!--
**********************************************************************************************************
Action lié au formualaire
- Modification d'une date
- Suppression d'une date
- Selection d'une date
- Mise à jour des informations (score, nb joueurs)
**********************************************************************************************************
-->


<?php

  include_once (gestion_club_dir_path() . '/common.php');

    
  $conn_db = new BaseDeDonnesPalet();


  //selection de la date pour saisir les résultats

  if (array_key_exists('type', $_POST))
  {
    $type=htmlspecialchars($_POST['type']);
  }
  else
  {
    $type="";
  }

  if (array_key_exists('action', $_GET))
  {
    $action=htmlspecialchars($_GET['action']);
  }
  else
  {
    $action="";
  }
  
  
  $date="";
  $lieu="";
  $dpct=0;
  
  //modification d'une date
  if ($action == "modif_date_db" )
  {
  
    $type = htmlspecialchars($_POST['type']);
    $date = htmlspecialchars($_POST['date']);
    $lieu = htmlspecialchars($_POST['lieu']);
    $dpct = htmlspecialchars($_POST['dpct']);
    
    
    $sql="INSERT IGNORE INTO `dates` SET `Date`='$date'";
    $conn_db->RequeteSQL($sql);

    
    $sql = "update `dates` set `Type` = '$type' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "update `dates` set `Lieu` = '$lieu' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "update `dates` set `Dpct` = '$dpct' where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    
  }
  //suppression d'une date
  else if ($action == "delete_date_db" )
  {
    $date = htmlspecialchars($_POST['date']);
    $sql = "delete from `dates` where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $sql = "delete from `resultats` where `Date` = '$date'";
    $conn_db->RequeteSQL($sql);
    $date="";
  }
  
  // sauvegarde des resultats dans la base de données
  else if ($action == "modif_resultat_match_db")
  {
    $date = htmlspecialchars($_GET['date']);
    include (gestion_club_dir_path() . '/rencontres/modifier_resultat_match_db.php');
  }

  //selection d'une date
  else
  {
    
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
  }
  

  if ($date != "")
  {
    $sql = "select * from `dates` where `Date`='$date'";
    $res = $conn_db->RequeteSQL($sql);
    
    if ($res)
    {
      $info_date=$res->fetch_array(MYSQLI_ASSOC);
      
      $type = $info_date['Type'];
      $type_filtre = $type;
      $lieu = $info_date['Lieu'];
      $dpct = $info_date['Dpct'];
      
      $res->free();
    }
  }
  
	$gestion_saison = new GestionSaison();
	$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();
	$annee1 = $gestion_saison->GetAnnee1Selectionnee();
	$annee2 = $gestion_saison->GetAnnee2Selectionnee();
  
  $sql = "select Date, Lieu, Type from dates
     where (dates.`Date` > '$annee1-08-01' and dates.`Date` < '$annee2-08-01') ORDER BY dates.`Date` ASC";

  
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
      Date :
      
      
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
  </tr>
  <tr>
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
            <td><input type='submit' value='Supprimer la date' formmethod='post' formaction='<?php echo add_query_arg(array('action'=>'delete_date_db'),get_permalink());?>'></td>
          </tr>
        </table>
      </form>
    </td>

  </tr>
</table>



<!--
**********************************************************************************************************
Affichage du formulaire de saisie de resultats du match
**********************************************************************************************************
-->

<?php 
if ( ($date != "") && (($type == "Championnat") || ($type == "Coupe") || ($type == "Amical")))
{
  
  $sql = "select Code, NOM, Prenom from licencies
   where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";
  
  $liste_licencies_req = $conn_db->RequeteSQL($sql);
  
  $sql = "select nb_joueurs, nb_adversaires, points_pour, points_contre from  `dates` where (dates.Date = '$date')";
  
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
  
<form action="<?php echo add_query_arg(array('action'=>'modif_resultat_match_db','date'=>$date),get_permalink()); ?>" method="post" name="saisie_resultat">
  <table>
    <tr>
      <td>
        Nb joueurs
        <br>
        <input name='nb_joueurs' type="int" value="<?php echo "$nb_joueurs"?>" style='width:auto'>
      </td>
      <td>
        Nb adversaires
        <br>
        <input name='nb_adversaires' type="int" value="<?php echo "$nb_adversaires"?>" style='width:auto'><br>
      </td>
    </tr>
    <tr>
      <td> 
        Points pour
        <br>
        <input name='points_pour' type="int" value="<?php echo "$points_pour"?>" style='width:auto'>
      </td>
      <td>
        Points contre
        <br>
        <input name='points_contre' type="int" value="<?php echo "$points_contre"?>" style='width:auto'><br>
      </td>
    </tr>
    <tr>
        <td><input type='submit' value='Sauvegarder les résultats du match'></td><td></td>
    </tr>
  </table>
</form>

<hr>


<?php    
}
?>
