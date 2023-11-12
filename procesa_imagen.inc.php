<?php
// Establecer el encabezado de respuesta como JSON
header("Content-Type: application/json");

// Verificar el token
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($token !== 'libros') {
    header("Location:" . dirname($_SERVER["PHP_SELF"]) . "/includes/404.inc.php");
    exit();
}

// Verificar si se recibió un archivo
if (!empty($_FILES) && isset($_FILES['Archivo']) && $_FILES['Archivo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['Archivo'];

    // Verificar la extensión del archivo si es necesario
    $allowedExtensions = ['webp', 'jpg', 'png'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['error' => 'Solo se aceptan imágenes con extensiones .jpg, .png, .webp']);
        exit;
    }

    // Mover el archivo a la ubicación deseada
    $route = "assets/images/libros/";

    // Generar un nombre único para el archivo
    $nombreArchivo = uniqid() . '.' . $fileExtension;
    $rutaCompleta = $route . $nombreArchivo;

    if (move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
        // Aquí puedes realizar otras acciones, como insertar en la base de datos
        echo json_encode(['success' => 'Archivo subido y procesado exitosamente!', 'nombreArchivo' => $rutaCompleta]);
    } else {
        echo json_encode(['error' => 'Lo siento, el archivo no se ha subido, por favor, inténtalo de nuevo.']);
    }
} else {
    echo json_encode(['error' => 'No se recibió ningún archivo o hubo un error en la subida.']);
}
?>