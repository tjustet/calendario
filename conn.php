<?php
$conn = mysqli_connect('localhost', 'root', '', 'calen')
  or die(mysqli_error($mysqli));
  
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>