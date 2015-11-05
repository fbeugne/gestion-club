
<?php
include (WPINC . '/gestion-club/rencontres/lib_classement.php');

$match_amical = htmlspecialchars($_POST['match_amical']);
$match_champ = htmlspecialchars($_POST['match_champ']);
$match_coupe = htmlspecialchars($_POST['match_coupe']);
$match_entrainement = htmlspecialchars($_POST['match_entrainement']);
$classement = htmlspecialchars($_POST['classement']);
?>

<form action='<?php echo get_permalink();?>&action=filter_match' method="post">
  <table>
    <tr>
      <td>Filtre des matchs : </td>
      <td><input type='checkbox' name='match_amical' id='match_amical' value='1' <?php if ($match_amical) { echo " checked"; } ?>/> Amical</td>
      <td><input type='checkbox' name='match_champ' id='match_champ' value='1' <?php if ($match_champ) { echo " checked"; } ?>/> Championnat</td>
      <td><input type='checkbox' name='match_coupe' id='match_coupe' value='1' <?php if ($match_coupe) { echo " checked"; } ?>/> Coupe</td>
      <td><input type='checkbox' name='match_entrainement' id='match_entrainement' value='1' <?php if ($match_entrainement) { echo " checked"; } ?>/> Entraînement</td>
    </tr>
    <tr>
    </tr>   
      <td>Tri du classement : </td>
      <td><input type="radio" name="classement" id="classement" value="0" <?php  if ($classement == "0") echo "checked";?> > Ordre alphabétique</td>
      <td><input type="radio" name="classement" id="classement" value="1" <?php  if ($classement == "1") echo "checked";?> > Par points</td>
      <td><input type="radio" name="classement" id="classement" value="2" <?php  if ($classement == "2") echo "checked";?> > Par assiduité</td>
      <td></td>
    <tr>
      <td><input type='submit' value='Appliquer' formmethod='post'></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
</form>


<?
$Filtre_SQL="FALSE";
$Tri_SQL="";
if ($match_amical)
{
  $Filtre_SQL=$Filtre_SQL . " OR Dates.`Type` = 'Amical'";
}
if ($match_champ)
{
  $Filtre_SQL=$Filtre_SQL . " OR Dates.`Type` = 'Championnat'";
}
if ($match_coupe)
{
  $Filtre_SQL=$Filtre_SQL . " OR Dates.`Type` = 'Coupe'";
}
if ($match_entrainement)
{
  $Filtre_SQL=$Filtre_SQL . " OR Dates.`Type` = 'Entrainement'";
}
if ($classement == "0")
{
  $Tri_SQL="`Licencies`.NOM ASC, `Licencies`.Prenom ASC";
}
if ($classement == "1")
{
  $Tri_SQL="points DESC, assiduite DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC";
}
if ($classement == "2")
{
  $Tri_SQL="assiduite DESC, points DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC";
}


AffichierClassement($Filtre_SQL, $Tri_SQL);
?>

