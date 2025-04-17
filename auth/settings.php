<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/components/session-con.php';

if (empty($_SESSION["id"])){
  header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/403.php');
  die();
}

?>
<?php include_once $_SERVER['DOCUMENT_ROOT']. '/msmr/utility/settings-variables.php' ?>

<!DOCTYPE html>
<html>

<head>
  <title>Impostazioni</title>

  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>

  <style>
    h3 {
      margin: 5px;
    }

    div.content {
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100%;
      color: var(--primary-color);
    }
  </style>
  <!--BODY-->

  <div class="pure-g login-card" style="justify-content: center">
    <div class="w3-card-4 pure-u-md-3-4 pure-u-1-1">

      <header class="w3-container" style="text-align: center;">
        <h1>Profilo</h1>
      </header>

      <div class="w3-container pure-g">

        <div class="pure-u-md-2-5 pure-u-1-1">
          <div class="pure-menu pure-menu sidebar-menu">
            <span class="pure-menu-heading">Opzioni</span>
            <ul class="pure-menu-list">
              <li class="pure-menu-item">
                <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/auth/settings.php?optn=1" class="pure-menu-link">
                  <h3><i class="fa-solid fa-user-pen"></i> Modifica Profilo</h3>
                </a>
              </li>
              <li class="pure-menu-item">
                <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/auth/settings.php?optn=2" class="pure-menu-link">
                  <h3><i class="fa-solid fa-shield-halved"></i> Sicurezza</h3>
                </a>
              </li>

              <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] < 2) 

                echo "<li class=\"pure-menu-item\">
                <a href=\"" . '//' . $_SERVER['SERVER_NAME'] . "/msmr/auth/indirizzo-update.php\" class=\"pure-menu-link\">
                  <h3><i class=\"fa-solid fa-location-dot\"></i> Modifica Ind. Spedizione</h3>
                </a>
                </li>
                <li class=\"pure-menu-item\">
                <a href=\"#\" class=\"pure-menu-link\">
                  <h3><i class=\"fa-solid fa-ticket\"></i> Abbonamenti</h3>
                </a>
                </li>";

                else echo "<li class=\"pure-menu-item\">
                <a href=\"" . '//' . $_SERVER['SERVER_NAME'] . "/msmr/auth/settings.php?optn=3\" class=\"pure-menu-link\">
                  <h3><i class=\"fa-solid fa-location-dot\"></i> Modifica zona di copertura</h3>
                </a>
              </li>";
              
              ?>

              <li class="pure-menu-item">
                <a href="<?= '//'.$_SERVER['SERVER_NAME']?>/msmr/auth/logout.php" class="pure-menu-link">
                  <h3> <i class="fa-solid fa-right-from-bracket"></i> Logout</h3>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="pure-u-md-3-5 pure-u-1-1">

          <div class="align-center content">


            <?= $content ?>
          </div>
        </div>


        <footer class="w3-container">
          <br>
        </footer>




      </div>
    </div>
  </div>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>