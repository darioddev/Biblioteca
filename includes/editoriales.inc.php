<?php
$initalize = initializeRow("rowEditorial");
define('PARAMETROSEDITORIAL', "ID, NOMBRE, FECHA_CREACION , FECHA_MODIFICACION ,ESTADO");
$pages = pages($_GET, $initalize, 'Editoriales');


initializeSession('columnaEditorial', 'ordenacionEditorial', 'rowEditorial', 'pageEditorial', 'nombre', 'ASC', $initalize, 1);

if(isset($_SESSION['rowEditorial']) && empty($_SESSION['rowEditorial'])) {
    $_SESSION['rowEditorial'] = 5 ;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    // Realizar la eliminación del usuario
    sql_update_estado('Editoriales', $_GET["remove"]);

    $redireccion = "?ruta=editoriales";

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
    $_SESSION["pageEditorial"] = $_GET["page"];
}

if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    //$sentencia = "ID_AUTOR, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO";
    $usuarios = sql_search(PARAMETROSEDITORIAL, 'Editoriales', $_GET['type'], '%' . $_GET['search'] . '%');
    $pages = pages($_GET, $initalize, (int) count($usuarios));

    $usuarios = sql_search(PARAMETROSEDITORIAL, 'Editoriales', $_GET['type'], '%' . $_GET['search'] . '%', $pages[0], $initalize);

    $route = "?ruta=editoriales&row=" . $_SESSION['rowEditorial'] . "&search=" . $_GET['search'] . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['rowEditorial'] = $_GET['row'];
    $_SESSION['columnaEditorial'] = $_GET['column'];
    $_SESSION['ordenacionEditorial'] = $_GET['order'];
    $usuarios = sql_get_all(PARAMETROSEDITORIAL, 'Editoriales', $pages[0], $initalize, $_GET["column"], $_GET["order"]);

    $route = "?ruta=editoriales&row=" . $_SESSION['rowEditorial'] . "&column=" . $_SESSION['columnaEditorial'] . "&order=" . $_SESSION['ordenacionEditorial'] . "";

} else {
    header("Location: ?ruta=editoriales&row=" . $_SESSION['rowEditorial'] . "&column=" . $_SESSION['columnaEditorial'] . "&order=" . $_SESSION['ordenacionEditorial'] . "&page=" . $_SESSION["pageEditorial"]);
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
        PANEL DE EDITORIALES
    </div>
    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>

        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $_SESSION['rowEditorial'] ?>" min="0" max="20">
                </span>
            </div>
            <div class="table-options-search">
                <?php
                if (!isset($_GET['search']) && !isset($_GET['type'])) {
                    optionOrdenacion("table-options-order one", "ordenarPor", "EditorialOrdenacion", $_SESSION, 'columnaEditorial', 'ordenacionEditorial', 'editoriales');
                    ?>
                </div>
                <?php
                }
                optionOrdenacion("table-options-order", "buscarPor", "EditorialBuscador");
                formSearch($_SERVER["PHP_SELF"] . "?ruta=editoriales&search=", "busqueda");
                ?>
        </div>
        </div>

        <div class="inline">
            <?php
            paginaLinks('pagination', $pages[1], $route);
            ?>
            <?php
            iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ','editoriales')
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
        $heads = ['ID', 'NOMBRE', 'FECHA_CREACION','FECHA_MODIFICACION', 'ESTADO','ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?token=libros&editorial=", "fas fa-user-cog", "modificado","editoriales"),
            array("option-link alt", "?ruta=editoriales&remove=", "fas fa-trash-alt", "borrado","editorial","ID_Editorial","verificaEstado"),
            array("option-link check", "?ruta=editoriales&remove=", "fas fa-user-check", "reactivar","")
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