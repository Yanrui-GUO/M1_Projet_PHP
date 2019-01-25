<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// ouvrir le session et conserver le email dans le session        
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Inscription</title>
    </head>
    <body>
        <h1>Résultat de l'inscription</h1>
        <p>
            <div id="box">
            <?php
            // recupérer les données du formulaire
            $nom = filter_input(INPUT_GET, "txt_nom", FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_GET, "txt_prenom", FILTER_SANITIZE_SPECIAL_CHARS);
            $pseudo = filter_input(INPUT_GET, "txt_pseudo", FILTER_SANITIZE_SPECIAL_CHARS);
            /*$email = filter_input(INPUT_GET, "txt_email", FILTER_SANITIZE_SPECIAL_CHARS);*/
            //on a déjà obtenu l'email dans le session.
            $competence = filter_input(INPUT_GET, "txt_com", FILTER_SANITIZE_SPECIAL_CHARS);
            $niveau = filter_input(INPUT_GET, "txt_niv", FILTER_SANITIZE_SPECIAL_CHARS);
            $mdp = filter_input(INPUT_GET, "txt_mdp", FILTER_SANITIZE_SPECIAL_CHARS);
            
            $CodeC = (int)$competence;
            $CodeN = (int)$niveau;
            
           
            // verification email, assurer que l'email est unique
            if (emailExiste($cx, $email) == FALSE){ // email jamais utilisé
                // construire ordre SQL (connexion faite !)
                $insertSQL = "INSERT INTO Membres (NomM, PrenomM, PseudoM, EmailM, MDP) VALUES('$nom', '$prenom','$pseudo','$email','$mdp')"; 
                // exécuter SQL               
                $crExecSQL = mysqli_query($cx, $insertSQL);
                // compte-rendu
                if ($crExecSQL == TRUE) {	// si bien enregistre, ajouter compétence
                    echo("<h2>");
                        echo("Membre $nom $prenom inscrit"); 
                        echo("</h2>");
						// if faut demander le code du nouveau membre car le code est donnée automatiquement.
                        $insertsql1 = "SELECT CodeM from Membres where EmailM='$email'";
                        $crExecSQL1 = mysqli_query($cx, $insertsql1);
                        $nupletSQL1 = mysqli_fetch_array($crExecSQL1);
                        $codem = (int)$nupletSQL1["CodeM"];
						// mettre à jour le BD
                        $insertSQL2 = "INSERT INTO Indiquer(CodeM, CodeC, CodeN) VALUES($codem,$CodeC,$CodeN)";
                        $crExecSQL2 = mysqli_query($cx, $insertSQL2);
                        echo("<h2>");
                        echo("<a href = 'index.php'>");
                        
                        echo("Retour pour connexion");
                        echo("</a>");
                        echo("</h2>"); 
                                if ($crExecSQL2 == TRUE) {
                                       echo("<h2>");
                                       echo("Compétences sont bien enregistré");
                                       echo("</h2>");
                                   } else {
                                       echo(mysqli_error($cx));
                                       echo("<br/>");
                                       echo("<h2>");
                                       echo("Compétences impossible");
                                       echo("</h2>");
                                   }
                    
                } else { //requte est faux
                    echo(mysqli_error($cx));
                    echo("<br/>");
                    echo("<h2>");
                    echo("Inscription impossible");
                    echo("</h2>");
                }
               
                }
                else { // email existant. demander l'utilisateur à vérifier son adress d'email
                    echo("<h2>");
                echo("email dejà utilisé - <a href='javascript:history.back()'>à modifier</a>");
                echo("</h2>");
            }

            ?>
            </div>
            
        </p>
    </body>
</html>
