
<?php

include (WPINC . '/gestion-club/common.php');



$gestion_saison = new GestionSaison();

if (htmlspecialchars($_POST['saison']) != "")
{
  $gestion_saison->SetSaisonSelectionnee(htmlspecialchars($_POST['saison']));
}

$saison_courante = $gestion_saison->GetSaisonSelectionnee();
$annee_en_cours = $gestion_saison->GetAnneeEnCours();


echo "<form method='post' action='" . get_permalink() . "'>";
echo "Modifier la saison : ";
echo "<select name='saison' id='saison' size='1' onchange='this.form.submit()'>";
$premiere_annee = 1987;
while ($annee_en_cours > $premiere_annee)
{
  $annee_precedente = $annee_en_cours-1;
  $val = "$annee_precedente/$annee_en_cours";
  $val_print = "$annee_precedente / $annee_en_cours";
  echo "<option value='$val'";
  if ($val == $saison_courante)
  {
    echo "selected";
  }
  echo ">$val_print</option>";
  $annee_en_cours = $annee_precedente;
}

echo "</select>";
echo "</form>";


?>