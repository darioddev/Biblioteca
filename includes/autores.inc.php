<?php
$initalize = initializeRow("row");

$pages = pages($_GET, $initalize, 'Autores');

initializeSession('columnaAutor', 'ordenacionAutor', 'rowAutor', 'pageAutor', 'nombre', 'ASC', $initalize, 1);

$route = '';

$usuarios = [];
?>

<link rel="stylesheet" href="./assets/css/usuarios.css">

<?php require_once("./includes/nav.inc.php"); ?>

<section class="home">
    <div class="text">
        PANEL DE AUTORES
    </div>

    <div class="table-options">
        <div class="table-options-row">
            <span>
                <input type="number" name="rows" id="rows" value="<?php echo $initalize ?>" min="0" max="20">

            </span>
        </div>
        <div class="table-options-search">
            <?php
            optionOrdenacion("table-options-order one", "ordenarPor", "AutorOrdenacion", $_SESSION, 'columnaAutor', 'ordenacionAutor');
            ?>
        </div>
        <?php
        optionOrdenacion("table-options-order", "buscarPor", "AutorBuscador");
        formSearch($_SERVER["PHP_SELF"] . "?ruta=autores&search=", "busqueda");
        ?>
    </div>
    </div>

    <div class="inline">
        <?php
        paginaLinks('pagination', $pages[1], $route);
        ?>
        <?php
        iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ')
            ?>
    </div>
    <?php
    ?>
    </div>

    <?php
    if (isset($usuarios) && empty($usuarios)) {
        require_once("./includes/nofound.php");
    } else {
        $heads = ['ID', 'NOMBRE', 'NOMBRE USUARIO', 'CORREO ELECTRONICO', 'FECHA REGISTRO', 'ROL', 'ESTADO', 'ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?user=", "fas fa-user-cog", "modificado"),
            array("option-link alt", "?ruta=usuarios&remove=", "fas fa-trash-alt", "borrado"),
            array("option-link check", "?ruta=usuarios&remove=", "fas fa-user-check", "reactivar")
        );

        tableAdd("table", $heads, $usuarios, $icons);
    }
    ?>

    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        paginaLinks('pagination', $pages[1], $route);
    }

    ?>
        </div>
</section>

    