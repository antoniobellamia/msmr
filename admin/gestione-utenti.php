<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 1) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

if ($msConn) {
    $querySql = "
       SELECT 
            U.id,
            U.username,
            U.telefono,
            U.nome,
            U.cognome,
            T.id AS tariffa_id,
            COUNT(DISTINCT O_mitt.id) AS ordini_inviati,
            COUNT(DISTINCT O_dest.id) AS ordini_ricevuti,
            U.tipo AS tipo_utente
        FROM utente U
        LEFT JOIN abbonamento A 
            ON U.id = A.id_utente
            AND A.scaduto = 0
        LEFT JOIN tariffe T
            ON A.tariffa = T.id
        LEFT JOIN ordine O_mitt
            ON O_mitt.id_utente_mitt = U.id
        LEFT JOIN ordine O_dest
            ON O_dest.id_utente_dest = U.id
        GROUP BY U.id
        ORDER BY U.tipo DESC, T.id DESC, U.id ASC;
    ";

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
            $content = "<tbody><tr><th>Nessun utente trovato.</th></tr></tbody>";
        } else {
            $content = '<tbody>';

            while ($row = mysqli_fetch_assoc($queryRes)) {
                $tariffa = isset($row["tariffa_id"]) ? htmlspecialchars($row["tariffa_id"]) : "nessuna";
                $ordini_inviati = intval($row["ordini_inviati"]);
                $ordini_ricevuti = intval($row["ordini_ricevuti"]);
                $tipo_utente = htmlspecialchars($row["tipo_utente"]);

                $content .= '<tr>';
                $content .= '<td>' . htmlspecialchars($row["username"]) . '</td>';
                $content .= '<td>' . htmlspecialchars($row["telefono"] ?? '-') . '</td>';
                $content .= '<td>' . htmlspecialchars($row["nome"] . ' ' . $row["cognome"]) . '</td>';
                $content .= '<td>' . $tipo_utente . '</td>';
                $content .= '<td>' . $tariffa . '</td>';
                $content .= '<td>' . $ordini_inviati . '</td>';
                $content .= '<td>' . $ordini_ricevuti . '</td>';
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
    <title>Gestione Utenti</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php'; ?>
</head>

<body>

    <section class="pure-g section-dashboard">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/admin/aside.php'; ?>

        <div class="pure-u-1-1 pure-u-md-1-24"></div>

        <div class="pure-u-1-1 pure-u-md-19-24 w3-card-2">
            <div class="dashboard">
                <header class="w3-container" style="text-align: center;">
                    <h2><i class="fa-solid fa-users"></i> Elenco Utenti</h2>
                </header>

                <table class="pure-table pure-table-bordered" style="margin: 0 auto;">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Telefono</th>
                            <th>Nome e Cognome</th>
                            <th>Tipo Utente</th>
                            <th>ID Tariffa</th>
                            <th>Ordini<BR>Inviati</th>
                            <th>Ordini<BR>Ricevuti</th>
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
