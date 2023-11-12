<?php
function paginaLinks($classname, $totalPaginas, $route)
{
    ?>
    <div class="<?php echo $classname ?>">
        <ul>
            <?php
            for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                <li><a href="<?php echo $route . "&page=" . $i ?>">
                        <?php echo $i ?>
                    </a></li>
            <?php } ?>

        </ul>
    </div>
    <?php
}

function pages($paramentGet, $initalize, $tabla)
{
    $paginaActual = isset($paramentGet['page']) ? $paramentGet['page'] : 1;
    $usuariosPorPagina = (int) $initalize;

    if ($usuariosPorPagina <= 0) {
        $usuariosPorPagina = 1; // O el valor por defecto que desees
    }

    $offset = (int) ($paginaActual - 1) * (int) $usuariosPorPagina;

    // Obtener el número total de usuarios para la paginación

    if (gettype($tabla) === 'string') {
        $contador = (int) sql_count_tabla($tabla);
    } else {
        $contador = (int) $tabla;
    }

    $total = $contador;

    $totalPaginas = ($usuariosPorPagina > 0) ? ceil($total / $usuariosPorPagina) : 1;

    return [$offset, $totalPaginas];
}




/*
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$usuariosPorPagina = (int) $initalize;

// Asegúrate de que $usuariosPorPagina sea mayor a cero para evitar la división por cero
if ($usuariosPorPagina <= 0) {
    $usuariosPorPagina = 1; // O el valor por defecto que desees
}

$offset = (int) ($paginaActual - 1) * $usuariosPorPagina;

// Obtener el número total de usuarios para la paginación
$totalUsuarios = (int) sql_count_tabla('Usuarios');

// Asegúrate de que $usuariosPorPagina no sea cero antes de realizar la división
$totalPaginas = ($usuariosPorPagina > 0) ? ceil($totalUsuarios / $usuariosPorPagina) : 1;
*/
?>