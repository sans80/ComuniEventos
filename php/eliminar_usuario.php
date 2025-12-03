<?php
$mysqli = new mysqli("localhost", "root", "", "salon_comunitario");

if (isset($_GET['doc'])) {
    $documento = $_GET['doc'];
    $sql = "DELETE FROM usuarios WHERE documento_identidad = '$documento'";

    if ($mysqli->query($sql)) {
        header("Location: ../admin_usuarios.php?msg=Eliminado correctamente");
        exit();
    } else {
        echo "Error al eliminar: " . $mysqli->error;
    }
} else {
    echo "Documento no especificado.";
}

