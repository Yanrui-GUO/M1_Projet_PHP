
<?php



// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
        // executer la fonction de connexion
        $cx = connexionChoix();
        
            
        session_start();

$IDemail = filter_input(INPUT_GET,"txt_email",FILTER_SANITIZE_SPECIAL_CHARS);
        
$_SESSION["IDemail"] = $IDemail; 

$email = $_SESSION["IDemail"];

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
        <title>Acceuil</title>
    </head>
    <body>
        
        <?php
        // Afficher le nom et le prénom de ce membre
        $chercherMembre = retrouverMembre($cx, $email);
        
        $PseudoM = $chercherMembre["PseudoM"];
        
        $chercherEmail = emailExiste($cx, $email);
        if ($chercherEmail == true){
            echo("<h1>Bienvenue!$PseudoM </h1>");
            echo ("<ul>
                <li><a href='AfficherProfil.php'>Accéder à votre profil</a></li>
                <li><a href='chercherParCom.php'>Chercher membre par compétences</a></li>
                <li><a href='chercherParEmail.php'>Chercher membre par email</a></li>
            </ul>");
        }
        else {
            echo("Erreur Email");
            echo("<br/><br/>");
            echo ("<a href='index.php'>Retour à la page Acceuil</a>");
        }
        
        ?>
        
            
        
        
    </body>
</html>
