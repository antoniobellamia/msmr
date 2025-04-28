<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || $_SESSION['tipo'] != 1) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {
    $querySql = "   DELETE FROM abbonamento;
        DELETE FROM ordine; 
        DELETE FROM stato;
        DELETE FROM utente WHERE tipo <> 'admin';
        DELETE FROM corriere WHERE tipo <> 'admin';

        ALTER TABLE abbonamento AUTO_INCREMENT = 1;
        ALTER TABLE ordine AUTO_INCREMENT = 1;
        ALTER TABLE stato AUTO_INCREMENT = 1;
        ALTER TABLE utente AUTO_INCREMENT = 1;
        ALTER TABLE corriere AUTO_INCREMENT = 1;

    ";

    

    try {
        $queryRes = mysqli_multi_query($msConn, $querySql);

        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr');

    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

mysqli_close($msConn);
?>