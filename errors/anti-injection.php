<?php

function antiInjection($input) {
    // Salviamo l'input originale per il controllo
    

    // Sanificazione dell'input
    $input = trim($input);  // Rimuove spazi all'inizio e alla fine

    $inputOriginale = $input;

    $input = stripslashes($input);  // Rimuove i backslash (\\)
    $input = str_replace(["'", '"', ";", "--", "#", "/*", "*/"], "", $input);  // Rimuove caratteri pericolosi

    // Controlla se c'è stato un cambiamento nell'input (per esempio, se è stato rimosso un carattere pericoloso)
    if ($input !== $inputOriginale) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
        exit();
    }

    return htmlentities($input);  // L'input è pulito
}

?>