<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 3 || !isset($_POST['username'])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {
    try {
        $username = antiInjection($_POST['username']);
        $tipo = antiInjection($_POST['tipo']);
        $copertura = antiInjection($_POST['copertura']);
        $password = md5($username);

        $querySql = " INSERT INTO corriere (username, password, tipo, copertura) 
        VALUES ('$username', '$password', '$tipo', '$copertura')";

        $queryRes = mysqli_query($msConn, $querySql);

        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/admin-corrieri/gestione-corrieri.php');
    } catch (Exception $exc) {
        echo $exc->getMessage();
        //header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/admin-corrieri/gestione-corrieri.php?err=1&m='.$exc->getMessage());
    }
}
