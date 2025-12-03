<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Método no permitido.');
}

$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");

if ($mysqli->connect_errno) {
    die("Error en la conexión: " . $mysqli->connect_error);
}

$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

// Validar campos vacíos
if (empty($nombre) || empty($contrasena)) {
    $_SESSION['error'] = "Completa todos los campos.";
    header("Location: ../inicio_sesion.php");
    exit();
}

// Buscar usuario
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$consulta = $stmt->get_result();


if ($consulta->num_rows === 0) {
    $_SESSION['error'] = "Usuario no encontrado.";
    header("Location: ../inicio_sesion.php");
    exit();
}

$usuario = $consulta->fetch_assoc();

$_SESSION['user_id'] = $usuario['id'];

// Comparar contraseña 
if ($contrasena !== $usuario['contraseña']) {
    $_SESSION['error'] = "Contraseña incorrecta.";
    header("Location: ../inicio_sesion.php");
    exit();
}

$rol = $usuario['id_rol']; 

if ($rol == 1 || $rol == 2) {
    header("Location: ../admin_usuarios.php"); 
} elseif ($rol == 4) {
    header("Location: ../mis_reservas.php");  
}

exit()

?>
