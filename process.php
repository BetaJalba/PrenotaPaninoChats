<?php
session_start();

if (!isset($_SESSION['order'])) {
  header('Location: index.php');
  exit;
}

$order = $_SESSION['order'];
$code = trim($_POST['code'] ?? '');

function safe($s) { return htmlspecialchars(trim($s)); }

$fullname = safe($order['fullname']);
$phone = safe($order['phone']);
$datetime = safe($order['datetime']);
$bread = safe($order['bread']);
$size = safe($order['size']);
$toppings = $order['toppings'];
$notes = safe($order['notes']);

$basePrice = 6.5;
$price = $basePrice + (count($toppings) * 0.5);
$discountApplied = false;
$discountPercent = 0.20;

// Verifica codice fidelity
$codesFile = __DIR__ . '/data/codes.txt';
if (file_exists($codesFile)) {
  $validCodes = file($codesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (in_array($code, $validCodes)) {
    $discountApplied = true;
    $price = round($price * (1 - $discountPercent), 2);
  }
}

// Scontrino
$receipt  = "=== SCONTRINO PANINO ===\n";
$receipt .= "Cliente: $fullname\n";
$receipt .= "Telefono: $phone\n";
$receipt .= "Data richiesta: $datetime\n";
$receipt .= "Pane: $bread\n";
$receipt .= "Dimensione: $size\n";
$receipt .= "Ingredienti: " . implode(', ', $toppings) . "\n";
$receipt .= "Note: $notes\n";
$receipt .= "Prezzo finale: €" . number_format($price, 2) . "\n";
$receipt .= $discountApplied ? "Sconto Fidelity: 20% ($code)\n" : "Sconto Fidelity: nessuno\n";
$receipt .= "=========================\n\n";

$receiptsFile = __DIR__ . '/data/receipts.txt';
file_put_contents($receiptsFile, $receipt, FILE_APPEND);

session_destroy(); // Fine sessione
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Conferma Ordine</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <header>
      <h1>Conferma Ordine</h1>
    </header>

    <p>Il tuo ordine è stato registrato correttamente.</p>

    <table>
      <tr><th>Nome</th><td><?= $fullname ?></td></tr>
      <tr><th>Telefono</th><td><?= $phone ?></td></tr>
      <tr><th>Data ritiro</th><td><?= $datetime ?></td></tr>
      <tr><th>Pane</th><td><?= $bread ?></td></tr>
      <tr><th>Dimensione</th><td><?= $size ?></td></tr>
      <tr><th>Ingredienti</th><td><?= implode(', ', $toppings) ?></td></tr>
      <tr><th>Note</th><td><?= $notes ?: '—' ?></td></tr>
      <tr><th>Prezzo finale</th><td>€<?= number_format($price, 2) ?></td></tr>
      <tr><th>Codice Fidelity</th><td><?= $discountApplied ? "Sconto 20% ($code)" : "Nessuno" ?></td></tr>
    </table>

    <div class="actions">
      <a href="index.php" class="button">Nuovo ordine</a>
      <a href="data/receipts.txt" class="button ghost" target="_blank">Vedi scontrini</a>
    </div>
  </main>
</body>
</html>
