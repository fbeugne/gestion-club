
<?php
include (gestion_club_dir_path() . '/rencontres/lib_classement.php');

$classement = 1;  /* par defaut on classe par point */
$filtre_match = 1;  /* par defaut on affiche le championnat */

if (empty($_POST))
{
  $filtre_match = 1; /* par defaut on affiche le championnat */
  $classement = 1;  /* par defaut on classe par point */
  
  if (array_key_exists('filtre_match', $_GET))
  {
    $filtre_match = htmlspecialchars($_GET['filtre_match']);
  }
}
else
{
  if (array_key_exists('classement', $_POST))
  {
    $classement = htmlspecialchars($_POST['classement']);
  }
  if (array_key_exists('filtre_match', $_POST))
  {
    $filtre_match = htmlspecialchars($_POST['filtre_match']);
  }
}


?>


<form method="post">
  <table>
    <tr>
      <td>
        <select name='filtre_match' onchange="this.form.submit()">  
          <option value="0" <?php  if ($filtre_match == "0") echo "selected";?>> Matchs amicaux </option>
          <option value="1" <?php  if ($filtre_match == "1") echo "selected";?>> Match de championnat </option>
          <option value="2" <?php  if ($filtre_match == "2") echo "selected";?>> Match de coupe </option>
          <option value="3" <?php  if ($filtre_match == "3") echo "selected";?>> Entraînements </option>
        </select>
      </td>
      <td>
        <select name='classement' onchange="this.form.submit()">  
          <option value="0" <?php  if ($classement == "0") echo "selected";?>> Ordre alphabétique </option>
          <option value="1" <?php  if ($classement == "1") echo "selected";?>> Tri par points </option>
          <option value="2" <?php  if ($classement == "2") echo "selected";?>> Tri par assiduité </option>
          <option value="3" <?php  if ($classement == "3") echo "selected";?>> Tri par la moyenne </option>
        </select>
      </td>
    </tr>   
  </table>
</form>


<?php


if ($filtre_match == "0")
{
  include (gestion_club_dir_path() . '/rencontres/lister_amical.php');
}
if ($filtre_match == "1")
{
  include (gestion_club_dir_path() . '/rencontres/lister_championnat.php');
}
if ($filtre_match == "2")
{
  include (gestion_club_dir_path() . '/rencontres/lister_coupe.php');
}
if ($filtre_match == "3")
{
  include (gestion_club_dir_path() . '/rencontres/lister_entrainement.php');
}



$Filtre_SQL="FALSE";
$Tri_SQL="";
if ($filtre_match == "0")
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Amical'";
}
if ($filtre_match == "1")
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Championnat'";
}
if ($filtre_match == "2")
{
  $Filtre_SQL=$Filtre_SQL . " OR dates.`Type` = 'Coupe'";
}
if ($filtre_match == "3")
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

