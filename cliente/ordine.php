<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
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
            AND (o.id_utente_mitt = " . $_SESSION["id"] . " OR o.id_utente_dest = " . $_SESSION["id"] . ")
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
        } else {

            $content = '<tbody>';
            $firstIcon = '<td><i class="fa-solid fa-circle-check" style="color: green"></i></td>';
            $secondIcon ='<td><i class="fa-solid fa-circle-half-stroke" style="color: var(--primary-color)"></i></td>';


            while ($row = mysqli_fetch_assoc($queryRes)) {
                
                $content .= '<tr>'.$firstIcon.'<th>' . htmlspecialchars($row["stato"]) . '</th></tr>';
                $content .= '<tr>'.$secondIcon.'<td>' . htmlspecialchars($row["data"]) . '</td></tr>';

                try{
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

                    $content .= '<tr>'. $secondIcon.'<td>' . htmlspecialchars($nomeComune) . '(' . htmlspecialchars($siglaProvincia) . ')</td></tr>';
                    



                }catch (Exception $exc) { }

                $content .= '<tr>'.$secondIcon.'<td>' . htmlspecialchars($row["informazioni"]) . '</td></tr>';

            }



            $content .= '</tbody>';
        }
    } catch (Exception $exc) {
        echo $exc ->getMessage();
    }

}





?>

<!DOCTYPE html>
<html>

<head>
    <title>INFO ORDINE</title>

    <style>
        table {
            text-align: left;
            border: 0;
            margin: 0 auto;
        }

        td, th {
            text-indent: 2rem;
        }

        @media screen and (max-width: 768px) {

            td, th{
                text-indent: 0;
            }
            tr {
                display: block;
                width: 100%;
                
            }
        }
    </style>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-u-md-4-5 pure-u-1-1">

            <header class="w3-container" style="text-align: center;">
                <h2><i class="fa-solid fa-circle-info"></i> Informazioni ordine #<?= $idOrdine ?></h2>
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
                        <th>Istruzioni per la consegna:</th>
                        <td><?= $istr_consegna ?></td>
                    </tr>
                    <tr>
                        <th>Consegna prevista:</th>
                        <td><?= $data_prevista ?></td>
                    </tr>
                    <tr>
                        <th>Note corriere:</th>
                        <td><?= $note_corriere ?></td>
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
                    <tr><td colspan="2" style="text-align: center;"><a href="ordine-modifica.php?idOrdine=<?=$idOrdine?>">Modifica</a><td><tr>

                </table>

                <header class="w3-container" style="text-align: center;">
                    <h2><i class="fa-solid fa-truck-fast"></i> Tracking</h2>
                </header>

                <table id="table2">
                <?= $content ?>
                </table>
            </div>

            <footer class="w3-container align-center">
                <br>
            </footer>



        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>