<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// executer la session        
        session_start();

        $IDemail = filter_input(INPUT_GET,"ie",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemail"] = $IDemail;       
        $IDmdp = filter_input(INPUT_GET,"mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDmdp"] = $IDmdp; 
	
        $email = $_SESSION["IDemail"];
        $mdp = $_SESSION["IDmdp"]; 
?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Resultat</title>
    </head>
    <body>
        <?php
// get codec
        $codecomget = filter_input(INPUT_GET,"competence",FILTER_SANITIZE_SPECIAL_CHARS);
        $codec = (int)$codecomget;
/// obtenir les données dans la balise 'select'
        $codenivget = filter_input(INPUT_GET,"txt_niv",FILTER_SANITIZE_SPECIAL_CHARS);
        $nbUpdate = (int)$codenivget;
//get codem
        $sqlcodem = "SELECT Membres.CodeM FROM Membres WHERE Membres.EmailM = '$email'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $getcode = mysqli_fetch_array($ddecodem);
        $codem = (int)$getcode["CodeM"];

// mis à jour le BD
                $queryUpdate = "UPDATE `Indiquer` SET  `CodeN` = $nbUpdate WHERE `Indiquer`.`CodeM` = $codem AND `Indiquer`.`CodeC` = $codec";
                if(mysqli_query($cx, $queryUpdate) == TRUE){
                    echo "<h1>";
					echo ("Vous avez bien change! "); echo "</h1>";
                    echo("<meta http-equiv='refresh' content ='2 ; URL = P2_PageAcceuil.php?ie=$email&mdp=$mdp'>");
                }else{
                    echo "mysqli_error($queryUpdate)";
                    die ("Wrong demande.");
                }     
        ?>
    </body>
</html>
