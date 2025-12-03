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
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$N_documento = $_POST['N_documento'];
$rol = $_POST['rol_usu'];
$contrasena = $_POST['contrasena'];
$confir = $_POST['con_contrasena'];


if (
    empty($nombre) || empty($apellido) || empty($correo) || empty($telefono) ||
    empty($direccion) || empty($N_documento) || empty($rol) || empty($contrasena)
) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: ../admin_usuarios.php");
    exit();
}

if ($contrasena !== $confir) {
    $_SESSION['error'] = "Las contraseñas no coinciden.";
    header("Location: ../admin_usuarios.php");
    exit();
}

// verificar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Ingrese bien su correo";
    header("Location: ../admin_usuarios.php");
    exit();
}

$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$query = $stmt->get_result();


if ($query->num_rows > 0) {
    $_SESSION['error'] = "El correo electronico ya esta registrado.";
    header("Location: ../admin_usuarios.php");
    exit();
}


// Validar si el documento ya existe
$query = $mysqli->query("SELECT * FROM usuarios WHERE documento_identidad = '$N_documento'");

if ($query->num_rows > 0) {
    $_SESSION['error'] = "El número de documento ya está registrado.";
    header("Location: ../admin_usuarios.php");
    exit();
} else {
    $consulta = "INSERT INTO usuarios 
        (nombre, apellido, email, contraseña, telefono, direccion, documento_identidad, id_rol, creado_en, actualizado_en)
        VALUES 
        ('$nombre', '$apellido', '$correo', '$contrasena', '$telefono', '$direccion', '$N_documento', $rol, NOW(), NOW())";

    if ($mysqli->query($consulta)) {
        $_SESSION['error'] = "Usuario registrado exitosamente.";
        header("Location: ../admin_usuarios.php");
    } else {
        $_SESSION['error'] = "Error en el registro: " . $mysqli->error;
        header("Location: ../admin_usuarios.php");
    }
}

?>