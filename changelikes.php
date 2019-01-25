<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
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
        <title></title>
    </head>
    <body>
        <?php
        /////// 
         $email = $_GET["ie"];
         $codecom = $_GET["co"];
         $mdp = $_GET["mdp"];

        if (isset($_GET["iec"])) { 
           $sonemail = $_GET["iec"];
        }
        if (isset($_GET["ies"])) { 
           $suivreemail = $_GET["ies"];
        }		
        //get codem
        $sqlcodem = "SELECT Membres.CodeM FROM Membres WHERE Membres.EmailM = '$email'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $codemA = mysqli_fetch_array($ddecodem);
        $codem = $codemA["CodeM"];
        ///
        if(existelike($cx, $email, $codecom)== FALSE){ //pas encore Amier, du coup il faut ajouter
            $add = "INSERT INTO `Likes` (`CodeM`, `CodeCom`) VALUES ($codem, $codecom)"; 
            mysqli_query($cx,$add);   
        } else { //déja aimer du coup il faut le supprimer
            $delect = "DELETE FROM `Likes` WHERE `Likes`.`CodeM` = $codem AND `Likes`.`CodeCom` = $codecom;";
            mysqli_query($cx,$delect);  
        }
        /// choisir les adresses à envoyer les paramètres 
		//si il existe 'iec',il est vient de la page de profil d'un autre membre 
        if (isset($sonemail)) {
                echo("<meta http-equiv='refresh' content ='0.1 ; URL = profilConsulter.php?ie=$email&iec=$sonemail&mdp=$mdp&iec=$sonemail'>");			
		}else{	//il existe ies donc il faut envoyer à la page acceuil
			if(isset($suivreemail)){
				echo("<meta http-equiv='refresh' content ='0.1 ; URL = P2_PageAcceuil.php?ie=$email&mdp=$mdp'>");
			}else{
				echo("<meta http-equiv='refresh' content ='0.1 ; URL = monProfil.php?ie=$email&mdp=$mdp'>");
        				
			}	
		}
		

        ?>
    </body>
</html>
