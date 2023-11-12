<?php
session_start();

require_once('./config/rutas.config.inc.php');
require_once('./lib/verificacion_datos.inc.php');
require_once('./includes/pagination.inc.php');
require_once('./includes/optionSearch.inc.php');
require_once('./lib/sessions.inc.php');
require_once('./includes/icons.inc.php');
require_once('./includes/tablas.inc.php');
require_once('./lib/database.inc.php');
?>

<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Mundo Libros</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="shortcut icon" href="./assets/images/logolibros.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (isset($_GET['ruta']) && !empty($_GET['ruta'])) {
        if (!isset($_SESSION['user'])) {
            if (isset($URL_PUBLIC[$_GET['ruta']]))
                include_once($URL_PUBLIC[$_GET['ruta']]);
            else
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=login');
        } else {
            if (isset($URL_USUARIOS[$_GET['ruta']])) {
                include_once($URL_USUARIOS[$_GET['ruta']]);
            } else
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=home');
        }
    } else {
        if (!empty($_GET))
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=login');
        else {
            if (!isset($_SESSION['user']))
                include_once('./includes/login.inc.php');
            else
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=home');
        }
    }

    ?>
    <script src="./assets/js/title.js" type="module"></script>
    <script src="./assets/js/index.js" type="module"></script>
</body>

</html>