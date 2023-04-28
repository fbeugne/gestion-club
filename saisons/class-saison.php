<?php

/**************************************************************/
/* Classe de Gestion de la saison selectionnee                */
/**************************************************************/
class GestionSaison
{
  protected $annee_1;
  protected $annee_2;
  protected $saison_courante;
  protected $conn_db;
  
  public function __construct()
  {
    $this->conn_db = new BaseDeDonnesPalet();

    $this->annee_2 = $this->GetAnneeEnCours();
    $this->annee_1 = $this->annee_2 - 1;
    $this->saison_courante = "$this->annee_1/$this->annee_2";
      
    $this->UpdateDonneesSaisonSelectionnee();
  }
  
  protected function UpdateDonneesSaisonSelectionnee()
  {
    $sql = "SELECT `annee` FROM `saison` WHERE `selected` = 1";
    $result = $this->conn_db->RequeteSQL($sql); 
    
    // On fixe une variable globale $saison_courante qui sera utilisée pour les requetes sql
    if ( ($result) && ($info_saison = $result->fetch_row()) )
    {
      $this->annee_1 = $info_saison[0];
      $this->annee_2 = $info_saison[0] + 1;
      $this->saison_courante = "$this->annee_1/$this->annee_2";
    }
    else
    {
      $this->annee_2 = $this->GetAnneeEnCours();
      $this->annee_1 = $this->annee_2 - 1;
      $this->saison_courante = "$this->annee_1/$this->annee_2";
    }
    return $this->saison_courante;
  }

  function SetSaisonSelectionnee($saison)
  {
    # deselectionne toutes les saisons
    $sql = "UPDATE `saison` SET `selected` = '0'";
    $this->conn_db->RequeteSQL($sql); 
    
    # selection la bonne saison
    
    $saison_selectionnee = intval(strtok($saison, '/'));
      
    $sql = "UPDATE `saison` SET `selected` = '1' WHERE `saison`.`annee` = $saison_selectionnee";
    $this->conn_db->RequeteSQL($sql); 
    
    $this->UpdateDonneesSaisonSelectionnee();
    
  }
  
  function GetAnneeEnCours()
  {
    $annee = intval(date('Y'));
    
    // A partir d'avril on commence a préparer la saison suivante
    if (intval(date('m')) > 4)
    {
      $annee = $annee + 1;
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