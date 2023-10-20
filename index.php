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
        if(isset($_GET['ruta']) && !empty($_GET['ruta'])){
            if($_GET['ruta'] == 'register'){
                include_once('./includes/register.inc.php');
            } elseif($_GET['ruta'] == 'login') {
                include_once('./includes/login.inc.php');
            }
        }
    ?>
    
    <?php
        include_once('./includes/footer.inc.php');
    ?>
</body>
</html>