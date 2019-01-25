<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// executer la session        
        session_start();

        $IDemail = filter_input(INPUT_GET,"txt_email",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemail"] = $IDemail; 
        $email = $_SESSION["IDemail"];
        
        $IDmdp = filter_input(INPUT_GET,"txt_mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDmdp"] = $IDmdp; 
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
        <meta charset="UTF-8">
        <title>ResultatRecommande</title>
    </head>
    <body>
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        
        <div id="box">
        <?php
        
        
       $email=$_GET["ie"];
       $EmailChercher=$_GET["iec"];

       $mdp=$_GET["mdp"];
       
       $trouverMembre="SELECT CodeM FROM Membres WHERE EmailM='$email'";
       $curseurTrouverM = mysqli_query($cx, $trouverMembre);
       $nuplet_trouverM = mysqli_fetch_array($curseurTrouverM);
       $codeM = $nuplet_trouverM["CodeM"];
      
       $trouverMembreChercher="SELECT CodeM FROM Membres WHERE EmailM='$EmailChercher'";
       $curseurTrouverMC = mysqli_query($cx, $trouverMembreChercher);
       $nuplet_trouverMC = mysqli_fetch_array($curseurTrouverMC);
       $codeMC = $nuplet_trouverMC["CodeM"];
       
       /// obtenir les données dans le balise checkbox
        $codecomget = array();
		foreach ($_GET["tabCom"] as $codecom)
		{ $codecomget[] = $codecom;}
                
        		foreach ($codecomget as $code){
			$codeC = (int)$code;        
 
       $insertSQL = "INSERT INTO `Recommander` (`CodeMestRe`, `CodeMRe`, `CodeC`) VALUES ('$codeMC', '$codeM', '$codeC');"; 
       $demarre = mysqli_query($cx, $insertSQL);
       
           }   
       
       
       echo("<h2>");
           
            echo("Votre recommandation est bien enregistre. La page va sauter à son profil après 3 secondes.");
            echo("</h2>");
            echo("<meta http-equiv='refresh' content ='3 ; URL = profilConsulter.php?ie=$email&iec=$EmailChercher&mdp=$mdp'>");
           
       
       
       
        ?>
        </div>
    </body>
</html>
