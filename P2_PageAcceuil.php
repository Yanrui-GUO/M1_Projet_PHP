<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// ouvrir le session       
        session_start();

// entregistre les données dans le session
        $IDemail = filter_input(INPUT_GET,"txt_email",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemail"] = $IDemail; 

        $IDmdp = filter_input(INPUT_GET,"txt_mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDmdp"] = $IDmdp; 
        
// vérifier l'origine des pages pour executer la session ou obtenir les paramètres     
        if(empty($_SESSION["IDmdp"])){ //si n'existe pas les valeurs des sessions, la page précédent est transmis par un URL.
            $email=filter_input(INPUT_GET,"ie",FILTER_SANITIZE_SPECIAL_CHARS);
            $mdp = filter_input(INPUT_GET,"mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        }
        else{
             $email = $_SESSION["IDemail"];
             $mdp = $_SESSION["IDmdp"];
        }         
?>


<!DOCTYPE html>
<!--
la page acceuil contient les parties pour modifier les coordo et les competences . et affichier les commentaires suivi
-->
<html>
    <head>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="style.css" rel="stylesheet" type="text/css" />
        
        <title>Acceuil</title>
    </head>
    <body>
        </form>
            <form action='resultatDeconnexion.php'>
            <input type='submit' value='Deconnexion' />
			</form>
        <?php
        
        // Afficher le nom et le prénom de ce membre
        // partie bienvenu en-tête
        $chercherMembre = retrouverMembre($cx, $email);
        
        $PseudoM = $chercherMembre["PseudoM"];
        
        $chercherEmail = emailExiste($cx, $email);
        $chercherMDP = verifierMDP($cx, $email, $mdp);
        
        if ($chercherEmail == TRUE){
            if ($chercherMDP == TRUE){
                
            echo("<h1>Bienvenue!$PseudoM </h1>");
            echo ("<div id='box'>");
            echo("<div class='menu'>");
            echo("    
            <ul>
                <li><strong>Acceuil</strong></li>
                <li><a href='monProfil.php?ie=$email&mdp=$mdp'>Mon profil</a></li>
                <li><a href='P5_chercherParCom.php?ie=$email&mdp=$mdp'>Chercher membre par compétences</a></li>
                <li><a href='chercherParEmail.php?ie=$email&mdp=$mdp'>Chercher membre par email</a></li>
            </ul>");
           echo("</div>");
           echo("<div class='centre'>");
            
            
        // partie des liens pour modifier les coordonées d'un membre                  
        echo ("
        <h1> Gerer mon compte </h1>
        <ul type='circle' class = 'modifier'>
            <li><a href='modificationProfil.php?ie=$email&mdp=$mdp'>Gerer mes coordonnee</a></li>
            <li><a href='m2_update.php?ie=$email&mdp=$mdp'>Changer mes competences</a></li>
            <li><a href='m3_ajouter.php?ie=$email&mdp=$mdp'>Ajouter un competence</a></li>
            <li><a href='m4_supprimer.php?ie=$email&mdp=$mdp'>Supprimmer un competence</a></li>
        </ul>	");
           echo("<div class='centre'>");   
           
        // partie pour voir tous les commentaires publiées par les membres qui sont suivi par lui.
        // cette partie pour afficher les commentaires est  comme un model afin de réutiliser 
        // dans le profil personnel et dans le profil d'un autre membre. 
        // le pensé et tous les requêtes utiliées sont similaires et seulement celui-ci on ajoute 
        // un verification de demande de suivre. dans les autres pages , on change les lieux pour mettre 
        // les buttons et les images (utilisée comme les liens) pour contrôler les differents niveau du intéraction.
           echo("<h1>Commentaires Suivis</h1>");
           
           $listsuivre = listsuivre($cx, $email);
           if ($listsuivre != NULL) {
            //obtenir tous les membres qui sont abonnés par 'moi'
            foreach ($listsuivre as $suivre) // boucle pour obtenir tous les commentaires du chaque membre 
            {   // pour obtenir tous les commentaires publiées par un membre qui est identifié par son adresse d'email 
                $emailS = $suivre["EmailM"]; 
		$listcommentaire = GetMesCommentaire($cx, $emailS);
            
            //Si il existe des commentaires publiées par ce membre qui est abonnés par 'moi'：
            if ($listcommentaire != NULL){
                foreach ($listcommentaire as $commentaire){
                 //get le code d'un commentaire
                $CodeCom = $commentaire["CodeCom"];
                $CodeCp = (int)$CodeCom; // string - integer

                // commencer un table pour exécuter les commentaires                        
                echo ("<table border = 0 width='600px' cellpadding='5'> <tr>");
                // obtenir la liste de réponse pour les commentaires et la liste de commentaire qui sont répondus 
                $listSouscom = GetReponse($cx, $CodeCp);
                $listor = GetOrigne($cx, $CodeCp);
                // noter les informations pour une commentaire
                $NomM = $commentaire["NomM"];
                $DateC= $commentaire["DateC"];
                $Contenu= $commentaire["Contenu"];

                if ($listor == NULL) {
                        //si le liste de commentaire est vide, alors ce commentaire n'a pas de réponse
                        echo ("<tr>");
                                // executer les infos du commentaires
                                echo("<td>"); 
                                echo("<h3>");
                                echo($DateC);
                                echo("</h3>");
                                echo ("</td>");

                                echo("<td>");
                                echo("<img src='images/222.png' width='30' height='25'/>");
                                echo("<h3>");
                                echo("<strong>");
                                echo ($NomM);
                                echo (" a dit : ");
                                echo("</strong>");
                                echo ($Contenu);
                                echo("</h3>");
                                echo ("</td>");
                                
                                echo ("<td>");
                                if(existelike($cx, $email, $CodeCp) == TRUE){ // vérifier si j'ai déjà apprécié ça
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&ies=$emailS&mdp=$mdp'>");                                
                                    echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                                }else{
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&ies=$emailS&mdp=$mdp'>");                                
                                    echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                                }  
                                ////en haut pour apprécier un commentaire, en bas pour rédiger un réponse

                                echo ("<img src='images/3.png' width='30' height='25' title='Repondre';'/>");
                                echo ("</td>");

                        echo ("</tr>"); 

                             // premier ligne de table est fini
                                        echo ('<div id="formreponse">
                                        <form name="reponse" id="areareponse" action="publiercommentaire.php">
                                        <textarea name="txtreponse" class="PlaceCommenter"rows="4" cols="20" placeholder="Metter votre reponse ici" maxlength="140" required></textarea>');
                                        echo  "<input type='hidden' name='ie' value='$email' />"; 
                                        echo  "<input type='hidden' name='co' value='$CodeCp' />"; 
                                        echo  "<input type='hidden' name='ies' value='$emailS' />";
                                        echo  "<input type='hidden' name='mdp' value='$mdp' />";
                                        echo ('<input type="submit" value="Envoyer" />
                                        </form>
                                         </div>');    
                                        
                             //---->chaque fois, vérifier si il y a les réponses pour ce message                    
                            if( $listSouscom != NULL ){
                                //la liste de réponse n'est pas vide du coup il existe les réponse pour lui
                                foreach($listSouscom as $reponse)
                                {   
                                    echo ("<tr>");
            
                                    echo ("<td>");
                                    $DateReponse = $reponse["DateC"];
                                    echo("<h3>");
                                    echo ("   ".$DateReponse);
                                    echo("</h3>");
                                    echo("</td>");

                                    echo ("<td>");echo ("   ");
                                    // afficher qui a répondu ce message et le contenu du réponse
                                    echo ("<img src='images/555.png' width='30' height='25'/>");
                                    $NomReponse = $reponse["NomM"];
                                    echo("<h3>");
                                    echo("<strong>");
                                    echo ($NomReponse."@".$NomM." : ");
                                    echo("</strong>");
                                    echo ($reponse["Contenu"]);    
                                    echo("</h3>");
                                    echo ("</td>");
                                    $code = $reponse["CodeCom"];
                                echo ("<td>");
                                if(existelike($cx, $email, $code) == TRUE){ // 'moi' peut aussi apprécier ce réponse
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                                }else{
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                                }  
                                //// mais ne peut pas le répondre
                                echo ("</td>");
 
                                    echo ("</tr>");//le ligne pour exécuter les réponses correspondant est fini.
                                   // les lignes pour affchier tous les 'commentaires' sont finis.
                            }}
                    }
                    else{
                        //si le liste de commentaire n'est pas vide, alors ce commentaire est réponse d'un autre message
                        foreach($listor as $origne){
                                echo ("<tr>");
                        
                                echo ("<td> </td>");
                                // obtenir les infos pour le message origine
                                echo ("<td>");
                                echo("<h3>");    
                                echo ("<strong>".$origne["NomM"]." a dit </strong>".$origne["Contenu"]);
                                echo("</h3>"); 
                                echo ("</td>");            
                                echo ("<td>");echo ("</td>"); // je ne peux pas interagir
                        
                        echo ("</tr>");// ligne pour afficher message origine est fini.
                        
                        echo ("<tr>");

                                echo ("<td>"); 
                                echo("<h3>");
                                echo ($DateC); 
                                echo("</h3>");
                                echo ("</td>");
                                // afficher le commentaire(réponse du message origine)
                                echo ("<td>");
                                echo ("<img src='images/555.png' width='30' height='25'/>");
                                echo("<h3>");
                                echo("<strong>");                                
                                echo ($NomM."@".$origne["NomM"]." : ");
                                echo("</strong>");
                                echo ($Contenu);                            
                                echo("</h3>");                             
                                echo ("</td>");
                                // 'je' peux apprecier et répondre à ce commentaire
                                echo ("<td>");
                                if(existelike($cx, $email, $CodeCp) == TRUE){ 
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                                }else{
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                                }  
                                echo ("<img src='images/3.png' width='30' height='25' title='Repondre';'/>");
                                echo ("</td>");
                        echo ("</tr>"); //le ligne est fini.
                            
                                        echo ('<div id="formreponse">
                                        <form name="reponse" id="areareponse" action="publiercommentaire.php">
                                        <textarea name="txtreponse" class="PlaceCommenter"rows="4" cols="20" placeholder="Metter votre reponse ici" maxlength="140" required></textarea>');
                                        echo  "<input type='hidden' name='ie' value='$email' />"; 
                                        echo  "<input type='hidden' name='co' value='$CodeCp' />"; 
                                        echo  "<input type='hidden' name='ies' value='$emailS' />";
                                        echo  "<input type='hidden' name='mdp' value='$mdp' />";
                                        echo ('<input type="submit" value="Envoyer" />
                                        </form>
                                         </div>');    

                          // vérifier et exposer les sous-commentaire = réponse                   
                            if( $listSouscom != NULL ){
                                foreach($listSouscom as $reponse)
                                {   
                                    echo ("<tr>");
            
                                    echo ("<td>");
                                   
                                                                     
                                    $DateReponse = $reponse["DateC"];
                                    echo("<h3>");
                                    echo ("   ".$DateReponse);
                                    echo("</h3>");
                                                                    
                                    echo("</td>");

                                    echo ("<td>");echo ("   ");
                                    
                                    echo ("<img src='images/555.png' width='30' height='25'/>");
                                    $NomReponse = $reponse["NomM"];
                                 echo("<h3>");
                                echo("<strong>");                                   
                                    echo ($NomReponse."@".$NomM." : ");
                                     echo("</strong>");
                                    echo ($reponse["Contenu"]);   
                                   
                                    echo("</h3>");
                                    echo ("</td>");
                                    // je peux seulement voir et l'apprécier.
                                echo ("<td>");
									$code = $reponse["CodeCom"];
                                if(existelike($cx, $email, $code) == TRUE){ 
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                                }else{
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&ies=$emailS'>");                                
                                    echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                                }
                                echo ("</td>"); 
                                    echo ("</tr>");//ligne fini
                                   //les lignes pour affchier tous les 'réponses' sont finis.
                            }}
                        
                    }
                  }
                }} // tous les commentaires ceux que 'je' demande à voir sont exposés.
            else { // si ce membre n'a pas rédiger les commentaires 
                echo("<h2>");
               echo ("Les membres que vous avez suivi n'ont pas des commentaires.");
               echo("</h2>");
            }
           }            
		   }
		   /////la liste des membres suivis est vide
		   else
           {
               echo("<h2>");
               echo ("Vous n'a pas suivi quelqu'un.");
               echo("</h2>");
           }
        }else{
            echo("<h2>erreur mot de passe</h2>");
            echo("<h2><a href = index.php>à modifier</a></h2>");}
        
        }
        ?>
	
        
        
    </body>
</html>
