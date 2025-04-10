<?php session_start();
    if (empty($_SESSION["id"])) {
        session_destroy();
    }

    // <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' >
?>

