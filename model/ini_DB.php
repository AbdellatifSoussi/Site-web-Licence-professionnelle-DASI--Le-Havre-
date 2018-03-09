<?php
    $bdduser = "sml37591";
    $bddpass = "lpromp2";

    try {
        $bdd = new PDO('mysql:host=cl1-sql23;dbname=sml37591', $bdduser, $bddpass);
        $bdd->exec('SET NAMES utf8');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //affichage des erreurs
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
?>
