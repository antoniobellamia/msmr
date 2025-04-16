<?php

session_start();
session_unset();
session_destroy();

header("Location: //".$_SERVER['SERVER_NAME'] . "/msmr");

?>
