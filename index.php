<?php
    /* Creamos una session */
    session_start();
    /* Llamadas a archivos que necesitaremos en el código*/
    require("./lib/index.php");
    require("./config/db.config.php");
    /* Se crea un array vacio para el control de errores este array sera un array asociativo */
    $errors = [] ;
    //Se comprueba si el formulario ha sido enviado
    if(isset($_POST["send"])) {
        //Comprobamos si existe la variable y no esta vacia mediante una funcion creada.
        if(checkExistenciaVar($_POST["user"])) $usuario = $_POST["user"];
        //Si la variable no esta definida almacenamos en un array el error y mensaje correspondiente.
        else $errors["user"] = "El campo de usuario es obligatorio."; 

        if(checkExistenciaVar($_POST["password"])) $password = $_POST["password"];
        else $errors["password"] = "El campo de contraseña es obligatorio."; 

        //Comprobamos si no ahi errores , si no ahi errores esque los parametros introducidos
        //Son validos.
        if(empty($errors)) {
            //Comprobaremos si el usuario existe y si la contraseña es correcta
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap">
    <link rel="stylesheet" href="./assets/css/index.css">
</head>
<body>
    <?php
        include_once("./includes/header.inc.php");
        include_once("./includes/nav.inc.php");
        include_once("./includes/login.inc.php");
    ?>
</body>
</html>