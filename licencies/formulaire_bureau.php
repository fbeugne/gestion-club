<?php

include (gestion_club_dir_path() . '/common.php');




$gestion_saison = new GestionSaison();
$saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

echo "Composition du bureau pour la saison $saison_selectionnee";
echo "<br>";
echo "<br>";

$ListeRole=array(
"",
"Président",
"Vice-Président",
"Secrétaire",
"Vice-Secrétaire",
"Trésorier",
"Vice-Trésorier",
"Bureau");


$conn_db = new BaseDeDonnesPalet();


$sql = "select Code, NOM, Prenom, `$saison_selectionnee` from licencies
where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";

$liste_licencies_req = $conn_db->RequeteSQL($sql);

if (array_key_exists('action', $_GET))
{
  $action=htmlspecialchars($_GET['action']);
  if ($action == "modif_bureau_db")
  {
    $liste_licencies_req->data_seek(0);
    // printing table rows
    while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
    {
        $code = $row['Code'];
        
        if (array_key_exists('role_'.$code, $_POST))
        {
            $newRole=htmlspecialchars($_POST["role_$code"]);
            if($newRole!="")
            {
                $sql="UPDATE `licencies` SET `$saison_selectionnee` = '$newRole' WHERE `Code`='$code'";
                $conn_db->RequeteSQL($sql);
            }
            else
            {
                $sql="UPDATE `licencies` SET `$saison_selectionnee` = 'OUI' WHERE `Code`='$code'";
                $conn_db->RequeteSQL($sql);
            }
        }
    }
    
    $liste_licencies_req->free();

    /* on met à jour la requete pour vérifier la mise à jour de la BD*/
    $sql = "select Code, NOM, Prenom, `$saison_selectionnee` from licencies
    where (licencies.`$saison_selectionnee` !='non' and licencies.`$saison_selectionnee` is not null) ORDER BY `licencies`.`NOM` ASC, `licencies`.Prenom ASC";
    $liste_licencies_req = $conn_db->RequeteSQL($sql);
  }
}


?>

<!--
**********************************************************************************************************
Affichage du formulaire de rôle des licenciés dans le bureau
**********************************************************************************************************
-->
<form method="post" action="<?php echo add_query_arg(array('action'=>'modif_bureau_db'),get_permalink());?>">
  <table>
<?php

    $liste_licencies_req->data_seek(0);
    // printing table rows
    while($row = $liste_licencies_req->fetch_array(MYSQLI_ASSOC))
    {
        $code = $row['Code'];
        $nom = $row['NOM'];
        $prenom = $row['Prenom'];
        $role = $row[$saison_selectionnee];
        
        echo "<tr>";
        echo "<td>$nom $prenom</td>";
        
        echo "<td>";
        echo "<select name='role_" . $code . "' id='role_" . $code . "'>";
        
        foreach ($ListeRole as $newRole)
        {
          if ($role==$newRole)
          {
            echo "<option value='$newRole', selected>$newRole</option>";
          }
          else
          {
  		    echo "<option value='$newRole'>$newRole</option>";
          }
        }
  		echo "</select>";
        echo"</td>";
        echo "</tr>";
    }
?>
  </table>
  
  <input type="submit" value="Mettre à jour le bureau"/>
</form>
</div>

