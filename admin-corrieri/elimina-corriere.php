<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 3 || !isset($_GET['idCorr'])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {
    try {
        $idCorr = antiInjection($_GET['idCorr']);

        $querySql = " DELETE FROM corriere WHERE id = ".$idCorr." AND tipo <> 'admin';";

        $queryRes = mysqli_query($msConn, $querySql);

        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/admin-corrieri/gestione-corrieri.php');
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/admin-corrieri/gestione-corrieri.php?err=2&m='.$exc->getMessage());
    }
}
