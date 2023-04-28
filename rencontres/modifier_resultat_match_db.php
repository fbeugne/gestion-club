

<?php

  $nb_joueurs = htmlspecialchars($_POST['nb_joueurs']);
  $nb_adversaires = htmlspecialchars($_POST['nb_adversaires']);
  $points_pour = htmlspecialchars($_POST['points_pour']);
  $points_contre = htmlspecialchars($_POST['points_contre']);
       
  $sql = "update `dates` set `nb_joueurs` = '$nb_joueurs' where `Date` = '$date'";
  $conn_db->RequeteSQL($sql);
  
  $sql = "update `dates` set `nb_adversaires` = '$nb_adversaires' where `Date` = '$date'";
  $conn_db->RequeteSQL($sql);
  
  $sql = "update `dates` set `points_pour` = '$points_pour' where `Date` = '$date'";
  $conn_db->RequeteSQL($sql);
  
  $sql = "update `dates` set `points_contre` = '$points_contre' where `Date` = '$date'";
  $conn_db->RequeteSQL($sql);
?>