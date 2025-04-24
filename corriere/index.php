<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 2 && $_SESSION['tipo'] != 3)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {

    $querySql = "
       SELECT 
    O.id,
    U.username AS mittente,
    UD.username AS destinatario,
    UD.id AS idDest,
    O.data_prevista,
    S.stato,
    S.data
FROM ordine O
JOIN utente U ON O.id_utente_mitt = U.id
JOIN utente UD ON O.id_utente_dest = UD.id
LEFT JOIN (
    SELECT S1.*
    FROM stato S1
    JOIN (
        SELECT id_ordine, MAX(data) AS max_data
        FROM stato
        GROUP BY id_ordine
    ) S2 ON S1.id_ordine = S2.id_ordine AND S1.data = S2.max_data
) AS S ON O.id = S.id_ordine
WHERE O.id_corriere = " . $_SESSION["id"] . "
AND (S.stato IS NULL OR S.stato <> 'Consegnato')
ORDER BY O.data_prevista DESC, S.data ASC;


    ";
    

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
            $content = "<tbody><tr><th>Nessun ordine in carico.</th></tr></tbody>";
        } else {

            $content = '<tbody>';

            while ($row = mysqli_fetch_assoc($queryRes)) {
                $link = '//' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine=' . urlencode($row["id"]);

                $content .= '<tr style="cursor:pointer" onclick="window.location.href=\'' . $link . '\'">';
                $content .= '<td>' . htmlspecialchars($row["id"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["mittente"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["destinatario"]) . '</td>';

                $ind = getIndirizzo($msConn, $row["idDest"]);

                $content .= '<td>' . $ind['comune'] . ' ('. $ind['sigla_provincia'] .'), '. $ind['ripartizione'] .'</td>';

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
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/corriere/aside.php'; ?>

        <div class="pure-u-1-1 pure-u-md-1-24"></div>

        <div class="pure-u-1-1 pure-u-md-19-24 w3-card-2">
            <div class="dashboard">
                <header class="w3-container" style="text-align: center;">
                    <h2> <i class="fa-solid fa-dolly"></i> Da Consegnare</h2>
                </header>


                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mittente</th>
                            <th>Destinatario</th>
                            <th>Località</th>
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
    
    mysqli_close($msConn);
    
    ?>