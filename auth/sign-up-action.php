<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

$username = antiInjection($_POST['username']);
$password = md5($_POST['password']);
$tel = antiInjection($_POST['tel']);
$nome = antiInjection($_POST['nome']);
$cognome = antiInjection($_POST['cognome']);





if ($msConn) {
    $querySql = "INSERT INTO `utente` (`id`, `username`, `password`, `telefono`, `cognome`, `nome`, `tipo`, `cod_istat`, `cap`, `indirizzo`) VALUES (NULL, '$username', '$password', '$tel', '$cognome', '$nome', 'cliente', NULL, NULL, NULL)";
    
    try{
        $queryRes = mysqli_query($msConn, $querySql);

        if(!$queryRes) header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/signup.php?err=1");
        
    }catch(Exception $exc){
        header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/signup.php?err=1");
    }
    
}


if ($msConn) {
    $querySql = "SELECT `id` FROM `utente` WHERE `username` = '$username' AND  `password` = '$password'";
    
    $queryRes = mysqli_query($msConn, $querySql);


    if ($queryRes) {
        if (mysqli_num_rows($queryRes) === 1) {

            $row = mysqli_fetch_assoc($queryRes);
            

            session_start();

            $_SESSION["id"] = $row["id"];
            header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/indirizzo-sign.php");
        } else {
            header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/signup.php?err=1");
        }
    } else {
        header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/signup.php?err=1");
    }
}

mysqli_close($geoConn);
mysqli_close($msConn);


