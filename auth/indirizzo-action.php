<?php

session_start();

/*UPDATE TABLE*/






if(isset($_SESSION["firstAccess"]) && $_SESSION["firstAccess"] = true){
     session_destroy();
     header("Location: //".$_SERVER['SERVER_NAME']."/msmr");
}