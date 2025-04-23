<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

session_start();

$id_mitt = $_SESSION['id'];
$id_dest = antiInjection($_POST['id_dest']);
$titolo = antiInjection($_POST['titolo']);
$descrizione = antiInjection($_POST['descrizione']);
$istr_consegna = antiInjection($_POST['istr_consegna']);


try{

    $query = "  SELECT R.ripartizione_geografica AS zona
                FROM gi_comuni C 
                JOIN gi_province P ON C.sigla_provincia = P.sigla_provincia 
                JOIN gi_regioni R ON P.codice_regione = R.codice_regione 
                WHERE C.codice_istat = ( 
                    SELECT cod_istat 
                    FROM msmr.utente 
                    WHERE id = '$id_dest'
                    LIMIT 1
                )";

    $result = mysqli_query($geoConn, $query);

    $zona_dest = mysqli_fetch_assoc($result)['zona'];

    mysqli_begin_transaction($msConn);

$sql_ordine = "
    INSERT INTO ordine (
        titolo, descrizione, istr_consegna, data_prevista,
        id_utente_mitt, id_utente_dest, id_corriere
    )
    SELECT 
        '$titolo', '$descrizione', '$istr_consegna', CURDATE() + INTERVAL 7 DAY,
        $id_mitt, $id_dest, C.id
    FROM (
        SELECT C.id
        FROM corriere C
        LEFT JOIN ordine O ON C.id = O.id_corriere
        WHERE C.copertura IN ('$zona_dest', 'Assoluta')
        GROUP BY C.id
        ORDER BY COUNT(O.id) ASC, C.copertura ASC
        LIMIT 1
    ) AS C
";

if (!mysqli_query($msConn, $sql_ordine)) {
    throw new Exception("Errore inserimento ordine: " . mysqli_error($msConn));
}

$last_id = mysqli_insert_id($msConn);

if (!$last_id) {
    throw new Exception("Errore nel recupero dell'ID ordine.");
}

$sql_stato = "
    INSERT INTO stato (stato, informazioni, id_ordine)
    VALUES ('Ordine ricevuto', 'Ordine inserito nel sistema.', $last_id)
";

if (!mysqli_query($msConn, $sql_stato)) {
    throw new Exception("Errore inserimento stato: " . mysqli_error($msConn));
}

mysqli_commit($msConn);



header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/cliente/spediti.php");

}catch(Exception $exc){
    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/cliente/nuovo-ordine.php?err=1");
    die();
}




mysqli_close($geoConn);
mysqli_close($msConn);
