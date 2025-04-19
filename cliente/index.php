<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 0) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {
    $querySql = "
        SELECT 
            O.id,
            O.titolo,
            U.username AS mittente,
            O.data_prevista,
            S.stato,
            S.data
        FROM (
            SELECT stato, data, id_ordine
            FROM stato
            WHERE (id_ordine, progressivo) IN (
                SELECT id_ordine, MAX(progressivo)
                FROM stato
                GROUP BY id_ordine
            )
        ) AS S
        JOIN ordine O ON S.id_ordine = O.id
        JOIN utente U ON O.id_utente_mitt = U.id
        WHERE O.id_utente_dest = " . $_SESSION["id"] . "
        ORDER BY S.data DESC;
    ";

    $content = "Content";

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
            throw new Exception("Nessun ordine trovato");
        }

        $content = '<tbody>';

        while ($row = mysqli_fetch_assoc($queryRes)) {
            $link = '//' . $_SERVER['SERVER_NAME'] . '/msmr/cliente/ordine.php?idOrdine=' . urlencode($row["id"]);

            $content .= '<tr style="cursor:pointer" onclick="window.location.href=\'' . $link . '\'">';
            $content .= '<td>' . htmlspecialchars($row["id"]) . '</td>';
            $content .= '<td>' . htmlspecialchars($row["titolo"]) . '</td>';
            $content .= '<td>' . htmlspecialchars($row["mittente"]) . '</td>';
            $content .= '<td>' . htmlspecialchars($row["stato"]) . '</td>';
            $content .= '<td>' . htmlspecialchars($row["data"]) . '</td>';
            $content .= '<td>' . htmlspecialchars($row["data_prevista"]) . '</td>';
            $content .= '</tr>';
        }



        $content .= '</tbody>';
    } catch (Exception $exc) {
        header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr?err=1");
        die();
    }
}





?>

<!DOCTYPE html>
<html>

<head>
    <title>DASHBOARD</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <section class="pure-g section-dashboard">
        <aside class="pure-u-1-1 pure-u-md-3-24 aside-custom">


            <div class="">
                <span class="pure-menu-heading">DASHBOARD</span>
                <ul class="pure-menu-list">
                    <li class="pure-menu-item">
                        <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/" class="pure-menu-link">
                            <h3> <i class="fa-solid fa-truck-fast"></i> Ordini In Arrivo</h3>
                        </a>
                    </li>
                    <li class="pure-menu-item">
                        <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/?S=404" class="pure-menu-link">
                            <h3> <i class="fa-solid fa-truck-ramp-box"></i> Ordini Spediti</h3>
                        </a>
                    </li>
                </ul>
            </div>


        </aside>

        <div class="pure-u-1-1 pure-u-md-1-24"></div>

        <div class="pure-u-1-1 pure-u-md-19-24 w3-card-2">
            <div class="dashboard">
                <header class="w3-container" style="text-align: center;">
                    <h2> <i class="fa-solid fa-truck-fast"></i> Ordini In Arrivo</h2>
                </header>


                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titolo</th>
                            <th>Mittente</th>
                            <th>Stato</th>
                            <th>Data Aggiornamento</th>
                            <th>Consegna Prevista</th>
                        </tr>
                    </thead>

                    <?= $content ?>


                </table>



            </div>
        </div>








    </section>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>