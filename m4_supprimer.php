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
?>
<!DOCTYPE html>
<!--
cette page permet l'utilisateur de supprimer certains compétence.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />       
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Supprimer mes compentence</title>
    </head>
    <body>
        <div id="box">
            <h1>Supprimer mes compentence:</h1>
                <form action = "m4_t_supprimer.php">
                <?php
                echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");
				echo "<input type='hidden' name='ie' value='$email' /><input type='hidden' name='mdp' value='$mdp'<input type='submit' value='Retour' />";
// afficher tous les compétences déclarés par ce membre en utilisant le balise checkbox pour réalier le choix                 
                $competenceD = CompetenceDeclare($cx, $email); 
                if($competenceD != NULL){
                    foreach($competenceD as $detailcompetence){
                        $CodeC = $detailcompetence["CodeC"];
                        $NomC = $detailcompetence["NomC"];
                        $Niv = $detailcompetence["LibelleN"];
                        echo "<h2>";
                        echo ("<input type='checkbox' name='tabCom[]' value = '$CodeC'/>");
                        echo ($NomC." (".$Niv.") ");
						echo "</h2>";
                        echo ("</br>");
                    }
                        echo ("<input type='submit' value='Valider' name='' />");
                        echo ("</form>");
                } else {
                    echo "<p>Pas de resultat..il semble que vous n'avez pas encore declarer un competence.</p>";
					echo "<form name='go' action='P2_PageAcceuil.php'><input type='hidden' name='ie' value='$email' /><input type='hidden' name='mdp' value='$mdp'<input type='submit' value='Retour' /><input type='submit' value='Retour au page Acceuil' /></form>	";	
                }
                ?>
                    
                
        </div>
    </body>
</html>