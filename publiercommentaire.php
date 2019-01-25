<?php
        date_default_timezone_set('Europe/Paris');
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
        
// executer la session        

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
        <title></title>
    </head>
    <body></form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        <div id="box">
        <?php
        $email = $_GET["ie"];
        $mdp = $_GET["mdp"];
        $contenu = filter_input(INPUT_GET, "txtreponse", FILTER_SANITIZE_SPECIAL_CHARS);
        $date = date('Y-m-d');

        if (isset($_GET["iec"])) { 
           $sonemail = $_GET["iec"];
        }
        if (isset($_GET["ies"])) { 
           $suivreemail = $_GET["ies"];
        }
        if (isset($_GET["co"])) { 
           $codecom = $_GET["co"];
        }
		
        
        echo("<h2>");
        echo "      Date : ";
        echo $date;
        echo("<strong>");
        echo "      Contenu : ";
        echo $contenu;
        echo("</strong>");
        echo("</h2>");
        echo("<br><br>");

//get codem
        $sqlcodem = "SELECT Membres.CodeM FROM Membres WHERE Membres.EmailM = '$email'";
        $ddecodem = mysqli_query($cx, $sqlcodem);
        $getcode = mysqli_fetch_array($ddecodem);
        $codem = (int)$getcode["CodeM"];
        ///vérifier il faut utilise quel query
        if(isset($codecom)){ //Codecom n'est pas vide, ça veut dire 
            $add = "INSERT INTO `Commentaires` (`DateC`, `Contenu`,`CodeComRe`,`CodeM`) VALUES ('$date','$contenu',$codecom,$codem)";
            $resultat = mysqli_query($cx,$add);
            if ($resultat == FALSE){
                die ("wrong query");
            } else{
                echo ("<h2>Votre commentaire est bien envoyé.</h2>");   
           
            }
        } else { 
            $add = "INSERT INTO `Commentaires` (`DateC`, `Contenu`,`CodeComRe`,`CodeM`) VALUES ('$date','$contenu',NULL,$codem)";
            $resultat = mysqli_query($cx,$add);
            if ($resultat == FALSE){
                die ("wrong query");
            }else{
                echo ("<h2>Votre commentaire est bien envoyé.</h2>");
                echo ("<a href = 'P2_PageAcceuil.php?ie=$email&mdp=$mdp'>");                                
                echo ("<img src='images/888.png' width='60' height='30' title='Retour'/></a>");               
            }
            
        }        
        ///
        if (isset($sonemail)) {
                echo ("<a href = 'profilConsulter.php?ie=$email&iec=$sonemail&mdp=$mdp&iec=$sonemail'>");                                
                echo ("<img src='images/888.png' width='60' height='30' title='Retour'/></a>");		
		}else{	
			if(isset($suivreemail)){
                echo ("<a href = 'P2_PageAcceuil.php?ie=$email&mdp=$mdp'>");                                
                echo ("<img src='images/888.png' width='60' height='30' title='Retour'/></a>");
			}	
		}  
       
        ?>
        </div>
    </body>
</html>
