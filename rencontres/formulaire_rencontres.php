
<?php

  if (array_key_exists('action', $_GET))
  {
    $action=htmlspecialchars($_GET['action']);
  }
  
  include (gestion_club_dir_path() . '/rencontres/saisie_date_formulaire.php');
  
  echo "<hr>";
  
  // une date a été selectionné dans le formulaire précédent
  // il est possible de rentrer les résultats
  if ($date != "")
  {
    include (WP_PLUGIN_DIR . '/gestion-club/rencontres/saisie_resultat_formulaire.php');
  }
?>
