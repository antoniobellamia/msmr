<?php
    session_start();

    if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 3) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
        die();
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>HOMEPAGE</title>
        <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar-IN.php' ?>
    <!--BODY-->

    <h1 class="align-center">ADMIN - CORRIERE</h1>

    <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/footer.php' ?>