<link rel="stylesheet" href="./assets/css/nav.css">
<nav class="sidebar close">
    <header>
        <div class="text logo">
            <span class="name">
                <?php echo $_SESSION['user'] ?>
            </span>
            <span class="rol">
                <?php echo $_SESSION['rol'] ; 
                ?>
            </span>
        </div>
        <i class="bx bx-menu toogle"></i>
    </header>

    <div class="menu-bar">
        <div class="menu">
            <li class="search-box" style="display:none">
                <i class="bx bx-search icon"></i>
                <input type="text" name="" id="" placeholder="Buscar..." />
            </li>

            <ul class="menu-links">
                <li class="nav-link">
                    <a href="?ruta=home">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="text nav-text">Home</span>
                    </a>
                </li>
                <?php
                if (isset($_SESSION['rol']) && $_SESSION['rol'] != "LECTOR") {

                    ?>
                    <li class="nav-link">
                        <a href="?ruta=usuarios">
                            <i class="bx bx-user icon"></i>
                            <span class="text nav-text">Usuarios</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="?ruta=autores">
                            <i class="fas fa-user-secret icon"></i>
                            <span class="text nav-text">Autores</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="?ruta=editoriales">
                            <i class="bx bx-bookmark-alt-minus icon"></i>
                            <span class="text nav-text">Editoriales</span>
                        </a>
                    </li>

                    <?php
                }
                ?>

                <li class="nav-link">
                    <a href="?ruta=libros">
                        <i class="bx bx-book-open icon"></i>
                        <span class="text nav-text">Libros</span>
                    </a>
                </li>


                <li class="nav-link">
                    <a href="?ruta=prestamos">
                        <i class="bx bx-user-pin icon"></i>
                        <span class="text nav-text">Prestamos</span>
                    </a>
                </li>

            </ul>
        </div>
        <div class="bottom-content">
        <li class="class">
                <a href="?ruta=configuracion" id="session">
                    <i class="bx bx-cog icon"></i>
                    <span class="text nav-text">Configuracion</span>
                </a>
            </li>

            <li class="class">
                <a href="?ruta=logout" id="session">
                    <i class="bx bx-log-out icon"></i>
                    <span class="text nav-text">Cerrar Sesión</span>
                </a>
            </li>
            <li class="mode">
                <div class="sun-moon">
                    <i class="bx bx-moon icon moon"></i>
                    <i class="bx bx-sun icon sun"></i>
                </div>
                <span class="mode-text text">Modo Oscuro</span>
                <div class="toogle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>