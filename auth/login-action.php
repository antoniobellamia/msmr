<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

$username = antiInjection($_POST['username']);
$password = md5($_POST['password']);
$id = 0;

if(isset($_POST['corr']) && $_POST['corr'] == 'on') $cat = 2;
else $cat = 0;


if ( $cat == 0 ) {

    $querySql = "SELECT `id`, `username`, `password`, `tipo` FROM  `utente` WHERE `username` = '$username' AND `password` = '$password'";
    
    try{
        $queryRes = mysqli_query($msConn, $querySql);

        if (mysqli_num_rows($queryRes) != 1) throw new Exception('err1');

        $res = mysqli_fetch_assoc($queryRes); // Non fetch_row
        $tipo = $res['tipo'];
        $id = $res['id'];

    }catch(Exception $exc){
        $cat = -1;
        header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/login.php?err=1");
        die();
    }
    
}else{
    $querySql = "SELECT `id`, `username`, `password`, `tipo` FROM  `corriere` WHERE `username` = '$username' AND `password` = '$password'";
    
    try{
        $queryRes = mysqli_query($msConn, $querySql);
        
        if (mysqli_num_rows($queryRes) != 1) throw new Exception('err1');

        $res = mysqli_fetch_assoc($queryRes); // Non fetch_row
        $tipo = $res['tipo'];
        $id = $res['id'];

    }catch(Exception $exc){
        $cat = -1;
        header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/login.php?err=1");
        die();
    }
}

if($tipo == "admin") $cat += 1;

/** CAT
 *  0 : Utente  (0)
 *  1 : Super-Admin (0+1)
 *  2 : Corriere (2)
 *  3 : Admin Corriere (2+1)
 */


session_start();

$_SESSION['id'] = $id;
$_SESSION['username'] = $username;
$_SESSION['tipo'] = $cat;

switch($cat){
    case 0: header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/cliente/"); break;
    case 1: header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/admin/"); break;
    case 2: header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/corriere/"); break;
    case 3: header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/admin-corrieri/"); break;
    default: header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/login.php?err=1"); break;
}

mysqli_close($geoConn);
mysqli_close($msConn);


