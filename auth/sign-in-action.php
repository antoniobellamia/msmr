<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

$username = antiInjection($_POST['username']);
$password = md5($_POST['password']);
$tel = antiInjection($_POST['tel']);
$nome = antiInjection($_POST['nome']);
$cognome = antiInjection($_POST['cognome']);


/*
if ($msConn) {
    $sql = "INSERT INTO `utente` (`id`, `username`, `password`, `telefono`, `cognome`, `nome`, `tipo`, `cod_istat`, `cap`, `indirizzo`) VALUES (NULL, '$username', '$password', '$tel', '$nome', '$cognome', NULL, NULL, NULL, NULL)";
    echo $sql;
}


if (true) {
    $sql = "SELECT `id` FROM `utente` WHERE `username` = '$username' AND  `password` = '$password'";
    echo $sql;
    $queryRes = mysqli_query($msConn, $querySql);


    if ($queryRes) {
        if (mysqli_num_rows($queryRes) === 1) {
            // Prendo il recordo dal DB
            $row = mysqli_fetch_assoc($queryRes);

            // Creo la sessione
            session_start();

            // Memorizzo i dati nelle variabili di sessione.
            $_SESSION["userId"] = $row["id"];

            // Ridireziono l'utente verso questa pagina.
            header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/indirizzo.php");
        } else {
            header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/signin.php?err=1");
        }
    } else {
        echo "Errore nella query: " . mysqli_error($dbConn);
    }
}
*/


/*********************TEST */

session_start();
$_SESSION["userId"] = $username;
header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/auth/indirizzo-sign.php");