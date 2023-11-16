<?php
$initalize = initializeRow("rowLibros");
$pages = pages($_GET, $initalize, 'Libros');


initializeSession('columnaLibros', 'ordenacionLibros', 'rowLibros', 'pageLibros', 'titulo', 'ASC', $initalize, 1);

if (isset($_SESSION['rowLibros']) && empty($_SESSION['rowLibros'])) {
    $_SESSION['rowLibros'] = 5;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    // Realizar la eliminación del usuario
    $value = sql_update_estado('Libros', $_GET["remove"]);
    $redireccion = "?ruta=libros";

    if (isset($_GET["search"]) && isset($_GET["type"])) {
        $redireccion .= "&search=" . $_GET["search"] . "&type=" . $_GET["type"] . "";
        if (isset($_GET["page"]) && !empty(trim($_GET["page"]))) {
            $redireccion .= "&page=" . $_GET["page"];
        }
    }

    header("Location: $redireccion");
    die();
}


$usuarios = [];

if (isset($_GET["page"])) {
    $_SESSION["pageLibros"] = $_GET["page"];
}

if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    $usuarios = sql_get_all_libros(null, null, $_GET['type'], $_GET['search'], null, null);
    $pages = pages($_GET, $initalize, (int) count($usuarios));
    $usuarios = sql_get_all_libros($pages[0], $initalize, $_GET['type'], trim($_GET['search']), null, null);

    $route = "?ruta=libros&row=" . $_SESSION['rowLibros'] . "&search=" . trim($_GET['search']) . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['rowLibros'] = $_GET['row'];
    $_SESSION['columnaLibros'] = $_GET['column'];
    $_SESSION['ordenacionLibros'] = $_GET['order'];
    $usuarios = sql_get_all_libros($pages[0], $initalize, null, null, $_GET["column"], $_GET["order"]);

    $route = "?ruta=libros&row=" . $_SESSION['rowLibros'] . "&column=" . $_SESSION['columnaLibros'] . "&order=" . $_SESSION['ordenacionLibros'] . "";

} else {
    header("Location: ?ruta=libros&row=" . $_SESSION['rowLibros'] . "&column=" . $_SESSION['columnaLibros'] . "&order=" . $_SESSION['ordenacionLibros'] . "&page=" . $_SESSION["pageLibros"]);
    die();
}

if (empty($usuarios)) {
    $pages[1] = 1;
}

if (count($usuarios) < $initalize) {
    $initalize = MAX_FILAS;
}

?>

<link rel="stylesheet" href="./assets/css/usuarios.css">

<?php require_once("./includes/nav.inc.php"); ?>

<section class="home">
    <div class="text">
        PANEL DE LIBROS
        <?php

        ?>
    </div>
    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>

        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $_SESSION['rowLibros'] ?>" min="0"
                        max="20">
                </span>
            </div>
            <div class="table-options-search">
                <?php
                if (!isset($_GET['search']) && !isset($_GET['type'])) {
                    optionOrdenacion("table-options-order one", "ordenarPor", "LibrosOrdenacion", $_SESSION, 'columnaLibros', 'ordenacionLibros', 'libros');
                    ?>
                </div>
                <?php
                }
                optionOrdenacion("table-options-order", "buscarPor", "LibrosBuscador");
                formSearch($_SERVER["PHP_SELF"] . "?ruta=libros&search=", "busqueda");
                ?>
        </div>
        </div>

        <div class="inline">
            <?php
            paginaLinks('pagination', $pages[1], $route);
            ?>
            <?php
            if(isset($_SESSION['rol']) && $_SESSION['rol'] == "ADMIN") {
                iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ', 'libros');

            }
                ?>
        </div>
        <?php
        ?>
        </div>
        <?php
    }
    ?>

    <?php
    if (isset($usuarios) && empty($usuarios)) {
        require_once("./includes/nofound.php");
    } else {
        $heads = ['ID', 'IMAGEN', 'TITULO', 'NOMBRE AUTOR', 'NOMBRE EDITORIAL', 'FECHA CREACION', 'ESTADO', 'ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?token=libros&libro=", "fas fa-user-cog", "modificado", "libros"),
            array("option-link alt", "?ruta=libros&remove=", "fas fa-trash-alt", "borrado", "libro", "", ""),
            array("option-link check", "?ruta=libros&remove=", "fas fa-user-check", "reactivar", "", "verificaReactivacion"),
            array("option-link cog", "", "fas fa-cloud-upload-alt" , "cambiarImagen"),
            array("option-link cog", "", "fas fa-cloud-upload-alt" , "reservarLibro"),
        );

        if(isset($_SESSION['rol']) && $_SESSION['rol'] == "LECTOR") {
            $heads = ['IMAGEN', 'TITULO', 'NOMBRE AUTOR', 'NOMBRE EDITORIAL', 'RESERVAR'];
        }

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