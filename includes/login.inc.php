<?php
      require_once('./lib/verificacion_datos.inc.php');
      require_once('./lib/database.inc.php');

      //Comprobamos si el boton ha sido enviado 
      if(isset($_POST['sendLogin'])) {
        //Primero compruebo si el campo de usuario y contraseña estan definidos y no son nulos
        // Y tambien compruebo que cumplan las condiciones de seguridad como cuando se registran.
        if(!validaExistenciaVaribale($_POST['userLogin']) || !validaUsuario($_POST['userLogin'])
         ||!validaExistenciaVaribale($_POST['passwordLogin']) || !validaPassword($_POST['passwordLogin'])) 
         $error_login = 'El usuario y/o contraseña introducida no son validos.';
        
         //En caso de que los parametros cumplen con las condiciones y no son vacios
         //Pasamos a comprobar si existe en la base de datos y es correcta su contraseña introducida
         
         //Comprobamos el if con negacion , esto es por que si es falso no existe y poder lanzar un mensaje
         //Descriptivo al usuario notificando que los datos no son correctos
         //De lo contrario si devuelve verdadero la funcion nunca creara $error_login
        if(!isset($error_login) && !sql_valida_login($_POST['userLogin'],$_POST['passwordLogin'])) 
            $error_login = 'El usuario y/o contraseña no son correctos';

        //En el caso de que la funcion sql_valida_login de verdadero llegaremos a este punto
        //Que significara que no ahi errores y que existe el usuario y la contraseña introducida es correcta.
        if(!isset($error_login)){
            $_SESSION['user'] = $_POST['userLogin'];
            header('Location:'.$_SERVER['PHP_SELF'].'?ruta=home');
        }
    }
?>


<div class="form-login">
        <form method="POST" action="" class="form">    
     
            <label for="userLogin">
                Nombre de Usuario :
            </label>
            <input type="text" name="userLogin" id="userLogin" title="Introduce tu correo electronico o usuario" value="<?php if(isset($_POST['userLogin'])) echo $_POST['userLogin']?>">
    
            <label for="passwordLogin">
                Contraseña :
            </label>
            <input type="password" name="passwordLogin" id="passwordLogin" title="Introduce tu contraseña" >
            <?php
            if(isset($error_login)) {
            ?>
            <div class="error-message-login">
            <p><i class="fa fa-times" style="font-size:20px;"></i><?php echo $error_login?></p>
            </div>
            <?php
            }
            ?>
            <input type="submit" name="sendLogin" id="sendLogin" value="Iniciar Sesion">
        </form>
</div>