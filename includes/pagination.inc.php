<?php
/**
 * Genera enlaces de paginación en una lista.
 *
 * @param string $classname La clase CSS del contenedor div externo.
 * @param int $totalPaginas El número total de páginas.
 * @param string $route La URL base para los enlaces de paginación.
 */
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

/**
 * 
 * Calcula el offset y el número total de páginas para la paginación.
 *
 * @param array $paramentGet El array con los parámetros de la solicitud GET.
 * @param int $initalize La cantidad de elementos por página.
 * @param mixed $tabla La tabla o contador de la base de datos para la paginación.
 * @return array Un array que contiene el offset y el número total de páginas.
 */
function pages($paramentGet, $initalize, $tabla)
{
    $paginaActual = (int) (isset($paramentGet['page']) ? $paramentGet['page'] : 1);
    $usuariosPorPagina = $initalize;

    if ($usuariosPorPagina <= 0) {
        $usuariosPorPagina = 1; // 1 el valor por defecto 
    }

    $offset = ($paginaActual - 1) * $usuariosPorPagina;

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

?>