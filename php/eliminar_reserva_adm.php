<?php
require_once __DIR__ . '/config.php';
require_login();

$id = intval($_POST['id_reserva'] ?? 0);

if ($id <= 0) {
    die("ID inválido.");
}

$stmt = $mysqli->prepare("DELETE FROM reservas WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../admin_reservas.php?msg=eliminada");
    exit;
} else {
    die("Error al eliminar la reserva.");
}
