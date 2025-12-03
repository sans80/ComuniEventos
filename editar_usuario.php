<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="assets/css/registro.css">
    <link rel="stylesheet" href="assets/css/mensaje.css">
</head>

<body>

    <?php
    session_start();

    $msg = '';
    $type = '';

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        $type = (strpos($msg, 'exitosamente') !== false) ? 'success' : 'error';
        unset($_SESSION['error']);
    } elseif (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        $type = 'success';
        unset($_SESSION['success']);
    }
    ?>

    <header>
        <div class="container inner">
            <div>
                <h1>Registro de Usuario</h1>
                <p class="lead">Crea tu cuenta para aparecer como contacto del salón.</p>
            </div>
            <nav aria-label="principal">
                <a href="index.php">Inicio</a>
                <a href="inicio_sesion.php">Iniciar sesión</a>
            </nav>
        </div>
    </header>

    <?php
    $mysqli = new mysqli("localhost", "root", "", "salon_comunitario");
    if ($mysqli->connect_errno) {
        echo "Error al conectar: " . $mysqli->connect_error;
        exit;
    }

    $documento = $_GET['doc'] ?? '';

    if (empty($documento)) {
        die("No se recibió documento del usuario.");
    }

    $sql = "SELECT * FROM usuarios WHERE documento_identidad = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        die("⚠ usuario no encontrado.");
    }
    ?>

    <main class="container">
        <div class="registro-container">
            <h2>Formulario de Registro</h2>

            <!-- Mensaje dinámico -->
            <?php if (!empty($msg)): ?>
                <div id="alert-msg" class="alert <?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form action="php/actualizar_usuario.php" method="post">

                <input type="hidden" name="documento" value="<?php echo $usuario['documento_identidad']; ?>">

                <div class="form-grid">
                    <div class="field">
                        <label>Nombre</label>
                        <input type="text" name="nombre" required
                        value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                       required maxlength="60">
                    </div>

                    <div class="field">
                        <label>Apellido</label>
                        <input type="text" name="apellido" required
                        value="<?php echo htmlspecialchars($usuario['apellido']); ?>"
                       required maxlength="60">
                    </div>

                    <div class="field">
                        <label>Correo electrónico</label>
                        <input type="email" name="correo" required
                        value="<?php echo htmlspecialchars($usuario['email']); ?>"
                       required maxlength="60">
                    </div>

                    <div class="field">
                        <label>Número de teléfono</label>
                        <input type="text" name="telefono" required
                        value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                    </div>

                    <div class="field">
                        <label>Dirección</label>
                        <input type="text" name="direccion" placeholder="Barrio / Calle / Número"
                        value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
                    </div>

                    <div class="field">
                    <label>Rol del usuario:</label>
                    <select name="rol_usu" required>
                        <option value="4">Invitado</option>
                        <option value="3">Residente</option>
                        <option value="2">Coordinador</option>
                    </select>
                </div>


                    <div class="field">
                        <label>Contraseña</label>
                        <input type="password" name="contrasena" placeholder="se deja en blanco si no se cambia">
                    </div>

                    <div class="field">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="con_contrasena" placeholder="se deja en blanco si no se cambia">
                    </div>
                </div>

                <div class="acciones">
                    <button type="submit" class="btn">Registrar</button>
                </div>

            </form>
        </div>
    </main>

    <script>
        const alerta = document.getElementById('alert-msg');
        if (alerta) {
            setTimeout(() => {
                alerta.classList.add('fade-out');
                setTimeout(() => alerta.remove(), 600);
            }, 5000);
        }
    </script>

</body>

</html>
