
<?php

$action=htmlspecialchars($_GET['action']);

if ($action == "")
{
  $action=htmlspecialchars($_POST['action']);
}
if ($action=="[modifier_db]")
{
  include_once (WPINC . '/gestion-club/licencies/modifier_licencie_db.php');
}


$id=htmlspecialchars($_GET['id']);

if ($id == "")
{
  $id=htmlspecialchars($_POST['id']);
}

include_once (WPINC . '/gestion-club/common.php');


$conn_db = new BaseDeDonnesPalet();

// Affichage d'une liste déroulante pour ajouter un licencié pour la saison courante

$sql = "select Code, NOM, Prenom from Licencies ORDER BY `Licencies`.`NOM` ASC";
$result_req = $conn_db->RequeteSQL($sql);

echo "<p>";
echo "<form method='post' action='" . get_permalink() . "'>";
echo "Sélectionner un licencié existant à la saison : ";
echo "<select name='id' id='id'>";

while($info_licencies=$result_req->fetch_array(MYSQLI_ASSOC))
{
  echo "<option value='" . $info_licencies["Code"] . "'>";
  echo $info_licencies['NOM'] . " " . $info_licencies['Prenom'] . "</option>";
}

echo "</select>";
echo "<br>";
echo  "<input type='submit' value='OK'/>";
echo "</form>";


echo "</p>";
  

if ($id != "")
{
  $sql = "select * from Licencies where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_licencies=$result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
  $sql = "select * from Adresse where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_adresse = $result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
  $sql = "select * from Naissance where Code = '$id'";
  $result_req = $conn_db->RequeteSQL($sql);
  $result_naissance = $result_req->fetch_array(MYSQLI_ASSOC);
  $result_req->free();
  
}

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
            <td>Numéro de licence : </td><td><input type="number" name="num_licence" value="<?php echo $result_licencies['N_licence']; ?>"/></td>
        </tr>
        <tr>
            <td>Date de naissance : </td><td><input type="date" name="date_naissance" value="<?php echo sprintf("%04d-%02d-%02d", $result_naissance['Année'], $result_naissance['Mois'], $result_naissance['Jour']); ?>" /></td>
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
            <td><input type="submit" value="Ajouter ce licencié à la saison"/></td><td></td>
        </tr>
    </table>
</form>