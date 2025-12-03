<?php
require_once __DIR__ . '/php/config.php';
require_login();

$stmt = $mysqli->prepare("
  SELECT r.*, s.nombre AS salon_nombre, u.nombre AS usuario_nombre
  FROM reservas r
  JOIN salon s ON s.id = r.id_salon
  JOIN usuarios u ON u.id = r.id_usuario
  ORDER BY r.fecha_evento DESC, r.hora_inicio DESC
");
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Todas las reservas</title>
  <link rel="stylesheet" href="assets/css/estilos.css">
  <link rel="stylesheet" href="assets/css/admin.css">
  <link rel="stylesheet" href="assets/css/formularios.css">
</head>

<body>
  <?php require_once "barra.php"; ?>
  <header>
    <div class="container header-inner">
      <h1 class="logo">Gestión de Reservas</h1>
      <div class="acciones-header">
        <a href="index.php" class="btn">Inicio</a>
      </div>
    </div>
  </header>

  <main class="container">
    <h2>Todas las reservas</h2>

    <?php if (!empty($_GET['msg'])): ?>
      <p class="mensaje"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Salón</th>
          <th>Fecha</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = $res->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($r['id']) ?></td>
            <td><?= htmlspecialchars($r['usuario_nombre']) ?></td>
            <td><?= htmlspecialchars($r['salon_nombre']) ?></td>
            <td><?= htmlspecialchars($r['fecha_evento']) ?></td>
            <td><?= htmlspecialchars($r['hora_inicio']) ?></td>
            <td><?= htmlspecialchars($r['hora_fin']) ?></td>
            <td><?= htmlspecialchars($r['estado']) ?></td>
            <td>
  <?php
    $estado = $r['estado'];
  ?>

  <!-- Aceptar (solo pendiente) -->
  <?php if ($estado === 'pendiente'): ?>
    <form action="php/cancelar_reserva_adm.php" method="post" style="display:inline">
      <input type="hidden" name="id_reserva" value="<?= intval($r['id']) ?>">
      <input type="hidden" name="accion" value="aceptar">
      <button type="submit" onclick="return confirm('¿Aceptar reserva?')"
              style="background-color:green;color:#fff;">Aceptar</button>
    </form>
  <?php endif; ?>

  <!-- Cancelar (pendiente o aprobado) -->
  <?php if (in_array($estado, ['pendiente','aprobado'])): ?>
    <form action="php/cancelar_reserva_adm.php" method="post" style="display:inline">
      <input type="hidden" name="id_reserva" value="<?= intval($r['id']) ?>">
      <input type="hidden" name="accion" value="cancelar">
      <button type="submit" onclick="return confirm('¿Cancelar reserva?')">Cancelar</button>
    </form>
  <?php endif; ?>

  <!-- Reactivar (solo cancelado) -->
  <?php if ($estado === 'cancelado'): ?>
    <form action="php/cancelar_reserva_adm.php" method="post" style="display:inline">
      <input type="hidden" name="id_reserva" value="<?= intval($r['id']) ?>">
      <input type="hidden" name="accion" value="reactivar">
      <button type="submit" onclick="return confirm('¿Reactivar reserva (volver a pendiente)?')">Reactivar</button>
    </form>
  <?php endif; ?>

  <!-- Eliminar (siempre visible) -->
  <form action="php/eliminar_reserva_adm.php" method="post" style="display:inline">
    <input type="hidden" name="id_reserva" value="<?= intval($r['id']) ?>">
    <button type="submit" style="background-color:red;color:#fff;"
            onclick="return confirm('¿Eliminar reserva definitivamente? Esta acción no se puede deshacer.')">
      Eliminar
    </button>
  </form>
</td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("open");
    }
  </script>

</body>
</html>
