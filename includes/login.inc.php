<?php
      require_once('./lib/verificacion_datos.inc.php');
      require_once('./lib/database.inc.php');

      //Comprobamos si el boton ha sido enviado 
      if(isset($_POST['sendLogin'])) {
        //Si el usuario no ha sido definidio o el usuario no existe en la base de datos , o el campo contraseña no ha sido definidio o no ha sido 
        //definio , devolveremos un mensaje al usuario descriptivo diciendo :
        // Usuario y/o contraseña no son correctos.
        if(!validaExistenciaVaribale($_POST['userLogin']) || !validaExistenciaVaribale($_POST['passwordLogin']) 
        || !sql_valida_login($_POST['userLogin'],$_POST['passwordLogin']))

        $error_login = 'Usuario y/o contraseña no son correctos.'; 

        //Si los campos estan deinifidos y estan correctos y existen en la BBDD , redireccionamos a home.
        else 
        header('Location:'.$_SERVER['PHP_SELF'].'?ruta=home');
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