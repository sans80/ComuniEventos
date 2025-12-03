<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Método no permitido. Por favor, usa el método POST.');
}

$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");

if ($mysqli->connect_errno) {
    die("Error en la conexión a MySQL: " . $mysqli->connect_error);
}

$nombre = $_POST['nombre'];
$detalle = $_POST['detalle'];
$cantidad = $_POST['cantidad'];

if ( empty($nombre) || empty($detalle) || empty($cantidad)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: ../admin_implementos.php");
    exit();
}

    $consulta = "INSERT INTO implemento 
        (nombre, detalle, cantidad )
        VALUES 
        ('$nombre', '$detalle', '$cantidad')";

    if ($mysqli->query($consulta)) {
        $_SESSION['error'] = "Implemento registrado exitosamente.";
        header("Location: ../admin_implementos.php");
    } else {
        $_SESSION['error'] = "Error en el registro: " . $mysqli->error;
        header("Location: ../admin_implementos.php");
    }


