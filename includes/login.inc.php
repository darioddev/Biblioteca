    <div class="login-container">
        <?php
        //En caso de que el usuario intente acceder con los campos vacios
        //Se lanzara un mensaje informativo que esta almacenado en el array asociativo.
            if(checkExistenciaVar($errors)) {
        ?>
            <p style="color:red"><?php if(isset($errors["user"])) echo $errors["user"]?></p>
            <p style="color:red"><?php if(isset($errors["password"])) echo $errors["password"]?></p>
        <?php    
        }
        ?>
        <h2>Iniciar Sesión</h2>
        <form method="POST">
            <label for="user" class="form-label">Usuario:</label>
            <input type="text" id="user" name="user" class="form-input" placeholder="dario@biblioteca.com" title="Usuario/Correo Electronico" value="<?php if(isset($_POST["user"])) echo $_POST["user"]?>">

            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" id="password" name="password" class="form-input" title="Constraseña">

            <input type="submit" id="send" name="send" class="login-button">
        </form>
        <div class="register-link">
            Aún no estás registrado, <a href="#">haz clic aquí</a>
        </div>
    </div>
