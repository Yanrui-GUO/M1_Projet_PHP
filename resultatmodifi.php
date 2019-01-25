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
        
        $TxtNom = filter_input(INPUT_GET, "txt_nom", FILTER_SANITIZE_SPECIAL_CHARS);
        $TxtPrenom = filter_input(INPUT_GET, "txt_prenom", FILTER_SANITIZE_SPECIAL_CHARS);
        $TxtPseudo = filter_input(INPUT_GET, "txt_pseudo", FILTER_SANITIZE_SPECIAL_CHARS);
        $TxtMdp = filter_input(INPUT_GET, "txt_mdp", FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["TxtNom"] = $TxtNom;
        $_SESSION["TxtPrenom"] = $TxtPrenom;
        $_SESSION["TxtPseudo"] = $TxtPseudo;
        $_SESSION["TxtMdp"] = $TxtMdp;

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
        <title>Modification étape2</title>
    </head>
    <body>
        </form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
       </form>
        
        <h1>Modification étape2</h1>
        <div id="box">
        
        <?php
       $nom = $_GET["txt_nom"];
       $prenom = $_GET["txt_prenom"];
       $pseudo = $_GET["txt_pseudo"];
       $email = $_GET["ie"];
       $mdp = $_GET["txt_mdp"];
       
       $trouvermembre = "SELECT CodeM FROM Membres WHERE EmailM='$email'";
       $curseurTrouvermembre = mysqli_query($cx, $trouvermembre);
       $nupletTrouvermembre = mysqli_fetch_array($curseurTrouvermembre);
       $codeM = $nupletTrouvermembre["CodeM"];
       
       
       $update = "UPDATE `Membres` SET `NomM` = '$nom', `PrenomM` = '$prenom', `PseudoM` = '$pseudo', `MDP` = '$mdp' WHERE `Membres`.`CodeM` = $codeM;";
       $curseurUpdate = mysqli_query($cx, $update);
       
       echo("<h2>Votre coordonnees sont bien modifier</h2>");
       echo("<h2><a href='modificationProfil.php?ie=$email&mdp=$mdp'>Retour</a></h2>");
       
       
?>
        </div>
                
    </body>
</html>
