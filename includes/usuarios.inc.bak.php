<?php
if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    // Realizar la eliminación del usuario
    sql_update_rol($_GET["remove"]);

    $redireccion = "?ruta=usuarios";

    if (isset($_GET["search"]) && isset($_GET["type"])) {
        $redireccion .= "&search=" . $_GET["search"] . "&type=" . $_GET["type"] . "";
        if (isset($_GET["page"]) && !empty(trim($_GET["page"]))) {
            $redireccion .= "&page=" . $_GET["page"];
        }
    }

    header("Location: $redireccion");
    exit;
}

$maxFilas = 20;

$initalize = isset($_GET["row"]) && !empty($_GET["row"]) ? $_GET["row"] : (isset($_SESSION['row']) ? $_SESSION['row'] : 5);

$pages = pages($_GET, $initalize, 'Usuarios');

$offset = $pages[0];
$totalPaginas = $pages[1];
$usuarios = [];

if (!isset($_SESSION['columnaOrdenacion']) && !isset($_SESSION['tipoOrdenacion']) && !isset($_SESSION['row'])) {
    $_SESSION['columnaOrdenacion'] = "nombre";
    $_SESSION['tipoOrdenacion'] = "ASC";
    $_SESSION["row"] = $initalize;
}

if (isset($_GET["search"]) && !empty($_GET["search"]) && isset($_GET['type'])) {
    $usuarios = sql_search("ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO", 'Usuarios', $_GET['type'], '%' . $_GET['search'] . '%');
    $pages = pages($_GET, $initalize, (int) count($usuarios));
    $offset = $pages[0];
    $totalPaginas = $pages[1];

    $usuarios = sql_search("ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO", 'Usuarios', $_GET['type'], '%' . $_GET['search'] . '%', $offset, $initalize);
    $route = "?ruta=usuarios&search=" . $_GET['search'] . "&type=" . $_GET['type'] . "";

} elseif (
    isset($_GET["order"]) && !empty(trim($_GET["order"]))
    && isset($_GET["column"]) && !empty(trim($_GET["column"]))
) {
    $_SESSION['row'] = $_GET['row'];
    $_SESSION['columnaOrdenacion'] = $_GET['column'];
    $_SESSION['tipoOrdenacion'] = $_GET['order'];

    $usuarios = sql_get_all_usuarios($offset, $initalize, $_GET["column"], $_GET["order"]);
    $route = "?ruta=usuarios&row=" . $_SESSION['row'] . "&column=" . $_SESSION['columnaOrdenacion'] . "&order=" . $_SESSION['tipoOrdenacion'] . "";

} else {
    header("Location: ?ruta=usuarios&row=" . $_SESSION['row'] . "&column=" . $_SESSION['columnaOrdenacion'] . "&order=" . $_SESSION['tipoOrdenacion']);
}
if (empty($usuarios)) {
    $totalPaginas = 1;
}

if (count($usuarios) < $initalize) {
    $initalize = $maxFilas;
}

?>
<?php require_once("./includes/nav.inc.php"); ?>

<link rel="stylesheet" href="./assets/css/usuarios.css">
<section class="home">
    <div class="text">
        PANEL DE USUARIO
        <?php
        ?>
    </div>

    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        ?>
        <div class="table-options">
            <div class="table-options-row">
                <span>
                    <input type="number" name="rows" id="rows" value="<?php echo $initalize ?>" min="0" max="20"
                        maxlength="3">

                </span>
            </div>
            <div class="table-options-search">

                <div class="table-options-order one">
                    <div>
                        <select id="ordenarPor">
                            <option value="column=nombre&order=ASC" <?php echo ($_SESSION['columnaOrdenacion'] == 'nombre' && $_SESSION['tipoOrdenacion'] == 'ASC') || isset($_GET['search']) ? 'selected' : ''; ?>>Ordenar
                                por...</option>
                            <optgroup label="ASCENDENTE">
                                <option value="column=nombre&order=ASC">NOMBRE</option>
                                <option value="column=nombre_usuario&order=ASC" <?php echo ($_SESSION['columnaOrdenacion'] == 'nombre_usuario' && $_SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                                <option value="column=correo_electronico&order=ASC" <?php echo ($_SESSION['columnaOrdenacion'] == 'correo_electronico' && $_SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                                <option value="column=fecha_registro&order=ASC" <?php echo ($_SESSION['columnaOrdenacion'] == 'fecha_registro' && $_SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                            </optgroup>
                            <optgroup label="DESCENDENTE">
                                <option value="column=nombre&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'nombre' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>NOMBRE</option>
                                <option value="column=nombre_usuario&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'nombre_usuario' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                                <option value="column=correo_electronico&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'correo_electronico' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                                <option value="column=fecha_registro&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'fecha_registro' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                            </optgroup>
                            <optgroup label="ESTADO">
                                <option value="column=ESTADO&order=ASC" <?php echo ($_SESSION['columnaOrdenacion'] == 'ESTADO' && $_SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>INACTIVO</option>
                                <option value="column=ESTADO&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] === 'ESTADO' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ACTIVO</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                
                <div class="table-options-order">
                    <select id="buscarPor">
                        <optgroup label="Por defecto : Nombre"></optgroup>
                        <option value="NOMBRE">Buscar por... </option>
                        <option value="ID">ID</option>
                        <option value="APELLIDO">APELLIDO</option>
                        <option value="APELLIDO2">SEGUNDO APELLIDO</option>
                        <option value="NOMBRE_USUARIO">NOMBRE USUARIO</option>
                        <option value="CORREO_ELECTRONICO">CORREO ELECTRONICO</option>
                        <option value="FECHA_REGISTRO">FECHA REGISTRO</option>
                        <optgroup label="Para ver inactivos ponga : false"></optgroup>
                        <optgroup label="Para ver activos ponga : 1"></optgroup>
                        <option value="ESTADO">ESTADO</option>
                        <option value="ROL">ROL</option>
                    </select>
                </div>

                <form method="GET" action="<?php echo $_SERVER["PHP_SELF"] . "?ruta=usuarios&search=" ?>" class="form"
                    id="busqueda">
                    <button>
                        <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
                            aria-labelledby="search">
                            <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                                stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </button>
                    <input class="inputSearch" placeholder="Buscar ... " required="" type="text" name="search" />
                    <button class="reset" type="reset">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="inline">
            <?php
            paginaLinks('pagination', $totalPaginas, "Usuarios", $route);

            ?>
            <div class="add">
                <div class="add-link">
                    <a href="#" id="anadeUsuario">
                        <i class="bx bx-user-plus "></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    </div>

    <?php
    if (isset($usuarios) && empty($usuarios)) {
        require_once("./includes/nofound.php");
    } else {
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>NOMBRE USUARIO</th>
                    <th>CORREO ELECTRONICO</th>
                    <th>FECHA REGISTRO</th>
                    <th>ROL</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $usuario) {
                    ?>
                    <tr>
                        <?php
                        foreach ($usuario as $propiedad => $value) {
                            if ($propiedad == "ESTADO") {
                                $value = $value ? "Activo" : "Inactivo";
                                ?>
                                <td class="table-state">
                                    <span>
                                        <?php echo $value ?>
                                    </span>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td>
                                    <?php echo $value ?>
                                </td>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                        <td class="option-table">
                            <ul>
                                <li class="option-link cog">
                                    <a href="<?php echo dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?user=" . $usuario['ID'] ?>"
                                        data-action="modificado">
                                        <i class="fas fa-user-cog"></i>
                                    </a>
                                </li>
                                <li class="option-link alt">
                                    <a href="?ruta=usuarios&remove=<?php echo $usuario['ID'] ?>" data-action="borrado">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </li>
                                <li class="option-link check">
                                    <a href="?ruta=usuarios&remove=<?php echo $usuario['ID'] ?>" data-action="reactivar">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                </li>
                            </ul>
                        </td>
                        <?php
                }

                ?>
            </tbody>
        </table>

        <?php
    }
    ?>

    <?php
    if (isset($usuarios) && !empty($usuarios)) {
        paginaLinks('pagination', $totalPaginas, "Usuarios", $route);
    }
    ?>

    </div>

</section>

<script>
    const selectOrdenacion = document.getElementById("ordenarPor");
    const selectBusqueda = document.getElementById("buscarPor")

    const inputFilas = document.getElementById("rows");
    const busqueda = document.getElementById('busqueda');

    busqueda.addEventListener("submit", (e) => {
        e.preventDefault()

        let timerInterval;
        // Mostrar un mensaje de "Buscando" con temporizador y barra de progreso
        Swal.fire({
            title: `Buscando por ${selectBusqueda.value}`, // Título del mensaje
            timer: 2000, // Duración del temporizador en milisegundos
            timerProgressBar: true, // Mostrar una barra de progreso durante el temporizador
            didOpen: () => {
                // Acciones que se ejecutan cuando se abre la alerta
                Swal.showLoading(); // Mostrar el indicador de carga
                const timer = Swal.getPopup().querySelector("b"); // Obtener el elemento del temporizador
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`; // Actualizar el temporizador en la barra de progreso
                }, 100);
            },
            willClose: () => {
                // Acciones que se ejecutan justo antes de cerrar la alerta
                clearInterval(timerInterval); // Limpiar el intervalo del temporizador
            }
        }).then((result) => {
            // Manejar el resultado después de cerrar la alerta
            /* Leer más sobre cómo manejar el cierre de la alerta a continuación */
            if (result.dismiss === Swal.DismissReason.timer) {
                // Redireccionar a la página de resultados de búsqueda si la alerta se cerró debido al temporizador
                window.location.href = `${e.target.action}${busqueda['search'].value}&type=${selectBusqueda.value}`;
            }
        });
    })

    selectOrdenacion.addEventListener('change', (e) => {
        actualizarURL();
    });


    inputFilas.addEventListener("input", (e) => {
        if (e.target.value > <?php echo $maxFilas; ?> || e.target.value <= 0) {
            e.target.value = <?php echo $initalize; ?>;
        }
        actualizarURL();
    });

    function actualizarURL() {
        const ordenSeleccionada = selectOrdenacion.value;
        const filasSeleccionadas = inputFilas.value;
        const busquedaTexto = busqueda['search'].value;
        const tipoBusqueda = selectBusqueda.value;

        let url = `?ruta=usuarios&row=${filasSeleccionadas}&${ordenSeleccionada}`;

        // Agregar parámetros de búsqueda si están presentes
        if (busquedaTexto && tipoBusqueda) {
            url += `&search=${busquedaTexto}&type=${tipoBusqueda}`;
        }

        window.location.href = url;
    }

</script>
<script src="./assets/js/index.js" type="module"></script>









//

<?php
function optionOrdenacion($classname, $idSelect, $tipo, $SESSION = '' ,$nameColumna , $nameOrdenacion)
{
    ?>
    <div class="<?php echo $classname ?>">
        <div>
            <select id=<?php echo $idSelect ?>>
                <?php
                switch ($tipo) {
                    case "UsuariosOrdenacion":
                        ?>
                        <option value="column=nombre&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre' && $SESSION['tipoOrdenacion'] == 'ASC') || isset($_GET['search']) ? 'selected' : ''; ?>>Ordenar
                            por...</option>
                        <optgroup label="ASCENDENTE">
                            <option value="column=ID&order=ASC">ID</option>
                            <option value="column=nombre&order=ASC">NOMBRE</option>
                            <option value="column=nombre_usuario&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre_usuario' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                            <option value="column=correo_electronico&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'correo_electronico' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                            <option value="column=fecha_registro&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_registro' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                        </optgroup>
                        <optgroup label="DESCENDENTE">
                            <option value="column=ID&order=DESC">ID</option>
                            <option value="column=nombre&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>NOMBRE</option>
                            <option value="column=nombre_usuario&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre_usuario' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                            <option value="column=correo_electronico&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'correo_electronico' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                            <option value="column=fecha_registro&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_registro' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                        </optgroup>
                        <optgroup label="ESTADO">
                            <option value="column=ESTADO&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'ESTADO' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>INACTIVO</option>
                            <option value="column=ESTADO&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] === 'ESTADO' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ACTIVO</option>
                        </optgroup>
                    </select>
                </div>
                <?php
                break;
                    case "UsuariosBuscador":
                        ?>
                <optgroup label="Por defecto : Nombre"></optgroup>
                <option value="NOMBRE">Buscar por... </option>
                <option value="ID">ID</option>
                <option value="APELLIDO">APELLIDO</option>
                <option value="NOMBRE_USUARIO">NOMBRE USUARIO</option>
                <option value="CORREO_ELECTRONICO">CORREO ELECTRONICO</option>
                <option value="FECHA_REGISTRO">FECHA REGISTRO</option>
                <optgroup label="Para ver inactivos ponga : false"></optgroup>
                <optgroup label="Para ver activos ponga : 1"></optgroup>
                <option value="ESTADO">ESTADO</option>
                <option value="ROL">ROL</option>
                </select>
            </div>
            </div>
            <?php
            break;
                    case "AutorOrdenacion": ?>
            <option value="column=nombre&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'ID' && $SESSION['tipoOrdenacion'] == 'ASC') || isset($_GET['search']) ? 'selected' : ''; ?>>Ordenar por...</option>
            <optgroup label="ASCENDENTE">
                <option value="column=ID&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'ID' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ID</option>
                <option value="column=nombre&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>NOMBRE</option>
                <option value="column=apellido&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'apellido' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>APELLIDO</option>
                <option value="column=fecha_nacimiento&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_nacimiento' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>FECHA NACIMIENTO</option>
                <option value="column=fecha_creacion&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_creacion' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>FECHA CREACION</option>
                <option value="column=fecha_modificacion&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_modificacion' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>FECHA MODIFICACION</option>
                <option value="column=estado&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'estado' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>ESTADO</option>
            </optgroup>
            <optgroup label="DESCENDENTE">
                <option value="column=ID&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'ID' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ID</option>
                <option value="column=nombre&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'nombre' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>NOMBRE</option>
                <option value="column=apellido&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'apellido' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>APELLIDO</option>
                <option value="column=fecha_nacimiento&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_nacimiento' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>FECHA NACIMIENTO</option>
                <option value="column=fecha_creacion&order=DESC" <?php echo ($_SESSION['columnaOrdenacion'] == 'fecha_creacion' && $_SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>FECHA CREACION</option>
                <option value="column=fecha_modificacion&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'fecha_modificacion' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>FECHA MODIFICACION</option>
                <option value="column=estado&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] == 'estado' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ESTADO</option>
            </optgroup>
            <optgroup label="ESTADO">
                <option value="column=ESTADO&order=ASC" <?php echo ($SESSION['columnaOrdenacion'] == 'ESTADO' && $SESSION['tipoOrdenacion'] == 'ASC') ? 'selected' : ''; ?>>INACTIVO</option>
                <option value="column=ESTADO&order=DESC" <?php echo ($SESSION['columnaOrdenacion'] === 'ESTADO' && $SESSION['tipoOrdenacion'] == 'DESC') ? 'selected' : ''; ?>>ACTIVO</option>
            </optgroup>
            </select>
            </div>
            <?php
            break;
                    case "AutorBuscador":
                        ?>
            <optgroup label="Por defecto : Nombre"></optgroup>
            <option value="NOMBRE">Buscar por... </option>
            <option value="ID">ID</option>
            <option value="APELLIDO">APELLIDO</option>
            <optgroup label="Para buscar por fechas solo se admite"></optgroup>
            <optgroup label="numeros ej : junio: 06 "></optgroup>
            <option value="FECHA_NACIMIENTO">FECHA_NACIMIENTO</option>
            <option value="FECHA_CREACION">FECHA CREACION</option>
            <option value="FECHA_MODIFICACION">FECHA MODIFICACION</option>
            <optgroup label="Para ver inactivos ponga : false"></optgroup>
            <optgroup label="Para ver activos ponga : 1"></optgroup>
            <option value="ESTADO">ESTADO</option>
            <option value="ROL">ROL</option>
            </select>
            </div>
            </div>
            <?php
            break;
                }

                ?>

    <?php
}

function formSearch($route, $id)
{
    ?>
    <form method="GET" action="<?php echo $route ?>" class="form" id="<?php echo $id ?>">
        <button>
            <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                    stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                </path>
            </svg>
        </button>
        <input class="inputSearch" placeholder="Buscar ... " required="" type="text" name="search" />
        <button class="reset" type="reset">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </form>
    <?php
}

?>
<script>
    const selectOrdenacion = document.getElementById("ordenarPor");
    const selectBusqueda = document.getElementById("buscarPor");

    const inputFilas = document.getElementById("rows");
    const busqueda = document.getElementById('busqueda');

    const url = new URL(window.location.href);

    const params = new URLSearchParams(url.search);


    busqueda.addEventListener("submit", (e) => {
        e.preventDefault()

        let timerInterval;
        // Mostrar un mensaje de "Buscando" con temporizador y barra de progreso
        Swal.fire({
            title: `Buscando por ${selectBusqueda.value}`, // Título del mensaje
            timer: 2000, // Duración del temporizador en milisegundos
            timerProgressBar: true, // Mostrar una barra de progreso durante el temporizador
            didOpen: () => {
                // Acciones que se ejecutan cuando se abre la alerta
                Swal.showLoading(); // Mostrar el indicador de carga
                const timer = Swal.getPopup().querySelector("b"); // Obtener el elemento del temporizador
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`; // Actualizar el temporizador en la barra de progreso
                }, 100);
            },
            willClose: () => {
                // Acciones que se ejecutan justo antes de cerrar la alerta
                clearInterval(timerInterval); // Limpiar el intervalo del temporizador
            }
        }).then((result) => {
            // Manejar el resultado después de cerrar la alerta
            /* Leer más sobre cómo manejar el cierre de la alerta a continuación */
            if (result.dismiss === Swal.DismissReason.timer) {
                // Redireccionar a la página de resultados de búsqueda si la alerta se cerró debido al temporizador
                window.location.href = `${e.target.action}${busqueda['search'].value}&type=${selectBusqueda.value}`;
            }
        });
    })


    selectOrdenacion.addEventListener('change', (e) => {
        actualizarURL();
    });



    inputFilas.addEventListener("input", (e) => {
        if (e.target.value > <?php echo MAX_FILAS; ?> || e.target.value <= 0) {
            e.target.value = <?php echo $initalize; ?>;
        }
        actualizarURL();
    });

    function actualizarURL() {
        const ordenSeleccionada = selectOrdenacion.value;
        const filasSeleccionadas = inputFilas.value;

        let url = `?ruta=usuarios&row=${filasSeleccionadas}`;

        if (params.get('type') !== null && params.get('search') !== null) {
            url += `&search=${params.get('search')}&type=${params.get('type')}`
        } else {
            url += `&${ordenSeleccionada}`
        }

        window.location.href = url;
    }

</script>