<?php
include_once (gestion_club_dir_path() . '/common.php');
include_once (gestion_club_dir_path() . '/licencies/modifier_licencie_db.php');

if (array_key_exists('action', $_GET))
{
  $action=htmlspecialchars($_GET['action']);
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



$conn_db = new BaseDeDonnesPalet();

?>

<p>
<form method="post" action="<?php echo add_query_arg(array('action'=>'Sélectionner'),get_permalink());?>">
<select name='id' id='id' onchange="this.form.submit()">
  <option value="">Sélectionner un licencié</option>
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
</form>

</p>


<?php

if ($id != "")
{
  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  if ($action == '[ajouter_db]') 
  {
    $gestion_saison->UpdateBdSaisonSelectionnee();
    $sql="UPDATE licencies SET `$saison_selectionnee` = 'OUI' WHERE Code='$id'";
    $conn_db->RequeteSQL($sql);
  } 
  else if ($action == '[supprimer_db]') 
  {
    $gestion_saison->UpdateBdSaisonSelectionnee();
    $sql="UPDATE licencies SET `$saison_selectionnee` = 'non' WHERE Code='$id'";
    $conn_db->RequeteSQL($sql);
  }
  else if ($action=="[modifier_db]")
  {
    
    // Recuperation des donnes du formulaire
    $code=$id;
    $nom=strtoupper(htmlspecialchars($_POST['nom']));
    $prenom=htmlspecialchars($_POST['prenom']);
    $date_naissance=htmlspecialchars($_POST['date_naissance']);
    $rue=htmlspecialchars($_POST['rue']);
    $CPostal=htmlspecialchars($_POST['CPostal']);
    $ville=htmlspecialchars($_POST['ville']);
    $email=htmlspecialchars($_POST['email']);
    $fixe=htmlspecialchars($_POST['fixe']);
    $portable=htmlspecialchars($_POST['portable']);

    modifier_licencie_db( $code,
                          $nom,
                          $prenom,
                          $date_naissance,
                          $rue,
                          $CPostal,
                          $ville,
                          $email,
                          $fixe,
                          $portable );
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

<br>
<table>
  <tr>
    <td>
      <form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'[ajouter_db]'),get_permalink()); ?>" method="post">
          <input type='submit'  value='Ajouter le licencié à la saison'/>
      </form>
    </td>
    <td>
      <form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'[supprimer_db]'),get_permalink()); ?>" method="post">
          <input type='submit' value='Supprimer le licencié de la saison' />
      </form>
    </td>
  </tr>
</table>

<br>

<hr>
<form action="<?php echo add_query_arg(array('id'=>$id,'action'=>'[modifier_db]'),get_permalink()); ?>" method="post">
    <table>
        <tr>
            <td>Code : </td><td><input type="text" name="code" value="<?php echo $result_licencies['Code']; ?>" readonly/></td>
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
    </table>
    <input type="submit" value="Mettre à jour"/>
</form>


<?php
}
?>