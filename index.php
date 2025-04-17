<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' ;

if(isset($_SESSION['tipo']))
    switch($_SESSION['tipo']){
        case 0: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/cliente/");  break;
        case 1: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/admin/");  break;
        case 2: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/corriere/");  break;
        case 3: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/admin-corrieri/");  break;
    }





?>

<!DOCTYPE html>
<html>
    <head>
        <title>HOMEPAGE</title>
        <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <h1 class="align-center">HOMEPAGE</h1>

    <form >

    <input type="search" placeholder="Cerca un ID spedizione..."></input>
    <p> - - id_ordine, progressivo, stato DA STATO</p>

</form>

    <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/footer.php' ?>