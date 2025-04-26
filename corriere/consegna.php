<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 2 && $_SESSION['tipo'] != 3)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
    die();
}


if ($msConn) {

    try {
        $querySql = "
            SELECT o.*,  
            mitt.nome AS nome_mittente,
            mitt.cognome AS cognome_mittente,
            mitt.username AS username_mittente,
            dest.nome AS nome_destinatario,
            dest.cognome AS cognome_destinatario,
            dest.username AS username_destinatario
            FROM ordine o
            JOIN utente mitt ON o.id_utente_mitt = mitt.id
            JOIN utente dest ON o.id_utente_dest = dest.id
            WHERE o.id = " . $_GET["idOrdine"] . "
            AND (o.id_corriere = " . $_SESSION["id"] . " OR " . $_SESSION['tipo'] . " = 3)
            
        ;";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {
            // Dati ordine
            $idOrdine = $row['id'];
            $titolo = $row['titolo'];
            $descrizione = $row['descrizione'];
            $istr_consegna = $row['istr_consegna'];
            $data_prevista = $row['data_prevista'];
            $id_utente_mitt = $row['id_utente_mitt'];
            $id_utente_dest = $row['id_utente_dest'];
            $note_corriere = $row['note_corriere'];
            $id_corriere = $row['id_corriere'];
            $isReso = $row['isReso'];

            // Dati mittente
            $nome_mittente = $row['nome_mittente'];
            $cognome_mittente = $row['cognome_mittente'];
            $username_mittente = $row['username_mittente'];

            // Dati destinatario
            $nome_destinatario = $row['nome_destinatario'];
            $cognome_destinatario = $row['cognome_destinatario'];
            $username_destinatario = $row['username_destinatario'];
        }
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
        die();
    }


    /*TRACKING*/

    try {
        $querySql = "
            SELECT s.*, 
                   IFNULL(m.nome, 'Nessun Magazzino') AS nome_magazzino, 
                   IFNULL(m.cod_istat, 'Nessun Codice ISTAT') AS codice_istat
            FROM stato s
            LEFT JOIN magazzino m ON s.id_magaz = m.id
            WHERE s.id_ordine = " . $_GET["idOrdine"] . "
            ORDER BY s.data DESC;
        ";



        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
            $content = "<tbody><tr><th>Errore Tracking</th></tr></tbody>";
            $magaz = [
                'nome' => 'Errore',
                'comune' => 'Tracking',
                'provincia' => 'MSMR'
            ];
        } else {

            $content = '<tbody>';
            $firstIcon = '<td><i class="fa-solid fa-circle-check" style="color: green"></i></td>';
            $secondIcon = '<td><i class="fa-solid fa-circle-half-stroke" style="color: var(--primary-color)"></i></td>';

            $firstStato = true;
            $magaz = [
                'nome' => 'Magazzino',
                'comune' => 'Comune',
                'provincia' => ' '
            ];

            while ($row = mysqli_fetch_assoc($queryRes)) {

                $content .= '<tr>' . $firstIcon . '<th>' . htmlspecialchars($row["stato"]) . '</th></tr>';
                $content .= '<tr>' . $secondIcon . '<td>' . htmlspecialchars($row["data"]) . '</td></tr>';

                try {
                    $codiceIstat = $row["codice_istat"];

                    $queryComune = "
                        SELECT denominazione_ita, sigla_provincia
                        FROM gi_comuni
                        WHERE codice_istat = '$codiceIstat'
                    ;";

                    $resComune = mysqli_query($geoConn, $queryComune);

                    if (!$resComune || mysqli_num_rows($resComune) != 1) {
                        throw new Exception();
                    }

                    $comuneRow = mysqli_fetch_assoc($resComune);
                    $nomeComune = $comuneRow["denominazione_ita"];
                    $siglaProvincia = $comuneRow["sigla_provincia"];

                    $content .= '<tr>' . $secondIcon . '<td>' . htmlspecialchars($nomeComune) . ' (' . htmlspecialchars($siglaProvincia) . ')</td></tr>';
                } catch (Exception $exc) {
                    $nomeComune = 0;
                    $siglaProvincia = 0;
                }

                if ($firstStato) {

                    $magaz['nome'] = $row['nome_magazzino'];
                    $magaz['comune'] = $nomeComune;
                    $magaz['provincia'] = $siglaProvincia;

                    if ($nomeComune === 0 || $magaz['nome'] == "Nessun Magazzino") $firstStato = true;
                    else $firstStato = false;
                }

                $content .= '<tr>' . $secondIcon . '<td>' . htmlspecialchars($row["informazioni"]) . '</td></tr>';
            }



            $content .= '</tbody>';
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }


    /** STATI */

    $ind = getIndirizzo($msConn, $id_utente_dest);

    if ($msConn) {

        $sql = "SELECT DISTINCT nome_stato FROM info ORDER BY id ASC";
        $stati = mysqli_query($msConn, $sql);

        $sqlMagaz = "SELECT id, nome, cod_istat FROM magazzino ORDER BY nome ASC";
        $magazzini = mysqli_query($msConn, $sqlMagaz);
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>INFO ORDINE</title>

    <style>
        #table1 table,
        #table2 table,
        #table1 th,
        #table2 th {
            text-align: left;
            border: 0;
        }

        #dash table {
            margin: 0 auto;
            margin-block: 1rem;
        }


        @media screen and (max-width: 768px) {

            #table1 tr,
            #table2 tr {
                display: block;
                width: 100%;

            }
        }
    </style>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->


    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-g pure-u-md-23-24 pure-u-1-1">

            <header class="w3-container align-center">
                <h2><i class="fa-solid fa-clipboard-list"></i> Spedizione #<?= $idOrdine ?></h2>
            </header>

            <section id="dash" class="pure-u-md-14-24 pure-u-1-1">
                <div class="w3-container">

                    <?php if (isset($_GET['err']))
                        echo
                        "<div class=\"w3-panel w3-red w3-display-container w3-center\">
                      <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
                      <h3>Errore! Dati errati!</h3>
                    </div>";
                    ?>

                    <header class="w3-container">
                        <h3 class="align-center"><i class="fa-solid fa-road-circle-check"></i> Aggiorna lo stato</h3>
                    </header>


                    <form class="pure-form pure-form-aligned" method="post" action="aggiorna-stato-action.php" style="overflow-x: auto; width: 100%;">
                        <fieldset class="custom-fieldset">

                            <div class="pure-control-group control-group-custom">
                                <label for="stato">Nuovo Stato</label>
                                <select name="stato" class="pure-input-2-3">
                                    <option value="">-- Seleziona Stato --</option>
                                    <?php
                                    if ($stati && mysqli_num_rows($stati) > 0) {
                                        while ($row = mysqli_fetch_assoc($stati)) {
                                            $stato = $row['nome_stato'];
                                            echo "<option value=\"$stato\">$stato</option>";
                                        }
                                    } else {
                                        echo "<option value=\"\">Nessuno stato disponibile</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="pure-control-group control-group-custom">
                                <label for="informazioni">Informazioni aggiuntive</label>
                                <textarea class="pure-input-2-3" name="informazioni" placeholder="Informazioni aggiuntive (facoltativo)" cols="40"></textarea>
                            </div>

                            <div class="pure-control-group control-group-custom">
                                <label for="magazzino">Magazzino</label>
                                <select name="magazzino" class="pure-input-2-3">
                                    <option value="">-- Seleziona Magazzino --</option>
                                    <?php
                                    // Verifica se la query ha restituito risultati
                                    if ($magazzini && mysqli_num_rows($magazzini) > 0) {
                                        // Ciclo attraverso i magazzini e creo le opzioni
                                        while ($row = mysqli_fetch_assoc($magazzini)) {
                                            $magazzinoId = htmlspecialchars($row['id']);
                                            $magazzinoNome = htmlspecialchars($row['nome']);
                                            echo "<option value=\"$magazzinoId\">$magazzinoNome</option>";
                                        }
                                    } else {
                                        // Se non ci sono magazzini, mostriamo un messaggio
                                        echo "<option value=\"\">Nessun magazzino disponibile</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="pure-control-group control-group-custom">
                                <label for="note">Note del corriere</label>
                                <textarea class="pure-input-2-3" name="note" placeholder="Note del corriere" cols="40"><?= $note_corriere ?></textarea>
                            </div>

                            <input type="hidden" value="<?= $_GET['idOrdine'] ?>" name="idOrdine">

                            <div class="pure-controls-custom">
                                <button type="submit" class="pure-button pure-button-primary">Aggiorna</button>
                                <button type="submit" class="pure-button pure-button-primary w3-red" formaction="elimina-stato-action.php">Elimina ultimo stato</button>
                            </div>

                        </fieldset>
                    </form>


                    <div style="overflow-x: auto; width: 100%;">

                        <table class="pure-table pure-table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" class="align-center"><i class="fa-solid fa-location-arrow"></i> Indirizzo Destinatario</th>
                                </tr>
                                <tr>
                                    <th class="align-center">Indirizzo</th>
                                    <th class="align-center">Località</th>
                                    <th class="align-center">Regione</th>
                                    <th class="align-center">Area</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><?= $ind['indirizzo'] ?></td>
                                    <td><?= $ind['cap'] . ' ' . $ind['comune'] . ' (' . $ind['sigla_provincia'] . ')' ?></td>
                                    <td><?= $ind['regione'] ?></td>
                                    <td><?= $ind['ripartizione'] ?></td>

                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: start">Istruzioni per la consegna:</th>
                                    <td colspan="2"><?= $istr_consegna ?></td>
                                </tr>
                            </tbody>

                        </table>

                        <table class="pure-table pure-table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2" class="align-center"><i class="fa-solid fa-warehouse"></i> Magazzino</th>
                                </tr>
                                <tr>
                                    <th class="align-center">Denominazione</th>
                                    <th class="align-center">Località</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><?= $magaz['nome'] ?></td>
                                    <td><?= $magaz['comune'] . ' (' . $magaz['provincia'] . ')' ?></td>
                            </tbody>
                            </tr>
                        </table>


                    </div>

                    <iframe allowfullscreen="true" class="maps" frameborder="0" id="mapnavi" name="mapnavi" src="https://www.google.com/maps/embed/v1/directions?
origin=<?= $magaz['comune'].'+comune' ?>
&destination=<?= $ind['comune'].'+comune' ?>
&key=AIzaSyC-5CY9mOCeg5Y3IhPqi_Yd0-DZtWrJl-E">
                    </iframe>

                </div>

            </section>

            <section id="info" class="pure-u-md-9-24 pure-u-1-1">

                <header class="w3-container">
                    <h3><i class="fa-solid fa-circle-info"></i> Informazioni generiche</h3>
                </header>

                <div class="w3-container">

                    <table id="table1">
                        <tr>
                            <th>Titolo:</th>
                            <td><?= $titolo ?></td>
                        </tr>
                        <tr>
                            <th>Descrizione:</th>
                            <td><?= $descrizione ?></td>
                        </tr>
                        <tr>
                            <th>Consegna prevista:</th>
                            <td><?= $data_prevista ?></td>
                        </tr>
                        <tr>
                            <th>Mittente:</th>
                            <td><?= $nome_mittente . " " . $cognome_mittente ?> (<?= $username_mittente ?>)</td>
                        </tr>
                        <tr>
                            <th>Destinatario:</th>
                            <td><?= $nome_destinatario . " " . $cognome_destinatario ?> (<?= $username_destinatario ?>)</td>
                        </tr>
                        <tr>
                            <th>È un reso?</th>
                            <td><?= ($isReso ? 'Sì' : 'No') ?></td>
                        </tr>

                    </table>
                </div>

                <header class="w3-container">
                    <h3><i class="fa-solid fa-truck-fast"></i> Tracking</h3>
                </header>

                <div class="w3-container">
                    <table id="table2">
                        <?= $content ?>
                    </table>
                </div>

            </section>

            <footer class="w3-container align-center">
                <br>
            </footer>

        </div>






    </div>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>