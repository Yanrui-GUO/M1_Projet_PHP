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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Chercher un membre par compétences</title>
    </head>
    <body>
        </form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        <h1>Chercher un membre par compétence</h1>
        <div id="box">
            
        <form action="afficherParCom.php">
        
        <?php
        // afficher tous les compétences dans la base de données
            
            echo $mdp = $_GET["mdp"];
            echo $email = $_GET["ie"];
        
            $sql_chercher1 = "SELECT CodeC, NomC FROM Competence";
            $curseur_chercher1 = mysqli_query($cx, $sql_chercher1);
           
           
           echo("<div class='menu'>");
        echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
           echo("</div>");
           
        echo("<div class='centre'>");
        
            echo("<select class='select' name = 'txt_com' required>");
            
            while($nuplet_chercher1 = mysqli_fetch_array($curseur_chercher1)){
                
                $nomCTous = $nuplet_chercher1["NomC"];
                 $codeC = $nuplet_chercher1["CodeC"];
                echo("<option value = '$codeC'>");
                echo("$nomCTous");
                echo("</option>");
            }
            echo("</select>");
            echo("</div>");
            echo("<input type='hidden' name = 'ie', value='$email'>");
            echo("<input type='hidden' name = 'mdp', value='$mdp'>");
           ?> 
        
        <input type="submit" value="Chercher" />
        </form>
        </div>
   
    </body>
</html>
