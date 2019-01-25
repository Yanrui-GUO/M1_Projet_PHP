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
        
        <title>Gérer mes coordonnées</title>
    </head>
    <body>
        
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        <h1>Gérer mes coordonnées</h1>
        
        <div id="box">
            <form action="resultatmodifi.php">
        <?php
        // put your code here
        $email=$_GET["ie"];
        $mdp=$_GET["mdp"];
        $sql_modification = "SELECT * FROM Membres WHERE EmailM='$email'";
        $curseur_modification = mysqli_query($cx, $sql_modification);
        $nuplet_modofication = mysqli_fetch_array($curseur_modification);
        
        $nomM = $nuplet_modofication["NomM"];
        $prenomM = $nuplet_modofication["PrenomM"];
        $pseudoM = $nuplet_modofication["PseudoM"];
        echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");

        echo("<h2><strong>Nom :</strong></h2> <input type='text' name='txt_nom' value='$nomM' required/>
                <h2><strong>Prenom :</strong></h2> <input type='text' name='txt_prenom' value='$prenomM'  required />
                <h2><strong>Pseudo :</strong></h2> <input type='text' name='txt_pseudo' value='$pseudoM'  required />
                <h2><strong>Mot de passe :</strong></h2> <input type='text' name='txt_mdp' value='' required />");

        /*$updateSQL = "UPDATE Membres (NomM, PrenomM, PseudoM, MDP) VALUES('$nom', '$prenom','$pseudo','$mdp')"; */
        echo("
       
            <br><br>
            
            <input type='hidden' name='ie' value='$email'><input type='hidden' name='mdp' value='$mdp'>
             <input type='hidden' name='nom' value='$nomM'><input type='hidden' name='prenom' value='$prenomM'><input type='hidden' name='pseudo' value='$pseudoM'>      


<input type='submit' value='Valider'></div>");
        
        
        ?>
           
        </form>
    </body>
</html>
