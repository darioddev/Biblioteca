<?php
    //Array con rutas que puede hacer cualquier usuario
    $URL_PUBLIC = [
        'register' =>  './includes/register.inc.php',
        'login' =>  './includes/login.inc.php',
    ];
    //Array que podra acceder el usuario administrador y lector
    // En rutas de administracion dentro habra logica para 
    // verificar si el usuario es administrador o lector
    // si no es administrador se redirigira a 404
    $URL_USUARIOS = [
        'home' => './includes/home.inc.php',
        'usuarios' => './includes/usuarios.inc.php',
        'libros' => './includes/libros.inc.php',
        'autores' => './includes/autores.inc.php',
        'editoriales' => './includes/editoriales.inc.php',
        'logout' => './includes/logout.inc.php',
        'prestamos' => './includes/prestamos.inc.php',
        '404' => './includes/404.inc.php',
        'configuracion' => './includes/configuracion.inc.php',
        'CambioContrasena' => './includes/cambiarClave.inc.php',
        'borrar' => './includes/borrarCuenta.inc.php',
    ]
?>