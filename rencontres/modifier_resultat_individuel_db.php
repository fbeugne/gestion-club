

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
    
    
    if ($type == "Coupe")
    {
      $bareme = $bareme_13;
    }
    else
    {
      $bareme = $bareme_11;
    }
    
    // Une presence vaut 25 points en entraineemnt
    if ($type == "Entrainement")
    {
      $bareme["presence"] = 25;
    }

		// printing table rows
		while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
		{
		  $code = $row['Code'];
		  $nom = $row['NOM'];
		  $prenom = $row['Prenom'];
      $total=0;
      
      $present=htmlspecialchars($_POST['presence_'.$code]);

      //recuperer l'id lié au resultat
      $sql="select Id from `Résultats`
        where (`Résultats`.`Date` ='" . $date . "' and `Résultats`.`Code` = '" . $code . "')";
      $res_id=$conn_db->RequeteSQL($sql);
         
      if ($present==1)
      {
        $total=40;
        if (($res_id == false)  || ($res_id->num_rows==0))
        {
          $sql_insert="INSERT INTO `Résultats` (`Date`, `Code`) VALUES('" . $date . "', '" . $code . "')";
          
          $conn_db->RequeteSQL($sql_insert);
          
          
          $res_id=$conn_db->RequeteSQL($sql);
        }
        
        if (($res_id == false)  || ($res_id->num_rows==0))
        {
          echo "Erreur lors de l'insertion d'un resultat dans la base de données";
          exit();
        }
        $id = $res_id->fetch_array(MYSQLI_ASSOC)['Id'];
                  
        for ($num_partie=0; $num_partie<$nb_partie; $num_partie++)
        {
          $gagne=htmlspecialchars($_POST[$num_partie."_gagne_".$code]);
          $perdu=htmlspecialchars($_POST[$num_partie."_perdu_".$code]);
          
          if (($gagne != -1) && ($perdu != -1))
          {
            $total = $total + $bareme[$gagne] - $bareme[$perdu];
            $id_partie=$num_partie+1;
            $sql = "update `Résultats` set `pour_$id_partie` = '$gagne' where `Id` = '$id'";
            $conn_db->RequeteSQL($sql);
            $sql = "update `Résultats` set `contre_$id_partie` = '$perdu' where `Id` = '$id'";
            $conn_db->RequeteSQL($sql);
          }
        }
        
        $sql = "update `Résultats` set `Résultat` = '$total' where `Id` = '$id'";
        $conn_db->RequeteSQL($sql);
        
        echo "<br>";
      }
      else if (($res_id) && ($res_id->num_rows>0))
      {
        $id = $res_id->fetch_array(MYSQLI_ASSOC)['Id'];
        $sql = "DELETE FROM `Résultats` WHERE `Résultats`.`Id` = '$id'";
        $conn_db->RequeteSQL($sql);
      }
    }

?>