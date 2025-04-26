<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

$abbonamenti = [];
if ($msConn) {
    try {
        $querySql = "
            SELECT * FROM tariffe
            ORDER BY id
        ";

        $queryRes = mysqli_query($msConn, $querySql);

        if (!$queryRes) {
            throw new Exception('Errore durante il recupero delle tariffe.');
        }

        while ($row = mysqli_fetch_assoc($queryRes)) {
            $abbonamenti[] = $row;
        }
    } catch (Exception $exc) {
        header('Location: //' . $_SERVER['SERVER_NAME'] . '/msmr/errors/500.php');
        die();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Listino Abbonamenti</title>

    <style>
        table {

            margin: 0 auto;
        }

        td {
            padding-bottom: 1rem;
        }
    </style>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>

    <header class="w3-container" style="text-align: center;">
        <h2><i class="fa-solid fa-ticket"></i> Listino Abbonamenti</h2>
    </header>

    <?php if (isset($_GET['err']))
        echo
        "<div class=\"w3-panel w3-red w3-display-container w3-center\">
        <span onclick=\"this.parentElement.style.display='none'\" class=\"w3-button w3-large w3-display-topright\">×</span>
        <h3>Errore nella sottoscrizione dell'abbonamento!</h3>
      </div>";
    ?>

    <div class="pure-g login-card" style="justify-content: center;">

        <?php foreach ($abbonamenti as $index => $abbonamento): ?>
            <div class="align-center w3-card-4 pure-u-md-7-24 pure-u-1-1 <?= ($index == 0 || $index == 2) ? 'smaller-card' : '' ?>">

                <header class="w3-container" style="text-align: center;">
                    <br>
                    <h2><i class="fa-solid fa-receipt"></i> <?= htmlspecialchars($abbonamento['nome']) ?></h2>
                </header>

                <table>
                    <tr>
                        <th>Spedizioni incluse:</th>
                    </tr>
                    <tr>
                        <td><?= $abbonamento['spedizioni'] == -1 ? 'Illimitate' : $abbonamento['spedizioni'] ?></td>
                    </tr>
                    <tr>
                        <th>Durata: </th>
                    </tr>
                    <tr>
                        <td><?= $abbonamento['mesi'] ?> mesi</td>
                    </tr>
                    <tr>
                        <th>Prezzo:</th>
                    </tr>
                    <tr>
                        <td>€ <?= number_format($abbonamento['prezzo'], 2) ?></td>
                    </tr>

                </table>


                <form method="POST" action="pagamento.php">
                    <input type="hidden" name="id_abbonamento" value="<?= $abbonamento['id'] ?>" />
                    <button type="submit" class="pure-button pure-button-primary">Scegli <?= htmlspecialchars($abbonamento['nome']) ?>!</button>
                </form>

                <footer class="w3-container align-center">
                    <br>
                </footer>

            </div>

            <?php if (($index + 1) % 3 == 0 && $index + 1 < count($abbonamenti)): ?>
    </div>
    <div class="pure-g login-card" style="justify-content: center;">
    <?php endif; ?>

    <!-- Aggiungi il div dopo ogni terzo elemento (non divisibile per 3) -->
    <?php if (($index + 1) % 3 != 0): ?>
        <div class="pure-u-md-1-24 pure-u-1-1 filler"></div>
    <?php endif; ?>

<?php endforeach; ?>

    </div>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php'; ?>

    <?php mysqli_close($msConn); ?>
</body>

</html>