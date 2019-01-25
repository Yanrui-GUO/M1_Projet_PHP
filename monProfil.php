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
cette page est le profil d'un membre. il contient trois parties : mes donneés personnels, mes compétences déclarés, et les commmentaires dont j'ai publié
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="style.css" rel="stylesheet" type="text/css" />
        <title>Mon Profil</title>
    </head>
    <body>
        
        
       <?php
        $mdp = $_GET["mdp"];
        $email = $_GET["ie"];

 // en-tête: Afficher les coordonnées de ce membre 	
        echo("<h1>Mon Profil</h1>"); 
     
	echo("<div id='box'>");
        $nuplet_profil1 = retrouverMembre($cx, $email);
        
        $NomM = $nuplet_profil1["NomM"];
        $PrenomM = $nuplet_profil1["PrenomM"];
        $PseudoM = $nuplet_profil1["PseudoM"];
       
        echo("<div class='menu'>");
        echo("    
            <ul>
                <li><a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Acceuil</a></li>
                <li><strong>Mon profil</strong></li>
                <li><a href='P5_chercherParCom.php?ie=$email&mdp=$mdp'>Chercher membre par compétences</a></li>
                <li><a href='chercherParEmail.php?ie=$email&mdp=$mdp'>Chercher membre par email</a></li>                              
            </ul>");
        echo("</div>");
		   
		   
// partie : mes données personnels	       
 
        echo("<h1>Mes coordonnées</h1>");
        echo("<h2><strong>Nom :</strong> $NomM </h2>");
        echo("<h2><strong>Prenom :</strong>  $PrenomM </h2>");
        echo("<h2><strong>Pseudo :</strong>  $PseudoM </h2>");
        echo("<h2><strong>Email :</strong>  $email </h2>");

        ?>
		

        <h1>Mes compétences </h1>
        <h2><strong>Compétences Declarées</strong></h2>
      
        <?php    
// partie : mes compétences déclarés        
// l'utilisateur peut voir tous les compétences déclarés          
//mettre les compétences, les niveaux et les types de compétences dans un tableau
        $competenceD=CompetenceDeclare($cx, $email);	// function pour trouver les compétences déclarés
        if ($competenceD != NULL){
        foreach($competenceD as $detailcompetence)
        {   
            echo("<h2>");
            echo($detailcompetence["NomC"] ."(".$detailcompetence["LibelleN"].")");
            echo("</h2>");
        }}	// les afficher 
        else {
            echo("<h2>");
            echo ("Pas de résultat");	// pas de compétences déclarés
            echo("</h2>");
        }

        ?>
		
       
        <h2><strong>Compétences Recommandées</strong></h2>        
        <?php
// et les compétences recommandées par les autres membres         
        $competenceR= CompetenceRecommande($cx, $email);
        if ($competenceR != NULL){
        foreach($competenceR as $detailcompetence)
        {   
            echo("<h2>");
            echo($detailcompetence["NomC"]."( recommandées   " .$detailcompetence["NbRecom"]."  fois)");
            echo("</h2>");
        }}
        else {
            echo("<h2>");
            echo ("Pas de résultat");
            echo("</h2>");
        }
    ?>
<!--partie : les commmentaires dont j'ai publié -->
        </br>
        <h1>Mes commentaires </h1>
<!-- premierement, ce membre peut publier son commentaire ici.   -->     
        <form name="compropore" action="publiercommentaire.php" >
            <textarea name="txtreponse" class="PlaceCommenter" rows="4" cols="20" placeholder="Quoi de neuf? " maxlength="140" required></textarea>
        <?php echo  ("<input type='hidden' name='ie' value='$email' />");
		echo("<input type='hidden' name='mdp' value='$mdp' />");
         // pour enregistrer les sessions                 ?>	
		<input type="submit" value="Publier" />
		</form>	
                 
<!-- et après, il peut voir tous les commentaires déjà publiés par lui-même	-->		
        <!--table commence. meme idée qui est déjà utilisé dans la page acceuil-->                
        <?php
//obtenir la liste de mes commentaires 
        $listcommentaire = GetMesCommentaire($cx, $email);
//si existe mes commentaires ：
        if ($listcommentaire != NULL){
            foreach ($listcommentaire as $commentaire){ // boucle -- chanque commentaire
//code de ce commentaire
		$CodeCom = $commentaire["CodeCom"];
		$CodeCp = (int)$CodeCom;
// chaque commentaire est mis dans un table
                echo ("<table border = 0 width='800px' cellpadding='5'>");
            //vérifier le statut de commentaire
                $listSouscom = GetReponse($cx, $CodeCp);
                $listor = GetOrigne($cx, $CodeCp);
            // infos du commentaire
                $NomM = $commentaire["NomM"];
                $DateC= $commentaire["DateC"];
                $Contenu= $commentaire["Contenu"];
                    if ($listor == NULL) {
                        //un message publié
                        echo ("<tr>");                               
                            echo ("<td>"); 
                            echo("<h3>");
                            echo ($DateC); 
                            echo("</h3>");
                            echo ("</td>"); // premier colonne- date                             
                                
                            echo ("<td>");
                            echo ("<img src='images/222.png' width='25' height='30'/>");
                            echo("<h3>");
                            echo("<strong>");
                            echo ($NomM);echo (" a dit : ");
                            echo("</strong>");
                            echo ($Contenu);
                            echo("</h3>");
                            echo ("</td>");	// 2 : contenu
                            
                            echo ("<td></td>");  
                        echo ("</tr>"); // 1 ligne fini
                         
                            //----> existe des réponses pour ce message?                   
                            if( $listSouscom != NULL ){
                                // si
                                foreach($listSouscom as $reponse)  // boucle -- chaque réponse
                                {   
                                    echo ("<tr>");
            
                                    echo ("<td>");
                                    $DateReponse = $reponse["DateC"];
                                    echo("<h3>");
                                    echo ("   ".$DateReponse);
                                    echo("</h3>");
                                    echo("</td>");
                                    echo ("<td>");echo ("   ");
                                    
                                    echo ("<img src='images/555.png' width='30' height='30'/>");
                                    $NomReponse = $reponse["NomM"];
                                    echo("<h3>");
                                    echo("<strong>");
                                    echo ($NomReponse."@".$NomM." : ");
                                    echo("</strong>");
                                    echo ($reponse["Contenu"]);       
                                    echo("</h3>");
                                    echo ("</td>");
									
					$codereponse = $reponse["CodeCom"]; // code de cette reponse
					// il peut apprécier et donner avis pour cette réponse
					echo ("<td>");
					if(existelike($cx, $email, $codereponse) == TRUE){ 
                                            echo ("<a href = 'changelikes.php?ie=$email&co=$codereponse&mdp=$mdp'>");                                
                                            echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
					}else{
                                            echo ("<a href = 'changelikes.php?ie=$email&co=$codereponse&mdp=$mdp'>");                                
                                            echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
					}  
					
                                        echo ("<img src='images/3.png' width='30' height='25' title='Repondre' onclick='show();'/>");
					echo ("</td>");
					echo ("</tr>");//  ligne pour réponse -- fini 									
							echo ("<script>
								var form=document.getElementById('formreponse');
								function show(){
                                                                    if(form.style.display=='none'){
									form.style.display='block';
                                                                    }else{
									form.style.display='none';
                                                                    }
								}</script>");

									
							// lieu pour rédiger un réponse
                                        echo ('<div id="formreponse" style="display:none">
                                        <form name="reponse" id="areareponse" action="publiercommentaire.php">
                                        <textarea name="txtreponse" class="PlaceCommenter" rows="4" cols="20" placeholder="Mettez votre reponse ici" maxlength="140" required></textarea>');
                                        echo  "<input type='hidden' name='ie' value='$email' />"; 
                                        echo  "<input type='hidden' name='co' value='$codereponse' />";
                                        echo  "<input type='hidden' name='mdp' value='$mdp' />"; 
                                        echo ('<input type="submit" value="Envoyer" />
                                        </form>
										</div>');
									
				} // end of foreach
                            }	// end of if -- existe réponse
                            else{	// aucune réponse pour ce message
                                echo ("<tr><td></td><td></td><td></td></tr>");// un ligne vide
                            } //end of else
                    } // end of partie commentaire publié
		echo "</table>";
                }// end of boucle -- chanque commentaire
            } // end of existe commentaire 
            // pas encore punblier un commentaires 
            else {
                echo ("<h2>Pas de commentaires</h2>");
            }
        ?>
		
		
    </body>
</html>


