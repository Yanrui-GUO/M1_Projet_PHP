<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
        // executer la fonction de connexion
        $cx = connexionChoix();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="style.css" rel="stylesheet" type="text/css"/>
        
        <title>Bienvenue</title>
    </head>
    <body>
        
            
        <div class="top">
        <h1>Bienvenue</h1>
            </div>
        <div class="contenu">
		<div class="index_left">
           
			<form action="P2_PageAcceuil.php">
        <!-- C'est la partie de connexion 
        Pour des membres qui ont déja inscrit,ils peuvent utiliser leurs Email et le mot de passe pour acceder au page Acceuil.
        -->
            <h2>Connexion</h2>
            
            <div class="index_inputs-left">
            <input type="email" name="txt_email" value="" class="email" placeholder = "Email" required/>
            <input type="password" name="txt_mdp" value="" class="email" placeholder = "Mot de passe"required>
            </div>
            
            <input type="submit" value="Connexion" />
			</form>
		</div>
        
        
        <!-- C'est la partie de inscription 
        Pour le côte de client, ils doivent inscrire leur coordonnées et indiquer leur compétences et leur niveaux des compétences dans cette partie
            Tous les inputbox sont obligatoire à remplir et l'email doit être unique. 
            Ils peuvent ajouter plusieurs compétences et niveaux en utilisant la button de 'ajouter'
        Pour la côte de serveur, on vérifier l'unicité de son email et puis on enregistre tous les données dans la base de données
             Une fois son email est unique dans la base de données, on pense que l'inscription est réussie
        -->
        
            <div class="index_right">
			<form action="P1_ResultatInscription.php">
            <h2>Inscrire</h2>
            
            <div class="index_leftins">
                <div class="index_inputs">   
                <input type="text" name="txt_nom" value="" placeholder="Nom" required/>
                </div>
                <div class="index_inputs"> 
                <input type="text" name="txt_prenom" value="" placeholder="Prenom" required />
                </div>
                <div class="index_inputs"> 
                <input type="text" name="txt_pseudo" value="" placeholder="Pseudo" required />
                </div>
                <div class="index_inputs"> 
                <input type="email" name="txt_email" value="" placeholder="Email" required />
                </div>
                <div class="index_inputs"> 
                <input type="password" name="txt_mdp" value="" placeholder="Mot de passe" required />
                </div>
            </div>
                
            <div class="index_rightins">
               
                <h3>Compétences</h3>
			<?php
			// afficher tous les compétences dans la base de données
            $sql_inscrit1 = "SELECT CodeC, NomC FROM Competence";
            $curseur_inscrit1 = mysqli_query($cx, $sql_inscrit1);
           
           
            echo("<select name = 'txt_com' class ='select' required>");
     
            while($nuplet_inscrit1 = mysqli_fetch_array($curseur_inscrit1)){
                
                $nomCTous = $nuplet_inscrit1["NomC"];
                 $codeC = $nuplet_inscrit1["CodeC"];
                echo("<option value = '$codeC'>");
                echo("$nomCTous");
                echo("</option>");
            }
            echo("</select>");
           ?> 
                
                
                <h3>Niveaux</h3>       
            <?php
            // afficher tous les niveaux dans la base de données
            
            $sql_inscrit2="SELECT CodeN, LibelleN FROM Niveau";
			$curseur_inscrit2 = mysqli_query($cx, $sql_inscrit2);
            
            
            echo("<select class='select' name = 'txt_niv' required>");
 
            while($nuplet_inscrit2 = mysqli_fetch_array($curseur_inscrit2)){
                
                $LibelleNiv = $nuplet_inscrit2["LibelleN"];
                $CodeN = $nuplet_inscrit2["CodeN"];
                echo("<option value = '$CodeN'>");
                echo("$LibelleNiv");
                echo("</option>");
            }
            echo("</select>");
               
            ?>
              <input type="submit" value="Valider" />
            </div>
            </div>
            </div>

            </form>
    
        
        
    </body>
</html>
