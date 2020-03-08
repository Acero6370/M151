<?php

include('dbconnector.inc.php');

$error = '';
$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)){
	if(!empty(trim($_POST['username']))){

		$username = trim($_POST['username']);

		if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username) || strlen($username) > 20){
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte den Benutzername an.<br />";
	}
	if(!empty(trim($_POST['password']))){
		$password = trim($_POST['password']);
		if(!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
			$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte das Passwort an.<br />";
	}

	if(empty($error)){
		$query = "SELECT Benutzername, PasswordHash, idUsers from user where Benutzername = ? and PasswordHash = password(?)";

		$stmt = $mysqli->prepare($query);
		if($stmt===false){
			$error .= 'prepare() failed '. $mysqli->error . '<br />';
		}
		if(!$stmt->bind_param("ss", $username, $password)){
			$error .= 'bind_param() failed '. $mysqli->error . '<br />';
		}
		if(!$stmt->execute()){
			$error .= 'execute() failed '. $mysqli->error . '<br />';
		}
		$result = $stmt->get_result();
		if($result->num_rows){
			$row = $result->fetch_assoc();
				session_start();
				$_SESSION['idUsers'] = $row['idUsers'];
				header("Location: hauptseite.php");
		} else {
			$error .= "Benutzername oder Passwort sind falsch";
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
			<h1>Login</h1>
			<p>
				Bitte melden Sie sich mit Benutzernamen und Passwort an.
			</p>
			<?php
				if(!empty($message)){
					echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
				} else if(!empty($error)){
					echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
				}
			?>
			<form action="" method="POST">
				<div class="form-group">
				<label for="username">Benutzername *</label>
				<input type="text" name="username" class="form-control" id="username"
						value=""
						placeholder="Gross- und Keinbuchstaben, min 6 Zeichen."
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
