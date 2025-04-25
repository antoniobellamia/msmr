<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';

if (isset($_SESSION['tipo']) && !isset($_GET['s']))
  switch ($_SESSION['tipo']) {
    case 0:
      header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/cliente/");
      break;
    case 1:
      header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/admin/");
      break;
    case 2:
      header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/corriere/");
      break;
    case 3:
      header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr/admin-corrieri/");
      break;
  }
?>

<!DOCTYPE html>
<html>

<head>
  <title>HOMEPAGE</title>
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
  <!--BODY-->

  <main class="homepage">
    <span id="ChiSiamo"></span>
    <section id="presentazione" class="w3-display-container slideDiv">

      <img src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/frontImg.png">

      <div CLASS="w3-display-middle w3-container w3-padding-16">



        <div class="textSlide w3-animate-zoom">
          <div class="w3-container">
            <h1>Management <br>
              System for<br>
              Mail & <br>
              Routing</h1>
          </div>
        </div>

        <div class="textSlide w3-animate-zoom">
          <div class="w3-container align-center ">
            <h1>
              M <br>
              S <br>
              M<br>
              R</h1>
          </div>
        </div>

        <div class="textSlide w3-animate-zoom">
          <div class="w3-container">
            <h1>
              Minimo <br>
              Sforzo <br>
              Massima<br>
              Resa</h1>
          </div>
        </div>

        <div class="textSlide w3-animate-zoom">
          <div class="w3-container align-center">
            <h1>
              MSMR</h1>
          </div>
        </div>

        <div class="textSlide w3-animate-zoom">
          <div class="w3-container align-center">
            <h1>
              Management System<br>Mail & Rouring</h1>
          </div>
        </div>

        <div class="textSlide w3-animate-zoom">
          <div class="w3-container align-center">
            <h1>
              MSMR</h1>
          </div>
        </div>

      </div>

    </section>

    <span id="Tracking"></span>

    <?php if (isset($_GET['err']))

      if ($_GET['err'] == 1)
        echo
        "<div class=\"w3-panel w3-red w3-display-container w3-center\">
      <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
    <h3>ID Spedizione inesistente!</h3>
    </div>";
    ?>

    <section class="w3-display-container slideDiv">

      <img class="slide w3-animate-opacity" src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/image1.jpg">
      <img class="slide w3-animate-opacity" src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/image2.jpg">
      <img class="slide w3-animate-opacity" src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/image3.jpg">
      <img class="slide w3-animate-opacity" src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/image4.jpg">

      <section class="w3-display-middle w3-container w3-padding-16">


        <form method="post" action="//<?= $_SERVER['SERVER_NAME'] ?>/msmr/no-user/" class="pure-form pure-form-aligned">
          <fieldset class="custom-fieldset">
            <h1 style="color: white;">Traccia un pacco</h1>
            <input id="idOrdine" type="number" name="idOrdine" placeholder="Cerca un ID spedizione..." required>
            <button type="submit" class="pure-button pure-button-primary pure-button-home">Ricerca</button>


          </fieldset>
        </form>
      </section>

    </section>

    <span id="Spedire"></span>

    <section id="spedisci" class="w3-display-container">

      <img src="<?= '//' . $_SERVER['SERVER_NAME'] ?>/msmr/res/mail.jpg">
      <div class="w3-container w3-display-middle align-center fitSpedisci">
        <h1>Spedisci un pacco</h1>

        <a href="//<?= $_SERVER['SERVER_NAME'] ?>/msmr/abbonamenti/listino.php" style="color: white;"><button class="pure-button pure-button-primary">Tariffe di spedizione</button></a>
        <a href="//<?= $_SERVER['SERVER_NAME'] ?>/msmr/cliente/nuovo-ordine.php" style="color: white;"><button class="pure-button pure-button-primary">Spedisci adesso</button></a>
      </div>


    </section>


  </main>
  <script>
    var slideIndices = {};

    carousel("slide");

    carousel("textSlide");

    function carousel(className) {
      var x = document.getElementsByClassName(className);

      // Inizializza slideIndex per questa classe, se non esiste
      if (!(className in slideIndices)) {
        slideIndices[className] = 0;
      }

      // Nasconde tutte le slide di quella classe
      for (var i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }

      // Incrementa indice e gestisce il ciclo
      slideIndices[className]++;
      if (slideIndices[className] > x.length) {
        slideIndices[className] = 1;
      }

      // Mostra la slide corrente
      x[slideIndices[className] - 1].style.display = "block";

      // Richiama la funzione ricorsivamente
      setTimeout(function() {
        carousel(className);
      }, 10000);
    }
  </script>

  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>