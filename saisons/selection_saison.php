
<?php


include (gestion_club_dir_path() . '/common.php');

$gestion_saison = new GestionSaison();

$saison = "";
if (array_key_exists('saison', $_POST))
{
  $saison=htmlspecialchars($_POST['saison']);
}
if ($saison != "")
{
  $gestion_saison->SetSaisonSelectionnee($saison);
}
$saison_courante = $gestion_saison->GetSaisonSelectionnee();
$annee_en_cours = $gestion_saison->GetAnneeEnCours();

?>


<form action="<?php get_permalink(); ?>" method="post">
<select name='saison' id='saison'>

<?php
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
?>

</select>

<input type='submit' value='OK'/>
</form>
