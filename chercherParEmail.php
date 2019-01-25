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
        <title>Chercher un membre par email</title>
    </head>
    <body></form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        <h1>Chercher un membre par email</h1>
        <div id="box">
        <form action="afficherParEmail.php">
                
              
            <ul>
                <?php
                $mdp = $_GET["mdp"];
                $email = $_GET["ie"];
                
                echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");
                ?>
                <br/><br/>
                
            </ul>
                  
        <h2>Email : <input type="text" name="txt_emailChercher" value="" /></h2>
        
        <input type="submit" value="Chercher" />
        <?php
            echo("<input type='hidden' name = 'ie', value='$email'>");
            echo("<input type='hidden' name = 'mdp', value='$mdp'>");
        ?>
        
        
        </form>
        </div>
        
    </body>
</html>
