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

    <main class="container">
        <div class="registro-container">
            <h2>Formulario de Registro</h2>

            <!-- Mensaje dinámico -->
            <?php if (!empty($msg)): ?>
                <div id="alert-msg" class="alert <?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form action="php/registrar_clie.php" method="post">

                <div class="form-grid">

                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>

                    <div class="field">
                        <label for="apellido">Apellido</label>
                        <input type="text" name="apellido" id="apellido" required>
                    </div>

                    <div class="field">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" name="correo" id="correo" required>
                    </div>

                    <div class="field">
                        <label for="telefono">Número de teléfono</label>
                        <input type="text" name="telefono" id="telefono" required>
                    </div>

                    <div class="field">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" id="direccion" placeholder="Barrio / Calle / Número">
                    </div>

                    <div class="field">
                        <label for="N_documento">Número de documento</label>
                        <input type="text" name="N_documento" id="N_documento">
                    </div>

                    <div class="field">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena">
                    </div>

                    <div class="field">
                        <label for="confir_contrasena">Confirmar contraseña</label>
                        <input type="password" name="confir_contrasena" id="confir_contrasena">
                    </div>

                </div>

                <div class="acciones">
                    <button type="submit" class="btn">Registrar</button>
                    <a href="index.php" class="btn secondary">Cancelar</a>
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