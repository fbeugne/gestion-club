<?php

/**************************************************************/
/* Classe de Gestion de la saison selectionnee                */
/**************************************************************/
class GestionSaison
{
  protected $annee_1;
  protected $annee_2;
  protected $saison_courante;
  
  public function __construct()
  {
    if (isset( $_COOKIE['coookie-saison-selectionnee'] ))
    {
      $anne_selectionnee = intval(strtok($_COOKIE['coookie-saison-selectionnee'], '/'));
      $this->annee_1 = $anne_selectionnee;
      $this->annee_2 = $this->annee_1 + 1;
      $this->saison_courante = "$this->annee_1/$this->annee_2";
    }
    else
    {
      $this->annee_1 = $this->GetAnneeEnCours();
      $this->annee_2 = $this->annee_1 + 1;
      $this->saison_courante = "$this->annee_1/$this->annee_2";
    }  
  }
  

  function UpdateBdSaisonSelectionnee()
  {
    $conn_db = new BaseDeDonnesPalet();


    # ajout de la saison dans la table des saisons si elle n'existe pas
    $sql = "INSERT IGNORE  INTO `saison` (`annee`, `selected`) VALUES ('$this->annee_1', '0')";
    $conn_db->RequeteSQL($sql); 
  
    # ajout de la saison dans la table des licenciés si elle n'existe pas
    $sql = "SELECT * FROM `licencies` LIMIT 1";
    $result = $conn_db->RequeteSQL($sql); 

    if ($result)
    {
      if (array_key_exists($this->saison_courante, $result->fetch_array()) == false)
      {
        $sql = "ALTER TABLE `licencies` ADD `$this->saison_courante` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non'";
        $conn_db->RequeteSQL($sql); 
      }
    }
  }
  
  function GetAnneeEnCours()
  {
    $annee = intval(date('Y'));
    
    // A partir de juin on commence a préparer la saison suivante
    if (intval(date('m')) < 6)
    {
      $annee = $annee - 1;
    }
    return $annee;
  }
  
  function GetSaisonSelectionnee()
  {
    return $this->saison_courante;
  }
  
  function GetAnnee1Selectionnee()
  {
    return $this->annee_1;
  }
  
  function GetAnnee2Selectionnee()
  {
    return $this->annee_2;
  }
}
?>