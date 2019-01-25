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
cette page permet l'utilisateur d'ajouter son niveau d'un nouveau compétence.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Ajouter un compentence</title>
    </head>
    <body>
        <div id="box">
            <h1>Ajouter un compentence:</h1>
              
                     
                    <?php
                    echo("<div class='menu'>");
                echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
                echo("</div>");
                 echo("<h2>Choisir un compétences et indiquer votre niveau: </h2>");
// obtenir la liste des compétences déclaré et la liste des tous coméptence sur BD
                $competenceD=CompetenceDeclare($cx, $email);
                $allcomp = listCompetence($cx);
// récupérer la liste de niveau 
                $listniv = listNiveau($cx); 
// // boucle pour affichier les compétences 
				foreach ($allcomp as $listcomp){
					$nomCTous = $listcomp["NomC"];
					$codeC = $listcomp["CodeC"];
// on envie de afficher seulement les competences qui sont pas dans la liste de compétences déclaré. si les deux Code de competence sont differents ,alors trouve est égale à true
					if($competenceD != NULL){
						foreach($competenceD as $detailcompetence){   // si pas égale, on ne l'affiche pas  
                            if ($detailcompetence["CodeC"] != $codeC){ 
							$trouve = TRUE;
                            }else{
							$trouve=FALSE;   
							}								   
                        }
// si true, on lui donne dans un form .
					if ($trouve == TRUE){
							echo ("<h2>");
							echo ($nomCTous."   ");
							echo ("</h2>");
							echo ("<form action = 'm3_t_ajouter.php'>");
							echo  "<input type='hidden' name='ie' value='$email' />";
							echo  "<input type='hidden' name='mdp' value='$mdp' />";
							echo  "<input type='hidden' name='competence' value='$codeC' />";							
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
// si il n a pas de compentence D					
						else{
							echo ("<h2>");
							echo ($nomCTous."   ");
							echo ("</h2>");
							echo ("<form action = 'm3_t_ajouter.php'>");
							echo  "<input type='hidden' name='ie' value='$email' />";
							echo  "<input type='hidden' name='mdp' value='$mdp' />";
							echo  "<input type='hidden' name='competence' value='$codeC' />";							
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
                                        }
                }      
				echo("</br>"); 
				echo("</br>"); 
				echo("</br>"); 
				echo "<form name='go' action='P2_PageAcceuil.php'><input type='hidden' name='ie' value='$email' /><input type='hidden' name='mdp' value='$mdp'<input type='submit' value='Retour' /><input type='submit' value='Retour au page Acceuil' /></form>";		
				?>	
        </div>
    </body>
</html>
