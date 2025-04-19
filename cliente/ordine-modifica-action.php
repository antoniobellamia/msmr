<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1) || !isset($_POST["idOrdine"]) ) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {

    try {
        if(isset($_POST["istr"]))
            $querySql = " UPDATE ordine SET istr_consegna = '". antiInjection($_POST["istr"]) ."' WHERE id = ". $_POST["idOrdine"] ." AND id_utente_dest =".$_SESSION["id"].";";
        else if(isset($_POST["titolo"]) && isset($_POST["descrizione"]))
            $querySql = " UPDATE ordine SET titolo = '". antiInjection($_POST["titolo"]) ."', descrizione = '". antiInjection($_POST["descrizione"]) ."' WHERE id = ". $_POST["idOrdine"] ." AND id_utente_mitt =".$_SESSION["id"].";";
        else throw new Exception("Errore");
        
        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes) {
            throw new Exception();
        } else{
            header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/cliente/ordine.php?idOrdine='.$_POST["idOrdine"].'');
        }
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/cliente/ordine-modifica.php?err=1');
        die();
    }
}

mysqli_close($msConn); ?>