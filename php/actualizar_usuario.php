<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");
if ($mysqli->connect_errno) {
    die("Error al conectar: " . $mysqli->connect_error);
}
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$documento = $_POST['documento']; 
$rol = $_POST['rol_usu'];
$contrasena = $_POST['contrasena'];
$confir = $_POST['con_contrasena'];

if (empty($documento)) {
    die("No se recibió el documento del cliente.");
}
// Si llena la contraseña
if (!empty($contrasena)) {
    if ($contrasena !== $confir) {  
        die("Las contraseñas no coinciden.");
    }
    $sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', email = '$correo', contraseña = '$contrasena', telefono = '$telefono', direccion = '$direccion', id_rol = '$rol', actualizado_en = NOW() WHERE documento_identidad = '$documento'";
}
// Si NO cambia la contraseña
else {
    $sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', email = '$correo', telefono = '$telefono', direccion = '$direccion', id_rol = '$rol', actualizado_en = NOW() WHERE documento_identidad = '$documento'";
}
if ($mysqli->query($sql)) {
    echo "<script>
        alert('Cliente actualizado correctamente');
        window.location.href = '../admin_usuarios.php';
    </script>";
} else {
    echo "<script>
        alert('Error al actualizar: " . $mysqli->error . "');
        window.history.back();
    </script>";
}
$mysqli->close();
