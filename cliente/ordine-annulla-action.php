<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1) || !isset($_POST["idOrdine"]) ) {
    echo $_SESSION["id"];
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

try{

    $idOrdine = $_POST['idOrdine'];
    $tipo = $_POST['tipo'];

}catch(Exception $exc) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors');
    die();
}

echo $tipo;
echo $_SESSION['id'];


if($tipo == "reso"){
    $sql = "

    UPDATE ordine
    SET isReso = 1, data_prevista = NULL
    WHERE id = ".$idOrdine." AND id_utente_dest = ".$_SESSION['id'].";

    INSERT INTO stato (stato, id_ordine)
    VALUES ('Reso effettuato. Verrà restituito al venditore', '$idOrdine');

    ";

   
}else if($tipo == "annulla"){
    $sql = "

    UPDATE ordine
    SET isReso = 1, data_prevista = NULL
    WHERE id = ".$idOrdine." AND id_utente_mitt = ".$_SESSION['id'].";

    INSERT INTO stato (stato, informazioni, id_ordine)
    VALUES ('Spedizione annullata dal venditore','La spedizione è stata annullata dal mittente prima della consegna.', 
    ".$idOrdine.");

    ";
}else $sql = " ";

if ($msConn) {

    try {
       
        $queryRes = mysqli_multi_query($msConn, $sql);

        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/cliente/ordine.php?idOrdine='.$idOrdine.'');

    } catch (Exception $exc) {
        echo $exc -> getMessage();
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/500.php');
        die();
    }
}

mysqli_close($msConn); ?>