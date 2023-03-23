
<?php
include (gestion_club_dir_path() . '/rencontres/lib_classement.php');

$match_amical = htmlspecialchars($_POST['match_amical']);
$match_champ = htmlspecialchars($_POST['match_champ']);
$match_coupe = htmlspecialchars($_POST['match_coupe']);
$match_entrainement = htmlspecialchars($_POST['match_entrainement']);
$classement = htmlspecialchars($_POST['classement']);
$action_affichage = htmlspecialchars($_GET['action_affichage']);

if ($classement == null)
{
  $classement = 1; //classement par points par defaut
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
      <td><input type='submit' value='Tri du classement' formaction='<?php echo add_query_arg(array('action_affichage'=>'afficher_resultat'),get_permalink());?>' formmethod='post'> </td>
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
  include (WP_PLUGIN_DIR . '/gestion-club/rencontres/lister_championnat.php');
}
if ($match_champ)
{
  include (WP_PLUGIN_DIR . '/gestion-club/rencontres/lister_championnat.php');
}
if ($match_coupe)
{
  include (WP_PLUGIN_DIR . '/gestion-club/rencontres/lister_coupe.php');
}
if ($match_entrainement)
{
  include (WP_PLUGIN_DIR . '/gestion-club/rencontres/lister_entrainement.php');
}



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
if ($classement == "3")
{
  $Tri_SQL="moyenne DESC, assiduite DESC, points DESC, `Licencies`.NOM ASC, `Licencies`.Prenom ASC";
}

if ($action_affichage == "afficher_resultat")
{
  AffichierClassement($Filtre_SQL, $Tri_SQL);
}

?>

