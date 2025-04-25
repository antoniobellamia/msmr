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
            SELECT a.*, t.nome, t.spedizioni, t.prezzo, t.mesi
            FROM abbonamento a
            JOIN tariffe t ON a.tariffa = t.id
            WHERE a.id_utente = " . $_SESSION['id'] . " AND a.scaduto = 0
            ORDER BY a.creato DESC
            LIMIT 1
        ;";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            // header('Location: /msmr/');
            //  die();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {
            $id_abbonamento = $row['id'];
            $nome_tariffa = $row['nome'];
            $spedizioni = $row['spedizioni'];
            $prezzo = $row['prezzo'];
            $durata_mesi = $row['mesi'];
            $data_attivazione = $row['creato'];

            // Calcolo opzionale della data di scadenza
            $data_scadenza = date('Y-m-d', strtotime("{$data_attivazione} +{$durata_mesi} months"));
        }
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/500.php');
        die();
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>INFO ORDINE</title>

    <style>
        table {
            text-align: left;
            border: 0;
            margin: 0 auto;
        }

        td,
        th {
            text-indent: 2rem;
        }

        @media screen and (max-width: 768px) {

            td,
            th {
                text-indent: 0;
            }

            tr {
                display: block;
                width: 100%;

            }
        }
    </style>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-u-md-3-5 pure-u-1-1">


            <header class="w3-container" style="text-align: center;">
                <h2><i class="fa-solid fa-ticket"></i> Gestione Abbonamento</h2>
            </header>

            <?php if (isset($_GET['err']))

                if ($_GET['err'] == 0) echo
                       "<div class=\"w3-panel w3-green w3-display-container w3-center\">
                      <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
                      <h3>Abbonamento sottoscritto con successo!</h3>
                    </div>";
                else
                    echo"<div class=\"w3-panel w3-red w3-display-container w3-center\">
                  <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
                  <h3>Errore nella disdetta dell'abbonamento!</h3>
                </div>";
            ?>


            <table id="table1">
                <?php if (isset($id_abbonamento)): ?>
                    <tr>
                        <th>Nome Tariffa:</th>
                        <td><?= htmlspecialchars($nome_tariffa) ?></td>
                    </tr>
                    <tr>
                        <th>Spedizioni:</th>
                        <td><?= $spedizioni == -1 ? 'Illimitate' : $spedizioni ?></td>
                    </tr>
                    <tr>
                        <th>Durata (mesi):</th>
                        <td><?= $durata_mesi ?></td>
                    </tr>
                    <tr>
                        <th>Prezzo:</th>
                        <td>€<?= $prezzo ?></td>
                    </tr>
                    <tr>
                        <th>Attivato il:</th>
                        <td><?= $data_attivazione ?></td>
                    </tr>
                    <tr>
                        <th>Scadenza prevista:</th>
                        <td><?= $data_scadenza ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="2"><a href="abbonamento-disdici.php">
                                <h3>Disdici Abbonamento</h3>
                            </a></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>
                            <h1>Non hai un abbonamento attivo.</h1>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="2"><a href="listino.php">
                                <h2>Abbonati</h2>
                            </a></td>
                    </tr>
                <?php endif; ?>
            </table>


            <footer class="w3-container align-center">
                <br>
            </footer>



        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>