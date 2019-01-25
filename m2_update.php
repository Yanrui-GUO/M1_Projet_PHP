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
cette page permet l'utilisateur de changer son niveau d'un compétence déclaré par lui.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />       
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Changement mes compentence</title>
    </head>
    <body>
        <div id="box">
            <h1>Changement mes compentence:</h1>
            
                <?php
                echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");
                
// obtenir les compétences déclarés               
                $competenceD=CompetenceDeclare($cx, $email);
// récupérer la liste de niveau 
                $listniv = listNiveau($cx);     
// boucle de compétence si il exsite 
				if($competenceD != NULL){
// d'après chanque compétence déclaré, on met la liste de niveau dans la balise select pour réaliser le changement de niveau
                 foreach($competenceD as $detailcompetence){
                         $CodeC = $detailcompetence["CodeC"];
                         $NomC = $detailcompetence["NomC"];                     
// mettre les données generales pour assurer de transmettre les paramètres.les utilisateurs ne peuvent pas les voir sur l'interface.                      
                        echo "<h2>";
						echo ($NomC."  ");       echo "</h2>";               
			echo ("<form action = 'm2_t_update.php'>");
                        echo  "<input type='hidden' name='ie' value='$email' />";
                        echo  "<input type='hidden' name='mdp' value='$mdp' />";
                        echo  "<input type='hidden' name='competence' value='$CodeC' />";
//boucle pour niveau                        
                        echo ("<select class='select' name = 'txt_niv'>");
                        foreach ($listniv as $allniv){
                            $CodeN = $allniv["CodeN"];
                            echo ("<option value='".$allniv["CodeN"]."'>");
                            echo($allniv["LibelleN"]);
                            echo ("</option>");    
                        }
                        echo ("</select>");
						echo ("   ");	
                        echo ("<input type='submit' value='Valider' name='' />");
                        echo ("</form>");
						echo("</br>"); 
                    }      
                } else {
                    echo "<p>Pas de resultat..il semble que vous n'avez pas encore declarer un competence.</p>";
                    echo ("<form name='go' action='P2_PageAcceuil.php'><input type='hidden' name='ie' value='$email' /><input type='hidden' name='mdp' value='$mdp'<input type='submit' value='Retour' /><input type='submit' value='Retour' /></form>");
                }
                ?>

        </div>
    </body>
</html>
