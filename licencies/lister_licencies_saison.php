
<?php

$action=htmlspecialchars($_GET['action']);

if ($action == "[modifier]")
{
  include (WPINC . '/gestion-club/licencies/modifier_licencie_formulaire.php');
}
else
{
  if ($action == "[modifier_db]")
  {
    include (WPINC . '/gestion-club/licencies/modifier_licencie_db.php');
  }
  if ($action == "[supprimer]")
  {
    include (WPINC . '/gestion-club/licencies/supprimer_licencie_db.php');
  }
  
  include_once (WPINC . '/gestion-club/common.php');
  
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

