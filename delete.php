<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Löschen</title>
  <link rel="stylesheet" href="Style/css.css">
</head>
<body>

  <?php
  session_start();

  if (empty($_SESSION['idUsers'])) {
    echo "You are not authorized!!<br>";
    exit;
  } else {
  ?>
  <?php
  include('dbconnector.inc.php');
  $error = $message =  '';
  if(empty($error)){
    $query = "DELETE FROM todo WHERE idToDo = ?";
    $stmt = $mysqli->prepare($query);

    $todo_id = $_GET['todo_id'];

    if($stmt===false){
      $error .= 'prepare() failed '. $mysqli->error . '<br />';
    }

    if (empty($todo_id)) {
      $error .= 'todo_id not set.';
    }

    if(!$stmt->bind_param('i', $todo_id)){
      $error .= 'bind_param() failed '. $mysqli->error . '<br />';
    }

    if(!$stmt->execute()){
      $error .= 'execute() failed '. $mysqli->error . '<br />';
    }

    if(empty($error)){
      $message .= "Das Todo wurde erfolgreich gelöscht<br/ >";
      $username = $passwordHash = $firstname = $lastname = $email =  '';
      $mysqli->close();
    } else {
      echo $error;
    }
    echo $message;
    header("Location: hauptseite.php");

}
}
    ?>
  </body>
  </html>
