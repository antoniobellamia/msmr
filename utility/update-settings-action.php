<?php

include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php';

if (empty($_SESSION["id"])){
  header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
  die();
}


include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';




if($_GET["optn"]==1){

    $username = antiInjection($_POST['username']);
    $tel = antiInjection($_POST['tel']);
    $nome = antiInjection($_POST['nome']);
    $cognome = antiInjection($_POST['cognome']);

    if ($msConn) {

        if ($username != $_SESSION["username"]) $querySql = "UPDATE `utente` SET `username` = '$username', `telefono` = '$tel', `nome` = '$nome', `cognome` = '$cognome' WHERE `id` = '" . $_SESSION["id"] . "'";
        else $querySql = "UPDATE `utente` SET `telefono` = '$tel', `nome` = '$nome', `cognome` = '$cognome' WHERE `id` = '" . $_SESSION['id'] . "'";

          
        try{
            $queryRes = mysqli_query($msConn, $querySql);
    
            if(!$queryRes) header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=1&err=1");
            else{
                $_SESSION['username'] = $username;
                header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=1&err=0");
            } 
            
        }catch(Exception $exc){
            //echo $exc->getMessage();
            header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=1&err=1");
        }
        
    }



}else if ($_GET["optn"]==2){

    $old = md5($_POST['old']);
    $new = md5($_POST['new']);


    if ($msConn) {

        $querySql = "SELECT `password`FROM  `utente` WHERE `id` = '" . $_SESSION['id'] . "' AND `password` = '$old'";
        
        try{
            $queryRes = mysqli_query($msConn, $querySql);

            if (mysqli_num_rows($queryRes) != 1) throw new Exception('err1');
    
            if(!$queryRes) throw new Exception('err1');


            $querySql = "UPDATE `utente` SET `password` = '$new' WHERE `id` = '" . $_SESSION['id'] . "'";
            $queryRes = mysqli_query($msConn, $querySql);   

            if(!$queryRes) throw new Exception('err1');
            else header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=2&err=0");

             
        }catch(Exception $exc){
            header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=2&err=1");
        }

    }
    
}

mysqli_close($geoConn);
mysqli_close($msConn);