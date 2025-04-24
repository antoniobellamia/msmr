<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 2 && $_SESSION['tipo'] != 3)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

if (
    !isset($_POST['idOrdine'])
) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

$idOrdine = antiInjection($_POST['idOrdine']);



$query = "
    DELETE FROM stato 
    WHERE id = (
        SELECT id FROM (
            SELECT id 
            FROM stato 
            WHERE id_ordine = $idOrdine 
            ORDER BY data DESC 
            LIMIT 1
        ) AS ultimo
    )
";

try{
    if($msConn){
        $res = mysqli_multi_query($msConn, $query);
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine='.$idOrdine.'');
    }
}catch(Exception $exc){
    echo $exc -> getMessage();
    //header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine='.$idOrdine.'&err=1');
    die();
}


?>