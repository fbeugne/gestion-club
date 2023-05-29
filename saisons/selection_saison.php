
<?php


include (gestion_club_dir_path() . '/common.php');

$gestion_saison = new GestionSaison();

$annee_1_selectionnee = $gestion_saison->GetAnnee1Selectionnee();
$annee_1 = $gestion_saison->GetAnneeEnCours();

?>


<form>
<select name='saison' id='saison' onChange='set_coookie_saison_selectionnee(this)'>

<?php
$premiere_annee = 1987;
while ($annee_1 >= $premiere_annee)
{
  $annee_2 = $annee_1 + 1;
  $val = "$annee_1/$annee_2";
  $val_print = "$annee_1 / $annee_2";
  echo "<option value='$val'";
  if ($annee_1 == $annee_1_selectionnee)
  {
    echo " selected";
  }
  echo ">$val_print</option>";
  $annee_1 = $annee_1 - 1;
}
?>

</select>
</form>



<script>
  function set_coookie_saison_selectionnee(element)
  {
    document.cookie = "coookie-saison-selectionnee=" + element.value + ";path=/;";
    location.reload();
  }
</script>