<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 2 && $_SESSION['tipo'] != 3)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}

if (
    !isset($_POST['idOrdine']) ||
    !isset($_POST['stato']) ||
    !isset($_POST['informazioni']) ||
    !isset($_POST['magazzino']) ||
    !isset($_POST['note'])
) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine='.$_POST['idOrdine'].'&err=1');
    die();
}

$idOrdine = antiInjection($_POST['idOrdine']);
$stato = $_POST['stato'];
$informazioni = antiInjection($_POST['informazioni']);
$magazzino = antiInjection($_POST['magazzino']);
$note = antiInjection($_POST['note']);

$query = " UPDATE ordine
            SET note_corriere = '$note'
             WHERE id = $idOrdine;";

    if(!empty($stato)) 
        $query .= "INSERT INTO stato (stato, informazioni, id_ordine, id_magaz)
        VALUES ('$stato', '$informazioni', $idOrdine, " . ($magazzino !== '' ? $magazzino : "NULL") . ")";

try{
    if($msConn){
        $res = mysqli_multi_query($msConn, $query);
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine='.$idOrdine.'');
    }
}catch(Exception $exc){
    echo $exc -> getMessage();
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/corriere/consegna.php?idOrdine='.$idOrdine.'&err=1');
    die();
}


?>