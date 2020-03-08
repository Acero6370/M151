<?php

include('dbconnector.inc.php');
session_start();
$error = $message =  '';
$erstellt = $inhalt = $prio = $expire = $titel = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){

  if(isset($_POST['inhalt']) && !empty(trim($_POST['inhalt'])) && strlen(trim($_POST['inhalt'])) <= 45){
    $inhalt = htmlspecialchars(trim($_POST['inhalt']));
  } else {
    $error .= "Geben Sie bitte einen korrekten Inhalt ein.<br />";
  }
  if(isset($_POST['prio']) && !empty(trim($_POST['prio'])) && strlen(trim($_POST['prio'])) <= 30){
    $prio = htmlspecialchars(trim($_POST['prio']));
  } else {
    $error .= "Geben Sie bitte eine korrekten Priorität ein.<br />";
  }

  if(isset($_POST['expire']) && !empty(trim($_POST['expire'])) && strlen(trim($_POST['expire'])) <= 30){
    $expire = htmlspecialchars(trim($_POST['expire']));
  } else {
    $error .= "Geben Sie bitte eine korrekten Termin ein.<br />";
  }

  if(isset($_POST['titel']) && !empty(trim($_POST['titel'])) && strlen(trim($_POST['titel'])) <= 10){
    $titel = trim($_POST['titel']);
		if(!preg_match("/\w{1,10}/", $titel)){
			$error .= "Der Titel entspricht nicht dem geforderten Format.<br />";
		}
  } else {
    $error .= "Geben Sie bitte einen korrekten Titel ein.<br />";
  }
  if(empty($error)){


    $query = "Insert into todo ( ErstelltAm, Inhalt, Prioritaet, TerminDatum, Titel) values (Current_Timestamp,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    if($stmt===false){
      $error .= 'prepare() failed '. $mysqli->error . '<br />';
    }

    if(!$stmt->bind_param('siss', $inhalt, $prio, $expire, $titel)){
      $error .= 'bind_param() failed '. $mysqli->error . '<br />';
    }

    if(!$stmt->execute()){
      $error .= 'execute() failed '. $mysqli->error . '<br />';
    }
    if(empty($error)){
      $message .= "Die Daten wurden erfolgreich in die Datenbank geschrieben<br/ >";
      $erstellt = $inhalt = $prio = $expire = $titel = '';
      $mysqli->close();
      header("Location: hauptseite.php");
    }

  }
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To-Do hinzufügen</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="Style/css.css">
</head>
  <body>
    <div class="container">
      <h1>To-Do erstellen</h1>
      <h3><a href="index.php">Abmelden</a></h3>
      <p>
        Erstellen sie hier ein To-Do.
      </p>
      <?php
        if(!empty($error)){
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)){
          echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
      ?>
      <form enctype="multipart/form-data" action="" method="post">
      <div class="form-group">
          <label for="title">Titel:</label>
          <input type="text" name="titel" class="form-control" id="titel"
                  placeholder="Geben sie hier den Titel ihres To-Dos an. Max. 10 Zeichen"
                  maxlength="10"
                  required="true">
      </div>
      <div class="form-group">
          <label for="message">Inhalt:</label>
          <input type="text" name="inhalt" class="form-control" id="inhalt"
                  placeholder="Geben sie hier den Inhalt ihres To-Dos an. (Max. 45 Zeichen)"
                  maxlength="45"
                  required="true">
      </div>
      <div class="form-group">
          <label for="prio">Priorität:</label>
          <select id="prio" name="prio">
            <option value="1">Gering</option>
            <option value="2">Mittel</option>
            <option value="3">Hoch</option>
          </select>
      </div>
      <div class="form-group">
          <label for="expire">Termin Ende:</label>
          <input type="date" id="expire" name="expire">
      </div>
          <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
        </form>
      </div>
  </body>
</html>
