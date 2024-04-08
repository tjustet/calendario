<?php
include ("conn.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


$nombrecategoria = $_POST["nombrecategoria"];
$colorCategoria = $_POST["colorCategoria"];

$insert = "INSERT INTO `categoria`(`ID`, `categoria`, `color`) VALUES (NULL,'$nombrecategoria','$colorCategoria')";

if (mysqli_query($conn, $insert)){
    header("Location: ./index.php");
}


?>