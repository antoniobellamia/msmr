<?php 
//include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';
//$ind = getIndirizzo($msConn, $_SESSION['id']);

function getIndirizzo($msConn, $userId) {
    $result = [
        'indirizzo' => "Errore.",
        'cap' => "Nessun",
        'comune' => "indirizzo",
        'sigla_provincia' => "NULL.",
        'regione' => "NULL.",
        'ripartizione' => "NULL."
    ];

    if($msConn){
        try{
            $sql = "SELECT u.indirizzo, u.cap, c.denominazione_ita AS comune, c.sigla_provincia, r.denominazione_regione, r.ripartizione_geografica 
                    FROM msmr.utente u 
                    JOIN geografia.gi_comuni c ON u.cod_istat = c.codice_istat 
                    JOIN geografia.gi_province p ON c.sigla_provincia = p.sigla_provincia 
                    JOIN geografia.gi_regioni r ON p.codice_regione = r.codice_regione 
                    WHERE u.id = ".$userId.";";

            $queryRes = mysqli_query($msConn, $sql);

            if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
                throw new Exception("Problema nella query o nessun risultato trovato.");
            } else {
                $row = mysqli_fetch_assoc($queryRes);
                $result = [
                    'indirizzo' => $row['indirizzo'],
                    'cap' => $row['cap'],
                    'comune' => $row['comune'],
                    'sigla_provincia' => $row['sigla_provincia'],
                    'regione' => $row['denominazione_regione'],
                    'ripartizione' => $row['ripartizione_geografica']
                ];
            }

        } catch(Exception $exc) {
            // In caso di errore, i valori di default rimangono
            // Puoi anche loggare l'errore se necessario
        }
    }
    
    return $result;
}

?>
