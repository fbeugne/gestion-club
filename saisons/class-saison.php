<?php
/**************************************************************/
/* Classe de Gestion de la saison selectionnee                */
/**************************************************************/
class GestionSaison
{
  protected $annee_courante;
  protected $annee_precedente;
  protected $saison_courante;
  
  public function __construct()
  {
    @session_start();
    $this->UpdateDonneesSaisonSelectionnee();
    setcookie('saison', $this->saison_courante); 
  }
  
  protected function UpdateDonneesSaisonSelectionnee()
  {
    // On fixe une variable globale $saison_courante qui sera utilisée pour les requetes sql
    if (isset($_COOKIE['saison']))
    {
      $this->saison_courante = $_COOKIE['saison'];
      $this->annee_precedente = intval(strtok($this->saison_courante, '/'));
      $this->annee_courante = intval(strtok('/'));
    }
    else
    {
      $this->annee_courante = $this->GetAnneeEnCours();
      $this->annee_precedente = $this->annee_courante - 1;
      $this->saison_courante = "$this->annee_precedente/$this->annee_courante";
    }
    return $this->saison_courante;
  }

  function SetSaisonSelectionnee($saison)
  {
    setcookie('saison', $saison); 
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
  
  function GetSaisonEnCours()
  {
    $annee = GetAnneeEnCours();
    
    $annee_prec = $annee - 1;
    return "$annee_prec/$annee";
  }
  
  function GetSaisonSelectionnee()
  {
    return $this->saison_courante;
  }
  
  function GetAnneeSelectionnee()
  {
    return $this->annee_courante;
  }
  
  function GetAnneeSelectionneePrecedente()
  {
    return $this->annee_precedente;
  }
}
?>