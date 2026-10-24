<?php
// Tariffe orarie in euro per tipologia di veicolo
$tariffe = [
    'Automobile'    => 2,
    'Motocicletta'  => 1,
];

// Tipologia di veicolo passata dalla home tramite GET
$veicolo = $_GET['veicolo'] ?? '';
if (!array_key_exists($veicolo, $tariffe)) {
    // Veicolo mancante o non valido: torno alla home
    header('Location: home.php');
    exit;
}

$errore = '';
$riepilogo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $ore  = isset($_POST['ore']) ? $_POST['ore'] : '';

    if (empty($nome) || empty($ore)) {
        $errore = 'Compila tutti i campi del modulo per calcolare il costo della sosta.';
    } elseif (!in_array($ore, ['1', '2', '3', '4', '5', '6'], true)) {
        $errore = 'Il numero di ore selezionato non è valido.';
    } else {
        $oreSosta   = (int) $ore;
        $costoOrario = $tariffe[$veicolo];
        $costoTotale = $costoOrario * $oreSosta;
        $riepilogo = true;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prenotazione parcheggio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        label {
            display: inline-block;
            width: 140px;
        }
        input, select, button {
            margin: 6px 0;
            padding: 4px;
        }
        table {
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 12px;
            text-align: left;
        }
        .errore {
            color: #b00020;
            font-weight: bold;
        }
        .conferma {
            color: #1b7a2f;
            font-weight: bold;
        }
        a {
            display: inline-block;
            margin-top: 12px;
        }
    </style>
</head>
<body>

<h1>Prenotazione parcheggio</h1>

<p>Veicolo selezionato: <strong><?= htmlspecialchars($veicolo) ?></strong></p>

<?php if ($riepilogo): ?>

    <h2>Riepilogo della prenotazione</h2>
    <table>
        <tr>
            <th>Nome cliente</th>
            <td><?= htmlspecialchars($nome) ?></td>
        </tr>
        <tr>
            <th>Tipologia di veicolo</th>
            <td><?= htmlspecialchars($veicolo) ?></td>
        </tr>
        <tr>
            <th>Numero di ore</th>
            <td><?= $oreSosta ?></td>
        </tr>
        <tr>
            <th>Costo orario</th>
            <td><?= $costoOrario ?> euro</td>
        </tr>
        <tr>
            <th>Costo totale</th>
            <td><strong><?= $costoTotale ?> euro</strong></td>
        </tr>
    </table>
    <p class="conferma">Prenotazione effettuata correttamente!</p>

<?php else: ?>

    <?php if ($errore !== ''): ?>
        <p class="errore"><?= htmlspecialchars($errore) ?></p>
    <?php endif; ?>

    <form method="post" action="parcheggio.php?veicolo=<?= urlencode($veicolo) ?>">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        </div>
        <div>
            <label for="ore">Numero di ore:</label>
            <select id="ore" name="ore">
                <option value=""></option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?= $i ?>" <?= (($_POST['ore'] ?? '') == $i) ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit">Calcola prezzo</button>
    </form>

<?php endif; ?>

<a href="home.php">Torna alla home</a>

</body>
</html>
