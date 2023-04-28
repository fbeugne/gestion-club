
<?php

if (array_key_exists('action', $_GET))
{
  $action=htmlspecialchars($_GET['action']);
}
else if (array_key_exists('action', $_POST))
{
  $action=htmlspecialchars($_POST['action']);
}
else
{
  $action="";
}

if (array_key_exists('id', $_GET))
{
  $id=htmlspecialchars($_GET['id']);
}
else if (array_key_exists('id', $_POST))
{
  $id=htmlspecialchars($_POST['id']);
}
else
{
  $id="";
}

include_once (gestion_club_dir_path() . '/common.php');


$conn_db = new BaseDeDonnesPalet();

?>

<p>
<form method="post" action="<?php echo get_permalink() ?>">
Sélectionner un licencié : 
<select name='id' id='id'>

<?php
$sql = "select Code, NOM, Prenom from licencies ORDER BY `licencies`.`NOM` ASC";
$result_req = $conn_db->RequeteSQL($sql);
while($info_licencies=$result_req->fetch_array(MYSQLI_ASSOC))
{
  if($info_licencies['Code'] == $id)
  {
    echo "<option value='" . $info_licencies["Code"] . "' selected>";
  }
  else
  {
    echo "<option value='" . $info_licencies["Code"] . "'>";
  }
  echo $info_licencies['NOM'] . " " . $info_licencies['Prenom'];
  echo "</option>";
}
?>

</select>
<input type='submit' value='Sélectionner'/>
<input type='submit' name='action' value='Ajouter' />
<input type='submit' name='action' value='Supprimer' />
</form>
</p>


<?php

if ($id != "")
{
  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  if ($action == 'Ajouter') 
  {
    $sql="UPDATE licencies SET `$saison_selectionnee` = 'OUI' WHERE Code='$id'";
    $conn_db->RequeteSQL($sql);
  } 
  else if ($action == 'Supprimer') 
  {
    $sql="UPDATE licencies SET `$saison_selectionnee` = null WHERE Code='$id'";
    $conn_db->RequeteSQL($sql);
  }   
  else if ($action=="[modifier_db]")
  {
    include_once (gestion_club_dir_path() . '/licencies/modifier_licencie_db.php');
  }
  else 
  {
    //rien a faire
  }
}
?>


<?php
if ($id != "")
{
  
  $sql = "select * from licencies where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_licencies=$result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
  $sql = "select * from adresse where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_adresse = $result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
  $sql = "select * from naissance where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_naissance = $result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
?>

<hr>
<form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'[modifier_db]'),get_permalink()); ?>" method="post">
    <table>
        <tr>
            <td>Code : </td><td><input type="text" name="code" value="<?php echo $result_licencies['Code']; ?>"/></td>
        </tr>
        <tr>
            <td>Nom : </td><td><input type="text" name="nom" value="<?php echo $result_licencies['NOM']; ?>"/></td>
        </tr>
        <tr>
            <td>Prénom : </td><td><input type="text" name="prenom" value="<?php echo $result_licencies['Prenom']; ?>"/></td>
        </tr>
        <tr>
            <td>Date de naissance : </td><td><input type="date" name="date_naissance" value="<?php echo sprintf("%04d-%02d-%02d", $result_naissance['Annee'], $result_naissance['Mois'], $result_naissance['Jour']); ?>" /></td>
        </tr>
        <tr>
            <td>Rue : </td><td><input type="text" name="rue" value="<?php echo $result_adresse['Rue']; ?>" /></td>
        </tr>
        <tr>
            <td>Code postale : </td><td><input type="number" name="CPostal" value="<?php echo $result_adresse['CPostal']; ?>" /></td>
        </tr>
        <tr>
            <td>Ville : </td><td><input type="text" name="ville" value="<?php echo $result_adresse['Ville']; ?>" /></td>
        </tr>
        <tr>
            <td>email : </td><td><input type="email" name="email" value="<?php echo $result_adresse['email']; ?>" /></td>
        </tr>
        <tr>
            <td>Fixe : </td><td><input type="tel" name="fixe" value="<?php echo $result_adresse['Fixe']; ?>" /></td>
        </tr>
        <tr>
            <td>Portable : </td><td><input type="tel" name="portable" value="<?php echo $result_adresse['Portable']; ?>" /></td>
        </tr>
        <tr>
            <td><input type="submit" value="Mettre à jour"/></td><td></td>
        </tr>
    </table>
</form>


<?php
}
?>