<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1) || !isset($_POST['id_abbonamento'])) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$id_abbonamento = (int) $_POST['id_abbonamento'];  // Assicurati che sia un numero intero

if ($msConn) {
    try {
        // Verifica se l'abbonamento esiste
        $querySql = "
            SELECT * FROM tariffe
            WHERE id = $id_abbonamento
        ";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception('Tariffa non trovata.');
        }

        // Verifica se l'utente ha già un abbonamento attivo
        $checkAbbonamentoSql = "
            SELECT * FROM abbonamento
            WHERE id_utente = {$_SESSION['id']} AND scaduto = 0
        ";

        $checkAbbonamentoRes = mysqli_query($msConn, $checkAbbonamentoSql);

        if ($checkAbbonamentoRes && mysqli_num_rows($checkAbbonamentoRes) > 0) {
            // Se esiste un abbonamento attivo
            throw new Exception('Hai già un abbonamento attivo.');
        }

        // Attivazione abbonamento per l'utente
        $insertSql = "
            INSERT INTO abbonamento (id_utente, tariffa)
            VALUES ({$_SESSION['id']}, $id_abbonamento)
        ";

        $insertRes = mysqli_query($msConn, $insertSql);

        if (!$insertRes) {
            throw new Exception('Errore durante l\'attivazione dell\'abbonamento.');
        }

        // Successo: redirect sulla pagina degli abbonamenti con messaggio di successo
        header('Location: index.php?err=0');
        die();
    } catch (Exception $exc) {
        // In caso di errore, redirect alla pagina degli abbonamenti con il messaggio di errore
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/abbonamenti/listino.php?err=' . urlencode($exc->getMessage()));
        die();
    }
}
?>
