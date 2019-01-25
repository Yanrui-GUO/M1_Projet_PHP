<?php

// definition des constantes de connexion
define("ID_MYSQL","21405117");
define("PASSE_MYSQL","P00M37");
define("HOST_MYSQL", "localhost");
define("BD_MYSQL","db_21405117");

/* fonction de connexion 
 * 
 * retour : object connexion 
 */

function connexionChoix(){
    $cx = mysqli_connect(HOST_MYSQL,ID_MYSQL,PASSE_MYSQL);
    
    if ($cx == NULL){
        die("Erreur connexion à MySQL ! ". mysqli_connect_error());
    } else { // connexion réussie
        if (mysqli_select_db($cx, BD_MYSQL) == false){
            die("Choix base impossible".mysqli_error($cx));
        }
        else{ // base est correctement - TOUT OK
            return $cx;
        }       
    }
}        
            

/*Vérification unicité email
 * 
 * connexion en parm entree
 * email en parm entree
 * boolean en sortie :  vrai si email existe sinon faux
 */
function emailExiste ($cx, $email)
{
    $sqlEmail = "SELECT * FROM Membres WHERE EmailM='$email'";
    
    $curseur = mysqli_query($cx, $sqlEmail);
    if ($curseur == FALSE){
            die("erreur fonction email");
    }else{
            if (mysqli_num_rows($curseur) != 0){
            return TRUE;
            }else{
            return FALSE;
            }
    }
}

/* Vérification le mot de passe
 * 
 * connexion en parm entree
 * email en parm entree
 * mot de passe en parm entree
 * boolean en sortie : vrai si le mot de passe est corret sinon faux
 */
function verifierMDP($cx,$email,$mdp){
    $SQLmdp= "SELECT MDP FROM Membres WHERE Membres.EmailM = '$email'";
    $curseur = mysqli_query($cx, $SQLmdp);
    
    if ($curseur == FALSE) {
        die ("Pas de MDP");}
    else {
         $rightMDP = mysqli_fetch_array($curseur);
         $corretMDP = $rightMDP["MDP"];
         
         if ($corretMDP == $mdp){
             return TRUE;
    } else{
             return FALSE;     
    }
    }
        

    
}

/* Trouver un membre par son email (car il est unique)
 *
 * connexion en parm entree
 * email en parm entree
 * boolean en sortie : vrai si on réussi à retrouver le membre sinon faux
 */
function retrouverMembre($cx, $email) {
    $sqlretrouverMembre = "SELECT CodeM, NomM, PrenomM, PseudoM FROM Membres WHERE EmailM = '$email'";

    $curseur = mysqli_query($cx, $sqlretrouverMembre);
    if ($curseur == FALSE) {
        die("Aucun résultat");
    } else {
        if (mysqli_num_rows($curseur) == 0) {
            return NULL;
        } else {
            return mysqli_fetch_array($curseur);
        }
    }
    
}

/* Trouver des compétences que un membre indique
 *
 * connexion en parm entree
 * email en parm entree
 * tableau en sortie : noms des compétences
 */
function CompetenceDeclare($cx, $email) {
    $sqlCompetenceDeclare = "SELECT C.CodeC,M.PseudoM, C.NomC, N.LibelleN,N.CodeN "
            . "FROM Membres AS M, Competence AS C, Niveau AS N, Indiquer AS I "
            . "WHERE M.EmailM = '$email' AND M.CodeM = I.CodeM AND C.CodeC = I.CodeC AND N.CodeN = I.CodeN";

    $curseur = mysqli_query($cx, $sqlCompetenceDeclare);
    if ($curseur == FALSE) {
        die("Aucun Compétence declaré");
    } else {
        if (mysqli_num_rows($curseur) == 0) {
            return NULL;
        } else {
            $i=0;
            $competencesD=array();
            while($nuplet= mysqli_fetch_array($curseur))
            {   
                $detailcompetence= array("CodeC" =>$nuplet["CodeC"],"NomC" =>$nuplet["NomC"], "CodeN" =>$nuplet["CodeN"],"LibelleN" =>$nuplet["LibelleN"]);
                $competencesD[$i]=$detailcompetence;
                $i++;
            }          
            return $competencesD;
        }
    }
    
}

/* Trouver des compétences que un membre est recommandé
 *
 * connexion en parm entree
 * email en parm entree
 * tableau en sortie :  noms des compétences
 */
function CompetenceRecommande($cx, $email) {
    $sqlCompetenceRecommande = "SELECT R.CodeMestRe, M.PseudoM, C.NomC, Count(R.CodeMRe) AS NbRecom "
            . "FROM Membres AS M, Competence AS C, Recommander AS R "
            . "WHERE M.EmailM = '$email' AND M.CodeM = R.CodeMestRe AND C.CodeC = R.CodeC GROUP BY R.CodeMestRe, M.PseudoM, C.NomC";

    $curseur = mysqli_query($cx, $sqlCompetenceRecommande);
    if ($curseur == FALSE) {
        die("Aucun Compétence recommandé");
    } else {
        if (mysqli_num_rows($curseur) == 0) {
            return NULL;
        } else {
            $i=0;
            $competenceR=array();
            while($nuplet=mysqli_fetch_array($curseur)){
                
                $detailcompetence = array("NomC"=>$nuplet["NomC"],"NbRecom"=>$nuplet["NbRecom"]);
                $competenceR[$i]=$detailcompetence;
                $i++;
            }
            return $competenceR;
        }
    }
    
}

/* Trouver tous les commentaires d'un membre
 *
 * connexion en parm entree
 * email en parm entree
 * tableau en sortie :  contenu, code et la date des commentaires
 */
function GetMesCommentaire ($cx,$email){
    $query = "SELECT Commentaires.DateC,Commentaires.Contenu,Commentaires.CodeCom,Membres.NomM
                FROM Membres,Commentaires
                WHERE Membres.CodeM = Commentaires.CodeM AND Membres.EmailM = '$email'
                ORDER BY Commentaires.DateC DESC";
    $curseur = mysqli_query($cx, $query);
    
    if ($curseur == FALSE) {
        die("Erreur");
    } else {
        if (mysqli_num_rows($curseur) == 0){
            return NULL;
         } else {
                $i=0;
                $commentaire = array();
                 while($nuplet = mysqli_fetch_array($curseur))
                 {
                  $detail = array ("CodeCom" => $nuplet["CodeCom"],"NomM" => $nuplet["NomM"],"Contenu" => $nuplet["Contenu"],"DateC" => $nuplet["DateC"]);
                  $commentaire[$i] = $detail;
                  $i++;
                 }
                 }
                 return $commentaire;
            }
}

/* Vérifier l'existence de reponse sur un commentaire
 * 
 * connexion en parm entree
 * email en parm entree
 * booleen en sortie : vrai si commentaire existe sinon faux
 */
function GetReponse ($cx,$CodeCom){
    $query ="SELECT com.DateC,com.Contenu,com.CodeCom,Membres.NomM
        FROM Commentaires AS com,Membres 
        WHERE com.CodeComRe = $CodeCom AND com.CodeM = Membres.CodeM  ORDER BY com.DateC DESC";

    $curseur = mysqli_query($cx, $query);
    
    if ($curseur == FALSE) {
        die("Erreur");
    } else {
        if (mysqli_num_rows($curseur) == 0){
            return NULL;
         } else {
                $i=0;
                $reponse = array();
                 while($nuplet = mysqli_fetch_array($curseur))
                 {
                  $detaile = array ("CodeCom" => $nuplet["CodeCom"],"NomM" => $nuplet["NomM"],"Contenu" => $nuplet["Contenu"],"DateC" => $nuplet["DateC"]);
                  $reponse[$i] = $detaile;
                  $i++;
                 }
                 }
                 return $reponse;
            }
}

/* Obtenir l'origine d'un reponse 
 * 
 * connexion en parm entree
 * code de compétence en parm entree
 * en sortie : competence original
 */

function  GetOrigne ($cx,$CodeCom){
    $query ="SELECT com.DateC,com.Contenu,com.CodeCom,Membres.NomM
        FROM Commentaires AS com,Membres 
        WHERE com.CodeM = Membres.CodeM AND com.CodeCom = 
        (SELECT Com2.CodeComRe
         FROM Commentaires AS Com2
      	 WHERE Com2.CodeCom = $CodeCom)
        ORDER BY com.DateC DESC";

    $curseur = mysqli_query($cx, $query);
    
    if ($curseur == FALSE) {
        die("Erreur");
    } else {
        if (mysqli_num_rows($curseur) == 0){
            return NULL;
         } else {
                $i=0;
                $origne = array();
                 while($nuplet = mysqli_fetch_array($curseur))
                 {
                  $detailo = array ("CodeCom" => $nuplet["CodeCom"],"NomM" => $nuplet["NomM"],"Contenu" => $nuplet["Contenu"],"DateC" => $nuplet["DateC"]);
                  $origne[$i] = $detailo;
                  $i++;
                 }
                 }
                 return $origne;
            }
}

/* Voir un membre aime ce commentaire ou pas
 * 
 * connexion en parm entree
 * email en parm entree
 * code de compétence en parm entree
 * booleen en sortie : si like vrai sinon faux
 */
function existelike ($cx, $email,$codecp)
{
    $sqlelike = "SELECT Likes.CodeM FROM Likes,Membres WHERE Likes.CodeM = Membres.CodeM AND Membres.EmailM = '$email' AND Likes.CodeCom = $codecp";
    
    $curseur = mysqli_query($cx, $sqlelike);
    if ($curseur == FALSE){
            die("erreur fonction trouver like");
    }else{
            if (mysqli_num_rows($curseur) != 0){ //s'il y a de résultat
            return TRUE;   //le membre a déjà aimé ce commentaire
            }else{
            return FALSE;
            }
    }
}

/* Voir un membre suivre ce membre ou pas
 * 
 * connexion en parm entree
 * email d'utilisation en parm entree
 * email d'un autre membre en parm entree
 * booleen en sortie : si suivre vrai sinon faux
 */
function existeSuivre ($cx, $email,$emailtrouve)
{
    $sqlesuivre = "SELECT s.* 
    FROM Suivre AS s,Membres AS m1 
    WHERE m1.CodeM = s.CodeMS AND m1.EmailM = '$email' 
    AND s.CodeMestS = 
    (SELECT m2.CodeM
    FROM Membres AS m2
    WHERE m2.EmailM ='$emailtrouve')";
    
    $curseur = mysqli_query($cx, $sqlesuivre);
    if ($curseur == FALSE){
            die("erreur fonction trouver like");
    }else{
            if (mysqli_num_rows($curseur) != 0){ //s'il y a de résultat
            return TRUE;   //l'utilisateur a déjà suivi ce membre
            }else{
            return FALSE;
            }
    }
}

/* retrouver tous les email dont les membres que l'utilisateur suivi
 * 
 * connexion en parm entree
 * email d'utilisation en parm entree
 * tableau en sortie : la liste de email
 */
function listsuivre ($cx,$email)
{   $query ="SELECT m1.EmailM
            FROM Suivre AS s,Membres AS m1 
            WHERE m1.CodeM = s.CodeMestS
            AND s.CodeMS = 
            (SELECT m2.CodeM
            FROM Membres AS m2
            WHERE m2.EmailM ='$email')";

    $curseur = mysqli_query($cx, $query);
    
    if ($curseur == FALSE) {
        die("Erreur");
    } else {
        if (mysqli_num_rows($curseur) == 0){
            return NULL;
         } else {
                $i=0;
                $suivre = array();
                 while($nuplet = mysqli_fetch_array($curseur))
                 {
                  $list = array ("EmailM" => $nuplet["EmailM"]);
                  $suivre[$i] = $list;
                  $i++;
                 }
                 }
                 return $suivre;
            }
    
}

/* retrouver tous les niveaux dans la BD pour choisir
 * 
 * connexion 
 * tableau en sortie : la liste de niveau
 */
function listNiveau($cx)
{
    $sql_inscrit2="SELECT CodeN, LibelleN FROM Niveau";
    $curseur_inscrit2 = mysqli_query($cx, $sql_inscrit2);
    
    if ($curseur_inscrit2 == FALSE) {
        die("Erreur function listniv");
    } else {
        if (mysqli_num_rows($curseur_inscrit2) == 0){
            return NULL;
         } else {
                $i=0;
                $listniv = array();
                 while($nuplet = mysqli_fetch_array($curseur_inscrit2))
                 {
                  $list = array ("CodeN" => $nuplet["CodeN"],"LibelleN" => $nuplet["LibelleN"]);
                  $listniv[$i] = $list;
                  $i++;
                 }
                 }
                 return $listniv;
            }
}

/* retrouver tous les compétences dans la BD pour choisir
 * 
 * connexion 
 * tableau en sortie : la liste de compétence
 */
function listCompetence($cx)
{
    $sql_inscrit2="SELECT CodeC, NomC FROM Competence";
    $curseur_inscrit2 = mysqli_query($cx, $sql_inscrit2);
    
    if ($curseur_inscrit2 == FALSE) {
        die("Erreur function listniv");
    } else {
        if (mysqli_num_rows($curseur_inscrit2) == 0){
            return NULL;
         } else {
                $i=0;
                $listcomp = array();
                 while($nuplet = mysqli_fetch_array($curseur_inscrit2))
                 {
                  $list = array ("CodeC" => $nuplet["CodeC"],"NomC" => $nuplet["NomC"]);
                  $listcomp[$i] = $list;
                  $i++;
                 }
                 }
                 return $listcomp;
            }
}
?>

