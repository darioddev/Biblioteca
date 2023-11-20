<?php
$state = sql_get_prestamos_by_id($_GET['borrar'], 'Usuarios.ID', true);
var_dump($state);
if ($state == 0 || empty($state)) {
    sql_update_estado('Usuarios', $_GET['borrar']);
    sleep(2);
    header("Location: ?ruta=logout");
} else {
    setcookie('mensajeError', 'No se puede eliminar la cuenta, tiene prestamos pendientes', time() + 3600, '/');
    header("Location: ?ruta=home");
}

?>