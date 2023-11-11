<?php
/* En este archivo se controlará las peticiones que serán mandadas desde un cliente desde JavaScript
en JavaScript estamos utilizando AXIOS*/
header("Content-Type: application/json"); // Establece el encabezado de respuesta como JSON
require_once('./lib/database.inc.php');
require_once('./lib/verificacion_datos.inc.php');

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    if (isset($data["action"]) && $data["action"] === "insertarUsuario") {
        // Acceder a los valores en PHP
        $name = $data['name'];
        $last_name1 = $data['last_name1'];
        $last_name2 = $data['last_name2'];
        $user = $data['user'];
        $contrasena = $data['contrasena'];
        $email = $data['email'];
        $fechaRegistro = $data['fechaRegistro'];
        $rol = $data['rol'];

        $errores_campos = formularioDatosUsuario($data);

        if (empty($errores_campos)) {
            // Comprobaremos si el usuario existe o el correo electrónico, en caso de que exista se notificará al usuario que introduzca otro.
            if (sql_valida_usuario_correo($user)) {
                echo json_encode(['error' => 'El usuario introducido ya existe, introduzca otro por favor.']);
            } elseif (sql_valida_usuario_correo($email)) {
                echo json_encode(['error' => 'El correo electrónico introducido ya existe, introduzca otro por favor.']);
            } else {
                insertar_usuario($name, $last_name1, $last_name2, $user, $email, $contrasena, $fechaRegistro, $rol);
            }
        } else {
            $errorsArray = [];
            foreach ($errores_campos as $key => &$value) {
                $errorsArray[] = [
                    'text' => $key,
                    'message' => strtolower($value),
                ];
            }
            // Combina la información de errores y el mensaje de error en un solo objeto JSON
            echo json_encode(['errors' => $errorsArray, 'error' => ''], JSON_UNESCAPED_UNICODE);
        }
    }
}

if (isset($_GET['user']) && !empty($_GET['user'])) {
    $data = sql_usuario_id($_GET['user']);

    if (!$data) {
        $data = sql_obtener_usuario($_GET['user']);
    }

    $response = [$data];

    $endpoint = ['nombre', 'apellido', 'apellido2', 'contraseña', 'nombre_usuario', 'correo_electronico', 'fecha_registro', 'rol'];

    foreach ($endpoint as $value) {
        if (isset($_GET[$value]) && !empty($_GET[$value])) {
            $sentencia = sql_query_update("Usuarios", $value, $_GET[$value], $_GET['user']);
            if ($sentencia !== true) {
                // Agrega el error al array de respuesta
                $response[] = ['error' => $sentencia];
            }
        }
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>