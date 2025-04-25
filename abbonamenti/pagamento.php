<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/auth/login.php?err=3');
    die();
}

if (!isset($_POST['id_abbonamento'])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>PAGAMENTO</title>
        <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/styles/header-include.php' ?>
    </head>
    <body>
        <main style="text-align: center;">
            <img src="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/res/pagamento.jpg">
            <br><br>

            <form method="POST" action="abbonati-action.php">
                    <input type="hidden" name="id_abbonamento" value="<?= $_POST['id_abbonamento'] ?>" />
                    <button type="submit" class="pure-button pure-button-primary">Procedi con l'abbonamento.</button>
            </form>
        </main>
    </body>
</html>
