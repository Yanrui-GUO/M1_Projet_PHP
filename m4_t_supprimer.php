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
        <title>Resultat</title>
    </head>
    <body>
        <?php
//get codem
        $sqlcodem = "SELECT Membres.CodeM FROM Membres WHERE Membres.EmailM = '$email'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $getcode = mysqli_fetch_array($ddecodem);
        $codem = (int)$getcode["CodeM"];
/// obtenir les données dans le balise checkbox
        $codecomget = array();
		foreach ($_GET["tabCom"] as $codecom)
		{ $codecomget[] = $codecom;}

		foreach ($codecomget as $code){
			$com = (int)$code;
		// mis à jour bd
                $querySuppr = "DELETE FROM `Indiquer` WHERE `Indiquer`.`CodeM` = $codem AND `Indiquer`.`CodeC` = $com;";
                if(mysqli_query($cx, $querySuppr) == TRUE){
                    echo "<h1>";
					echo ("Vous avez bien supprime! ");
					echo "</h1>";
                    echo("<meta http-equiv='refresh' content ='2 ; URL = P2_PageAcceuil.php?ie=$email&mdp=$mdp'>");
                }else{
                    echo "mysqli_error($querySuppr)";
                    die ("Wrong demande.");
                }
		}		
        ?>
    </body>
</html>
