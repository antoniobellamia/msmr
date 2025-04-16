<?php

session_start();

if (empty($_SESSION["id"])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
} else{
    $userId = $_SESSION["id"];
} 

?>

<!DOCTYPE html>
<html>

<head>
    <title>First Access</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>

    <div class="align-center">
        <h1>Modifica il tuo indirizzo di spedizione.</h1>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/indirizzo.php' ?>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>