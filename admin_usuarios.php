<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/mensaje.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
    session_start();
    require_once "barra.php" ;

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
        <div class="container header-inner">

            <h1 class="logo">Gestión de Usuarios</h1>
            <div class="acciones-header">
                <a href="index.php" class="btn">Inicio</a>
            </div>
        </div>
    </header>

    <main class="container admin-container">



        <section class="admin-content">

            <h2>Gestión de usuarios</h2>

            <h3>Listado de usuarios</h3>

            <div class="tabla-wrapper">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre usuario</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Número de documento</th>
                            <th>Rol</th>
                            <th>Fecha de creacion</th>
                            <th>Fecha de actualizacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $mysqli = new mysqli("localhost", "root", "", "salon_comunitario");
                        if ($mysqli->connect_errno) {
                            echo "<tr><td colspan='7'>Error al conectar: " . $mysqli->connect_error . "</td></tr>";
                        } else {
                            $sql = "SELECT nombre, apellido, email, telefono, direccion, documento_identidad, id_rol, creado_en, actualizado_en  
            FROM usuarios WHERE id_rol != 1";
                            $result = $mysqli->query($sql);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$row['nombre']}</td>";
                                    echo "<td>{$row['apellido']}";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['telefono']}</td>";
                                    echo "<td>{$row['direccion']}</td>";
                                    echo "<td>{$row['documento_identidad']}</td>";
                                    echo "<td>{$row['id_rol']}</td>";
                                    echo "<td>{$row['creado_en']}</td>";
                                    echo "<td>{$row['actualizado_en']}</td>";
                                    echo "<td class='acciones'>
                    <a href='editar_usuario.php?doc={$row['documento_identidad']}' class='btn btn-editar' title='Editar usuario'>
                        <i class='fa-solid fa-pen-to-square'></i>
                    </a>

                    <a href='php/eliminar_usuario.php?doc={$row['documento_identidad']}' 
                       class='btn btn-danger'
                       onclick='return confirm(\"¿Eliminar al usuario con documento {$row['documento_identidad']}?\")'
                       title='Eliminar usuario'>
                        <i class='fa-solid fa-trash'></i>
                    </a>
                </td>";
                                    echo "</tr>";
                                }

                            } else {
                                echo "<tr><td colspan='7'>No hay usuarios registrados</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <h3>Crear Nuevo Usuario</h3>
            <!-- registrar usuarios -->
            <?php if (!empty($msg)): ?>
                <div id="alert-msg" class="alert <?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>
            <form action="php/registrar_usuario.php" method="POST" class="formulario">

                <div class="campo">
                    <label for="nombre">Nombre Usuario:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="campo">
                    <label for="apellido">Apellido Usuario:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>

                <div class="campo">
                    <label for="correo">Correo electronico:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>

                <div class="campo">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required maxlength="9">
                </div>

                <div class="campo">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>

                <div class="campo">
                    <label for="N_documento">Número de documento:</label>
                    <input type="number" id="N_documento" name="N_documento" required>
                </div>

                <div class="campo">
                    <label for="rol_usu">Rol del usuario:</label>
                    <select id="rol_usu" name="rol_usu" required>
                        <option value="4">Invitado</option>
                        <option value="3">Residente</option>
                        <option value="2">Coordinador</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <div class="campo">
                    <label for="con_contrasena">Confirmar Contraseña:</label>
                    <input type="password" id="con_contrasena" name="con_contrasena" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>

            </form>


        </section>

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
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }
    </script>
</body>
</html>
