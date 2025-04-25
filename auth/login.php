<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php' ?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
  <!--BODY-->

  <?php 
  if (isset($_GET['err'])){
    if ($_GET['err'] == 1)
      echo
      "<div class=\"w3-panel w3-red w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Errore! Username o password errati!</h3>
      </div>";
    else if ($_GET['err'] == 2)
      echo
      "<div class=\"w3-panel w3-green w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Registrazione avvenuta con successo!</h3>
      </div>";
      else if ($_GET['err'] == 3)
      echo
      "<div class=\"w3-panel w3-orange w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Procedi prima con il login!</h3>
      </div>";
 }
  ?>


  <div class="pure-g login-card" style="justify-content: center">
    <div class="w3-card-4 pure-u-md-1-2 pure-u-1-1">

      <header class="w3-container" style="text-align: center;">
        <h1>Login</h1>
      </header>

      <div class="w3-container">


        <form class="pure-form pure-form-aligned" method="post" action="login-action.php">

          <fieldset class="custom-fieldset">
            <div class="pure-control-group control-group-custom">
              <label for="aligned-name">Username</label>
              <input id="aligned-name" type="text" placeholder="Username" required name="username"/>
            </div>
            <div class="pure-control-group control-group-custom">
              <label for="aligned-password">Password</label>
              <input id="aligned-password" type="password" placeholder="Password" required name="password"/>
            </div>
            <label for="default-remember">
              <input id="default-remember" type="checkbox" name="corr"/> Sono un corriere
            </label>
            <div class="pure-controls-custom">
              <button type="submit" class="pure-button pure-button-primary">Accedi</button>
            </div>
          </fieldset>
        </form>

        <footer class="w3-container" style="text-align: end" ;>
          <h4>Non hai un account? <a href="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/auth/signin.php">Sign-In</a> </h4>
        </footer>

      </div>
    </div>
  </div>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>