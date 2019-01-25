<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// ouvrir le session       
        session_start();

// entregistre les données dans le session
        $IDemail = filter_input(INPUT_GET,"txt_email",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemail"] = $IDemail; 

        $IDmdp = filter_input(INPUT_GET,"txt_mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDmdp"] = $IDmdp; 
        
// vérifier l'origine des pages pour executer la session ou obtenir les paramètres     
        if(empty($_SESSION["IDmdp"])){ //si n'existe pas les valeurs des sessions, la page précédent est transmis par un URL.
            $email=filter_input(INPUT_GET,"ie",FILTER_SANITIZE_SPECIAL_CHARS);
            $mdp = filter_input(INPUT_GET,"mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        }
        else{
             $email = $_SESSION["IDemail"];
             $mdp = $_SESSION["IDmdp"];
        }         
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
        
        <title>Afficher les membres par Email</title>
    </head>
    <body></form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        <?php
        $mdp = $_GET["mdp"];
        $email=$_GET["ie"];
        $EmailChercher=$_GET["txt_emailChercher"];
        
        echo("<h1>");
        echo("Vous chercher de<strong> $EmailChercher </strong>");
        echo("</h1>");
        
        echo("<div id='box'>");
        if (emailExiste($cx, $EmailChercher) == true){
            echo("<h2>");
            echo("La recherche a été reussie. La page va sauter à son profil après 3 secondes.");
            echo("</h2>");
            echo("<meta http-equiv='refresh' content ='3 ; URL = profilConsulter.php?ie=$email&iec=$EmailChercher&mdp=$mdp'>");
            
        }
        else{
            echo("<h2>");
            echo("La recherche a échoué.  - <a href='javascript:history.back()'>   à vérifier </a>");
            echo("</h2>");
        }
        echo("</div>");
        
        // put your code here
        ?>
    </body>
</html>
