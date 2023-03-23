
<?php

$action=htmlspecialchars($_GET['action']);

if ($action == "[modifier]")
{
  include (gestion_club_dir_path() . '/licencies/modifier_licencie_formulaire.php');
}
else
{
  if ($action == "[modifier_db]")
  {
    include (gestion_club_dir_path() . '/licencies/modifier_licencie_db.php');
  }
  if ($action == "[supprimer]")
  {
    include (gestion_club_dir_path() . '/licencies/supprimer_licencie_db.php');
  }
  
  include_once (gestion_club_dir_path() . '/common.php');
  
  $gestion_saison = new GestionSaison();
  $saison_selectionnee = $gestion_saison->GetSaisonSelectionnee();

  $conn_db = new BaseDeDonnesPalet();
  
  $sql = "Licencies where (Licencies.`$saison_selectionnee` !='non' and Licencies.`$saison_selectionnee` is not null) ORDER BY `Licencies`.`NOM` ASC, `Licencies`.Prenom ASC";
  
  $DonneesAAfficher = array(
    "NOM" => array("NOM"),
    "Prénom" => array("Prenom"),
    "[modifier]" => array("Code"),
    "[supprimer]" => array("Code"),
  );
  
  $conn_db->AfficherTable($DonneesAAfficher, $sql);
  
  // si la saison selectionner correspond à la saison courante
  // proposition de rajouter un licencié à la base de donnée.
	$gestion_saison = new GestionSaison();
  if ( $gestion_saison->GetSaisonEnCours() == $saison_selectionnee)
  {
    echo '<a href="' . get_permalink() . '&action=[modifier]">Ajout d\'un lincencié pour la saison ' . $saison_selectionnee . '</a>';
  }
}

?>

