
<?php

global $saison_selectionnee;


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
    $this->UpdateDonneesSaisonSelectionnee();
  }
  
  protected function UpdateDonneesSaisonSelectionnee()
  {
    session_start();
    
    // On fixe une variable globale $saison_courante qui sera utilisée pour les requetes sql
    if (isset($_SESSION["saison"]))
    {
      $this->saison_courante = $_SESSION["saison"];
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
    session_start();
    $_SESSION["saison"] = $saison;
    
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
    $annee = intval(date('Y'));
    
    // A partir d'avril on commence a préparer la saison suivante
    if (intval(date('m')) > 4)
    {
      $annee = $annee + 1;
    }
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


/**************************************************************/
/* Classe de Gestion de la base de donnees                    */
/**************************************************************/
class BaseDeDonnesPalet
{
  
	protected $servername = "localhost";
	protected $username = "root";
	protected $password = "admin";
	protected $dbname = "bdpalet";
  
  protected $conn_db;
  protected $saison_selectionnee; 
  
	// Connexion à la base de donnees
  public function __construct()
  {
    // Create connection
    $this->conn_db = new mysqli( $this->servername, $this->username, $this->password, $this->dbname );

    // Check connection
    if ($this->conn_db->connect_error) {
      die("Connection failed: " . $this->$conn_db->connect_error);
    }
  }


	// Deconnexion à la base de donnees
  public function __destruct()
  {
    $this->conn_db->close();
  }

  public function RequeteSQL($sql_req)
  {
    return $this->conn_db->query($sql_req);
  }
  
  public function AfficherTable ( $table_description, $sql_fromreq)
  {
    $liste_champs = "";

    foreach ($table_description as $colonne)
    {
      foreach ($colonne as $champ)
      {
        if ($champ[0]!= ' ')
        {
          if ($liste_champs != "")
          {
            $liste_champs = "$liste_champs, $champ" ;
          }
          else
          {
            $liste_champs = "$champ" ; 
          }
        }
      }
    }

    $gestion_saison = new GestionSaison();
    $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

    $sql = "select $liste_champs from $sql_fromreq";

    $result = $this->RequeteSQL($sql);

    
    echo "<table>";
    // printing table headers
    reset($table_description);
    while (current($table_description))
    {
      echo "<th>   " . key($table_description) . "</th>";
      next($table_description);
    }

    while($array_res = $result->fetch_row())
    {
      $indice=0;
      echo "<tr>";
      reset($table_description);
      while ($colonne = current($table_description))
      {
        echo "<td>";
        
        foreach ($colonne as $champ)
        {
          if ($champ[0]==' ')
          {
            echo $champ;
          }
          else
          {        
            // indique une action a effectuer
            if (key($table_description)[0] == '[')
            {
              echo "<a href=" . get_permalink() . "&id=" . $array_res[$indice] . "&action=" . key($table_description) . ">". key($table_description) ."</a>";
            }
            // sinon on affiche le resultat de la requete sql
            else
            {
              echo $array_res[$indice];
            }
            $indice=$indice+1; 
          }
        } 
        echo "</td>";

        next($table_description);        
      }
      echo "</tr>";
    }


    echo "</table>";
  }

  
  public function GenererTrigramme( $arg_prenom, $arg_nom)
  {
    // Il s'agit d'un ajout de licencié
    if  (($arg_nom == "") || ($arg_prenom == ""))
    {
      echo "Indiquer au moins le nom et le prénom <br>";
      $ret_code=false;
    }
    else
    {
      // Le code est le trigramme de la personne
      $ret_code = $arg_prenom[0] . "--";
      $prenom2 = strstr($arg_prenom, '-');
      if ($prenom2 != false)
      {
        $ret_code[1] = $prenom2[1];
        $index_nom = 0;
      }
      else
      {
        $ret_code[1] = $arg_nom[0];
        $index_nom = 1;
      }
      
      $ret_code = strtoupper($ret_code);
      
      // Si le trigramme existe déja, on prend la 3ème lettre du nom
      $tri_existe = true;
      while (($index_nom < strlen($arg_nom)) && ($tri_existe == true))
      {
        $ret_code[2] = strtoupper($arg_nom)[$index_nom];
        $req = $this->conn_db->RequeteSQL("SELECT Code FROM Licencies WHERE Code = '" . $ret_code . "'");
        
        if (($req == false) || ($req->num_rows == 0)) {
          $tri_existe = false;
        }
        else
        {
          $index_nom = $index_nom + 1;
        }
        
        $req->free();
      }
      
      if ($index_nom >= strlen($arg_nom))
      {
        echo "Impossible de generer un trigramme unique pour $arg_prenom $arg_nom <br>";
        $ret_code=false;
      }

    }
      
    return $ret_code;
  }
}


?>

