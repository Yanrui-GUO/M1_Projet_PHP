<?php
// charger le fichier des fonctions
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
        <meta charset="UTF-8">
        <title>Inscription (2/2))</title>
    </head>
    <body>
        <h1>Résultat de l'inscription</h1>
        <p>
            <?php
            // recupérer les données du formulaire
            $nom = filter_input(INPUT_GET, "txt_nom", FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_GET, "txt_prenom", FILTER_SANITIZE_SPECIAL_CHARS);
            $pseudo = filter_input(INPUT_GET, "txt_pseudo", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_GET, "txt_email", FILTER_SANITIZE_SPECIAL_CHARS);
            $competence = filter_input(INPUT_GET, "txt_com", FILTER_SANITIZE_SPECIAL_CHARS);
            $niveau = filter_input(INPUT_GET, "txt_niv", FILTER_SANITIZE_SPECIAL_CHARS);
            
            $CodeC = (int)$competence;
            $CodeN = (int)$niveau;
            
           
            // verification email
            if (emailExiste($cx, $email) == FALSE){ // email jamais utilisé
                // construire ordre SQL (connexion faite !)
                $insertSQL = "INSERT INTO Membres (NomM, PrenomM, PseudoM, EmailM) VALUES('$nom', '$prenom','$pseudo','$email')"; 
                


                // exécuter SQL               
                $crExecSQL = mysqli_query($cx, $insertSQL);
               
                
                // compte-rendu
                if ($crExecSQL == TRUE) {
                    
                    echo("Membre $nom $prenom inscrit");
                } else {
                    echo(mysqli_error($cx));
                    echo("<br/>");
                    echo("Inscription impossible");
                }
            } else { // email existant
                echo("email dejà utilisé - <a href='javascript:history.back()'>à modifier</a>");
            }
            
            
            
            $insertsql1 = "SELECT CodeM from Membres where EmailM='$email'";
            $crExecSQL1 = mysqli_query($cx, $insertsql1);
            

            echo
            
            $insertSQL2 = "INSERT INTO Indiquer(CodeM, CodeC, CodeN) VALUES($crExecSQL1,$CodeC,$CodeN)";
            $crExecSQL2 = mysqli_query($cx, $insertSQL2);
            
             if ($crExecSQL2 == TRUE) {
                    
                    echo("Compétences sont bien enregistré");
                } else {
                    echo(mysqli_error($cx));
                    echo("<br/>");
                    echo("Compétences impossible");
                }
             
            
            ?>
            
            
        </p>
    </body>
</html>
