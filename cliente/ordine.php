<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {

    try {
        $querySql = "
            SELECT o.*,  
            mitt.nome AS nome_mittente,
            mitt.cognome AS cognome_mittente,
            mitt.username AS username_mittente,
            dest.nome AS nome_destinatario,
            dest.cognome AS cognome_destinatario,
            dest.username AS username_destinatario
            FROM ordine o
            JOIN utente mitt ON o.id_utente_mitt = mitt.id
            JOIN utente dest ON o.id_utente_dest = dest.id
            WHERE o.id = " . $_GET["idOrdine"] . "
            AND (o.id_utente_mitt = " . $_SESSION["id"] . " OR o.id_utente_dest = " . $_SESSION["id"] . ")
        ;";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {
            // Dati ordine
            $idOrdine = $row['id'];
            $titolo = $row['titolo'];
            $descrizione = $row['descrizione'];
            $istr_consegna = $row['istr_consegna'];
            $data_prevista = $row['data_prevista'];
            $id_utente_mitt = $row['id_utente_mitt'];
            $id_utente_dest = $row['id_utente_dest'];
            $note_corriere = $row['note_corriere'];
            $id_corriere = $row['id_corriere'];
            $isReso = $row['isReso'];

            // Dati mittente
            $nome_mittente = $row['nome_mittente'];
            $cognome_mittente = $row['cognome_mittente'];
            $username_mittente = $row['username_mittente'];

            // Dati destinatario
            $nome_destinatario = $row['nome_destinatario'];
            $cognome_destinatario = $row['cognome_destinatario'];
            $username_destinatario = $row['username_destinatario'];
        }
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
        die();
    }
}





?>

<!DOCTYPE html>
<html>

<head>
    <title>DASHBOARD</title>

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

            <header class="w3-container" style="text-align: center;">
                <h2><i class="fa-solid fa-circle-info"></i> Informazioni ordine #<?= $idOrdine ?></h2>
            </header>

            <div class="w3-container">

                <table table style="margin: 0 auto;">
                    <tr>
                        <th>Titolo:</th>
                        <td><?= $titolo ?></td>
                    </tr>
                    <tr>
                        <th>Descrizione:</th>
                        <td><?= $descrizione ?></td>
                    </tr>
                    <tr>
                        <th>Istruzioni per la consegna:</th>
                        <td><?= $istr_consegna ?></td>
                    </tr>
                    <tr>
                        <th>Consegna prevista:</th>
                        <td><?= $data_prevista ?></td>
                    </tr>
                    <tr>
                        <th>Note corriere:</th>
                        <td><?= $note_corriere ?></td>
                    </tr>
                    <tr>
                        <th>Mittente:</th>
                        <td><?= $nome_mittente . " " . $cognome_mittente ?> (<?= $username_mittente ?>)</td>
                    </tr>
                    <tr>
                        <th>Destinatario:</th>
                        <td><?= $nome_destinatario . " " . $cognome_destinatario ?> (<?= $username_destinatario ?>)</td>
                    </tr>
                    <tr>
                        <th>È un reso?</th>
                        <td><?= ($isReso ? 'Sì' : 'No') ?></td>
                    </tr>
                    <tr><td colspan="2" style="text-align: center;"><a href="ordine-modifica.php?idOrdine=<?=$idOrdine?>">Modifica</a><td><tr>

                </table>

            </div>

            <footer class="w3-container align-center">
                <p>TRACKING...</p>
            </footer>



        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>