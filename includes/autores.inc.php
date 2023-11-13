<?php
$initalize = initializeRow("rowAutor");
define('PARAMETROS', "ID, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO");
$pages = pages($_GET, $initalize, 'Autores');



initializeSession('columnaAutor', 'ordenacionAutor', 'rowAutor', 'pageAutor', 'nombre', 'ASC', $initalize, 1);

if(isset($_SESSION['rowAutor']) && empty($_SESSION['rowAutor'])) {
    $_SESSION['rowAutor'] = 5 ;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    // Realizar la eliminación del usuario
    sql_update_estado('Autores', $_GET["remove"]);

    $redireccion = "?ruta=autores";

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
    $_SESSION["pageAutor"] = $_GET["page"];
}

if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    //$sentencia = "ID_AUTOR, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO";
    $usuarios = sql_search(PARAMETROS, 'Autores', $_GET['type'], '%' . $_GET['search'] . '%');
    $pages = pages($_GET, $initalize, (int) count($usuarios));

    $usuarios = sql_search(PARAMETROS, 'Autores', $_GET['type'], '%' . $_GET['search'] . '%', $pages[0], $initalize);

    $route = "?ruta=autores&row=" . $_SESSION['rowAutor'] . "&search=" . $_GET['search'] . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['rowAutor'] = $_GET['row'];
    $_SESSION['columnaAutor'] = $_GET['column'];
    $_SESSION['ordenacionAutor'] = $_GET['order'];
    $usuarios = sql_get_all(PARAMETROS, 'Autores', $pages[0], $initalize, $_GET["column"], $_GET["order"]);

    $route = "?ruta=autores&row=" . $_SESSION['rowAutor'] . "&column=" . $_SESSION['columnaAutor'] . "&order=" . $_SESSION['ordenacionAutor'] . "";

} else {
    header("Location: ?ruta=autores&row=" . $_SESSION['rowAutor'] . "&column=" . $_SESSION['columnaAutor'] . "&order=" . $_SESSION['ordenacionAutor'] . "&page=" . $_SESSION["pageAutor"]);
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
        PANEL DE AUTORES
    </div>
    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>

        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $_SESSION['rowAutor'] ?>" min="0" max="20">
                </span>
            </div>
            <div class="table-options-search">
                <?php
                if (!isset($_GET['search']) && !isset($_GET['type'])) {
                    optionOrdenacion("table-options-order one", "ordenarPor", "AutorOrdenacion", $_SESSION, 'columnaAutor', 'ordenacionAutor', 'autores');
                    ?>
                </div>
                <?php
                }
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
            iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ','autores')
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
        $heads = ['ID', 'NOMBRE', 'APELLIDO', 'FECHA NACIMIENTO', 'FECHA CREACION', 'FECHA_MODIFICACION', 'ESTADO', 'ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?token=libros&autor=", "fas fa-user-cog", "modificado","autores"),
            array("option-link alt", "?ruta=autores&remove=", "fas fa-trash-alt", "borrado","autor","ID_Autor","verificaEstado"),
            array("option-link check", "?ruta=autores&remove=", "fas fa-user-check", "reactivar","")
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