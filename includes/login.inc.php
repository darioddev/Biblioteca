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
        }else {
            $error_login = 'El usuario y/o contraseña introducida no son validos.';
        }
        //Si uno de los inputs no esta definidio o esta vacio lanzara un error.
    } else
        $error_login = 'El usuario y/o contraseña introducida no son validos.';

    if (!isset($error_login)) {
        $_SESSION['user'] = $_POST['userLogin'];
        header('Location:' . $_SERVER['PHP_SELF'] . '?ruta=home');
    }

}
?>

<?php
include_once('./includes/header.inc.php');
?>

<div class="form-login">
    <form method="POST" action="" class="form">
        <label for="userLogin">
            Nombre de Usuario :
        </label>
        <input type="text" name="userLogin" id="userLogin" title="Introduce tu correo electronico o usuario" value="<?php if (isset($_POST['userLogin']))
            echo $_POST['userLogin'] ?>">

            <label for="passwordLogin">
                Contraseña :
            </label>
            <input type="password" name="passwordLogin" id="passwordLogin" title="Introduce tu contraseña"><i
                class="fa fa-eye fa-eye-open"></i>
            <?php
        if (isset($error_login)) {
            ?>
            <div>
                <p class="message-error"><i class="fa fa-times" style="font-size:20px;"></i>
                    <?php echo $error_login ?>
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