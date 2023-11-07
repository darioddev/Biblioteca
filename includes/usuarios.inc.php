<?php
$initalize = (isset($_GET["row"]) && !empty(trim($_GET["row"]) && $_GET["row"] >= 0) ? $_GET["row"] : 5);
$usuarios = sql_get_all_usuarios(0, $initalize);
$maxFilas = 20;

if (count($usuarios) < $initalize) {
    $initalize = $maxFilas;
}

if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
    $var = sql_update_row($_GET["remove"]);
    header("Location:?ruta=usuarios");
    die();
}
?>


<?php
require_once("./includes/nav.inc.php");
?>

<link rel="stylesheet" href="./assets/css/usuarios.css">
<section class="home">
    <div class="text">
        PANEL DE USUARIO
    </div>
    <div class="table-options">
        <div class="table-options-row">
            <span>Mostrar
                <input type="number" name="rows" id="rows" value="<?php echo $initalize ?>" min="0" max="20"
                    maxlength="3">
                filas
            </span>
            </span>

            <script>
                document.getElementById("rows").addEventListener("input", (e) => {
                    if (e.target.value > <?php echo $maxFilas; ?> || e.target.value <= 0) {
                        e.target.value = <?php echo $initalize; ?>;
                    }
                    window.location.href = `?ruta=usuarios&row=${e.target.value}`;
                    document.getElementById("rows")
                });
            </script>

        </div>

        <div class="table-options-order">
            <div>
                <label for="ordenarPor">Ordenar por:</label>
                <select id="ordenarPor">
                    <optgroup label="ASCENDENTE">
                        <option value="NOMBRE">NOMBRE</option>
                        <option value="NOMBRE_USUARIO">NOMBRE USUARIO</option>
                        <option value="CORREO_ELECTRONICO">CORREO ELECTRONICO</option>
                        <option value="FECHA_REGISTRO">FECHA REGISTRO</option>
                        <option value="ROL">ROL</option>
                    </optgroup>
                    <optgroup label="DESCENDENTE">
                        <option value="NOMBRE_DESC">NOMBRE DESCENDENTE</option>
                        <option value="NOMBRE_USUARIO_DESC">NOMBRE USUARIO DESCENDENTE</option>
                        <option value="CORREO_ELECTRONICO_DESC">CORREO ELECTRONICO DESCENDENTE</option>
                        <option value="FECHA_REGISTRO_DESC">FECHA REGISTRO DESCENDENTE</option>
                        <option value="ROL_DESC">ROL DESCENDENTE</option>
                    </optgroup>
                    <optgroup label="ESTADO">
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                        <option value="TODOS" selected>TODOS</option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="table-options-search">
            <form class="form">
                <button>
                    <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"
                        aria-labelledby="search">
                        <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                            stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>
                <input class="input" placeholder="Buscar ... " required="" type="text" />
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div class="add">
        <div class="add-link">
            <a href="#" id="anadeUsuario">
                <i class="bx bx-user-plus "></i>
            </a>
        </div>
    </div>

    </div>

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
                                <a href="<?php echo dirname($_SERVER["PHP_SELF"]) . "/procesa_datos.inc.php?user=" . $usuario['CORREO_ELECTRONICO'] ?>"
                                    data-action="modificado">
                                    <i class="fas fa-user-cog"></i>
                                </a>
                            </li>
                            <li class="option-link alt">
                                <a href="?ruta=usuarios&remove=<?php echo $usuario['CORREO_ELECTRONICO'] ?>"
                                    data-action="borrado">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                    <?php
            }

            ?>
        </tbody>
    </table>
    </div>

</section>
<script src="./assets/js/nav.js" type="module"></script>