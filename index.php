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

    // Si existe la variable $_GET['ruta'] y no está vacía
    if (isset($_GET['ruta']) && !empty($_GET['ruta'])) {
        // Si no existe la sesión 'user'
        if (!isset($_SESSION['user'])) {
            // Si existe la ruta en la tabla de rutas
            if (isset($URL_PUBLIC[$_GET['ruta']]))
                // Carga la página que corresponda
                include_once($URL_PUBLIC[$_GET['ruta']]);
            else
                // Carga la página de inicio
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=login');
        } else {
            // Si existe la ruta en la tabla de rutas
            if (isset($URL_USUARIOS[$_GET['ruta']])) {
                // Carga la página que corresponda
                include_once($URL_USUARIOS[$_GET['ruta']]);
            } else
                // Carga la página de inicio
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=home');
        }
    } else {
        // Si no existe ninguna variable GET
        if (!empty($_GET))
            // Carga la página de login 
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=login');
        else {
            // Si no existe la sesión 'user'
            if (!isset($_SESSION['user']))
                // Carga la página de login
                include_once('./includes/login.inc.php');
            else
                // Carga la página de inicio
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ruta=home');
        }
    }

    ?>
    <script src="./assets/js/title.js" type="module"></script>
    <script src="./assets/js/index.js" type="module"></script>
</body>

</html>