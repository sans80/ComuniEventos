<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar sesión - Gestión de eventos</title>
    <link rel="stylesheet" href="assets/css/inicio_sesion.css">
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
                <h1>Salón Comunal</h1>
                <p class="lead">Gestión de eventos</p>
            </div>
            <nav>
                <a href="index.php">Inicio</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <section class="login-wrapper">
            <div class="login-card">

                <h2 class="form-title">Inicia sesión</h2>
                <p class="form-sub">Accede a la administración del salón comunal.</p>
                <?php if (!empty($msg)): ?>
                <div id="alert-msg" class="alert <?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

                <form action="php/validacion.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Corre del usuario</label>
                        <input id="nombre" name="nombre" type="email"required>
                    </div>

                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input id="contrasena" name="contrasena" type="password" required>
                    </div>



                    <button class="btn-primary" type="submit">Entrar</button>
                </form>

                <p style="margin-top:12px; color:var(--muted); font-size:14px;" a>
                    ¿No tienes cuenta?
                    <a href="registro.php" class="small-link">Regístrate</a>
                </p>

            </div>
        </section>

        <footer>
            <p>© Salón Comunal • Todos los derechos reservados</p>
        </footer>

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
