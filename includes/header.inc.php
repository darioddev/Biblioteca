<?php
    //Almaceno en una variable SOLAMENTE el nombre del fichero es decir localhost/Biblioteca/index.php?ruta=ejemplo
    //Almacenara en la variable index.php?ruta=ejemplo.
    $nomre_url = basename($_SERVER['REQUEST_URI']);
    //Almaceno las rutas en un array las rutas que quiero que solo muestre el header , 
    //En este caso son el principales.
    $url_header_principal=['index.php','index.php?ruta=login','index.php?ruta=register'];
    if(in_array($nomre_url,$url_header_principal)){
?>
<header class="site-header">
        <div class="header-content">
            <img src="./assets/images/logolibros.png" alt="Libros" class="logo">
            <h1 class="site-title">
            <?php
                if(isset($_GET['ruta'])) {
                    if($_GET['ruta'] == 'register') {
            ?>
                REGISTRO
            <?php
                   }elseif($_GET['ruta'] == 'login') {
            ?>
                INICIO DE SESION
            <?php
                    }
                }else {
            ?>
                MUNDO LIBROS
            <?php
                }
            ?>
             </h1>
        </div>
        <div class="line"></div>
        <?php if(isset($_GET['ruta']) && $_GET['ruta'] == 'register'){?>
        <div>
            <p class="paragraph-header">¡Bienvenido al Mundo de Libros! Regístrate para acceder a nuestra amplia colección de libros y recursos.</p>
        </div>
        <?php
        }
        ?>
</header>
<?php
    }
?>