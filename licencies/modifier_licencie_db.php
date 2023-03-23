<?php

$code=htmlspecialchars($_GET['id']);

if ($code == "")
{
  $code=htmlspecialchars($_POST['id']);
}

// Recuperation des donnes du formulaire
//$code=htmlspecialchars($_POST['code']);
$nom=strtoupper(htmlspecialchars($_POST['nom']));
$prenom=htmlspecialchars($_POST['prenom']);

$date_naissance=htmlspecialchars($_POST['date_naissance']);

$rue=htmlspecialchars($_POST['rue']);
$CPostal=htmlspecialchars($_POST['CPostal']);
$ville=htmlspecialchars($_POST['ville']);
$email=htmlspecialchars($_POST['email']);
$fixe=htmlspecialchars($_POST['fixe']);
$portable=htmlspecialchars($_POST['portable']);


include_once (gestion_club_dir_path() . '/common.php');


$conn_db = new BaseDeDonnesPalet();


if ($code == "")
{
  $code = $conn_db->GenererTrigramme($prenom, $nom);
  if ($code == false)
  {
    echo "<br>Une erreur est survenue lors de la construction du trigramme<br>";
    exit();
  }
  echo "Le trigramme est $code <br>";
}

$sql="INSERT IGNORE INTO `Licencies` SET `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE `Licencies` SET `NOM` = '$nom' WHERE `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Licencies SET Prenom = '$prenom' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);

$sql="INSERT IGNORE INTO `Naissance` SET `Code`='$code'";
$conn_db->RequeteSQL($sql);

sscanf($date_naissance,"%04d-%02d-%02d",$annee,$mois,$jour);

$sql="UPDATE `Naissance` SET `Année` = '$annee' WHERE `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE `Naissance` SET `Mois` = '$mois' WHERE `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE `Naissance` SET `Jour` = '$jour' WHERE `Code`='$code'";
$conn_db->RequeteSQL($sql);


$sql="INSERT IGNORE INTO `Adresse` SET `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE `Adresse` SET `Rue` = '$rue' WHERE `Code`='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Adresse SET CPostal = '$CPostal' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Adresse SET Ville = '$ville' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Adresse SET email = '$email' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Adresse SET Fixe = '$fixe' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);

$sql="UPDATE Adresse SET Portable = '$portable' WHERE Code='$code'";
$conn_db->RequeteSQL($sql);


// reaffichage du formulaire mis à jour
echo "<i>Base de données mis à jour pour $prenom $nom</i><br><br>";
?>
<br>
