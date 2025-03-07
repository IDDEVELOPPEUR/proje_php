<?php

    try {
        $con=new PDO('mysql:host=localhost;dbname=gestion_de_reservation','root','passer');
    } catch (PDOException $er) {
        echo"Erreur de connection !".$er->getMessage();
    }
?>
