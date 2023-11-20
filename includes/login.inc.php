<?php
//Comprobamos si el boton ha sido enviado 
if (isset($_POST['sendLogin'])) {

    //Comprobamos si los valorables del formulario estan definidos y no estan vacios mediante la funcion 'validaExistenciaVaribale'
    if (validaExistenciaVaribale($_POST['userLogin']) && validaExistenciaVaribale($_POST['passwordLogin'])) {
        //Comprobamos que los datos introducidos cumplen unas condiciones REGEX 
        //Para el input de 'userLogin' haremso una comprobacion doble , ya que puede ser que recibamos el Email o de lo contrario usuario.
        //Desoues con un operador '&&' comprobaremos el e la contraseña.
        if (validaUsuario($_POST['userLogin']) || validaEmail($_POST['userLogin'])) {
            if (validaPassword($_POST['passwordLogin'])) {
                if (!sql_valida_login($_POST['userLogin'], $_POST['passwordLogin'])) {
                    $error_login = 'El usuario y/o contraseña no son correctos';
                }
            } else {
                $error_login = 'La contraseña es incorrecta';
            }
        } else {
            $error_login = 'El usuario y/o contraseña introducida no son validos.';
        }
        //Si uno de los inputs no esta definidio o esta vacio lanzara un error.
    } else
        $error_login = 'El usuario y/o contraseña introducida no son validos.';

    //Si no hay errores en el login , procederemos a iniciar sesion y redirigir al usuario a la pagina de inicio.
    if (!isset($error_login)) {
        // Se crea la sesion y se redirige al usuario a la pagina de inicio
        $_SESSION['user'] = $_POST['userLogin'];
        // Se crea sessiones para almacenar los datos del usuario
        initializeDataSession($_SESSION['user']);

        // Comprobamos si el usuario esta activo o no
        if (isset($_SESSION['estado']) && $_SESSION['estado'] == 0) {
            setcookie('mensajeError', 'Al parecer tu cuenta esta desactivada para volver activarla debe comunicarse con un administrador.', time() + 3600, '/');
            header('Location:' . $_SERVER['PHP_SELF'] . '?ruta=logout');
            exit();
        }
        // Si existe la cookie de error la eliminamos
        if (isset($_COOKIE['mensajeError']))
            setcookie('mensajeError', '', time() - 3600, '/');

        // Redirigimos al usuario a la pagina de inicio
        sleep(2);
        header('Location:' . $_SERVER['PHP_SELF'] . '?ruta=home');
        exit();
    }

}else {
    setcookie('mensajeError', '', time() - 3600, '/');
}
?>

<?php
include_once('./includes/header.inc.php');
?>

<div class="form-login">
    <form method="POST" action="" class="form">
        <label for="userLogin">
            Nombre de Usuario / Correo electronico :
        </label>
        <input type="text" name="userLogin" id="userLogin" title="Introduce tu correo electronico o usuario" value="<?php if (isset($_POST['userLogin']))
            echo $_POST['userLogin'] ?>">

            <label for="passwordLogin">
                Contraseña :
            </label>
            <input type="password" name="passwordLogin" id="passwordLogin" title="Introduce tu contraseña">
            <?php
        if (isset($error_login) || isset($_COOKIE['mensajeError'])) {
            ?>
            <div>
                <p class="message-error"><i class="fa fa-times" style="font-size:20px;"></i>
                    <?php
                    if (isset($_COOKIE['mensajeError']))
                        echo $_COOKIE['mensajeError'];
                    ?>
                    <?php if (isset($error_login))
                        echo $error_login ?>
                    </p>
                </div>
            <?php
        }
        ?>

        <input type="submit" name="sendLogin" id="sendLogin" value="Iniciar Sesion">
        <div class="register-link">
            <p>¿Aún no estás registrado? <a href="<?php echo $_SERVER['PHP_SELF'] . '?ruta=register' ?>">¡Pincha aquí
                    para
                    registrarte!</a></p>
        </div>
    </form>
</div>
<?php
include_once('./includes/footer.inc.php');
?>