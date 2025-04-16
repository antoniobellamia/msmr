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
        <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"\">
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
                <input id=\"aligned-telefono\" type=\"text\" name=\"telefono\" value=\"$telefono\" placeholder=\"$telefono\"/>
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
            <h3>Errore! Password non valida!</h3>
          </div>".$content;
        }

    }else if($_GET["optn"] == 2){

        $content = "
        <form class=\"pure-form pure-form-aligned\" method=\"post\" action=\"\">
        <fieldset class=\"custom-fieldset\">
            <div class=\"pure-control-group control-group-custom\">
                <label for=\"aligned-username\">Password Attuale:</label>
                <input id=\"aligned-username\" type=\"password\" name=\"password\" placeholder=\"Password Attuale\" required/>
            </div>
            <div class=\"pure-control-group control-group-custom\">
                <label for=\"aligned-nome\">Nuova Password:</label>
                <input id=\"aligned-nome\" type=\"password\" name=\"password\" placeholder=\"Nuova Password\" required/>
            </div>

            <div class=\"pure-controls-custom\">
                <button type=\"submit\" class=\"pure-button pure-button-primary\">Aggiorna</button>
            </div>
        </fieldset>
        </form>    
        ";



    }else if($_GET["optn"] == 3){
        $content = "<h1><i class=\"fa-solid fa-globe\"></i><br>INDIRIZZO DI SPEDIZIONE MODIFICATO!</h1>";
    }

}


?>