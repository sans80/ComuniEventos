<?php
$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");

if (isset($_GET['id'])) {
    $documento = $_GET['id'];
    $sql = "DELETE FROM implemento WHERE id = '$documento'";

    if ($mysqli->query($sql)) {
        header("Location: ../admin_implementos.php?msg=Eliminado correctamente");
        exit();
    } else {
        echo "Error al eliminar: " . $mysqli->error;
    }
} else {
    echo "id no especificado.";
}

