<?php

?>

<link rel="stylesheet" href="./assets/css/usuarios.css">
<link rel="stylesheet" href="./assets/css/configuracion.css">

<?php require_once("./includes/nav.inc.php"); ?>

<section class="home">
    <div class="text">
        PANEL DE CONFIGURACION
    </div>
    <div class="containerConfiguracion">
      <div>
        <h1>Informacion Personal</h1>
        <form action="<?php echo dirname($_SERVER['PHP_SELF']) ."/procesa_datos.inc.php?token=libros&user=" . $_SESSION['id'] ?>" method="POST" class="personal-info" id="formularioPersonal">
          
        <input type="hidden" name="url" id="url" value="<?php echo $_SERVER["PHP_SELF"] ."?ruta=logout" ?>">

          <input type="hidden" name="ID" id="ID" value="<?php echo $_SESSION['id']?>">
        
          <label for="nombre">Nombre :</label><br />
          <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre']?>"/><br />
          
          <label for="apellido">Apellido :</label><br />
          <input type="text" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido']?>"/><br />

          <label for="apellido2">Segundo apellido :</label><br />
          <input type="text" id="apellido2" name="apellido2" value="<?php echo $_SESSION['apellido2']?>"/><br />


          <label for="nombre_usuario">Nombre de usuario  :</label><br />
          <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $_SESSION['nombre_usuario']?>"/><br />
        
          <label for="correo">Correo electronico :</label><br />
          <input type="correo" id="correo" name="correo" value="<?php echo $_SESSION['correo_electronico']?>"/><br />
        
           
          <label for="rol">ROL:</label><br />
          <input type="text" id="rol" name="rol" value="<?php echo $_SESSION['rol']?>" disabled/><br />


          <input type="submit" name="modificar" value="Modificar datos" />

        </form>
      </div>
      <div>
        <h1>Cambiar Contraseña</h1>
        <form class="password-change">
          <label for="current-password">Contraseña actual:</label><br />
          <input
            type="password"
            id="current-password"
            name="current-password"
          /><br />
          <label for="new-password">Nueva contraseña :</label><br />
          <input type="password" id="new-password" name="new-password" /><br />
          <label for="confirm-password">Confirma la nueva contraseña :</label><br />
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
          /><br />
          <input type="submit" send="cambiarContrasena" value="Cambiar contraseña" />
        </form>
      </div>
    </div>

    
</section>