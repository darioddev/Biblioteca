<?php
/* En este archivo se controlara las peticiones que seran mandadas desde un cliente desde JavaScript
en JavaScript estamos utilizando AXIOS*/
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {

    if (isset($data["action"]) && $data["action"] === "insertarUsuario") {
        // Acceder a los valores en PHP
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $apellido2 = $data['apellido2'];
        $nombreUsuario = $data['nombreUsuario'];
        $contrasena = $data['contrasena'];
        $correoElectronico = $data['correoElectronico'];
        $fechaRegistro = $data['fechaRegistro'];
        $rol = $data['rol'];

        insertar_usuario($nombre, $apellido, $apellido2, $nombreUsuario, $correoElectronico, $contrasena, $fechaRegistro, $rol);
    }
}
?>
?>