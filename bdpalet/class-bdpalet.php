
<?php


include_once (gestion_club_dir_path() . '/bdpalet/class-config-bdpalet.php');

/**************************************************************/
/* Classe de Gestion de la base de donnees                    */
/**************************************************************/
class BaseDeDonnesPalet
{
  
  protected $conn_db;
  protected $saison_selectionnee; 
  
	// Connexion à la base de donnees
  public function __construct()
  {
    $config_bd = new ConfigBaseDeDonnesPalet();
    $servername = $config_bd->config_bdpalet_get_servername();
    $port = $config_bd->config_bdpalet_get_port();
    $dbname = $config_bd->config_bdpalet_get_dbname();
    $username = $config_bd->config_bdpalet_get_username();
    $password = $config_bd->config_bdpalet_get_password();
    
    // Create connection
    $this->conn_db = new mysqli( $servername, $username, $password, $dbname, $port );

    // Check connection
    if ($this->conn_db->connect_error) {
      die("Connection failed: " . $this->conn_db->connect_error);
    }
    $this->conn_db->query("SET NAMES utf8 COLLATE utf8_unicode_ci");

  }


	// Deconnexion à la base de donnees
  public function __destruct()
  {
    $this->conn_db->close();
  }

  public function RequeteSQL($sql_req)
  {
    $result = $this->conn_db->query($sql_req);
    if ($result == false)
    {
      die("Erreur " . $this->conn_db->error . "sur la requete : " . $sql_req);
    }
    return $result;
  }
  
  public function AfficherTable ( $table_description, $sql_fromreq)
  {
    $liste_champs = "";

    foreach ($table_description as $colonne)
    {
      foreach ($colonne as $champ)
      {
        if (($champ[0] != ' ') && ($champ != 'num_ligne'))
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

    $num_ligne=0;
    echo "<table>";
    // printing table headers
    reset($table_description);
    while (current($table_description))
    {
      echo "<th>   " . key($table_description) . "</th>";
      next($table_description);
    }

    $result = $this->RequeteSQL($sql);

    while($array_res = $result->fetch_row())
    {
      $num_ligne+=1;
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
          else if ($champ=='num_ligne')
          {
            echo "$num_ligne";
          }
          else
          {
            // on affiche le resultat de la requete sql
            echo $array_res[$indice];
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
        $req = $this->conn_db->query("SELECT Code FROM licencies WHERE Code = '" . $ret_code . "'");
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

