<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>To-Do</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="Style/css.css">
  <h1>Nachrichten</h1>


</head>
<body>
  <?php


  include('dbconnector.inc.php');
  session_start();

  if (empty($_SESSION['idUsers'])) {
    echo "You are not authorized!!<br>";
    exit;
  } else {
  ?>
    <h3><a href="hinzu.php">To-Do hinzufügen</a></h3>
    <h3><a href="dest.php">Abmelden</a></h3>
  <?php
  $sqltodo = "SELECT * FROM todo";
  $resulttodo = $mysqli->query($sqltodo);

  if ($resulttodo->num_rows > 0) {
    while($row = $resulttodo->fetch_assoc()) {
      echo "<p>";
      echo "Titel: ". $row["Titel"]. "<br> Inhalt: ". $row["Inhalt"]. "<br> Priorität: ". $row["Prioritaet"]. "<br> Erstellt am: ". $row["ErstelltAm"]. "<br> To-Do ID: ". $row["idToDo"]. "<br> Läuft ab am: ". $row["TerminDatum"];
      echo "<br>";
      echo "<a href='delete.php?todo_id=" . $row["idToDo"] . "'>Löschen</a>";
      echo "</p>";
    }
  } else {
    echo "<br>";
    echo "Keine To-Dos vorhanden";
  }
}
  ?>
</br>
</body>
</html>
