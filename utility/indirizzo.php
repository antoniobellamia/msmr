<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php' ?>

<div class="geo-container">
    <div id="geo-raccolta">

        <form id="regioneForm" class="pure-form pure-form-custom-1" method="post">
            <input list="Regione" id="regSel" name="regione" autocomplete="off" onkeydown="return false;" required

                <?php if (isset($_POST["regione"])) echo "value=\"" . $_POST["regione"] . "\"";
                else  echo "placeholder=\"---Seleziona Regione---\""; ?>>
            <datalist id="Regione">
                <option value="">Seleziona una regione</option>

                <?php
                if ($geoConn) {
                    $query = "SELECT denominazione_regione FROM gi_regioni";
                    $result = mysqli_query($geoConn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['denominazione_regione'] . "'>" . $row['denominazione_regione'] . "</option>";
                    }
                } else {
                    echo "Errore di connessione al DB \"Geografia\".";
                }
                ?>

            </datalist>
            <!--<input type="submit" value="Submit Regione">-->
            <input type="button" class="pure-button pure-button-custom-1" onclick="cancella(1)" value="Cancella selezione">
        </form>

        <form id="provForm" class="pure-form pure-form-custom-1" method="post">
            <input list="Provincia" id="provSel" name="provincia" autocomplete="off" onkeydown="return false;" required
                <?php if (isset($_POST["provincia"])) echo "value=\"" . $_POST["provincia"] . "\"";
                else  echo "placeholder=\"---Seleziona Provincia---\""; ?>>
            <datalist id="Provincia">

                <?php
                if (isset($_POST["regione"])) {
                    $regione = $_POST["regione"];

                    if ($geoConn) {
                        $query = "SELECT denominazione_provincia FROM gi_regioni R JOIN gi_province P ON R.codice_regione = P.codice_regione WHERE R.denominazione_regione = '$regione'";
                        $result = mysqli_query($geoConn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['denominazione_provincia'] . "'>" . $row['denominazione_provincia'] . "</option>";
                        }
                    } else {
                        echo "Errore di connessione al DB \"Geografia\".";
                    }
                } else echo "<option value=''>Seleziona una provincia</option>";

                ?>
            </datalist>
            <input type="hidden" name="regione" <?php if (isset($_POST["regione"])) echo "value=\"" . $_POST["regione"] . "\""; ?>>
            <!--<input type="submit" value="Submit Provincia">-->
            <input type="button" class="pure-button pure-button-custom-1" onclick="cancella(2)" value="Cancella selezione">
        </form>


        <form id="cittaForm" class="pure-form pure-form-custom-1" method="post">
            <input list="Comune" id="comuneSel" name="comune" autocomplete="off" required
                <?php if (isset($_POST["comune"])) echo "value=\"" . $_POST["comune"] . "\"";
                else  echo "placeholder=\"---Seleziona Comune---\"";

                if (!isset($_POST["provincia"])) echo "disabled";
                ?>>
            <datalist id="Comune">

                <?php
                if (isset($_POST["provincia"])) {
                    $provincia = $_POST["provincia"];

                    if ($geoConn) {
                        $query = "SELECT denominazione_ita FROM gi_province R JOIN gi_comuni P ON R.codice_sovracomunale = P.codice_sovracomunale WHERE R.denominazione_provincia = '$provincia'";
                        $result = mysqli_query($geoConn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['denominazione_ita'] . "'>" . $row['denominazione_ita'] . "</option>";
                        }
                    } else {
                        echo "Errore di connessione al DB \"Geografia\".";
                    }
                } else echo "<option value=''>Seleziona una provincia</option>";

                ?>
            </datalist>
            <input type="hidden" name="regione" <?php if (isset($_POST["regione"])) echo "value=\"" . $_POST["regione"] . "\""; ?>>
            <input type="hidden" name="provincia" <?php if (isset($_POST["provincia"])) echo "value=\"" . $_POST["provincia"] . "\""; ?>>
            <input type="submit" class="pure-button pure-button-custom-1" value="Submit Comune" <?php if (!isset($_POST["provincia"])) echo "disabled"; ?>>
            <input type="button" class="pure-button pure-button-custom-1" onclick="cancella(3)" value="Cancella selezione">
        </form>

        <form id="capForm" class="pure-form pure-form-custom-1" method="post">

            <?php
            $num_caps = 0;
            if (isset($_POST["comune"])) {
                $comune = $_POST["comune"];

                if ($geoConn) {
                    $query = "SELECT COUNT(*) AS cont FROM gi_comuni C JOIN gi_cap P ON C.codice_istat = P.codice_istat WHERE denominazione_ita = '$comune'";
                    $result = mysqli_query($geoConn, $query);

                    $num_caps = mysqli_fetch_assoc($result)['cont'];

                    if ($num_caps > 0) {
                        $query = "SELECT cap FROM gi_comuni C JOIN gi_cap P ON C.codice_istat = P.codice_istat WHERE denominazione_ita = '$comune'";
                        $result = mysqli_query($geoConn, $query);
                    }
                } else {
                    echo "Errore di connessione al DB \"Geografia\".";
                }
            }

            ?>

            <input list="CAP" id="capSel" name="cap" autocomplete="off" required
                <?php if (isset($_POST["cap"])) echo "value=\"" . $_POST["cap"] . "\"";
                else  echo "placeholder=\"---Seleziona CAP---\"";

                if (!isset($_POST["comune"])) echo "disabled";
                ?>>
            <datalist id="CAP">

                <?php


                if ($num_caps < 1) echo "<option value=''>Seleziona un comune valido!</option>";
                else if ($num_caps >= 1) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['cap'] . "'>" . $row['cap'] . "</option>";
                    }
                }



                ?>
            </datalist>
            <input type="hidden" name="regione" <?php if (isset($_POST["regione"])) echo "value=\"" . $_POST["regione"] . "\""; ?>>
            <input type="hidden" name="provincia" <?php if (isset($_POST["provincia"])) echo "value=\"" . $_POST["provincia"] . "\""; ?>>
            <input type="hidden" name="comune" <?php if (isset($_POST["comune"])) echo "value=\"" . $_POST["comune"] . "\""; ?>>
            <input type="submit" class="pure-button pure-button-custom-1" value="Verifica CAP"
                <?php if (!isset($_POST["comune"])) echo "disabled"; ?>>
            <input type="button" class="pure-button pure-button-custom-1" onclick="cancella(4)" value="Cancella selezione">
        </form>

        <form id="indForm" class="pure-form pure-form-custom-1" method="post" action="indirizzo-action.php" target="">

            <input list="tipoVia" id="tipoViaInpt" name="tipoVia" size="6" autocomplete="off" onkeydown="return false;" placeholder="-tipo-" required
                <?php if (isset($_POST["tipoVia"])) echo "value=\"" . $_POST["tipoVia"] . "\"";
                if (!isset($_POST["cap"])) echo "readonly";

                ?>>
            <datalist id="tipoVia">
                <option value="Via">Via</option>
                <option value="Piazza">Piazza</option>
            </datalist>

            <input type="text" id="indInpt" name="indirizzo" placeholder="Inserisci l'indirizzo ed il numero civico..." size="40" required
                <?php if (isset($_POST["indirizzo"])) {
                    echo "value=\"" . $_POST["indirizzo"] . "\"";
                    $verifyIndirizzo = true;
                } else $verifyIndirizzo = false;

                if (!isset($_POST["cap"])) echo "readonly";

                ?>><br>


            <input type="hidden" name="regione" <?php if (isset($_POST["regione"])) echo "value=\"" . $_POST["regione"] . "\""; ?> required>
            <input type="hidden" name="provincia" <?php if (isset($_POST["provincia"])) echo "value=\"" . $_POST["provincia"] . "\""; ?> required>
            <input type="hidden" name="comune" <?php if (isset($_POST["comune"])) echo "value=\"" . $_POST["comune"] . "\""; ?> required>
            <input type="hidden" name="cap" <?php if (isset($_POST["cap"])) echo "value=\"" . $_POST["cap"] . "\""; ?> required>
            <input type="submit" class="pure-button pure-button-custom-1 pure-form-custom-1" id="salvabtn" value="Salva Dati" formaction="" formtarget="_self" <?php if (!isset($_POST["cap"])) echo "disabled"; ?>>
            <input type="submit" class="pure-button pure-button-custom-1 pure-form-custom-1" id="inviabtn" value="Invia Dati" <?php if (!$verifyIndirizzo) echo "disabled"; ?>>

        </form>



    </div>

    <div id="geo-mappa">
        <?php
        $coord = "";

        if (isset($_POST['cap']) && isset($_POST['comune'])) {

            $cap = $_POST['cap'];
            $com = $_POST['comune'];



            // Verifico la connessione al DB
            if ($geoConn) {
                // La query da eseguire
                $querySql = "SELECT denominazione_ita, lat, lon, cap FROM gi_comuni C JOIN gi_cap P ON C.codice_istat = P.codice_istat  WHERE cap = '$cap' AND denominazione_ita = '$com'";
                // Eseguo la query
                $queryRes = mysqli_query($geoConn, $querySql);



                // Se NON ci sono errori
                if ($queryRes && mysqli_num_rows($queryRes) > 0) {


                    // Ottengo riga per riga i risultati come "array associativo"
                    $row = mysqli_fetch_assoc($queryRes);

                    if ($row) {

                        $coord = $row["lat"] . "," . $row["lon"];
                    }
                } else {
                    echo "Dati inseriti non validi.<br>";
                }

                // Chiudo la connessione al DB
                mysqli_close($geoConn);
                mysqli_close($msConn);
            } else {
                echo "Errore di connessione al DB \"Geografia\".";
            }
        } else if (isset($_POST['provincia'])) $coord = $_POST['provincia'] . "+provincia";
        else if (isset($_POST['regione'])) $coord = $_POST['regione'];

        if (!isset($_POST['cap']) && isset($_POST['comune'])) echo "Submit CAP! <BR>";






        echo "<iframe class=\"geo-iframe\" width=\"520\" height=\"400\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"  src=\"https://maps.google.com/maps?q=@" . $coord . "&t=&z=7&ie=UTF8&iwloc=&output=embed\"></iframe>";
        ?>
    </div>
</div>

<script>
    document.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Previene l'invio del form
        }
    });

    document.getElementById("regSel").addEventListener("input", function() {
        document.getElementById("regioneForm").submit();
    });

    document.getElementById("provSel").addEventListener("input", function() {
        document.getElementById("provForm").submit();
    });

    document.getElementById("provSel").addEventListener("input", function() {
        document.getElementById("provForm").submit();
    });

    document.getElementById("capSel").addEventListener("input", function() {
        let val = document.getElementById("capSel").value;
        if (val.toString().length == 5)
            document.getElementById("capForm").submit();
    });

    function inviaCap1() {
        document.getElementById("capForm").submit();
    }

    function cancella(a) {
        if (a == 1) {
            document.getElementById("regSel").value = "";
            document.getElementById("provSel").value = "";
            document.getElementById("comuneSel").value = "";
            document.getElementById("capSel").value = "";
            document.getElementById("indInpt").value = "";
            document.getElementById("tipoViaInpt").value = "";
        } else if (a == 2) {
            document.getElementById("provSel").value = "";
            document.getElementById("comuneSel").value = "";
            document.getElementById("capSel").value = "";
            document.getElementById("indInpt").value = "";
            document.getElementById("tipoViaInpt").value = "";
        } else if (a == 3) {
            document.getElementById("comuneSel").value = "";
            document.getElementById("capSel").value = "";
            document.getElementById("indInpt").value = "";
            document.getElementById("tipoViaInpt").value = "";
        } else if (a == 4) {
            document.getElementById("capSel").value = "";
            document.getElementById("indInpt").value = "";
            document.getElementById("tipoViaInpt").value = "";
        }
    }


    /******************************************/

    let geo_raccolta = document.getElementById("geo-raccolta");
    let geo_mappa = document.getElementById("geo-mappa");


    let geo_mappaHeight = window.getComputedStyle(geo_mappa).height;

    geo_raccolta.style.height = geo_mappaHeight;
</script>