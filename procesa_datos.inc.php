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

        if (!validaExistenciaVaribale($contrasena) || !validaPassword($contrasena)) {
            $errores_campos['contraseña'] = 'La contraseña no es valida y/o no cumple los requisitos.';
        }

        if (empty($errores_campos)) {
            // Comprobaremos si el usuario existe o el correo electrónico, en caso de que exista se notificará al usuario que introduzca otro.
            if (sql_valida_usuario_correo($user)) {
                echo json_encode(['error' => 'El usuario introducido ya existe, introduzca otro por favor.']);
            } elseif (sql_valida_usuario_correo($email)) {
                echo json_encode(['error' => 'El correo electrónico introducido ya existe, introduzca otro por favor.']);
            } else {
                $value = insertar_usuario($name, $last_name1, $last_name2, $user, $email, $contrasena, $fechaRegistro, $rol);
            }

            if ($value) {
                echo json_encode(['succes' => 'El usuario ha sido introducido correctamente, introduzca otro por favor.']);
            } else {
                echo json_encode(['warning' => 'Hubo un problema en la inserccion.']);
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
    } else if (isset($data['action']) && $data['action'] === "insertarAutor") {
        $errores_campos = formularioDatosAutor($data);

        if (empty($errores_campos)) {
            // Si no hay errores en los campos, procede con la inserción en la base de datos.
            $array = [
                'nombre' => $data["nombre"],
                'apellido' => $data["apellido"],
                'fecha_nacimiento' => $data["fecha_nacimiento"]
            ];
        
            // Llamada al método para insertar en la base de datos
            $value = sql_insertar_dato('Autores', $array);

            if($value) {
                $response = ['success' => 'Datos del autor insertados correctamente.'];
            }else {
                $response = ['warning'=> 'Hubo un problema en la insercion de datos'];
            }
        
        } else {
            $errorsArray = [];
            foreach ($errores_campos as $key => &$value) {
                $errorsArray[] = [
                    'text' => $key,
                    'message' => strtolower($value),
                ];
            }
            // Si hay errores en los campos, devuelve un mensaje de error.
            $response = ['error' => '', 'errors' => $errorsArray];
        }

        echo json_encode($response , JSON_UNESCAPED_UNICODE);
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
        if (isset($_GET[$value])) {
            $sentencia = sql_query_update("Usuarios", $value, $_GET[$value], $_GET['user']);
            if ($sentencia !== true) {
                // Agrega el error al array de respuesta
                $response[] = ['error' => $sentencia];
            }
        }
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

if (isset($_GET['autor']) && !empty($_GET['autor'])) {
    define('PARAMETROS', "ID, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO");
    $autores = sql_get_row(PARAMETROS, 'Autores', $_GET['autor']);
    $response[] = $autores;

    $endpoint = ['nombre', 'apellido' , 'fecha_nacimiento', 'fecha_creacion'];

    foreach ($endpoint as $value) {
        if (isset($_GET[$value]) && !empty($_GET[$value])) {            
            if ($value === 'FECHA_NACIMIENTO') {
                $fecha_nacimiento = date('Y-m-d', strtotime($_GET[$value]));
                $sentencia = sql_query_update("Autores", $value, $fecha_nacimiento, $_GET['autor']);
            } else {
                // No es una fecha, usa directamente el valor
                $sentencia = sql_query_update("Autores", $value, $_GET[$value], $_GET['autor']);
            }

            if ($sentencia !== true) {
                // Agrega el error al array de respuesta
                $response[] = ['error' => $sentencia];
            }
        }
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>