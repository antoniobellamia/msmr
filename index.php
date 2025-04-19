<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' ;

if(isset($_SESSION['tipo']) && !isset($_GET['s']))
    switch($_SESSION['tipo']){
        case 0: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/cliente/");  break;
        case 1: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/admin/");  break;
        case 2: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/corriere/");  break;
        case 3: header("Location: //".$_SERVER['SERVER_NAME']."/msmr/admin-corrieri/");  break;
    }





?>

<!DOCTYPE html>
<html>
    <head>
        <title>HOMEPAGE</title>
        <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <h1 class="align-center">HOMEPAGE</h1>
    
<section>
    <?php if (isset($_GET['err']))

    if($_GET['err'] == 1)
      echo
      "<div class=\"w3-panel w3-red w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Errore! ID Spedizione errato!</h3>
      </div>";
  ?>

    <form method="post" action="//<?=$_SERVER['SERVER_NAME']?>/msmr/no-user/" class="pure-form pure-form-aligned">
        <fieldset class="custom-fieldset">
          
          <div class="pure-control-group control-group-custom">
            <label for="idOrdine">ID Spedizione</label>
            <input id="idOrdine" type="text" name="idOrdine" placeholder="Cerca un ID spedizione..." required>
          </div>
      
          <div class="pure-controls-custom">
            <button type="submit" class="pure-button pure-button-primary">Ricerca</button>
          </div>
          
        </fieldset>
      </form>
</section>   

    <?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/footer.php' ?>