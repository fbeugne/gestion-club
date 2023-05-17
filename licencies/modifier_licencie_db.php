<?php

function modifier_licencie_db($code,
                              $nom,
                              $prenom,
                              $date_naissance,
                              $rue,
                              $CPostal,
                              $ville,
                              $email,
                              $fixe,
                              $portable)
{

  include_once (gestion_club_dir_path() . '/common.php');
  $conn_db = new BaseDeDonnesPalet();


  if ($code == "")
  {
    $code = $conn_db->GenererTrigramme($prenom, $nom);
    if ($code == false)
    {
      die ("Une erreur est survenue lors de la construction du trigramme");
    }
  }

  $sql="INSERT IGNORE INTO `licencies` SET `Code`='$code'";
  $conn_db->RequeteSQL($sql);
  $sql="INSERT IGNORE INTO `naissance` SET `Code`='$code'";
  $conn_db->RequeteSQL($sql);
  $sql="INSERT IGNORE INTO `adresse` SET `Code`='$code'";
  $conn_db->RequeteSQL($sql);
  $sql="INSERT IGNORE INTO `certificats` SET `Code`='$code'";
  $conn_db->RequeteSQL($sql);


  $sql="UPDATE `licencies` SET `NOM` = '$nom' WHERE `Code`='$code'";
  $conn_db->RequeteSQL($sql);

  $sql="UPDATE licencies SET Prenom = '$prenom' WHERE Code='$code'";
  $conn_db->RequeteSQL($sql);


  if($date_naissance != "")
  {
    sscanf($date_naissance,"%04d-%02d-%02d",$annee,$mois,$jour);
  
    $sql="UPDATE `naissance` SET `Annee` = '$annee' WHERE `Code`='$code'";
    $conn_db->RequeteSQL($sql);
  
    $sql="UPDATE `naissance` SET `Mois` = '$mois' WHERE `Code`='$code'";
    $conn_db->RequeteSQL($sql);
  
    $sql="UPDATE `naissance` SET `Jour` = '$jour' WHERE `Code`='$code'";
    $conn_db->RequeteSQL($sql);
  }



  if($rue != "")
  {
    $sql="UPDATE `adresse` SET `Rue` = '$rue' WHERE `Code`='$code'";
    $conn_db->RequeteSQL($sql);
  }

  if($CPostal != "")
  {
    $sql="UPDATE adresse SET CPostal = '$CPostal' WHERE Code='$code'";
    $conn_db->RequeteSQL($sql);
  }

  if($ville != "")
  {
    $sql="UPDATE adresse SET Ville = '$ville' WHERE Code='$code'";
    $conn_db->RequeteSQL($sql);
  }

  if($email != "")
  {
    $sql="UPDATE adresse SET email = '$email' WHERE Code='$code'";
    $conn_db->RequeteSQL($sql);
  }

  if($fixe != "")
  {
    $sql="UPDATE adresse SET Fixe = '$fixe' WHERE Code='$code'";
    $conn_db->RequeteSQL($sql);
  }

  if($portable != "")
  {
    $sql="UPDATE adresse SET Portable = '$portable' WHERE Code='$code'";
    $conn_db->RequeteSQL($sql);
  }

  echo "<i>Base de données mis à jour pour $prenom $nom ($code)</i><br><br>";

}

?>
