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

    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        die("No se recibió id del implemento.");
    }

    $sql = "SELECT * FROM implemento WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $implemento = $result->fetch_assoc();

    if (!$implemento) {
        die("⚠ implemeto no encontrado.");
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

            <form action="php/actualizar_implemento.php" method="post">

                <input type="hidden" name="id" value="<?php echo $implemento['id']; ?>">

                <div class="form-grid">

                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre"
                            value="<?php echo htmlspecialchars($implemento['nombre']); ?>" required maxlength="60">
                    </div>

                    <div class="form-grid">
                        <div class="field">
                            <label for="detalle">Detalle</label>
                            <input type="text" name="detalle" id="detalle"
                                value="<?php echo htmlspecialchars($implemento['detalle']); ?>" required maxlength="60">
                        </div>

                        <div class="form-grid">
                            <div class="field">
                                <label for="cantidad">Cantidad</label>
                                <input type="number" name="cantidad" id="cantidad"
                                    value="<?php echo htmlspecialchars($implemento['cantidad']); ?>" required>
                            </div>

                            <div class="acciones">
                                <button type="submit" class="btn">Actualizar</button>
                            </div>
                        </div>
                    </div>

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
