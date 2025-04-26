<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 3) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$idMagaz = isset($_GET['idMagaz']) ? antiInjection($_GET['idMagaz']) : null;

if (!$idMagaz || !$msConn) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

try {
    // Recupera info sul magazzino
    $queryInfo = "SELECT * FROM magazzino WHERE id = '$idMagaz'";
    $resInfo = mysqli_query($msConn, $queryInfo);

    if (!$resInfo || mysqli_num_rows($resInfo) != 1) throw new Exception();

    $magazzino = mysqli_fetch_assoc($resInfo);

    // Recupera ordini attivi presenti nel magazzino
    $queryOrdini = "
        SELECT O.id, U.username AS mittente, UD.username AS destinatario, O.data_prevista, S.stato, S.data
        FROM ordine O
        JOIN utente U ON O.id_utente_mitt = U.id
        JOIN utente UD ON O.id_utente_dest = UD.id
        JOIN (
            SELECT S1.id_ordine, S1.stato, S1.data, S1.id_magaz
            FROM stato S1
            JOIN (
                SELECT id_ordine, MAX(data) AS max_data
                FROM stato
                WHERE id_magaz IS NOT NULL
                GROUP BY id_ordine
            ) S2 ON S1.id_ordine = S2.id_ordine AND S1.data = S2.max_data
            WHERE S1.id_ordine NOT IN (
                SELECT id_ordine FROM stato WHERE stato = 'Consegnato'
            )
        ) AS S ON O.id = S.id_ordine
        WHERE S.id_magaz = '$idMagaz'
        ORDER BY S.data DESC
    ";

    $resOrdini = mysqli_query($msConn, $queryOrdini);

    $ordini = [];
    if ($resOrdini && mysqli_num_rows($resOrdini) > 0) {
        while ($row = mysqli_fetch_assoc($resOrdini)) {
            $ordini[] = $row;
        }
    }
} catch (Exception $e) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/500.php');
    die();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gestione Magazzino</title>
    <style>
        table {
            margin: 0 auto;
            margin-block: 1rem;
        }
    </style>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>

    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-g pure-u-md-23-24 pure-u-1-1 align-center">

            <div class="w3-container dashboard">
                <header class="w3-container">
                    <h2><i class="fa-solid fa-warehouse"></i> <?= htmlspecialchars($magazzino['nome']) ?> (<?= htmlspecialchars($magazzino['cod_istat']) ?>)</h2>
                </header>

                <h3><i class="fa-solid fa-boxes-stacked"></i> Ordini attualmente presenti:</h3>
                <?php if (count($ordini) > 0): ?>
                    <table class="pure-table pure-table-bordered">
                        <thead>
                            <tr>
                                <th>ID Ordine</th>
                                <th>Mittente</th>
                                <th>Destinatario</th>
                                <th>Stato Attuale</th>
                                <th>Ultimo Aggiornamento</th>
                                <th>Consegna Prevista</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordini as $ordine): ?>

                                <?php $link = '//' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine=' . urlencode($ordine["id"]);
                                echo '<tr style="cursor:pointer" onclick="window.location.href=\'' . $link . '\'">'; ?>
                               
                                    <td><?= htmlspecialchars($ordine['id']) ?></td>
                                    <td><?= htmlspecialchars($ordine['mittente']) ?></td>
                                    <td><?= htmlspecialchars($ordine['destinatario']) ?></td>
                                    <td><?= htmlspecialchars($ordine['stato']) ?></td>
                                    <td><?= htmlspecialchars($ordine['data']) ?></td>
                                    <td><?= htmlspecialchars($ordine['data_prevista']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h4>Nessun ordine attivo presente in questo magazzino.</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';
    mysqli_close($msConn); ?>
</body>

</html>