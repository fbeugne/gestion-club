
<?php
$type_de_gestion=htmlspecialchars($_POST["type_de_gestion"]);
?>


<form method="POST" novalidate>
<select name="type_de_gestion" onchange="this.form.submit()">
<?php

$liste_gestion = array(
"licencies" => "Les licenciés", 
"championnat" => "Le championnat", 
"coupe" => "La coupe", 
"entrainement" => "Les entrainements", 
"bilan" => "Le bilan");

// Array elements are shown
reset($liste_gestion);
while( key($liste_gestion) !== NULL )
{
	$type_select=key($liste_gestion);
	echo "<option value=".$type_select;
	if ($type_de_gestion==$type_select) echo " selected";
	echo ">" . current($liste_gestion) . "</option>";
	next($liste_gestion);
}

?>

</select>
</form>

<hr>

<?php
$page_php=gestion-club-index.php;
echo "<h1>";
if ($type_de_gestion == "")
{
	$type_de_gestion = "licencies";
}
echo $liste_gestion[$type_de_gestion];
echo "</h1>";
?>

<?php 


include(WPINC . '/gestion-club/gestion-club-' . $type_de_gestion . '.php');


?>
