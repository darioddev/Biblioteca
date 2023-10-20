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