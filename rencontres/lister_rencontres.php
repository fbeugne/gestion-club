
<?php
include (gestion_club_dir_path() . '/rencontres/lib_classement.php');

$match_amical = false;
$match_champ = false; /* par defaut on affiche le championnat */
$match_coupe = false;
$match_entrainement = false;
$classement = 1;  /* par defaut on classe par point */
$action_affichage = null;

if (empty($_POST))
{
  $match_champ = true; /* par defaut on affiche le championnat */
  $classement = 1;  /* par defaut on classe par point */
}
else
{
  if (array_key_exists('match_amical', $_POST))
  {
    $match_amical = htmlspecialchars($_POST['match_amical']);
  }
  if (array_key_exists('match_champ', $_POST))
  {
    $match_champ = htmlspecialchars($_POST['match_champ']);
  }
  if (array_key_exists('match_coupe', $_POST))
  {
    $match_coupe = htmlspecialchars($_POST['match_coupe']);
  }
  if (array_key_exists('match_entrainement', $_POST))
  {
    $match_entrainement = htmlspecialchars($_POST['match_entrainement']);
  }
  if (array_key_exists('classement', $_POST))
  {
    $classement = htmlspecialchars($_POST['classement']);
  }
}


?>


<form>
  <table>
    <tr>
      <td>Filtre des matchs : </td>
      <td><input type='checkbox' name='match_amical' id='match_amical' value='1' <?php if ($match_amical) { echo " checked"; } ?>/> Amical</td>
      <td><input type='checkbox' name='match_champ' id='match_champ' value='1' <?php if ($match_champ) { echo " checked"; } ?>/> Championnat</td>
      <td><input type='checkbox' name='match_coupe' id='match_coupe' value='1' <?php if ($match_coupe) { echo " checked"; } ?>/> Coupe</td>
      <td><input type='checkbox' name='match_entrainement' id='match_entrainement' value='1' <?php if ($match_entrainement) { echo " checked"; } ?>/> Entraînement</td>
    </tr>
    <tr>
      <td><input type='submit' value='Tri du classement' formaction='<?php echo get_permalink();?>' formmethod='post'> </td>
      <td><input type="radio" name="classement" id="classement" value="0" <?php  if ($classement == "0") echo "checked";?> > Alphabétique</td>
      <td><input type="radio" name="classement" id="classement" value="1" <?php  if ($classement == "1") echo "checked";?> > Points</td>
      <td><input type="radio" name="classement" id="classement" value="2" <?php  if ($classement == "2") echo "checked";?> > Assiduité</td>
      <td><input type="radio" name="classement" id="classement" value="3" <?php  if ($classement == "3") echo "checked";?> > Moyenne</td>
    </tr>   
  </table>
</form>


<?


if ($match_amical)
{
  include (gestion_club_dir_path() . '/rencontres/lister_championnat.php');
}
if ($match_champ)
{
  include (gestion_club_dir_path() . '/rencontres/lister_championnat.php');
}
if ($match_coupe)
{
  include (gestion_club_dir_path() . '/rencontres/lister_coupe.php');
}
if ($match_entrainement)
{
  include (gestion_club_dir_path() . '/rencontres/lister_entrainement.php');
}



$Filtre_SQL="FALSE";
$Tri_SQL="";
if ($match_amical)
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Amical'";
}
if ($match_champ)
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Championnat'";
}
if ($match_coupe)
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Coupe'";
}
if ($match_entrainement)
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Entrainement'";
}


if ($classement == "0")
{
  $Tri_SQL="`licencies`.NOM ASC, `licencies`.Prenom ASC";
}
if ($classement == "1")
{
  $Tri_SQL="points DESC, assiduite DESC, `licencies`.NOM ASC, `licencies`.Prenom ASC";
}
if ($classement == "2")
{
  $Tri_SQL="assiduite DESC, points DESC, `licencies`.NOM ASC, `licencies`.Prenom ASC";
}
if ($classement == "3")
{
  $Tri_SQL="moyenne DESC, assiduite DESC, points DESC, `licencies`.NOM ASC, `licencies`.Prenom ASC";
}

AffichierClassement($Filtre_SQL, $Tri_SQL);

?>

