<?php
/*
Plugin Name: Gestion du club
Description: Permet de gerer les donnees du club
Author: Fabien Beugné
*/

function gestion_club_dir_path()
{
    return WP_PLUGIN_DIR . '/gestion-club';
}

function selectionner_saison()
{
    include (gestion_club_dir_path() . '/saisons/selection_saison.php');
}

function afficher_licencies()
{
    include (gestion_club_dir_path() . '/licencies/lister_licencies_contact.php');
}

function afficher_bureau()
{
    include (gestion_club_dir_path() . '/licencies/lister_bureau.php');
}

function afficher_anniversaire()
{
    include (gestion_club_dir_path() . '/licencies/lister_licencies_anniversaire.php');
}

function afficher_rencontres()
{
    include (gestion_club_dir_path() . '/rencontres/lister_rencontres.php');
}

function afficher_resultat_rencontre()
{
    include (gestion_club_dir_path() . '/rencontres/lister_resultat_date.php');
}


function afficher_formulaire_licencies()
{
    include (gestion_club_dir_path() . '/licencies/formulaire_licencies.php');
}

function afficher_formulaire_rencontres()
{
    include (gestion_club_dir_path() . '/rencontres/formulaire_rencontres.php');
}

function afficher_formulaire_resultats()
{
    include (gestion_club_dir_path() . '/rencontres/formulaire_resultats.php');
}


include_once (gestion_club_dir_path() . '/bdpalet/class-config-bdpalet.php');
$config_bd = new ConfigBaseDeDonnesPalet();
$config_bd->init_config_bdpalet();
?>