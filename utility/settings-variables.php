<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';


if(!isset($_GET["optn"])){
    if(isset($_SESSION["username"])) $content = "<h1><i class=\"fa-sharp fa-solid fa-circle-user\"></i><br>".$_SESSION["username"];
    else $content = "<h1><i class=\"fa-sharp fa-solid fa-circle-user\"></i><br>Profilo</h1>";

    if(isset($_SESSION["tipo"])){

        switch($_SESSION['tipo']){
            case 0: $content = $content."<br>CLIENTE"."</h1>"; break;
            case 1: $content = $content."<br>SUPER ADMIN"."</h1>"; break;
            case 2: $content = $content."<br>CORRIERE"."</h1>"; break;
            case 3: $content = $content."<br>ADMIN CORRIERI"."</h1>"; break;
        }
    }
}else{

    if($_SESSION['tipo']>=2){

        if ($msConn) {
            $querySql = "SELECT * FROM corriere WHERE username = '".$_SESSION["username"]."'";

            try{
                $queryRes = mysqli_query($msConn, $querySql);
                $row = mysqli_fetch_assoc($queryRes);

                $username = $row["username"];
                $telefono = $row["telefono"];
                $copertura = $row["copertura"];

            }catch(Exception $exc){
                echo $exc->getMessage();
            }

        }


        if($_GET["optn"] == 1){

            $content = "
            <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"//".$_SERVER['SERVER_NAME']."/msmr/utility/update-settings-action-corr.php?optn=1\">
            <fieldset class=\"custom-fieldset\">
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-username\">Cambia Username</label>
                    <input id=\"aligned-username\" type=\"text\" name=\"username\" value=\"$username\" placeholder=\"$username\"/>
                </div>

                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-telefono\">Modifica Telefono</label>
                    <input id=\"aligned-telefono\" type=\"text\" name=\"tel\" value=\"$telefono\" placeholder=\"$telefono\"/>
                </div>

                <div class=\"pure-controls-custom\">
                    <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
                </div>
            </fieldset>
            </form>    
            ";

            if(isset($_GET["err"]) && $_GET["err"] == "1"){
                $content = "<div class=\"w3-panel w3-red w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Errore! Campi non validi!</h3>
              </div>".$content;
            }else if(isset($_GET["err"]) && $_GET["err"] == "0"){

                $content = "<div class=\"w3-panel w3-green w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Aggiornamento avvenuto con successo!</h3>
              </div>".$content;

            }

        }else if($_GET["optn"] == 2){

            $content = "
            <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"//".$_SERVER['SERVER_NAME']."/msmr/utility/update-settings-action-corr.php?optn=2\">
            <fieldset class=\"custom-fieldset\">
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-username\">Password Attuale:</label>
                    <input id=\"aligned-username\" type=\"password\" name=\"old\" placeholder=\"Password Attuale\" required/>
                </div>
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-nome\">Nuova Password:</label>
                    <input id=\"aligned-nome\" type=\"password\" name=\"new\" placeholder=\"Nuova Password\" required/>
                </div>

                <div class=\"pure-controls-custom\">
                    <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
                </div>
            </fieldset>
            </form>    
            ";


            if(isset($_GET["err"]) && $_GET["err"] == "1"){
                $content = "<div class=\"w3-panel w3-red w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Errore! Password attuale non valida!</h3>
              </div>".$content;
            }else if(isset($_GET["err"]) && $_GET["err"] == "0"){

                $content = "<div class=\"w3-panel w3-green w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Aggiornamento avvenuto con successo!</h3>
              </div>".$content;

            }



        }else if($_GET["optn"] == 3){



            $content = "<form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"//" . $_SERVER['SERVER_NAME'] . "/msmr/utility/update-settings-action-corr.php?optn=3\">
            <fieldset class=\"custom-fieldset\">
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"copertura\">Zona di copertura:</label>
                    <select name=\"copertura\" id=\"zona\" class=\"pure-input-1-2\" required>";

            $options = ['Nord-ovest', 'Nord-est', 'Centro', 'Sud', 'Isole', 'Assoluta'];

            foreach ($options as $option) {
                $selected = ($option == $copertura) ? "selected" : "";
                $content .= "<option value=\"$option\" $selected>$option</option>";
            }

            $content .= "  </select>
                            </div>

                            <div class=\"pure-controls-custom\">
                                <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
                            </div>
                        </fieldset>
                      </form>";


        if(isset($_GET["err"]) && $_GET["err"] == "1"){
            $content = "<div class=\"w3-panel w3-red w3-display-container w3-center\">
            <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
            <h3>Errore!</h3>
          </div>".$content;
        }else if(isset($_GET["err"]) && $_GET["err"] == "0"){
    
            $content = "<div class=\"w3-panel w3-green w3-display-container w3-center\">
            <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
            <h3>Aggiornamento avvenuto con successo!</h3>
          </div>".$content;
    
        }


        }



    }else{

        if ($msConn) {
            $querySql = "SELECT * FROM utente WHERE username = '".$_SESSION["username"]."'";

            try{
                $queryRes = mysqli_query($msConn, $querySql);
                $row = mysqli_fetch_assoc($queryRes);

                $username = $row["username"];
                $nome = $row["nome"];
                $cognome = $row["cognome"];
                $telefono = $row["telefono"];
                $cod_istat = $row["cod_istat"];
                $cap = $row["cap"];

            }catch(Exception $exc){
                echo $exc->getMessage();
            }

        }



        if($_GET["optn"] == 1){

            $content = "
            <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"//".$_SERVER['SERVER_NAME']."/msmr/utility/update-settings-action.php?optn=1\">
            <fieldset class=\"custom-fieldset\">
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-username\">Cambia Username</label>
                    <input id=\"aligned-username\" type=\"text\" name=\"username\" value=\"$username\" placeholder=\"$username\"/>
                </div>
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-nome\">Modifica Nome</label>
                    <input id=\"aligned-nome\" type=\"text\" name=\"nome\" value=\"$nome\" placeholder=\"$nome\"/>
                </div>
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-cognome\">Modifica Cognome</label>
                    <input id=\"aligned-cognome\" type=\"text\" name=\"cognome\" value=\"$cognome\" placeholder=\"$cognome\"/>
                </div>
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-telefono\">Modifica Telefono</label>
                    <input id=\"aligned-telefono\" type=\"text\" name=\"tel\" value=\"$telefono\" placeholder=\"$telefono\"/>
                </div>

                <div class=\"pure-controls-custom\">
                    <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
                </div>
            </fieldset>
            </form>    
            ";

            if(isset($_GET["err"]) && $_GET["err"] == "1"){
                $content = "<div class=\"w3-panel w3-red w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Errore! Campi non validi!</h3>
              </div>".$content;
            }else if(isset($_GET["err"]) && $_GET["err"] == "0"){

                $content = "<div class=\"w3-panel w3-green w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Aggiornamento avvenuto con successo!</h3>
              </div>".$content;

            }

        }else if($_GET["optn"] == 2){

            $content = "
            <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"//".$_SERVER['SERVER_NAME']."/msmr/utility/update-settings-action.php?optn=2\">
            <fieldset class=\"custom-fieldset\">
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-username\">Password Attuale:</label>
                    <input id=\"aligned-username\" type=\"password\" name=\"old\" placeholder=\"Password Attuale\" required/>
                </div>
                <div class=\"pure-control-group control-group-custom\">
                    <label for=\"aligned-nome\">Nuova Password:</label>
                    <input id=\"aligned-nome\" type=\"password\" name=\"new\" placeholder=\"Nuova Password\" required/>
                </div>

                <div class=\"pure-controls-custom\">
                    <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
                </div>
            </fieldset>
            </form>    
            ";


            if(isset($_GET["err"]) && $_GET["err"] == "1"){
                $content = "<div class=\"w3-panel w3-red w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Errore! Password attuale non valida!</h3>
              </div>".$content;
            }else if(isset($_GET["err"]) && $_GET["err"] == "0"){

                $content = "<div class=\"w3-panel w3-green w3-display-container w3-center\">
                <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-display-topright\">×</span>
                <h3>Aggiornamento avvenuto con successo!</h3>
              </div>".$content;

            }



        }else if($_GET["optn"] == 3){

            $content = "";

            if(isset($_GET['m'])){
                $content = "<h2><i class=\"fa-solid fa-globe\"></i><br>INDIRIZZO DI SPEDIZIONE MODIFICATO!</h2>";
            }

            include $_SERVER['DOCUMENT_ROOT'] . '/msmr/utility/trova-indirizzo.php';

            $content .= "<h2><i class=\"fa-solid fa-map-pin\"></i>Indirizzo di spedizione attuale:<br>
            $indirizzo <br> $cap $comune ($sigla_provincia), $regione ($ripartizione)</h2>";

            $content .="<a href=\"" . '//' . $_SERVER['SERVER_NAME'] . "/msmr/auth/indirizzo-update.php\" class=\"pure-menu-link\">
            <h3><i class=\"fa-solid fa-location-dot\"></i> Modifica Ind. Spedizione</h3>
            </a>";



            
        }

    }

}


?>