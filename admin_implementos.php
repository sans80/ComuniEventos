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
    require_once "barra.php";

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

            <h1 class="logo">Gestión de implementos</h1>
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
                            <th>Id</th>
                            <th>Nombre usuario</th>
                            <th>Detalles</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $mysqli = new mysqli("localhost", "root", "", "salon_comunitario");

                        if ($mysqli->connect_errno) {
                            echo "<tr><td colspan='7'>Error al conectar: " . $mysqli->connect_error . "</td></tr>";
                        } else {

                            $sql = "SELECT id, nombre, detalle, cantidad FROM implemento";

                            $result = $mysqli->query($sql);

                            if ($result && $result->num_rows > 0) {

                                while ($row = $result->fetch_assoc()) {

                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['nombre']}</td>";
                                    echo "<td>{$row['detalle']}";
                                    echo "<td>{$row['cantidad']}";
                                    echo "<td class='acciones'>
                    <a href='editar_implemento.php?id={$row['id']}' class='btn btn-editar' title='Editar implemento'>
                        <i class='fa-solid fa-pen-to-square'></i>
                    </a>

                    <a href='php/eliminar_implemento.php?id={$row['id']}' 
                       class='btn btn-danger'
                       onclick='return confirm(\"¿Eliminar al implemeto con el id {$row['id']}?\")'
                       title='Eliminar usuario'>
                        <i class='fa-solid fa-trash'></i>
                    </a>
                </td>";
                                    echo "</tr>";
                                }

                            } else {
                                echo "<tr><td colspan='7'>No hay implementos registrados</td></tr>";
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
            <form action="php/registrar_implemento.php" method="POST" class="formulario">

                <div class="campo">
                    <label for="nombre">Nombre implemento:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="campo">
                    <label for="detalle">Detalle implemento:</label>
                    <input type="text" id="detalle" name="detalle" required>
                </div>

                <div class="campo">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required>
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
            }, 5000); // 5 segundos
        }
    </script>
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }
    </script>

</body>

</html>