<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");
if ($mysqli->connect_errno) {
    die("Error al conectar: " . $mysqli->connect_error);
}

$id = $_POST["id"];
$nombre = $_POST['nombre'];
$detalle = $_POST["detalle"];
$cantidad = $_POST["cantidad"];


if (empty($id)) {
    die("No se recibió el id del implemento.");
}


$sql = "UPDATE implemento SET nombre = '$nombre', detalle = '$detalle', cantidad = '$cantidad' WHERE id = '$id'";


if ($mysqli->query($sql)) {
    echo "<script>
        alert('implemento actualizado correctamente');
        window.location.href = '../admin_implementos.php';
    </script>";
} else {
    echo "<script>
        alert('Error al actualizar: " . $mysqli->error . "');
        window.history.back();
    </script>";
}

$mysqli->close();

