<?php
    require_once('./lib/verificacion_datos.inc.php');
    require_once('./lib/database.inc.php');
    $errores_campos = [];

    //Comprobamos si el usuario ha enviado el formulario
    if(isset($_POST['send'])) {
        //Si el usuario ha enviado el formulario , validaremos que los campos que estan declarados y no estan vacios mediante la variable "validaExistenciaVariable"
        //Y mediante funciones comprobaremos que dichos datos son validos por ejemplo nombre y apellidos sin caracteres especiales , email necesita '@' y al menos un '.'
        if(!validaExistenciaVaribale($_POST['name']) || !validaNombreApellidos($_POST['name'])) 
            $errores_campos[] = 'El campo nombre no puede estar vacio y/o no puede contener caracteres especiales.' ;
        if(!validaExistenciaVaribale($_POST['last_name1']) || !validaNombreApellidos($_POST['last_name1']))
            $errores_campos[] = 'El campo Primer apellido no puede estar vacio y/o no puede contener caracteres especiales.';
        if(!validaExistenciaVaribale($_POST['last_name2']) || !validaNombreApellidos($_POST['last_name2']))
            $errores_campos[] = 'El campo Segundo apellido no puede estar vacio y/o no puede contener caracteres especiales.';
        if(!validaExistenciaVaribale($_POST['user']) || !validaUsuario($_POST['user']))
            $errores_campos[] = 'El campo usuario no puede estar vacio y/o no es válido.';
        if(!validaExistenciaVaribale($_POST['email']) || !validaEmail($_POST['email']))
            $errores_campos[] = ' EL campo email no puede estar vacío y/o debe contener una dirección de correo electrónico válida, por ejemplo, "nombre@ejemplo.com".';
        if(!validaExistenciaVaribale($_POST['password']) || !validaPassword($_POST['password']))
            $errores_campos[] = ' EL campo contraseña no puede estar vacío y/o como mínimo debe tener 8 caracteres , maximo 15 , minimo una mayuscula , minuscula y un número.';
        
        //Comprobamos si ahi errores en el array $errores_campos
        if(empty($errores_campos)) {
            //Comprobaremos si el usuario existe o el correo electronico , en caso de que exista se notificara al usuario de que introduzca otro.
            if(sql_valida_usuario_correo($_POST['user'])) 
                $advertencia_existencia= 'El usuario introducido ya existe , introduzca otro porfavor.';
            elseif(sql_valida_usuario_correo($_POST['email']))
                $advertencia_existencia= 'El correo electronico introducido ya existe , introduzca otro porfavor.';
            //Si ambos han devuelto falso significa que nadie tiene el usuario/correo electronico que ha introducido el usuario entonces procedemos a 
            //llamar una funcion booleana que nos devolvera true si se ha podido introducir los datos o false en caso de que haya habido algun problema
            else 
                $verifica_inserccion = insertar_usuario($_POST['name'], $_POST['last_name1'], $_POST['last_name2'] , $_POST['user'] , $_POST['email'] , $_POST['password']);
        }
        //Comprobamos si la inserccion ha ido correctamente
        //Esta variable solo se hara en caso de que este definida y sea verdadera ya que devuelve un tipo boolean 
        if(isset($verifica_inserccion) && $verifica_inserccion) {
            header('Location:'.dirname($_SERVER['PHP_SELF']).'/login.php');
        }
    }
?>

<?php
    // Comprobamos si el usuario ha enviado el formulario con datos de registro y si existen errores.
    if (validaExistenciaVaribale($errores_campos)) {
    ?>
    <div class="error-message">
        <ul>
        <?php
        // Si hay errores, estaremos en este punto. Iteraremos el array asociativo para mostrar los errores en una lista.
        foreach ($errores_campos as $message_error) {
        ?>
            <li><i class="fa fa-times" style="font-size:20px;"></i><?php echo $message_error?></li>
        <?php
        }
        ?>
        </ul>
    </div>
    <?php
    }
?>

<?php
    if (isset($advertencia_existencia)) {
    ?>
    <div class="warning-message">
        <ul>
            <li><i class="fa fa-exclamation-triangle" style="font-size:20px; padding-right:10px"></i><?php echo $advertencia_existencia?></li>
        </ul>
    </div>
    <?php 
    }
?>


<div class="form-header">
        <form method="POST" action="" class="form">
            <label for="name">
                Nombre :
            </label>
            <input type="text" name="name" id="name" title="Introduce tu nombre" value="<?php if(isset($_POST['name'])) echo $_POST['name'] ?>" placeholder="Ej. Dario Alexander">
    
            <label for="last_name1">
                Primer apellido :
            </label>
            <input type="text" name="last_name1" id="last_name1" title="Introduce tu primer apellido" value="<?php if(isset($_POST['last_name1'])) echo $_POST['last_name1'] ?>" placeholder="Ej. Quinde">
    
            <label for="last_name2">
                Segundo apellido :
            </label>
            <input type="text" name="last_name2" id="last_name2" title="Introduce tu segundo apellido" value="<?php if(isset($_POST['last_name2'])) echo $_POST['last_name2']?>" placeholder="Ej. Quiñonez">
    
            <label for="user">
                Nombre de Usuario :
            </label>
            <input type="text" name="user" id="user" title="Elige un nombre de usuario único" value="<?php if(isset($_POST['user'])) echo $_POST['user']?>"placeholder="Ej. BibliotecaMadrid">
    
            <label for="email">
                Correo Electrónico :
            </label>
            <input type="email" name="email" id="email" title="Introduce tu dirección de correo electrónico" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>"placeholder="Ej. biblioteca@gmail.com">
    
            <label for="password">
                Contraseña :
            </label>
            <input type="password" name="password" id="password" title="Introduce una contraseña segura" placeholder="Mínimo 8 caracteres , una mayuscula , una minuscula y un número" >
    
            <input type="submit" name="send" id="send" value="Registrarse">
        </form>
</div>