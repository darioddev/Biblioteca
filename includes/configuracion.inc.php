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
      <form
        action="<?php echo dirname($_SERVER['PHP_SELF']) . "/procesa_datos.inc.php?token=libros&user=" . $_SESSION['id'] ?>"
        method="POST" class="personal-info" id="formularioPersonal">

        <input type="hidden" name="url" id="url" value="<?php echo $_SERVER["PHP_SELF"] . "?ruta=logout" ?>">

        <input type="hidden" name="ID" id="ID" value="<?php echo $_SESSION['id'] ?>">

        <label for="nombre">Nombre :</label><br />
        <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre'] ?>" required /><br />

        <label for="apellido">Apellido :</label><br />
        <input type="text" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido'] ?>" required /><br />

        <label for="apellido2">Segundo apellido :</label><br />
        <input type="text" id="apellido2" name="apellido2" value="<?php echo $_SESSION['apellido2'] ?>" /><br />


        <label for="nombre_usuario">Nombre de usuario :</label><br />
        <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $_SESSION['nombre_usuario'] ?>"
          required /><br />

        <label for="correo">Correo electronico :</label><br />
        <input type="correo" id="correo" name="correo" value="<?php echo $_SESSION['correo_electronico'] ?>"
          required /><br />


        <label for="rol">ROL:</label><br />
        <input type="text" id="rol" name="rol" value="<?php echo $_SESSION['rol'] ?>" disabled /><br />


        <input type="submit" name="modificar" value="Modificar datos" />

        <a href="<?php echo $_SERVER["PHP_SELF"] . "?ruta=CambioContrasena" ?>"
          style="text-decoration: none; color: #3498db; font-weight: bold; margin-top:20px;">
          PARA CAMBIAR LA CONTRASEÑA, HAZ CLIC AQUÍ
        </a>

      </form>

    </div>
  </div>


</section>