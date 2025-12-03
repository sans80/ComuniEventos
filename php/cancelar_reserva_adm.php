<?php
require_once __DIR__ . '/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_reservas.php');
    exit;
}

$id_reserva = intval($_POST['id_reserva'] ?? 0);
$accion = strtolower(trim($_POST['accion'] ?? ''));

if ($id_reserva <= 0) {
    header('Location: ../admin_reservas.php?msg=ID_invalido');
    exit;
}

if (!in_array($accion, ['aceptar', 'cancelar', 'reactivar'])) {
    header('Location: ../admin_reservas.php?msg=Accion_invalida');
    exit;
}

// Obtener estado actual
$stmt = $mysqli->prepare("SELECT estado FROM reservas WHERE id = ?");
$stmt->bind_param("i", $id_reserva);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header('Location: ../admin_reservas.php?msg=Reserva_no_encontrada');
    exit;
}
$row = $res->fetch_assoc();
$estado_actual = $row['estado'];
$stmt->close();

switch ($accion) {
    case 'aceptar':
        // Solo aceptar si está pendiente
        if ($estado_actual !== 'pendiente') {
            header("Location: ../admin_reservas.php?msg=No_se_puede_aceptar_estado_{$estado_actual}");
            exit;
        }
        $nuevo_estado = 'aprobado';
        break;

    case 'cancelar':
        // Cancelar si está pendiente o aprobado
        if (!in_array($estado_actual, ['pendiente', 'aprobado'])) {
            header("Location: ../admin_reservas.php?msg=No_se_puede_cancelar_estado_{$estado_actual}");
            exit;
        }
        $nuevo_estado = 'cancelado';
        break;

    case 'reactivar':
        // Reactivar solo si está cancelado
        if ($estado_actual !== 'cancelado') {
            header("Location: ../admin_reservas.php?msg=No_se_puede_reactivar_estado_{$estado_actual}");
            exit;
        }
        $nuevo_estado = 'pendiente';
        break;

    default:
        header('Location: ../admin_reservas.php?msg=Accion_no_soportada');
        exit;
}

// Actualizar estado
$upd = $mysqli->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
$upd->bind_param("si", $nuevo_estado, $id_reserva);
$upd->execute();

if ($upd->affected_rows > 0) {
    $msg = ($accion === 'aceptar') ? 'Aceptada' : (($accion === 'cancelar') ? 'Cancelada' : 'Reactivada');
    header("Location: ../admin_reservas.php?msg={$msg}");
    exit;
} else {
    header("Location: ../admin_reservas.php?msg=No_se_pudo_actualizar");
    exit;
}
