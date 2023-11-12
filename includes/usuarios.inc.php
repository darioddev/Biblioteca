<?php
$initalize = initializeRow("row");

$pages = pages($_GET, $initalize, 'Usuarios');

$usuarios = [];

initializeSession('columnaOrdenacion', 'tipoOrdenacion', 'row', 'page', 'nombre', 'ASC', $initalize, 1);

if(isset($_SESSION['row']) && $_SESSION['row'] < 1) {
    $_SESSION['row'] = 5 ;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    // Realizar la eliminación del usuario
    sql_update_estado('Usuarios',$_GET["remove"]);

    $redireccion = "?ruta=usuarios";

    if (isset($_GET["search"]) && isset($_GET["type"])) {
        $redireccion .= "&search=" . $_GET["search"] . "&type=" . $_GET["type"] . "";
        if (isset($_GET["page"]) && !empty(trim($_GET["page"]))) {
            $redireccion .= "&page=" . $_GET["page"];
        }
    }

    header("Location: $redireccion");
    die();
}


if (isset($_GET["page"])) {
    $_SESSION["page"] = $_GET["page"];
}


if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    $usuarios = sql_search("ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO", 'Usuarios', $_GET['type'], '%' . $_GET['search'] . '%');
    $pages = pages($_GET, $initalize, (int) count($usuarios));

    $usuarios = sql_search("ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO", 'Usuarios', $_GET['type'], '%' . $_GET['search'] . '%', $pages[0], $initalize);
    $route = "?ruta=usuarios&row=" . $_SESSION['row'] . "&search=" . $_GET['search'] . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['row'] = $_GET['row'];
    $_SESSION['columnaOrdenacion'] = $_GET['column'];
    $_SESSION['tipoOrdenacion'] = $_GET['order'];

    $usuarios = sql_get_all_usuarios($pages[0], $initalize, $_GET["column"], $_GET["order"]);
    $route = "?ruta=usuarios&row=" . $_SESSION['row'] . "&column=" . $_SESSION['columnaOrdenacion'] . "&order=" . $_SESSION['tipoOrdenacion'] . "";

} else {
    header("Location: ?ruta=usuarios&row=" . $_SESSION['row'] . "&column=" . $_SESSION['columnaOrdenacion'] . "&order=" . $_SESSION['tipoOrdenacion'] . "&page=" . $_SESSION["page"]);
    die();
}

if (empty($usuarios)) {
    $pages[1] = 1;
}

if (count($usuarios) < $initalize || $initalize < 0) {
    $initalize = MAX_FILAS;
}


?>
<?php require_once("./includes/nav.inc.php"); ?>

<link rel="stylesheet" href="./assets/css/usuarios.css">
<section class="home">
    <div class="text">
        PANEL DE USUARIO
    </div>

    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>
        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $_SESSION['row'] ?>" min="0" max="20"
                        maxlength="3">

                </span>
            </div>
            <div class="table-options-search">
                <?php
                if (!isset($_GET['search']) && !isset($_GET['type'])) {
                    ?>
                    <?php
                    optionOrdenacion("table-options-order one", "ordenarPor", "UsuariosOrdenacion", $_SESSION, 'columnaOrdenacion', 'tipoOrdenacion','usuarios');
                    ?>
                </div>

                <?php
                }
                optionOrdenacion("table-options-order", "buscarPor", "UsuariosBuscador",'usuarios');
                formSearch($_SERVER["PHP_SELF"] . "?ruta=usuarios&search=", "busqueda");
                ?>
        </div>
        </div>

        <div class="inline">
            <?php
            paginaLinks('pagination', $pages[1], $route);
            ?>
            <?php
            iconAddDiv('add', 'add-link', 'anadeUsuario', 'bx bx-user-plus ' , 'usuarios')
                ?>
        </div>
        <?php
    }
    ?>
    </div>


    <?php
    if (isset($usuarios) && empty($usuarios)) {
        require_once("./includes/nofound.php");
    } else {
        $heads = ['ID', 'NOMBRE', 'NOMBRE USUARIO', 'CORREO ELECTRONICO', 'FECHA REGISTRO', 'ROL', 'ESTADO', 'ACCIONES'];
        $icons = array(
            array("option-link cog", dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?token=libros&user=", "fas fa-user-cog", "modificado","usuarios"),
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

<script>
    const initializeRow = <?php echo json_encode($initalize); ?>;
    const maxFilas = <?php echo MAX_FILAS; ?>;
</script>

