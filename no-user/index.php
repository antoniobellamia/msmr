<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/session-con.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/errors/anti-injection.php';

if (isset($_POST["idOrdine"]) && !empty($_POST["idOrdine"])) $idOrdine = antiInjection($_POST['idOrdine']);
else {
    header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr?err=1&s=");
    die();
}


if ($msConn) {
    $querySql = "SELECT id_ordine, stato, data, isReso FROM stato S JOIN ordine O ON S.id_ordine = O.id WHERE id_ordine = $idOrdine ORDER BY S.data DESC LIMIT 1;";

    try {
        $queryRes = mysqli_query($msConn, $querySql);

        $row = mysqli_fetch_assoc($queryRes);
        if (empty($row)) throw new Exception();

        $id_ordine = $row["id_ordine"];
        $stato = $row["stato"];
        $data = $row["data"];
        $isReso = $row["isReso"];
    } catch (Exception $exc) {
        header("Location: //" . $_SERVER['SERVER_NAME'] . "/msmr?err=1&s=");
        die();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            text-align: left;
            border: 0;
        }

        td {
            text-indent: 2rem;
        }

        @media screen and (max-width: 768px) {

            td,
            tr {
                display: block;
                width: 100%;
                text-indent: 0;
            }
        }
    </style>

    <title>RICERCA ORDINE</title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/navbar.php' ?>
    <!--BODY-->
    <div class="pure-g login-card" style="justify-content: center">
        <div class="w3-card-4 pure-u-md-1-2 pure-u-1-1">

            <header class="w3-container" style="text-align: center;">
                <h2>Informazioni Ordine Nro: <?= $id_ordine ?></h2>
            </header>

            <div class="w3-container">
                <table table style="margin: 0 auto;">
                    <tr>
                        <th>ULTIMO STATO: </th>
                        <td><?= $stato ?></td>
                    </tr>
                    <tr>
                        <th>ULTIMO AGGIORNAMENTO: </th>
                        <td><?= $data ?></td>
                    </tr>
                    <tr>
                        <th>E' UN RESO? </th>
                        <td><?php if ($isReso) echo "SI";
                            else echo "NO"; ?></td>
                    </tr>
                </table>
            </div>

            <footer class="w3-container align-center">
                <p>Accedere per visualizzare i dettagli della spedizione.</p>
            </footer>



        </div>
    </div>

    </form>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/msmr/components/footer.php' ?>