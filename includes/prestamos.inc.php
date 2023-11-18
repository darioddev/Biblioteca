<?php
$initalize = initializeRow("rowPrestamos");
define('PARAMETROS', "ID, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO");
$pages = pages($_GET, $initalize, 'Prestamos');



initializeSession('columnaPrestamos', 'ordenacionPrestamos', 'rowPrestamos', 'pagePrestamos', 'NombreUsuario', 'ASC', $initalize, 1);

if (isset($_SESSION['rowPrestamos']) && empty($_SESSION['rowPrestamos'])) {
    $_SESSION['rowPrestamos'] = 5;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    sql_update_estado('Prestamos', $_GET["remove"]);
    sql_query_update('Prestamos', 'dias_restantes', 0, $_GET['remove']);
    sql_query_update('Prestamos', 'Fecha_devolucion', date("Y-m-d"), $_GET['remove']);

    $redireccion = "?ruta=prestamos";

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
    $_SESSION["pagePrestamos"] = $_GET["page"];
}

if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    $usuarios = sql_get_all_prestamos(null, null, $_GET['type'], $_GET['search'], null, null);
    $pages = pages($_GET, $initalize, (int) count($usuarios));
    $usuarios = sql_get_all_prestamos($pages[0], $initalize, $_GET['type'], trim($_GET['search']), null, null);

    $route = "?ruta=prestamos&row=" . $_SESSION['rowPrestamos'] . "&search=" . trim($_GET['search']) . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['rowPrestamos'] = $_GET['row'];
    $_SESSION['columnaPrestamos'] = $_GET['column'];
    $_SESSION['ordenacionPrestamos'] = $_GET['order'];
    $usuarios = sql_get_all_prestamos($pages[0], $initalize, null, null, $_GET["column"], $_GET["order"]);

    $route = "?ruta=prestamos&row=" . $_SESSION['rowPrestamos'] . "&column=" . $_SESSION['columnaPrestamos'] . "&order=" . $_SESSION['ordenacionPrestamos'] . "";

} else {
    header("Location: ?ruta=prestamos&row=" . $_SESSION['rowPrestamos'] . "&column=" . $_SESSION['columnaPrestamos'] . "&order=" . $_SESSION['ordenacionPrestamos'] . "&page=" . $_SESSION["pagePrestamos"]);
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
        PANEL DE PRESTAMOS
    </div>
    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>

        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $_SESSION['rowPrestamos'] ?>" min="0"
                        max="20">
                </span>
            </div>
            <div class="table-options-search">
                <?php
                if (!isset($_GET['search']) && !isset($_GET['type'])) {
                    optionOrdenacion("table-options-order one", "ordenarPor", "PrestamosOrdenacion", $_SESSION, 'columnaPrestamos', 'ordenacionPrestamos', 'prestamos');
                    ?>
                </div>
                <?php
                }
                optionOrdenacion("table-options-order", "buscarPor", "PrestamosBuscador");
                formSearch($_SERVER["PHP_SELF"] . "?ruta=prestamos&search=", "busqueda");
                ?>
        </div>
        </div>

        <div class="inline">
            <?php
            paginaLinks('pagination', $pages[1], $route);
            ?>
            <?php
            iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ', 'prestamos')
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
        $heads = ['ID', 'NOMBRE', 'CORREO ELECTRONICO', 'NOMBRE DEL LIBRO ', 'FECHA INICIO', 'FECHA DEVOLUCION', 'DIAS RESTANTES', 'ESTADO', 'ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?token=libros&prestamos=", "fas fa-user-cog", "modificado", "prestamos"),
            array("option-link alt", "?ruta=prestamos&remove=", "fas fa-trash-alt", "borrado", "prestamo", "", ""),
            array("option-link check", "?ruta=prestamos&remove=", "fas fa-user-check", "reactivar", "")
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