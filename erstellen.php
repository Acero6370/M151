<?php

include('dbconnector.inc.php');

$error = $message =  '';
$firstname = $lastname = $email = $username = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){

  if(isset($_POST['firstname']) && !empty(trim($_POST['firstname'])) && strlen(trim($_POST['firstname'])) <= 45){
    $firstname = htmlspecialchars(trim($_POST['firstname']));
  } else {
    $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
  }

  if(isset($_POST['lastname']) && !empty(trim($_POST['lastname'])) && strlen(trim($_POST['lastname'])) <= 45){
    $lastname = htmlspecialchars(trim($_POST['lastname']));
  } else {
    $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
  }

  if(isset($_POST['email']) && !empty(trim($_POST['email'])) && strlen(trim($_POST['email'])) <= 255){
    $email = htmlspecialchars(trim($_POST['email']));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
      $error .= "Geben Sie bitte eine korrekte Email-Adresse ein<br />";
    }
  } else {
    $error .= "Geben Sie bitte eine korrekte Email-Adresse ein.<br />";
  }
  if(isset($_POST['username']) && !empty(trim($_POST['username'])) && strlen(trim($_POST['username'])) <= 20){
    $username = trim($_POST['username']);
		if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username)){
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
  } else {
    $error .= "Geben Sie bitte einen korrekten Benutzernamen ein.<br />";
  }

  if(isset($_POST['password']) && !empty(trim($_POST['password']))){
    $passwordHash = trim($_POST['password']);
    if(!preg_match("/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $passwordHash)){
      $error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
    }
  } else {
    $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
  }

  if(empty($error)){
    $query = "Insert into user (Vorname, Nachname, email, Benutzername, PasswordHash, IsAdmin) values (?,?,?,?,password(?), 0)";
    $stmt = $mysqli->prepare($query);
    if($stmt===false){
      $error .= 'prepare() failed '. $mysqli->error . '<br />';
    }
    if(!$stmt->bind_param('sssss', $firstname, $lastname, $email, $username, $passwordHash)){
      $error .= 'bind_param() failed '. $mysqli->error . '<br />';
    }
    if(!$stmt->execute()){
      $error .= 'execute() failed '. $mysqli->error . '<br />';
    }
    if(empty($error)){
      $message .= "Die Daten wurden erfolgreich in die Datenbank geschrieben<br/ >";
      $username = $passwordHash = $firstname = $lastname = $email =  '';
      $mysqli->close();
      session_start();
      header("Location: hauptseite.php");
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrierung</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="Style/css.css">
  </head>
  <body>

    <div class="container">
      <h1>Registrierung</h1>
      <p>
        Bitte registrieren Sie sich, damit Sie diesen Dienst benutzen können.
      </p>
      <?php
        if(!empty($error)){
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)){
          echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label for="firstname">Vorname *</label>
          <input type="text" name="firstname" class="form-control" id="firstname"
                  value="<?php echo $firstname ?>"
                  placeholder="Geben Sie Ihren Vornamen an. (Max. 45 Zeichen)"
                  maxlength="45"
                  required="true">
        </div>
        <div class="form-group">
          <label for="lastname">Nachname *</label>
          <input type="text" name="lastname" class="form-control" id="lastname"
                  value="<?php echo $lastname ?>"
                  placeholder="Geben Sie Ihren Nachnamen an. (Max. 45 Zeichen)"
                  maxlength="45"
                  required="true">
        </div>
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" name="email" class="form-control" id="email"
                  value="<?php echo $email ?>"
                  placeholder="Geben Sie Ihre Email-Adresse an. (Max. 255 Zeichen)"
                  maxlength="255"
                  required="true">
        </div>
        <div class="form-group">
          <label for="username">Benutzername *</label>
          <input type="text" name="username" class="form-control" id="username"
                  value="<?php echo $username ?>"
                  placeholder="Gross- und Keinbuchstaben (Min. 6 Zeichen, Max. 20 Zeichen)"
                  maxlength="20" required="true"
                  pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
                  title="Gross- und Keinbuchstaben, min 6 Zeichen.">
        </div>
        <div class="form-group">
          <label for="password">Password *</label>
          <input type="password" name="password" class="form-control" id="password"
                  placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute"
                  pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                  title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute."
                  required="true">
        </div>
        <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
      </form>
    </div>
  </body>
</html>
