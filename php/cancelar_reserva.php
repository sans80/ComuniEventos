<?php
require_once __DIR__ . '/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../mis_reservas.php');
    exit;
}
$id_reserva = intval($_POST['id_reserva'] ?? 0);
$user_id = intval($_SESSION['user_id']);

$stmt = $mysqli->prepare("SELECT estado FROM reservas WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_reserva, $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) die("Reserva no encontrada o sin permiso.");
$row = $res->fetch_assoc();
if (!in_array($row['estado'], ['pendiente','aprobado'])) die("No se puede cancelar una reserva con estado {$row['estado']}.");
$stmt->close();

$upd = $mysqli->prepare("UPDATE reservas SET estado = 'cancelado' WHERE id = ?");
$upd->bind_param("i", $id_reserva);
$upd->execute();
if ($upd->affected_rows > 0) {
    header("Location: ../mis_reservas.php?msg=Cancelada");
    exit;
} else {
    die("No se pudo cancelar la reserva.");
}
