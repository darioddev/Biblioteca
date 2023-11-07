<?php
//Almaceno en una variable SOLAMENTE el nombre del fichero es decir localhost/Biblioteca/index.php?ruta=ejemplo
//Almacenara en la variable index.php?ruta=ejemplo.
$nomre_url = basename($_SERVER['REQUEST_URI']);
//Almaceno las rutas en un array las rutas que quiero que solo muestre el header , 
//En este caso son el principales.
$url_header_principal = ['Biblioteca', 'index.php', '?ruta=login', '?ruta=register', 'index.php?ruta=login', 'index.php?ruta=register'];
?>
<header class="site-header">
    <div class="header-content">
        <img src="./assets/images/logolibros.png" alt="Libros" class="logo" id="logo">
        <h1 class="site-title">
            <?php
            if (isset($_GET['ruta'])) {
                if ($_GET['ruta'] == 'register') {
                    ?>
                    REGISTRO -
                    <?php
                } elseif ($_GET['ruta'] == 'login') {
                    ?>
                    INICIO DE SESION -
                    <?php
                }
            }
            ?>
            MUNDO LIBROS
            <?php
            ?>
    <div class="line"></div>
    <?php if (isset($_GET['ruta']) && $_GET['ruta'] == 'register') { ?>
        <div>
            <p class="paragraph-header">¡Bienvenido al Mundo de Libros! Regístrate para acceder a nuestra amplia colección
                de libros y recursos.</p>
        </div>
        <?php
    }
    ?>
</header>