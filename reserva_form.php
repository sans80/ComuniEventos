<?php
require_once __DIR__ . '/php/config.php';
require_login();

$salones = $mysqli->query("SELECT id, nombre FROM salon ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$implementos = $mysqli->query("SELECT id, nombre, cantidad FROM implemento ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reservar salón</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="assets/css/formularios.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>
    <?php require_once("barra_clientes.php"); ?>
<header>
    <div class="container header-inner">

      <h1 class="logo">Reservar salón</h1>
      <div class="acciones-header">
      </div>
    </div>
  </header>
    <main class="container">
        <section class="registro-container" aria-labelledby="titulo-reserva">
            <h2 id="titulo-reserva">Reservar salón</h2>

            <div class="separator" role="separator" aria-hidden="true"></div>

            <form action="php/procesar_reserva.php" method="post" enctype="multipart/form-data" id="formReserva" class="form-grid" novalidate>
                <!-- Salón -->
                <div class="field">
                    <label for="id_salon">
                        <span class="label-icon" aria-hidden="true">🏛️</span>
                        Salón:
                    </label>
                    <select name="id_salon" id="id_salon" required>
                        <option value="" disabled selected>Seleccione un salón</option>
                        <?php foreach ($salones as $s): ?>
                            <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="field">
                    <label for="fecha_evento">
                        <span class="label-icon" aria-hidden="true">📅</span>
                        Fecha del evento:
                    </label>
                    <input type="date" name="fecha_evento" id="fecha_evento" required>
                </div>

                <!-- Hora inicio -->
                <div class="field">
                    <label for="hora_inicio">
                        <span class="label-icon" aria-hidden="true">⏱️</span>
                        Hora inicio:
                    </label>
                    <input type="time" name="hora_inicio" id="hora_inicio" required>
                </div>

                <!-- Hora fin -->
                <div class="field">
                    <label for="hora_fin">
                        <span class="label-icon" aria-hidden="true">⏱️</span>
                        Hora fin:
                    </label>
                    <input type="time" name="hora_fin" id="hora_fin" required>
                </div>

                <!-- Motivo -->
                <div class="field">
                    <label for="motivo_evento">
                        <span class="label-icon" aria-hidden="true">✏️</span>
                        Motivo / descripción:
                    </label>
                    <textarea name="motivo_evento" id="motivo_evento" rows="3" placeholder="Agrega detalles del evento (opcional)"></textarea>
                </div>

                <!-- Implementos -->
                <div class="field">
                    <label>
                        <span class="label-icon" aria-hidden="true">📦</span>
                        Implementos (indica cantidad)
                    </label>

                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <?php foreach ($implementos as $imp): ?>
                            <div class="imp-line" style="display:flex; align-items:center; gap:10px;">
                                <label for="imp-<?= intval($imp['id']) ?>" style="flex:1;">
                                    <?= htmlspecialchars($imp['nombre']) ?> (disponibles: <?= intval($imp['cantidad']) ?>)
                                </label>
                                <input type="number"
                                       id="imp-<?= intval($imp['id']) ?>"
                                       name="implementos[<?= $imp['id'] ?>]"
                                       min="0"
                                       max="<?= intval($imp['cantidad']) ?>"
                                       value="0"
                                       style="width:90px;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Valor a pagar -->
                <div class="field">
                    <label for="valor_pago">
                        <span class="label-icon" aria-hidden="true">💳</span>
                        Valor a pagar (opcional):
                    </label>
                    <input type="number" step="0.01" name="valor_pago" id="valor_pago" placeholder="0.00">
                </div>

                <!-- Comprobante -->
                <div class="field">
                    <label for="comprobante_pago">
                        <span class="label-icon" aria-hidden="true">📎</span>
                        Comprobante de pago (jpg/png/pdf) opcional:
                    </label>
                    <input type="file" name="comprobante_pago" id="comprobante_pago" accept=".jpg,.jpeg,.png,.pdf">
                    <div class="hint">Tamaño máximo sugerido: 5 MB. Formatos permitidos: JPG, PNG, PDF.</div>
                </div>

                <!-- Acciones -->
                <div class="acciones">
                    <button type="submit" class="btn primary">Enviar reserva</button>
                </div>

                <p class="hint" style="grid-column: 1 / -1;">
                    Al enviar la reserva aceptas las condiciones de uso del salón y la veracidad de la información proporcionada.
                </p>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const form = document.getElementById('formReserva');

            form.addEventListener('submit', function (e) {
                const hi = document.getElementById('hora_inicio').value;
                const hf = document.getElementById('hora_fin').value;
                if (hi && hf && hi >= hf) {
                    e.preventDefault();
                    alert('La hora de fin debe ser posterior a la hora de inicio.');
                    return;
                }

                // Validación mínima: salón y fecha
                const salon = document.getElementById('id_salon').value;
                const fecha = document.getElementById('fecha_evento').value;
                if (!salon || !fecha) {
                    e.preventDefault();
                    alert('Por favor selecciona salón y fecha del evento.');
                    return;
                }                
            });

            const hiInput = document.getElementById('hora_inicio');
            const hfInput = document.getElementById('hora_fin');

            function syncMin() {
                if (hiInput.value) {
                    hfInput.min = hiInput.value;
                } else {
                    hfInput.removeAttribute('min');
                }
            }
            hiInput.addEventListener('change', syncMin);
            syncMin();
        })();

        function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("open");
    }
    </script>
    
</body>

</html>
