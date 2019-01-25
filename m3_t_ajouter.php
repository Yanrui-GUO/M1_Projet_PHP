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

-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Resultat</title>
    </head>
    <body>
        <div id="box">
        <?php
// get codec
       $competence = filter_input(INPUT_GET, "competence", FILTER_SANITIZE_SPECIAL_CHARS);
       $niveau = filter_input(INPUT_GET, "txt_niv", FILTER_SANITIZE_SPECIAL_CHARS);
            
       $CodeC = (int)$competence;
       $CodeN = (int)$niveau;
//get codem
        $sqlcodem = "SELECT Membres.CodeM FROM Membres WHERE Membres.EmailM = '$email'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $getcode = mysqli_fetch_array($ddecodem);
        $codem = (int)$getcode["CodeM"];
//mettre à jour BD 
        $queryAjoute = "INSERT INTO `Indiquer` (`CodeM`, `CodeC`, `CodeN`) VALUES ($codem,$CodeC,$CodeN)";
            if(mysqli_query($cx, $queryAjoute) == TRUE){
                    echo ("<h1>Vous avez bien ajoute!  Voulez continer ajouter?<h1>");
                    echo ("<h2>");
                    echo ("<form name='back' action='m3_ajouter.php'><input type='hidden' name='ie' value='$email'><input type='hidden' name='mdp' value='$mdp'><input type='submit' value='Oui!' /></form>");
                    echo ("         ");
                    echo ("<form name='go' action='P2_PageAcceuil.php'><input type='hidden' name='ie' value='$email'><input type='hidden' name='mdp' value='$mdp'><input type='submit' value='Non!' /></form>");
                    echo ("</h2>");
            }else{
					echo ("<h1>Vous avez déjà ajouter ce compétence. Merci de vérifier<h1>");
                    echo ("<h2>");
                    echo ("<form name='back' action='m3_ajouter.php'><input type='hidden' name='ie' value='$email'><input type='hidden' name='mdp' value='$mdp'><input type='submit' value='retour et rechoisir' /></form>");
					echo ("</h2>");
                    die ("Wrong demande.");
                }      
        ?>
            
            
        </div>
    </body>
</html>
