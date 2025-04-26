<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) && $_SESSION['tipo'] != 3) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$content = "<tbody><tr><td colspan='3'>Nessun risultato</td></tr></tbody>";

if ($msConn) {

    $querySql = "
       SELECT M.id AS idMagaz,
    M.nome,
    M.cod_istat,
    COUNT(OS.id_ordine) AS numero_ordini
FROM magazzino M
LEFT JOIN (
    SELECT S.id_ordine, S.id_magaz
    FROM stato S
    JOIN (
        SELECT id_ordine, MAX(data) AS max_data
        FROM stato
        WHERE id_magaz IS NOT NULL
        GROUP BY id_ordine
    ) SM ON S.id_ordine = SM.id_ordine AND S.data = SM.max_data
    WHERE S.id_ordine NOT IN (
        SELECT id_ordine FROM stato WHERE stato = 'Consegnato'
    )
) AS OS ON OS.id_magaz = M.id
GROUP BY M.id
ORDER BY numero_ordini DESC, M.id;
    ";

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if ($queryRes && mysqli_num_rows($queryRes) > 0) {
            $content = '<tbody>';

            while ($row = mysqli_fetch_assoc($queryRes)) {
                $link = '//' . $_SERVER['SERVER_NAME'] . '/msmr/admin-corrieri/gestione-magazzino.php?idMagaz=' . urlencode($row["idMagaz"]);

                $content .= '<tr style="cursor:pointer" onclick="window.location.href=\'' . $link . '\'">';
                $content .= '<td>' . htmlspecialchars($row["nome"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["cod_istat"]) . '</td>';
                $content .= '<td>' . intval($row["numero_ordini"]) . '</td>';
                $content .= '</tr>';
            }

            $content .= '</tbody>';
        }
    } catch (Exception $exc) {
        $content = "<tbody><tr><th>Errore nella connessione al DB.</th></tr></tbody>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Magazzini - Stato Ordini</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php'; ?>

    <section class="pure-g section-dashboard">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/admin-corrieri/aside.php'; ?>

        <div class="pure-u-1-1 pure-u-md-1-24"></div>

        <div class="pure-u-1-1 pure-u-md-19-24 w3-card-2">
            <div class="dashboard">
                <header class="w3-container" style="text-align: center;">
                    <h2><i class="fa-solid fa-warehouse"></i> Stato Magazzini</h2>
                </header>

                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>Nome Magazzino</th>
                            <th>Codice ISTAT</th>
                            <th>Ordini Attivi</th>
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
