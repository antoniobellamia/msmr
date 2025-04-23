<?php session_start() ?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Ordine</title>
</head>
<body>
    <h1>Crea un Nuovo Ordine</h1>
    <form action="nuovo-ordine-action.php" method="POST">
        <label for="titolo">Titolo:</label><br>
        <input type="text" id="titolo" name="titolo" required><br><br>

        <label for="descrizione">Descrizione:</label><br>
        <textarea id="descrizione" name="descrizione" required></textarea><br><br>

        <label for="istr_consegna">Istruzioni di Consegna:</label><br>
        <textarea id="istr_consegna" name="istr_consegna" required></textarea><br><br>

        <label for="id_mitt">ID Mittente:</label><br>
        <input type="number" id="id_mitt" name="id_mitt" required><br><br>

        <label for="id_dest">ID Destinatario:</label><br>
        <input type="number" id="id_dest" name="id_dest" required><br><br>

        <input type="submit" value="Crea Ordine">
    </form>
</body>
</html>

