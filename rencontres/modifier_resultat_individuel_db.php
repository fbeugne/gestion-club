

<?php

$bareme_11=array(
  0 => 0,
  1 => 0,
  2 => 0,
  3 => 1,
  4 => 1,
  5 => 1,
  6 => 2,
  7 => 2,
  8 => 2,
  9 => 3,
  10 => 3,
  11 => 5,
  "presence" => 40
);


$bareme_13=array(
  0 => 0,
  1 => 0,
  2 => 0,
  3 => 1,
  4 => 1,
  5 => 1,
  6 => 2,
  7 => 2,
  8 => 2,
  9 => 3,
  10 => 3,
  11 => 4,
  12 => 4,
  13 => 6,
  "presence" => 40
);

$bareme_tournoi_gagne=array(
  0 => 1000,
  1 => 1001,
  2 => 1002,
  3 => 1003,
  4 => 1004,
  5 => 1005,
  6 => 1006,
  7 => 1007,
  8 => 1008,
  9 => 1009,
  10 => 1010,
  11 => 1011,
  12 => 1012,
  13 => 1001013,
  "presence" => 500
);

$bareme_tournoi_perdu=array(
  0 => 0,
  1 => 1,
  2 => 2,
  3 => 3,
  4 => 4,
  5 => 5,
  6 => 6,
  7 => 7,
  8 => 8,
  9 => 9,
  10 => 10,
  11 => 11,
  12 => 12,
  13 => 13,
  "presence" => 500
);

if ($type == "Coupe")
{
  $bareme = $bareme_13;
  $bareme_gagne = $bareme;
  $bareme_perdu = $bareme;
}
else if ($type == "Tournoi")
{
  $bareme = $bareme_tournoi_gagne;
  $bareme_gagne = $bareme_tournoi_gagne;
  $bareme_perdu = $bareme_tournoi_perdu;
}
else
{
  $bareme = $bareme_11;
  $bareme_gagne = $bareme;
  $bareme_perdu = $bareme;
}

// Une presence vaut 25 points en entraineemnt
if ($type == "Entrainement")
{
  $bareme["presence"] = 25;
}

$nb_joueurs=0;


while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
{
  $code = $row['Code'];
  $nom = $row['NOM'];
  $prenom = $row['Prenom'];
  $total=0;
  
  if (array_key_exists('presence_'.$code, $_POST))
  {
    $present=htmlspecialchars($_POST['presence_'.$code]);
  }
  else
  {
    $present=0;
  }

  //recuperer l'id lié au resultat
  $sql="select Id from `resultats`
    where (`resultats`.`Date` ='" . $date . "' and `resultats`.`Code` = '" . $code . "')";
  $res_id=$conn_db->RequeteSQL($sql);
     
  if ($present==1)
  {
    $nb_joueurs = $nb_joueurs + 1;
    $total=$bareme["presence"];
    if (($res_id == false)  || ($res_id->num_rows==0))
    {
      $sql_insert="INSERT INTO `resultats` (`Date`, `Code`) VALUES('" . $date . "', '" . $code . "')";
      
      $conn_db->RequeteSQL($sql_insert);
      
      
      $res_id=$conn_db->RequeteSQL($sql);
    }
    
    if (($res_id == false)  || ($res_id->num_rows==0))
    {
      echo "Erreur lors de l'insertion d'un resultat dans la base de données";
      exit();
    }
    $id = $res_id->fetch_array(MYSQLI_ASSOC)['Id'];
     

    //Enregistrement des resultats de chaque partie
    for ($num_partie=0; $num_partie<$nb_partie; $num_partie++)
    {
      $gagne=htmlspecialchars($_POST[$num_partie."_gagne_".$code]);
      $perdu=htmlspecialchars($_POST[$num_partie."_perdu_".$code]);
      
      if (($gagne != -1) && ($perdu != -1))
      {
        $total = $total + $bareme_gagne[$gagne] - $bareme_perdu[$perdu];
        $id_partie=$num_partie+1;
        $sql = "update `resultats` set `pour_$id_partie` = '$gagne' where `Id` = '$id'";
        $conn_db->RequeteSQL($sql);
        $sql = "update `resultats` set `contre_$id_partie` = '$perdu' where `Id` = '$id'";
        $conn_db->RequeteSQL($sql);
      }
    }
    
    //Enregistrement du score du match
    $sql = "update `resultats` set `Resultat` = '$total' where `Id` = '$id'";
    $conn_db->RequeteSQL($sql);
    
    echo "<br>";
  }
  //Suppression d'un resultat
  else if (($res_id) && ($res_id->num_rows>0))
  {
    $id = $res_id->fetch_array(MYSQLI_ASSOC)['Id'];
    $sql = "DELETE FROM `resultats` WHERE `resultats`.`Id` = '$id'";
    $conn_db->RequeteSQL($sql);
  }
}


// Sauvegarde du nombre de joueurs a l'entrainement ou tournoi interne
// Il n'y a pas de formulaire pour saisir cette donnees. Elle est deduite a partir du formulaire des resultats individuel
if (($type == "Entrainement") || ($type == "Tournoi"))
{
  $sql = "update `dates` set `nb_joueurs` = '$nb_joueurs' where `Date` = '$date'";
  $conn_db->RequeteSQL($sql); 
}

?>