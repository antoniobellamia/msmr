<?php

if($msConn){
    try{

        $sql = "SELECT u.indirizzo, u.cap, c.denominazione_ita AS comune, c.sigla_provincia, r.denominazione_regione, r.ripartizione_geografica FROM msmr.utente u JOIN geografia.gi_comuni c ON u.cod_istat = c.codice_istat JOIN geografia.gi_province p ON c.sigla_provincia = p.sigla_provincia JOIN geografia.gi_regioni r ON p.codice_regione = r.codice_regione 
        WHERE u.id = ".$_SESSION['id'].";";

        $queryRes = mysqli_query($msConn, $sql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {

            $indirizzo = $row['indirizzo'];
            $cap = $row['cap'];
            $comune = $row['comune'];
            $sigla_provincia = $row['sigla_provincia'];
            $regione = $row['denominazione_regione'];
            $ripartizione = $row['ripartizione_geografica'];

        }

    }catch(Exception $exc){
        $indirizzo =       "Errore.";
        $cap =             "Nessun";
        $comune =          "indirizzo";
        $sigla_provincia = "NULL.";
        $regione =         "NULL.";
        $ripartizione =    "NULL.";
    }
}




?>