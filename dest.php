<?php
session_start();
$_SESSION['idUsers'] = null;
if (empty($_SESSION['idUsers'])) {
  echo "You are not authorized!!<br>";
  header("Location: index.php");
}
?>
