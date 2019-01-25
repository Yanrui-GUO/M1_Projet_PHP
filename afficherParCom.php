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
        
        <title>Afficher les membres par compétences </title>
    </head>
    <body>
        </form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        
        <?php
        // put your code here
        
        $email=$_GET["ie"];
        $mdp=$_GET["mdp"];
        
        $competence = filter_input(INPUT_GET, "txt_com", FILTER_SANITIZE_SPECIAL_CHARS);

        $sql_afficherParCom1 = "SELECT DISTINCT PseudoM, LibelleN, EmailM "
                . "FROM Membres, Indiquer, Niveau WHERE Indiquer.CodeC = '$competence' "
                . "AND Membres.CodeM=Indiquer.CodeM AND Niveau.CodeN = Indiquer.CodeN";
        $curseur_afficherParCom1 = mysqli_query($cx, $sql_afficherParCom1);
        
        $sql_afficherParCom2 = "SELECT NomC FROM Competence WHERE CodeC='$competence'";
        $curseur_afficherParCom2 = mysqli_query($cx, $sql_afficherParCom2);
        $nuplet_afficherParCom2 = mysqli_fetch_array($curseur_afficherParCom2);
        $NomC = $nuplet_afficherParCom2["NomC"];
        
        echo("<h1>Les membres qui possèdent le compétence de <strong>'$NomC'</strong></h1>");

        echo("<div id='box'>");
        echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");
        echo("<h2><strong>Compétences declarés</strong></h2>");
        echo("<br/><br/>");
        
        while($nuplet_afficherParCom1 = mysqli_fetch_array($curseur_afficherParCom1)){
            
            $PseudoMAffiche = $nuplet_afficherParCom1["PseudoM"];
            $LibelleNAffiche = $nuplet_afficherParCom1["LibelleN"];
            $emailAffiche = $nuplet_afficherParCom1["EmailM"];
            echo("<h2>");
            echo("<a href='profilConsulter.php?ie=$email&iec=$emailAffiche&mdp=$mdp'>");
            echo("$PseudoMAffiche");
            echo("  ($LibelleNAffiche)");
            echo("</a>");
            
            echo("</h2>");

        }
           
       
        $sql_afficherParCom3 = "SELECT DISTINCT M1.PseudoM, M1.EmailM, M2.PseudoM as recommandeur FROM Membres as M1, Recommander, Membres as M2 "
                . "WHERE Recommander.CodeC = '$competence' "
                . "AND M1.CodeM=Recommander.CodeMestRe AND M2.CodeM=Recommander.CodeMRe";
        $curseur_afficherParCom3 = mysqli_query($cx, $sql_afficherParCom3);

        echo("<br/><br/>");
       
        echo("<h2><strong>Compétences recommandés</strong></h2>");
        echo("<br/><br/>");
        

        
        while($nuplet_afficherParCom3 = mysqli_fetch_array($curseur_afficherParCom3)){
             $PseudoMAffiche3 = $nuplet_afficherParCom3["PseudoM"];
             $emailAffiche3 = $nuplet_afficherParCom3["EmailM"];
             $recommandeur = $nuplet_afficherParCom3["recommandeur"];
             
             echo("<h2>");
             echo("<a href='profilConsulter.php?ie=$email&iec=$emailAffiche3&mdp=$mdp'>");
             
            echo("$PseudoMAffiche3");
            echo("    (Recommandé par $recommandeur)");
            echo("</a>");
            echo("</h2>");
        echo(" ");}
       

        ;
             
            
             
        ?>
    </body>
</html>
