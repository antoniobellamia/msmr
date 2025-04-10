<?php

    if (empty($_SESSION["id"])) {
        include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar-out.php';
    }else include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar-in.php';
?>
    
