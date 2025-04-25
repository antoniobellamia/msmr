<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$id_utente = $_SESSION['id'];

if ($msConn) {
    try {
        // Disdetta l'abbonamento (setta "scaduto" a 1)
        $querySql = "
            UPDATE abbonamento
            SET scaduto = 1
            WHERE id_utente = $id_utente AND scaduto = 0
        ";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes) {
            throw new Exception('Errore nella disdetta dell\'abbonamento');
        } else {
            // Redirect alla pagina abbonamenti.php con un messaggio di successo
            header('Location: listino.php');
            die();
        }
    } catch (Exception $exc) {
        // In caso di errore, redirect sulla pagina degli errori
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/abbonamenti/?err');
        die();
    }
}
?>