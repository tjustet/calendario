<?php
include ("conn.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


$fecha = $_POST["fecha"];
$hora = $_POST["hora"];
$tarea = $_POST["tarea"];
$categoria = $_POST["categoria"];
$descripcion = $_POST["descripcion"];

$año = substr($fecha, 0, 4);
$mes = substr($fecha, 5, -3);
$dia = substr($fecha, 8);

$insert = "INSERT INTO `tareas`(`ID`, `fecha`, `dia`, `mes`, `año`, `hora`, `tarea`, `categoria`, `descripcion`) VALUES (NULL,'$fecha','$dia','$mes','$año','$hora','$tarea','$categoria','$descripcion')";


if (mysqli_query($conn, $insert)){
    header("Location: ./index.php");
}


?>