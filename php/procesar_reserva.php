<?php

require_once __DIR__ . '/config.php';
require_login();


$user_id = intval($_SESSION['user_id']);
$id_salon = intval($_POST['id_salon'] ?? 0);
$fecha_evento = trim($_POST['fecha_evento'] ?? '');
$hora_inicio = trim($_POST['hora_inicio'] ?? '');
$hora_fin = trim($_POST['hora_fin'] ?? '');
$motivo = substr(trim($_POST['motivo_evento'] ?? ''), 0, 255);
$valor_pago = isset($_POST['valor_pago']) && $_POST['valor_pago'] !== '' ? floatval($_POST['valor_pago']) : null;
$implementos = $_POST['implementos'] ?? [];


if (!$id_salon || !$fecha_evento || !$hora_inicio || !$hora_fin) {
    die("Faltan datos requeridos.");
}
if ($hora_inicio >= $hora_fin) {
    die("La hora de fin debe ser posterior a la de inicio.");
}

$fecha_sql = $mysqli->real_escape_string($fecha_evento);
$hora_inicio_sql = $mysqli->real_escape_string($hora_inicio);
$hora_fin_sql = $mysqli->real_escape_string($hora_fin);

$lock_name = 'reserva_salon_' . intval($id_salon) . '_' . str_replace('-', '_', $fecha_sql);

$escaped_lock = $mysqli->real_escape_string($lock_name);
$r = $mysqli->query("SELECT GET_LOCK('{$escaped_lock}', 8) AS gotlock");
if (!$r) {
    error_log("GET_LOCK fallo query: " . $mysqli->error);
    die("Error interno. Intenta de nuevo.");
}
$got = $r->fetch_assoc()['gotlock'] ?? 0;
if (!$got) {
    die("Servidor ocupado. Intenta de nuevo en unos segundos.");
}

try {
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) AS cnt FROM reservas
        WHERE id_salon = ? AND fecha_evento = ?
          AND estado IN ('pendiente','aprobado')
          AND (? < hora_fin) AND (? > hora_inicio)
    ");
    if (!$stmt) throw new Exception("Prepare fallo: " . $mysqli->error);
    $stmt->bind_param("isss", $id_salon, $fecha_sql, $hora_fin_sql, $hora_inicio_sql);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (intval($row['cnt']) > 0) {
        $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
        die("La hora/fecha solicitada se solapa con otra reserva. Elige otro horario.");
    }
    $imp_ids = array_keys(array_filter($implementos, function($q){ return intval($q) > 0; }));
    $reserved = [];
    $dispon = [];
    if (count($imp_ids) > 0) {
        $in = implode(',', array_map('intval', $imp_ids));
        $q = $mysqli->query("SELECT id, cantidad FROM implemento WHERE id IN ($in)");
        while($r2 = $q->fetch_assoc()) $dispon[intval($r2['id'])] = intval($r2['cantidad']);

        $sql = "
            SELECT dr.id_implemento, SUM(dr.cantidad) as total
            FROM detalle_reserva dr
            JOIN reservas r ON r.id = dr.id_reserva
            WHERE r.id_salon = ? AND r.fecha_evento = ?
              AND r.estado IN ('pendiente','aprobado')
              AND (? < r.hora_fin) AND (? > r.hora_inicio)
              AND dr.id_implemento IN ($in)
            GROUP BY dr.id_implemento
        ";
        $stmt2 = $mysqli->prepare($sql);
        if (!$stmt2) throw new Exception("Prepare2 fallo: " . $mysqli->error);
        $stmt2->bind_param("isss", $id_salon, $fecha_sql, $hora_fin_sql, $hora_inicio_sql);
        $stmt2->execute();
        $resr = $stmt2->get_result();
        while($rr = $resr->fetch_assoc()) $reserved[intval($rr['id_implemento'])] = intval($rr['total']);
        $stmt2->close();

        foreach($implementos as $id => $cant) {
            $idI = intval($id);
            $cantReq = intval($cant);
            if ($cantReq <= 0) continue;
            $total_available = ($dispon[$idI] ?? 0) - ($reserved[$idI] ?? 0);
            if ($cantReq > $total_available) {
                $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
                die("No hay suficiente cantidad del implemento ID $idI. Disponibles: $total_available.");
            }
        }
    }

    $upload_path = __DIR__ . '/../uploads/comprobantes';
    if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);
    $comprobante_db = null;
    if (!empty($_FILES['comprobante_pago']['name'])) {
        $file = $_FILES['comprobante_pago'];
        $allowed = ['image/jpeg','image/png','application/pdf'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
            die("Error subiendo comprobante.");
        }
        if (!in_array($file['type'], $allowed)) {
            $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
            die("Tipo de archivo no permitido.");
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newname = 'comp_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $upload_path . '/' . $newname)) {
            $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
            die("No se pudo guardar el comprobante.");
        }
        $comprobante_db = 'uploads/comprobantes/' . $newname;
    }

    $mysqli->begin_transaction();

    $ins = $mysqli->prepare("INSERT INTO reservas (id_usuario, id_salon, fecha_evento, hora_inicio, hora_fin, estado, motivo_evento, valor_pago, comprobante_pago) VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?, ?)");
    if (!$ins) throw new Exception("Prepare insert fallo: " . $mysqli->error);

    $v_valor = $valor_pago !== null ? $valor_pago : 0.0;
    $v_comprob = $comprobante_db !== null ? $comprobante_db : null;
    $ins->bind_param("iisssdss", $user_id, $id_salon, $fecha_sql, $hora_inicio_sql, $hora_fin_sql, $motivo, $v_valor, $v_comprob);
    $ins->execute();
    $id_reserva = $mysqli->insert_id;
    $ins->close();

    if (count($imp_ids) > 0) {
        $stmtDet = $mysqli->prepare("INSERT INTO detalle_reserva (id_reserva, id_implemento, cantidad) VALUES (?, ?, ?)");
        if (!$stmtDet) throw new Exception("Prepare detalle fallo: " . $mysqli->error);
        foreach($implementos as $id => $cant) {
            $idI = intval($id);
            $cantReq = intval($cant);
            if ($cantReq <= 0) continue;
            $stmtDet->bind_param("iii", $id_reserva, $idI, $cantReq);
            $stmtDet->execute();
        }
        $stmtDet->close();
    }

    $mysqli->commit();

    $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");

    error_log("Reserva creada id={$id_reserva} user={$user_id} salon={$id_salon} fecha={$fecha_sql} {$hora_inicio_sql}-{$hora_fin_sql}");

    header("Location: ../mis_reservas.php?msg=Reservado&id={$id_reserva}");
    exit;

} catch (Exception $e) {
    if ($mysqli->in_transaction) $mysqli->rollback();
    $mysqli->query("SELECT RELEASE_LOCK('{$escaped_lock}')");
    error_log("Error procesar_reserva: " . $e->getMessage());
    die("Error procesando la reserva: " . $e->getMessage());
}
