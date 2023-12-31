<?php
if (isset($_POST["cambiarContrasena"]) && !empty($_POST["cambiarContrasena"])) {
    // Obtener las contraseñas desde el formulario
    $contrasena_actual = $_POST["current-password"];
    $contrasena_nueva = $_POST["new-password"];
    $contrasena_confirmada = $_POST["confirm-password"];

    // Verificar que ninguna de las contraseñas esté vacía o no definidas.
    if (!validaExistenciaVaribale($contrasena_actual) || !validaExistenciaVaribale($contrasena_nueva) || !validaExistenciaVaribale($contrasena_confirmada)) {
        // Mostrar un mensaje de error si algún campo está vacío
        echoAlert('Error de campos', 'Ningun campo del formulario contraseña puede estar vacio', 'error');
    } else {
        $state = sql_valida_contrasena($_SESSION["user"], $contrasena_actual);

        if (
            !validaExistenciaVaribale($contrasena_nueva) || !validaPassword($contrasena_nueva)
            || !validaExistenciaVaribale($contrasena_confirmada) || !validaPassword($contrasena_confirmada)
        ) {
            echoAlert('Error en la confirmacion y/o nueva contraseña', 'No puede estar vacío y/o como mínimo debe tener 8 caracteres, máximo 15, al menos una mayúscula, una minúscula y un número.', 'error');
        } else if (isset($state) && $state) {
            // Validar la contraseña nueva
            if (!validaExistenciaVaribale($contrasena_nueva) || !validaPassword($contrasena_nueva)) {
                // Mostrar un mensaje de error si la contraseña nueva no cumple con los requisitos
                echoAlert('Error en el input de contraseña nueva', 'No puede estar vacío y/o como mínimo debe tener 8 caracteres, máximo 15, al menos una mayúscula, una minúscula y un número.', 'error');
            } elseif (!validaExistenciaVaribale($contrasena_confirmada) || !validaPassword($contrasena_confirmada)) {
                // Mostrar un mensaje de error si la contraseña de confirmación no cumple con los requisitos
                echoAlert('Error en el input de confirmar contraseña', 'No puede estar vacío y/o como mínimo debe tener 8 caracteres, máximo 15, al menos una mayúscula, una minúscula y un número.', 'error');
            } else {
                // Verificar que la contraseña nueva y la de confirmación coincidan
                if ($contrasena_nueva === $contrasena_confirmada) {
                    // Actualizar la contraseña en la base de datos
                    $passwor = password_hash($contrasena_nueva, PASSWORD_BCRYPT);

                    $state = sql_query_update('Usuarios', 'Contraseña', $passwor, $_SESSION['id']);

                    if ($state) {
                        echoAlert('Contraseña cambiada', 'La contraseña se ha cambiado correctamente. Su session sera cerrada para efectuar las cambios', 'success');
                        header("Refresh:2; url=" . $_SERVER["PHP_SELF"] . "?ruta=logout" . "");
                    } else {
                        // Mostrar un mensaje de error si la contraseña no se pudo cambiar
                        echoAlert('Error de contraseña', 'La contraseña no se ha podido cambiar', 'error');
                    }
                } else {
                    // Mostrar un mensaje de error si la contraseña nueva y la de confirmación no coinciden
                    echoAlert('Error de contraseña', 'La contraseña nueva no coincide con la contraseña confirmada', 'error');
                }
            }
        } else {
            // Mostrar un mensaje de error si la contraseña actual no coincide con la contraseña del usuario
            echoAlert('Error de contraseña', 'La contraseña actual no coincide con la contraseña del usuario', 'error');
        }
    }
}
?>
<link rel="stylesheet" href="./assets/css/usuarios.css">
<link rel="stylesheet" href="./assets/css/configuracion.css">

<?php require_once("./includes/nav.inc.php"); ?>

<section class="home">
    <div class="text">
        PANEL DE CONFIGURACION
    </div>

    <div class="containerConfiguracion alt">
        <div>
            <h1>Cambiar Contraseña</h1>
            <form action="<?php echo $_SERVER["PHP_SELF"] . "?ruta=CambioContrasena" ?>" method="POST"
                class="password-change" id="changePassword">

                <label for="current-password">Contraseña actual:</label><br />
                <input type="password" id="current-password" name="current-password" /><br />
                <label for="new-password">Nueva contraseña :</label><br />
                <input type="password" id="new-password" name="new-password" /><br />
                <label for="confirm-password">Confirma la nueva contraseña :</label><br />
                <input type="password" id="confirm-password" name="confirm-password" /><br />
                <input type="submit" name="cambiarContrasena" value="Cambiar contraseña" />
            </form>
        </div>
    </div>
</section>