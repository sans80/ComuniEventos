<?php
require_once __DIR__ . '/php/config.php';
require_login();
$user_id = intval($_SESSION['user_id']);

$stmt = $mysqli->prepare("
  SELECT r.*, s.nombre AS salon_nombre
  FROM reservas r
  JOIN salon s ON s.id = r.id_salon
  WHERE r.id_usuario = ?
  ORDER BY r.fecha_evento DESC, r.hora_inicio DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Mis reservas</title>
  <link rel="stylesheet" href="assets/css/estilos.css">
  <link rel="stylesheet" href="assets/css/admin.css">
  <link rel="stylesheet" href="assets/css/formularios.css">
</head>

<body>
  <?php require_once "barra_clientes.php"; ?>
  <header>
    <div class="container header-inner">

      <h1 class="logo">Gestión de Usuarios</h1>
      <div class="acciones-header">
        <a href="index.php" class="btn">Inicio</a>
      </div>
    </div>
  </header>
  <main class="container">
    <h2>Mis reservas</h2>
    <?php if (!empty($_GET['msg'])): ?>
      <p class="mensaje"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
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
            <td><?= htmlspecialchars($r['salon_nombre']) ?></td>
            <td><?= htmlspecialchars($r['fecha_evento']) ?></td>
            <td><?= htmlspecialchars($r['hora_inicio']) ?></td>
            <td><?= htmlspecialchars($r['hora_fin']) ?></td>
            <td><?= htmlspecialchars($r['estado']) ?></td>
            <td>
              <?php if (in_array($r['estado'], ['pendiente', 'aprobado'])): ?>
                <form action="php/cancelar_reserva.php" method="post" style="display:inline">
                  <input type="hidden" name="id_reserva" value="<?= intval($r['id']) ?>">
                  <button type="submit" onclick="return confirm('¿Cancelar reserva?')">Cancelar</button>
                </form>
              <?php else: ?>
                -
              <?php endif; ?>
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
