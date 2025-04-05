<?php

    $geoHost = "127.0.0.1";  # Indirizzo "localhost"
    $geoUser = "root";       # L'utente del DBMS
    $geoPass = "";           # La password dell'utente del DBMS
    $geoName = "geografia"; # Il nome del database da selezionare
    
    $msHost = "127.0.0.1";  # Indirizzo "localhost"
    $msUser = "root";       # L'utente del DBMS
    $msPass = "";           # La password dell'utente del DBMS
    $msName = "indirizzi"; # Il nome del database da selezionare
    
    
    // Effettuo la connessione al DB
    $geoConn = mysqli_connect(
        $geoHost,
        $geoUser,
        $geoPass,
        $geoName
    );
    
    $msConn = mysqli_connect(
        $msHost,
        $msUser,
        $msPass,
        $msName
    );
?>