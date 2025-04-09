<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';

try{

    $query = "DELETE FROM utente";
    $result = mysqli_query($msConn, $query);

    $query = "ALTER TABLE utente AUTO_INCREMENT=1";
    $result = mysqli_query($msConn, $query);

    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/success.php");

}catch(Exception $exc){
    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/errors/500.php");
}