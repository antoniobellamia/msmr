<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 3) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$content = "<tbody><tr><td colspan='6'>Nessun corriere trovato</td></tr></tbody>";

if ($msConn) {
    $querySql = "
        SELECT 
            C.id,
            C.username,
            C.telefono,
            C.tipo,
            C.copertura,
            COUNT(O.id) AS ordini_attivi
        FROM corriere C
        LEFT JOIN ordine O 
            ON C.id = O.id_corriere
            AND O.id NOT IN (
                SELECT id_ordine 
                FROM stato 
                WHERE stato = 'Consegnato'
            )
        GROUP BY C.id
        ORDER BY ordini_attivi DESC
    ";

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if ($queryRes && mysqli_num_rows($queryRes) > 0) {
            $content = '<tbody>';

            while ($row = mysqli_fetch_assoc($queryRes)) {
               

                $content .= '<tr>';
                $content .= '<td>' . htmlspecialchars($row["username"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["telefono"] ?? '-') . '</td>';
                $content .= '<td>' . htmlspecialchars($row["tipo"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["copertura"]) . '</td>';
                $content .= '<td>' . intval($row["ordini_attivi"]) . '</td>';
                $content .= '</tr>';
            }

            $content .= '</tbody>';
        }
    } catch (Exception $exc) {
        $content = "<tbody><tr><td colspan='6'>Errore durante la lettura dei dati.</td></tr></tbody>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestione Corrieri</title>
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php'; ?>

    <section class="pure-g section-dashboard">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/admin-corrieri/aside.php'; ?>

        <div class="pure-u-1-1 pure-u-md-1-24"></div>

        <div class="pure-u-1-1 pure-u-md-19-24 w3-card-2">
            <div class="dashboard">
                <header class="w3-container" style="text-align: center;">
                    <h2><i class="fa-solid fa-people-group"></i> Elenco Corrieri</h2>
                </header>

                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Telefono</th>
                            <th>Tipo</th>
                            <th>Copertura</th>
                            <th>Ordini a carico</th>
                        </tr>
                    </thead>
                    <?= $content ?>
                </table>
            </div>
        </div>
    </section>

    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';
    mysqli_close($msConn);
    ?>
</body>
</html>