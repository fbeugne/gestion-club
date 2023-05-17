<?php
include_once (gestion_club_dir_path() . '/common.php');
include_once (gestion_club_dir_path() . '/licencies/modifier_licencie_db.php');

if (array_key_exists('action', $_GET))
{
  $action=htmlspecialchars($_GET['action']);
}
else
{
  $action="";
}

if ($action=="[modifier_db]")
{
    // recuperation des données du formulaire
    $nom=strtoupper(htmlspecialchars($_POST['nom']));
    $prenom=htmlspecialchars($_POST['prenom']);
    $date_naissance=htmlspecialchars($_POST['date_naissance']);
    $rue=htmlspecialchars($_POST['rue']);
    $CPostal=htmlspecialchars($_POST['CPostal']);
    $ville=htmlspecialchars($_POST['ville']);
    $email=htmlspecialchars($_POST['email']);
    $fixe=htmlspecialchars($_POST['fixe']);
    $portable=htmlspecialchars($_POST['portable']);

    
    $conn_db = new BaseDeDonnesPalet();
    $sql = "select NOM, Prenom from licencies where (licencies.`NOM` = '$nom' AND licencies.`Prenom` = '$prenom')";
    $result = $conn_db->RequeteSQL($sql); 

    // on vérifie si le nom existe déjà dans la base de donnée. Pour éviter le risque de doublon ou de confusion
    if ( $result && ($result->fetch_row()) )
    {
        echo "  
        <p> 
        <b>Le licencié $prenom $nom existe déjà.</b>
        <br> 
        Si tu veux ajouter ce licencié, ajoute des précisions dans le prénom pour distinguer les licenciés
        </p>
        ";
    }
    else
    {
       // pas de doublon donc on peut ajouter dans la base de données
        include_once (gestion_club_dir_path() . '/licencies/modifier_licencie_db.php');
        modifier_licencie_db(   "",
                                $nom,
                                $prenom,
                                $date_naissance,
                                $rue,
                                $CPostal,
                                $ville,
                                $email,
                                $fixe,
                                $portable );
    }
}
else 
{
    // initilisation des données du formulaire
    $nom="";
    $prenom="";
    $date_naissance="";
    $rue="";
    $CPostal="";
    $ville="";
    $email="";
    $fixe="";
    $portable="";
}
?>

<hr>
<form action="<?php echo add_query_arg(array('id'=>'','action'=>'[modifier_db]'),get_permalink()); ?>" method="post">
    <table>
        <tr>
            <td>Nom : </td><td><input type="text" name="nom" value="<?php echo $nom; ?>"/></td>
        </tr>
        <tr>
            <td>Prénom : </td><td><input type="text" name="prenom" value="<?php echo $prenom; ?>"/></td>
        </tr>
        <tr>
            <td>Date de naissance : </td><td><input type="date" name="date_naissance" value="<?php echo $date_naissance; ?>" /></td>
        </tr>
        <tr>
            <td>Rue : </td><td><input type="text" name="rue" value="<?php echo $rue; ?>" /></td>
        </tr>
        <tr>
            <td>Code postale : </td><td><input type="number" name="CPostal" value="<?php echo $CPostal; ?>" /></td>
        </tr>
        <tr>
            <td>Ville : </td><td><input type="text" name="ville" value="<?php echo $ville; ?>" /></td>
        </tr>
        <tr>
            <td>email : </td><td><input type="email" name="email" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td>Fixe : </td><td><input type="tel" name="fixe" value="<?php echo $fixe; ?>" /></td>
        </tr>
        <tr>
            <td>Portable : </td><td><input type="tel" name="portable" value="<?php echo $portable; ?>" /></td>
        </tr>
        <tr>
            <td><input type="submit" value="Ajouter"/></td><td></td>
        </tr>
    </table>
</form>
