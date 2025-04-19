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
            SELECT id, titolo, istr_consegna
            FROM ordine
            WHERE id = " . $_GET["idOrdine"] . "
            AND id_utente_dest = " . $_SESSION["id"] . "
        ;";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
            throw new Exception();
        } else if ($row = mysqli_fetch_assoc($queryRes)) {
            $idOrdine = $row['id'];
            $titolo = $row['titolo'];
            $istr_consegna = $row['istr_consegna'];
        }

        $mittente = false;
    } catch (Exception $exc) {

        try {
            $querySql = "
                SELECT id, titolo, descrizione
                FROM ordine
                WHERE id = " . $_GET["idOrdine"] . "
                AND id_utente_mitt = " . $_SESSION["id"] . "
            ;";

            $queryRes = mysqli_query($msConn, $querySql);

            if (!$queryRes || mysqli_num_rows($queryRes) != 1) {
                throw new Exception();
            } else if ($row = mysqli_fetch_assoc($queryRes)) {
                $idOrdine = $row['id'];
                $titolo = $row['titolo'];
                $descrizione = $row['descrizione'];
            }

            $mittente = true;
        } catch (Exception $exc) {
            header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
            die();
        }
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>DASHBOARD</title>

    <style>
        table {
            text-align: left;
            border: 0;
        }

        td {
            text-indent: 2rem;
        }

        @media screen and (max-width: 768px) {

            td,
            tr {
                display: block;
                width: 100%;
                text-indent: 0;
            }
        }
    </style>


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-u-md-4-5 pure-u-1-1">

        <?php if ($mittente) echo '
            <header class="w3-container" style="text-align: center;">
                <h2>Modifica l\'ordine: <br> #' . $idOrdine . ' - ' . htmlspecialchars($titolo) . '</h2>
            </header>

            <div class="w3-container">

                <form class="pure-form pure-form-aligned" method="post" action="ordine-modifica-action.php">

                    <fieldset class="custom-fieldset">
                        <div class="pure-control-group control-group-custom">
                            <label for="titolo">Titolo:</label>
                            <input type="text" id="titolo" placeholder="' . htmlspecialchars($titolo) . '" value="' . htmlspecialchars($titolo) . '" required name="titolo" />
                        </div>
                        <div class="pure-control-group control-group-custom">    
                            <label for="descrizione">Descrizione:</label>
                            <textarea id="descrizione" placeholder="' . htmlspecialchars($descrizione) . '" required name="descrizione">' . htmlspecialchars($descrizione) . '</textarea>
                            <input type="hidden" name="idOrdine" value="' . $idOrdine . '">
                        </div>

                        <div class="pure-controls-custom">
                            <button type="submit" class="pure-button pure-button-primary">Modifica</button>
                        </div>
                    </fieldset>
                </form>

            </div>';

        else echo '<header class="w3-container" style="text-align: center;">
             <h2>Modifica istruzioni di consegna per l\'ordine: <br> #' . $idOrdine . ' - ' . $titolo . '</h2>
         </header>

         <div class="w3-container">

             <form class="pure-form pure-form-aligned" method="post" action="ordine-modifica-action.php">

                 <fieldset class="custom-fieldset">
                     <div class="pure-control-group control-group-custom">
                         <textarea id="istruzioni" placeholder="' . htmlspecialchars($istr_consegna) . '" required name="istr" rows="4" cols="50">' . htmlspecialchars($istr_consegna) . '</textarea>
                         <input type="hidden" name="idOrdine" value="' . $idOrdine . '">
                     </div>

                     <div class="pure-controls-custom">
                         <button type="submit" class="pure-button pure-button-primary">Modifica</button>
                     </div>
                 </fieldset>
             </form>

         </div>';

?>

            <footer class="w3-container align-center">
                <?php if (isset($_GET["err"])) echo "Errore durante la modifica. Riprova <br>"; ?>
                <br>
            </footer>



        </div>
    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';

    mysqli_close($msConn); ?>