<?php

session_start();

if (empty($_SESSION["userId"])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
} else {

    $userId = $_SESSION["userId"];
    $_SESSION["firstAccess"] = true;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>First Access</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>

    <div class="align-center">
        <h1>Inserisci il tuo indirizzo di spedizione.</h1>
        <p>Potrai cambiarlo in seguito.</p>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/indirizzo.php' ?>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>