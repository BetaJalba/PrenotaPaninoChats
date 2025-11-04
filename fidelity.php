<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

// Salva i dati nella sessione
$_SESSION['order'] = [
  'fullname' => $_POST['fullname'],
  'phone' => $_POST['phone'],
  'datetime' => $_POST['datetime'],
  'bread' => $_POST['bread'],
  'size' => $_POST['size'],
  'toppings' => $_POST['toppings'] ?? [],
  'notes' => $_POST['notes'] ?? ''
];

foreach($_SESSION['order']['toppings'] as $k => $v){
  echo $v;
  setcookie($v, 'True', time() + (60 * 60 * 24 * 60),'/', null, false, true);
}
  
  
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Codice Fidelity</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <h1>Codice Fidelity</h1>
    <p>Inserisci il codice fidelity per ottenere uno sconto del 20%.</p>

    <form action="process.php" method="post">
      <label for="code">Codice fidelity</label>
      <input type="text" id="code" name="code" placeholder="Inserisci codice o lascia vuoto">

      <div class="actions">
        <button type="submit">Conferma ordine</button>
        <a href="index.php" class="button ghost">Torna indietro</a>
      </div>
    </form>
  </main>
</body>
</html>
