<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' ?>

<!DOCTYPE html>
<html>

<head>
  <title>Sign-In</title>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
  <!--BODY-->

  <?php if (isset($_GET['err']))

    if($_GET['err'] == 1)
      echo
      "<div class=\"w3-panel w3-red w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Errore! Username già in uso!</h3>
      </div>";
  ?>


  <div class="pure-g login-card" style="justify-content: center">
    <div class="w3-card-4 pure-u-md-1-2 pure-u-1-1">

      <header class="w3-container" style="text-align: center;">
        <h1>Sign-In</h1>
      </header>

      <div class="w3-container">


        <form class="pure-form pure-form-aligned" method="post" action="sign-in-action.php">
          <fieldset class="custom-fieldset">

            <div class="pure-g custom-signin">
              <div class="pure-u-1 pure-u-md-1-3">
                <label for="multi-first-name">Nome</label>
                <input id="multi-first-name" class="pure-u-23-24" type="text" placeholder="Inserisci il tuo nome..." required name="nome"/>
              </div>
              <div class="pure-u-1 pure-u-md-1-3">
                <label for="multi-last-name">Cognome</label>
                <input id="multi-last-name" class="pure-u-23-24" type="text" placeholder="Inserisci il tuo cognome..." required name="cognome"/>
              </div>
              <div class="pure-u-1 pure-u-md-1-3">
                <label for="multi-email">Recapito Telefonico</label>
                <input id="multi-email" class="pure-u-23-24" type="tel" placeholder="+039..." name="tel"/>
              </div>
            </div>


            <div class="pure-control-group control-group-custom">
              <label for="aligned-name">Username</label>
              <input id="aligned-name" type="text" placeholder="Username" required name="username"/>
            </div>
            <div class="pure-control-group control-group-custom">
              <label for="aligned-password">Password</label>
              <input id="aligned-password" type="password" placeholder="Password" required name="password"/>
            </div>
            <input type="hidden" name="first" value="1"/>
            <div class="pure-controls-custom">
              <button type="submit" class="pure-button pure-button-primary">Registrati</button>
            </div>
          </fieldset>
        </form>

        <footer class="w3-container" style="text-align: end" ;>
          <h4>Hai già un account? <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/auth/login.php">Log-In</a> </h4>
        </footer>

      </div>
    </div>
  </div>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>