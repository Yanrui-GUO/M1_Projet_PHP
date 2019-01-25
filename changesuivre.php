<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
?>
<!DOCTYPE html>
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
        <title></title>
    </head>
    <body>

        <?php
        
         $myemail = $_GET["ie"];
         $sonemail = $_GET["iec"];
         $mdp=$_GET["mdp"];
        //get mycode
        $sqlcodem = "SELECT CodeM FROM Membres WHERE EmailM = '$myemail'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $mycodeA = mysqli_fetch_array($ddecodem);
        $mycode = $mycodeA["CodeM"];
        //get soncode
        $sqlcodems = "SELECT CodeM FROM Membres WHERE EmailM = '$sonemail'";
        $ddecodems = mysqli_query($cx, $sqlcodems);
        $soncodeA = mysqli_fetch_array($ddecodems);
        $soncode = $soncodeA["CodeM"];
        ///
        
        if(existeSuivre($cx, $myemail, $sonemail)== FALSE){ //false signifie que il n'a pas demandé à suivre un autre membre, du coup c'est  un demande de suivi.
            $add = "INSERT INTO `Suivre` (`CodeMestS`, `CodeMS`) VALUES ($soncode, $mycode)";
            $curseurInsert1 = mysqli_query($cx,$add);   
        } else { //au contraire, il a besoin de supprimer la demande de suivi
            $delect = "DELETE FROM `Suivre` WHERE `Suivre`.`CodeMestS` = $soncode AND `Suivre`.`CodeMS` = $mycode;";
            
            $curseurDelete = mysqli_query($cx,$delect);  
        }
        
        
        ///
        
        echo("<meta http-equiv='refresh' content ='0.1 ; URL = profilConsulter.php?ie=$myemail&iec=$sonemail&mdp=$mdp'>");
        
        ?>
    </body>
</html>
