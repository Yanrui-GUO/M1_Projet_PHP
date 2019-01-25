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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <link href="style.css" rel="stylesheet" type="text/css" />
        <div id="box">
        <?php
        session_start();
        session_unset();
        session_destroy();
        echo("<h2>Vous avez deconnexion, la page va sauter a la premiere page apres 3 secondes</h2>");
        echo("<meta http-equiv='refresh' content ='3 ; URL = index.php'>");
   
        ?>
        </div>
    </body>
</html>
