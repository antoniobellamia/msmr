<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

session_start();

$userId = $_SESSION['userId'];
$citta = $_POST['comune'];
$cap = $_POST['cap'];
$indirizzo = antiInjection($_POST['tipoVia'] . " " . $_POST['indirizzo']);

/*UPDATE TABLE*/
echo $userId;

try{

    $query = "SELECT P.codice_istat AS istat FROM gi_cap P JOIN gi_comuni C ON P.codice_istat = C.codice_istat WHERE C.denominazione_ita = '$citta' AND P.cap = '$cap'";
    $result = mysqli_query($geoConn, $query);

    $row = mysqli_fetch_assoc($result)['istat'];

    $istat = $row;

    $query = "UPDATE utente SET cod_istat = '$istat', cap = '$cap', indirizzo = '$indirizzo' WHERE id = '$userId'";
    $result = mysqli_query($msConn, $query);


    if (isset($_SESSION["firstAccess"]) && $_SESSION["firstAccess"] = true) {
        session_destroy();
        header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/success.php");
    }

}catch(Exception $exc){
    if (isset($_SESSION["firstAccess"]) && $_SESSION["firstAccess"] = true) session_destroy();
    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/errors/500.php");
}



mysqli_close($geoConn);
mysqli_close($msConn);
