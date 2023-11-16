<?php


define('MAX_FILAS', 20);

/**
 * Inicializa las sesiones con valores predeterminados si no existen.
 *
 * @param string $columnaKey La clave para la sesión de la columna de ordenación.
 * @param string $tipoKey La clave para la sesión del tipo de ordenación.
 * @param string $rowKey La clave para la sesión del número de filas por página.
 * @param string $pageKey La clave para la sesión de la página actual.
 * @param string $defaultColumna Valor predeterminado para la columna de ordenación.
 * @param string $defaultTipo Valor predeterminado para el tipo de ordenación.
 * @param int $defaultRow Valor predeterminado para el número de filas por página.
 * @param int $defaultPage Valor predeterminado para la página actual.
 */
function initializeSession($columnaKey, $tipoKey, $rowKey, $pageKey, $defaultColumna, $defaultTipo, $defaultRow, $defaultPage)
{
    if (!isset($_SESSION[$columnaKey]) && !isset($_SESSION[$tipoKey]) && !isset($_SESSION[$rowKey]) && !isset($_SESSION[$pageKey])) {
        $_SESSION[$columnaKey] = $defaultColumna;
        $_SESSION[$tipoKey] = $defaultTipo;
        $_SESSION[$rowKey] = $defaultRow;
        $_SESSION[$pageKey] = (int) $defaultPage;
    }
}

/**
 * Función para inicializar el valor de una fila ($nameRow) desde $_GET o $_SESSION.
 * 
 * @param string $nameRow Nombre de la fila que se desea obtener.
 * @return mixed Valor de la fila obtenido de $_GET, $_SESSION o un valor predeterminado.
 */
function initializeRow($nameRow)
{
    // Verifica si el valor de la fila está presente en $_GET y no está vacío
    if (isset($_GET[$nameRow]) && !empty($_GET[$nameRow])) {
        return $_GET[$nameRow];
    }
    // Si no está en $_GET, verifica si está presente en $_SESSION
    elseif (isset($_SESSION[$nameRow])) {
        return $_SESSION[$nameRow];
    }
    // Si no está en $_GET ni en $_SESSION, devuelve un valor predeterminado (5 en este caso)
    else {
        return 5; // Valor predeterminado si no se proporciona ningún valor en $_GET ni en $_SESSION
    }
}

function inializeDataSession($id) {
    foreach(sql_obtener_usuario($id) as $propiedad=>$value) {
        $_SESSION[strtolower($propiedad)] = $value;
    }
}

?>