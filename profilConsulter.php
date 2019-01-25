<?php
// charger la fichier des fonctions *require : trouver pas et s'arrête là/include : trouver pas et continuer
        require("functionUtiles.php");
        
// executer la fonction de connexion
        $cx = connexionChoix();
    
// executer la session        
        session_start();
        $IDmdp = filter_input(INPUT_GET,"txt_mdp",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDmdp"] = $IDmdp; 
        
        $IDemail = filter_input(INPUT_GET,"txt_email",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemail"] = $IDemail; 
        
        $IDemailChercher = filter_input(INPUT_GET,"txt_emailChercher",FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["IDemailChercher"] = $IDemailChercher;
// vérifier l'origine des pages pour executer la session ou obtenir les paramètres        
        if(empty($_SESSION["IDmdp"])){ //si n'existe pas les valeurs des sessions, la page précédent est transmis par un URL.
			 $email = $_GET["ie"];
			 $EmailChercher=$_GET["iec"];
			 $mdp=$_GET["mdp"];
        }
        else{
             $email = $_SESSION["IDemail"];
             $mdp = $_SESSION["IDmdp"];
             $EmailChercher = $_SESSION["IDemailChercher"];
        }  
?>
<!DOCTYPE html>
<!--
cette page est le profil d'un autre membre. il contient trois parties : les donneés personnels, les compétences déclarés, les recommandation et les commmentaires 
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        
        <title>Profil Consulter</title>
    </head>
    <body>

        
        <?php
        
       
        
// Afficher les coordonnées de ce membre
	 echo(" <form action='resultatDeconnexion.php'>");
         echo("<input type='submit' value='Deconnexion' />");
         echo("</form>");
// vérifier si le visiteur a suivi ce membre ou pas . donner un images different
         if(existeSuivre($cx, $email, $EmailChercher) == TRUE){ // 
            echo ("<a href = 'changesuivre.php?ie=$email&iec=$EmailChercher&mdp=$mdp'>");                                
            echo ("<img src='images/s2.png' width='120' height='60' title=''/></a>");
         }else{
            echo ("<a href = 'changesuivre.php?ie=$email&iec=$EmailChercher&mdp=$mdp'>");                                
            echo ("<img src='images/s1.png' width='120' height='60' title='Suivre'/></a>");
         } 
         
         
// coor de ce membre         
        echo("<h1>Consultation du Profil</h1>");
        $nuplet_profil1 = retrouverMembre($cx, $EmailChercher);
        
        $NomM = $nuplet_profil1["NomM"];
        $PrenomM = $nuplet_profil1["PrenomM"];
        $PseudoM = $nuplet_profil1["PseudoM"];
       
        echo("<div id='box'>");
        echo("<div class='menu'>");
        echo("<a href='P2_PageAcceuil.php?ie=$email&mdp=$mdp'>Retour à la page acceuil</a>");
        echo("</div>");
        echo("<h1>Ses coordonnées</h1>");
        echo("<h2><strong>Nom :</strong> $NomM </h2>");
        echo("<h2><strong>Prenom :</strong> $PrenomM </h2>");
        echo("<h2><strong>Pseudo :</strong> $PseudoM </h2>");
        echo("<h2><strong>Email :</strong> $EmailChercher </h2>");

        ?>
        
<!--partie : afficher ses compétences-->        
<h1>Ses compétences </h1>
	<h2><strong>Compétences Declarées</strong></h2>
	<?php  //mettre les compétences, les niveaux et les types de compétences dans un tableau
		$competenceD=CompetenceDeclare($cx, $EmailChercher);
		if ($competenceD != NULL){
                    foreach($competenceD as $detailcompetence){
			echo("<h2>");
			echo($detailcompetence["NomC"] ."(".$detailcompetence["LibelleN"].")");
			echo("</h2>");
                    }
		}else {
                        echo("<h2>");
                        echo ("Pas de résultat");
                        echo("</h2>");
		} 
	?>
        <h2><strong>Compétences Recommandées</strong></h2>        
	<?php
	 $email=$_GET["ie"];
        $mdp=$_GET["mdp"];
        $EmailChercher=$_GET["iec"];	
        
        $competenceR= CompetenceRecommande($cx, $EmailChercher);
                    if ($competenceR != NULL){
			foreach($competenceR as $detailcompetence){
			echo("<h2>");
			echo($detailcompetence['NomC']."(   Recommandées ".$detailcompetence["NbRecom"]."  fois)");
			echo("</h2>");
                        }	
                     }else {
			echo("<h2>");
			echo ("Pas de résultat");
			echo("</h2>");
                    }  
	?>
 <!--partie : recommander son compétence--> 
</br>
<h1>Recommander des competence </h1>
    <?php
            echo("<form action='ResultatRecommand.php'>");
            $sql_touscom = "SELECT CodeC, NomC FROM Competence";
            $curseur_touscom = mysqli_query($cx, $sql_touscom);
 //  tous les compétences dans le table Compétence           
             while($nuplet_touscom = mysqli_fetch_array($curseur_touscom)){
                $Nomtouscom = $nuplet_touscom["NomC"];
                $Codetouscom = $nuplet_touscom["CodeC"];
   // trouver les compétences recommandées par les autres membres              
                $trouverRecommande ="SELECT CodeC "
                        . "FROM Recommander,Membres as Membres1, Membres as Membres2 "
                        . "WHERE Membres1.EmailM='$email' AND Membres2.EmailM='$EmailChercher' "
                        . "AND Recommander.CodeMRe=Membres1.CodeM "
                        . "AND Recommander.CodeMestRe=Membres2.CodeM";
                
                $curseur_trouverRecommande = mysqli_query($cx, $trouverRecommande);
                $nuplet_trouverRecommande = mysqli_fetch_array($curseur_trouverRecommande);
                
     // si le compétence recommandée n'est pas égal à le compétence dans la liste   
     
                 if ($nuplet_trouverRecommande["CodeC"] != $Codetouscom){
                        echo("<h2>");
                        echo("<input type='checkbox' name='tabCom[]' value='$Codetouscom' />");
                        echo($Nomtouscom);
                        echo("</h2>");
		}else{
                        echo("<h2>");
			echo("<input type='checkbox' value='' disabled='true'/>");
			echo($Nomtouscom);
			echo("</h2>");
             }}
 
                        echo("<input type='hidden' name='ie' value='$email'/>");
                        echo("<input type='hidden' name='mdp' value='$mdp'/>");
                        echo("<input type='hidden' name='iec' value='$EmailChercher'/>");
                        echo("<input type='submit' value='Recommander'/>");
                 
            echo("</form>");
	?>
			
<h1>Ses commentaires </h1>
<!--TABLE commence. meme idée qui est déjà utilisé dans la page acceuil-->
 <?php	// utiliser son email pour recevoir tous ses commentaires
	$listcommentaire = GetMesCommentaire($cx, $EmailChercher);			
    if ($listcommentaire != NULL){ // il a les commentaires 
        foreach ($listcommentaire as $commentaire){ // boucle -- chaque commentaire
			$CodeCom = $commentaire["CodeCom"];
			$CodeCp = (int)$CodeCom;
			// oublier pas de mettre le paramètre de son adresse email
                                        echo ('<div id="formreponse" style="display:none">
                                        <form name="reponse"  id="areareponse" action="publiercommentaire.php">
                                        <textarea name="txtreponse" class="PlaceCommenter"rows="4" cols="20" placeholder="Mettrer votre reponse ici" maxlength="140" required></textarea>');
                                        echo  "<input type='hidden' name='ie' value='$email' />"; 
                                        echo  "<input type='hidden' name='iec' value='$EmailChercher' />";
                                        echo  "<input type='hidden' name='co' value='$CodeCp' />"; // visiteur peut donner leurs avis sur son commentaire
                                        echo  "<input type='hidden' name='mdp' value='$mdp' />"; 
                                        echo ('<input type="submit" value="Envoyer" />
					</form></div>'); 
//////table                                                                               
	echo ("<table border = 0 width='800px' cellpadding='5'>");
                        $listSouscom = GetReponse($cx, $CodeCp);
			$listor = GetOrigne($cx, $CodeCp);
                
			$NomM = $commentaire["NomM"];
			$DateC= $commentaire["DateC"];
			$Contenu= $commentaire["Contenu"];
			
		if ($listor == NULL) {
                    echo ("<tr>");

                    echo ("<td>"); 
                    echo ("<h3>");
                    echo ($DateC); 
                    echo ("</h3>");                                                                                                      
                    echo ("</td>");
                                
                    echo ("<td>");
                    echo ("<img src='images/222.png' width='25' height='30'/>");
                                
                    echo ("<h3>");       
                    echo ("<strong>");
                    echo ($NomM);echo (" a dit : ");
                    echo ("</strong>");
                    echo ($Contenu);
                    echo("</h3>");
                    echo ("</td>");
                                
                    echo ("<td>");	// visteur peut apprécier et envoyer un réponse pour un commentaire publié par ce membre
                    if(existelike($cx, $email, $CodeCp) == TRUE){ 
                        echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&mdp=$mdp&iec=$EmailChercher'>");                                
                        echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                    }else{
                        echo ("<a href = 'changelikes.php?ie=$email&co=$CodeCp&mdp=$mdp&iec=$EmailChercher'>");                                
                        echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                    }  
					echo ("<img src='images/3.png' width='50' height='30' title='Repondre' onclick='show();'/>");
                    echo ("</td>");
							echo (" <script>
									var form=document.getElementById('formreponse');
									function show(){
									if(form.style.display=='none'){
                                        form.style.display='block';
                                        }else{
                                        form.style.display='none';
                                        }
									}</script>");  
                    echo ("</tr>");                   
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
                                    echo ("<img src='images/555.png' width='30' height='30'/>");
                                    $NomReponse = $reponse["NomM"];
                                    
                                    echo("<h3>");
                                    echo("<strong>");
                                    echo ($NomReponse."@".$NomM." : ");
                                    echo("</strong>");
                                    echo ($reponse["Contenu"]);    
                                    echo("</h3>");
                                    echo ("</td>");
                                    
				$code = $reponse["CodeCom"];
                                echo ("<td>");	// pour tous les réponses correspondants , les visiteurs peuvent seulement les apprécier
                                if(existelike($cx, $email, $code) == TRUE){ 
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&iec=$EmailChercher'>");                                
                                    echo ("<img src='images/2.png' width='30' height='25' title='J`aime'/></a>");
                                }else{
                                    echo ("<a href = 'changelikes.php?ie=$email&co=$code&mdp=$mdp&iec=$EmailChercher'>");                                
                                    echo ("<img src='images/1.png' width='30' height='25' title='J`aime'/></a>");
                                }  
                                echo ("</td>");
							echo ("</tr>");
				}  
			} // end for reponse
                    }
        echo ("</table>") ;  
        echo "<br>";
////end of table			
        }
    } else { 
            echo("<h2>");
            echo ("Pas de commentaires");
            echo("</h2>");
    }
  ?>
 </div>
</div>
    </body>
</html>