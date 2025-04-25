<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (empty($_SESSION["id"]) || ($_SESSION['tipo'] != 0 && $_SESSION['tipo'] != 1)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/auth/login.php?err=3');
    die();
}

if ($msConn) {
  try {
    // ID utente preso dalla sessione
    $idUtente = $_SESSION['id'];

    // Verifica se l'utente ha un abbonamento attivo e non scaduto
    $querySql = "
      SELECT 1
      FROM abbonamento
      WHERE id_utente = $idUtente AND scaduto = 0
      LIMIT 1;
    ";

    $queryRes = mysqli_query($msConn, $querySql);

    if (!$queryRes || mysqli_num_rows($queryRes) == 0) {
      throw new Exception("Non hai un abbonamento attivo.");
    }


  } catch (Exception $exc) {
    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/abbonamenti");
    die();
  }
}


if($msConn){

try{

  $querySql = "SELECT id, username, nome, cognome FROM `utente` WHERE id <> ".$_SESSION['id'].";";
  $queryRes = mysqli_query($msConn, $querySql);

  if(!$queryRes || mysqli_num_rows($queryRes) == 0) throw new Exception();

  $content = ' ';

  while ($row = mysqli_fetch_assoc($queryRes)) {

    $content .= '<option value=' . $row["id"] .'> '.$row["nome"].' '.$row["cognome"].' ('.$row["username"].')</option>';

  }

}catch(Exception $exc) {
  $content .= '<option value=-1>Errore.</option>';
}
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Nuovo ordine</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->

    <div class="pure-g login-card" style="justify-content: center">
    <div class="w3-card-4 pure-u-md-20-24 pure-u-1-1">

      <header class="w3-container" style="text-align: center;">
        <h1><i class="fa-solid fa-dolly"></i> Nuova spedizione</h1>
      </header>

      <?php 
         if (isset($_GET['err']))
          echo
          "<div class=\"w3-panel w3-red w3-display-container w3-center\">
            <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
            <h3>Errore! Dati errati!</h3>
          </div>"; 
      ?>

      <div class="w3-container">


        <form class="pure-form pure-form-aligned" method="post" action="nuovo-ordine-action.php">

          <fieldset class="custom-fieldset">
            <div class="pure-control-group control-group-custom">
              <label for="titolo">Titolo</label>
              <input id="titolo" type="text" placeholder="Titolo" required name="titolo"/>
            </div>

            <div class="pure-control-group control-group-custom">
              <label for="desc">Descrizione</label>
              <textarea id="desc" type="text" name="descrizione" cols="24" placeholder="Descrivi il contenuto del pacco"></textarea>
            </div>

            <div class="pure-control-group control-group-custom">

              <label for="dest">Scegli il destinatario</label>

              <select id="dest" required name="id_dest">
                <option value="0">---</option>
                <?= $content ?>
              </select>
              
            </div>

            <div class="pure-controls-custom">
              <button type="submit" class="pure-button pure-button-primary">Invia</button>
            </div>
          </fieldset>
        </form>

        <footer class="w3-container" ;>
          <br>
        </footer>

      </div>
    </div>
  </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php';
    
    mysqli_close($msConn); ?>