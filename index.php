<?php
session_start();

$visitsFile = __DIR__ . '/data/visits.txt';
if (file_exists($visitsFile)) {
    $visits = file_get_contents($visitsFile);
    echo $visits;

    $temp = intval($visits) + 1;
    $visits = strval($temp);

    $file = fopen($visitsFile, 'w');
    fwrite($file, $visits);
    fclose($file);
}


?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ordina il tuo panino</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <header>
      <h1>Ordina il tuo panino</h1>
      <p class="lead">Compila il modulo per prenotare il tuo panino.</p>
    </header>

    <form id="orderForm" action="fidelity.php" method="post">
      <fieldset>
        <legend>Dati cliente</legend>
        <label>Nome completo</label>
        <input type="text" name="fullname" minlength="3" maxlength="100" required>

        <label>Telefono</label>
        <input type="tel" name="phone" pattern="[0-9+ \-()]{6,20}" required>
      </fieldset>

      <fieldset>
        <legend>Data e ora di ritiro</legend>
        <input type="datetime-local" name="datetime" required>
      </fieldset>

      <fieldset>
        <legend>Il tuo panino</legend>
        <p>Tipo di pane</p>
        <label><input type="radio" name="bread" value="Baguette" required> Baguette</label>
        <label><input type="radio" name="bread" value="Ciabatta"> Ciabatta</label>
        <label><input type="radio" name="bread" value="Focaccia"> Focaccia</label>

        <label>Dimensione</label>
        <select name="size">
          <option>Small</option>
          <option selected>Medium</option>
          <option>Large</option>
        </select>

        <label>Ingredienti</label>
        <label><input type="checkbox" name="toppings[]" value="Prosciutto" <?php if (isset($_COOKIE['Prosciutto']) && $_COOKIE['Prosciutto'] === 'True') echo 'checked'; ?>> Prosciutto</label>
        <label><input type="checkbox" name="toppings[]" value="Formaggio" <?php if (isset($_COOKIE['Formaggio']) && $_COOKIE['Formaggio'] === 'True') echo 'checked'; ?>> Formaggio</label>
        <label><input type="checkbox" name="toppings[]" value="Pomodoro" <?php if (isset($_COOKIE['Pomodoro']) &&  $_COOKIE['Pomodoro'] === 'True') echo 'checked'; ?>> Pomodoro</label>
        <label><input type="checkbox" name="toppings[]" value="Insalata" <?php if (isset($_COOKIE['Insalata']) && $_COOKIE['Insalata'] === 'True') echo 'checked'; ?>> Insalata</label>
        <label><input type="checkbox" name="toppings[]" value="Salsa" <?php if (isset($_COOKIE['Salsa']) &&  $_COOKIE['Salsa'] === 'True') echo 'checked'; ?>> Salsa</label>

        <label>Note</label>
        <textarea name="notes" rows="3"></textarea>
      </fieldset>

      <div class="actions">
        <button type="submit">Prosegui</button>
      </div>
    </form>
  </main>

  <?php
      $username = 'root';
      $password = '';
      $endpoint = 'localhost';
      
      $conn = new mysqli($endpoint, $username, $password);

      if ($conn->connect_error) {
          die('Connection failed: ' . $conn -> connect_error);
      }

      echo 'Connected successfully <br>';

      $conn -> select_db('paninoteca');

      $query = 'SELECT * FROM `utenti`';
      $stmt = $conn -> prepare($query);
      $stmt -> execute();
      $result = $stmt -> get_result();

      if ($result != false)
          echo 'Succesful statement execute! <br>';


      $row = $result -> fetch_row();
      

      $stmt -> close();
      $conn -> close();

      echo 'Statement & Connection closed!';
  ?>
</body>
</html>
