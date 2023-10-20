<?php
    session_start();

    //Almaceno mediante un array asociativo como clave las rutas y como valor la ruta que utilisamos como valor del GET
    $url_todos = [
        './includes/register.inc.php' => 'register',
        './includes/login.inc.php'    => 'login',
    ];

    $url_usuarios = [
        './includes/home.inc.php'     =>  'home',
        './includes/logout.inc.php'   =>  'logout'
    ]

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Registro - Mundo Libros</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="./assets/images/logolibros.png" type="image/x-icon">
</head>
<body>
    <?php
        include_once('./includes/header.inc.php');
    ?>
    <?php
        //Verificamos si ahi un metodo GET con la palabra clave "ruta" y que no este vacia.
        if(isset($_GET['ruta']) && !empty($_GET['ruta'])){
            //
            $url_get = array_search($_GET['ruta'],$url_todos);
            if($url_get){
                if(!isset($_SESSION['user'])) include_once($url_get);
                else header('Location:'.$_SERVER['PHP_SELF'].'?ruta=home');
            }else {
               $url_get = array_search($_GET['ruta'],$url_usuarios);
               if($url_get && isset($_SESSION['user'])) include_once($url_get);
               else header('Location:'.$_SERVER['PHP_SELF'].'?ruta=login');
            }
        }
    
    ?>
    
    <?php
        include_once('./includes/footer.inc.php');
    ?>


<?php
            /*
            //Rutas que cualquier usuario puede visionar
            if($_GET['ruta'] == 'register'){
                if(!isset($_SESSION['user'])) include_once('./includes/register.inc.php');
                else header('Location:'.$_SERVER['PHP_SELF'].'?ruta=home');

            } elseif($_GET['ruta'] == 'login') {
                if(!isset($_SESSION['user'])) include_once('./includes/login.inc.php');
                else header('Location:'.$_SERVER['PHP_SELF'].'?ruta=home');
            
            }elseif($_GET['ruta'] == 'home') {
                if(isset($_SESSION['user'])) include_once('./includes/home.inc.php');
                else header('Location:'.$_SERVER['PHP_SELF'].'?ruta=login');

            }elseif ($_GET['ruta'] == 'logout') {
                include_once('./includes/logout.inc.php');*/
?>
</body>
</html>