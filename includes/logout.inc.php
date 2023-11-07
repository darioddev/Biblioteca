<?php
//eliminamos las variables de sesión
session_unset();
//eliminamos la sesión
session_destroy();

//Antes de cerrar session ponemos un tiempo de 2 segundos
//Esto es simplemente por que en al cerrar session poder visionar un mensaje
//De que la session ha sido cerrada y no se cirre de golpe.
//sleep(2);
//redireccionamos a index
header("Location:" . $_SERVER["PHP_SELF"] . "");
?>