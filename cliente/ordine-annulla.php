<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1) || !isset($_GET['idOrdine'])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {

    try {
        $querySql = "
            SELECT id_utente_mitt AS mitt, isReso
            FROM ordine
            WHERE (id_utente_mitt = " . $_SESSION["id"] . " OR id_utente_dest = " . $_SESSION["id"] . ")
            AND id = " . $_GET['idOrdine'] . "
        ;";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {

            if($row['isReso']) throw new Exception("La spedizione è già un reso.");

            $tipo = $row['mitt'];

            if ($tipo == $_SESSION['id']) $content = "annullare";
            else $content = "effettuare il reso per";
        }
    } catch (Exception $exc) {
        $content = $exc->getMessage();
        header("location:javascript://history.go(-1)");
        die();

    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>ANNULLA ORDINE</title>

    <style>
        table {
            text-align: left;
            border: 0;
        }

        td {
            text-indent: 2rem;
        }

        @media screen and (max-width: 768px) {

            td,
            tr {
                display: block;
                width: 100%;
                text-indent: 0;
            }
        }
    </style>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-u-md-4-5 pure-u-1-1">

            <div class="w3-panel w3-red w3-display-container w3-center">
                <h3>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Vuoi davvero <?= $content ?> l'ordine #<?= $_GET['idOrdine'] ?>?
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </h3>
            </div>

            <?php

            if ($content == "annullare") $content = "annulla";
            else $content = "reso";

            ?>

            <form action="ordine-annulla-action.php" method="post" class="pure-form pure-form-aligned">
                <fieldset class="align-center">
                    <input type="hidden" value="<?= $content ?>" name="tipo">
                    <input type="hidden" value="<?= $_GET['idOrdine'] ?>" name="idOrdine">
                    <input type="submit" value="SI." class="pure-button pure-button-primary w3-green">
                    <input type="submit" value="NO" formaction="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/cliente" class="pure-button pure-button-primary">
                </fieldset>
            </form>





            <footer class="w3-container align-center">
                <?php if (isset($_GET["err"])) echo "Errore durante la modifica. Riprova <br>"; ?>
                <br>
            </footer>



        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>