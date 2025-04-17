<?php session_start();
    if (empty($_SESSION["id"])) {
        session_destroy();
    }
    if (isset($_SESSION["firstAccess"]) && $_SESSION["firstAccess"] = true) header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr/auth/logout.php");

    // <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' >
?>

