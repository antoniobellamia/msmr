<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {
    $querySql = "
        SELECT 
            O.id,
            O.titolo,
            U.username AS destinatario,
            O.data_prevista,
            S.stato,
            S.data
        FROM (
            SELECT stato, data, id_ordine
            FROM stato
            WHERE (id_ordine, data) IN (
                SELECT id_ordine, MAX(data)
                FROM stato
                GROUP BY id_ordine
            )
        ) AS S
        JOIN ordine O ON S.id_ordine = O.id
        JOIN utente U ON O.id_utente_dest = U.id
        WHERE O.id_utente_mitt = " . $_SESSION["id"] . "
        ORDER BY S.data DESC;
    ";

    

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
            $content = "<tbody><tr><th>Nessun ordine spedito.</th></tr></tbody>";
        } else {

            $content = '<tbody>';

            while ($row = mysqli_fetch_assoc($queryRes)) {
                $link = '//' . $_SERVER['SERVER_NAME'] . '/msmr/cliente/ordine.php?idOrdine=' . urlencode($row["id"]);

                $content .= '<tr style="cursor:pointer" onclick="window.location.href=\'' . $link . '\'">';
                $content .= '<td>' . htmlspecialchars($row["id"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["titolo"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["destinatario"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["stato"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["data"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["data_prevista"]) . '</td>';
                $content .= '</tr>';
            }



            $content .= '</tbody>';
        }
    } catch (Exception $exc) {
        $content = "<tbody><tr><th>Errore nella connessione al DB.</th></tr></tbody>";
        //echo $exc->getMessage();
        
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
                        <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/cliente/" class="pure-menu-link">
                            <h3> <i class="fa-solid fa-truck-fast"></i> Ordini In Arrivo</h3>
                        </a>
                    </li>
                    <li class="pure-menu-item">
                        <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/cliente/spediti.php" class="pure-menu-link">
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
                    <h2> <i class="fa-solid fa-truck-ramp-box"></i> Ordini Spediti</h2>
                </header>


                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titolo</th>
                            <th>Destinatario</th>
                            <th>Stato</th>
                            <th>Ultimo Aggiornamento</th>
                            <th>Consegna Prevista</th>
                        </tr>
                    </thead>

                    <?= $content ?>


                </table>



            </div>
        </div>








    </section>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';
    
    mysqli_close($msConn); ?>