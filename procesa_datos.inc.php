<?php
/* En este archivo se controlará las peticiones que serán mandadas desde un cliente desde JavaScript
en JavaScript estamos utilizando AXIOS*/
header("Content-Type: application/json"); // Establece el encabezado de respuesta como JSON

// Verificar el token
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($token !== 'libros') {
    header("Location:" . dirname($_SERVER["PHP_SELF"]) . "/includes/404.inc.php");
    exit();
}

require_once('./lib/database.inc.php');
require_once('./lib/verificacion_datos.inc.php');

$data = json_decode(file_get_contents("php://input"), true);



if (!empty($data)) {
    switch ($data['action']) {
        case 'insertarUsuario':
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
                if (sql_valida_usuario_correo($user)) {
                    echo json_encode(['error' => 'El usuario introducido ya existe, introduzca otro por favor.']);
                } elseif (sql_valida_usuario_correo($email)) {
                    echo json_encode(['error' => 'El correo electrónico introducido ya existe, introduzca otro por favor.']);
                } else {
                    $value = insertar_usuario($name, $last_name1, $last_name2, $user, $email, $contrasena, $fechaRegistro, $rol);
                }

                if ($value) {
                    echo json_encode(['success' => 'El usuario ha sido introducido correctamente, introduzca otro por favor.']);
                } else {
                    echo json_encode(['warning' => 'Hubo un problema en la insercción.']);
                }

            } else {
                $errorsArray = formatearErrores($errores_campos);
                echo json_encode(['errors' => $errorsArray, 'error' => ''], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'insertarAutor':
            $errores_campos = formularioDatosAutor($data);

            $array = [
                'nombre' => $data["nombre"],
                'apellido' => $data["apellido"],
                'fecha_nacimiento' => $data["fecha_nacimiento"]
            ];

            $response = proccesaData($errores_campos, $data, 'Autores', $array);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        case 'insertarEditorial':
            $errores_campos = formularioDatosEditorial($data);
            $array = [
                'nombre' => $data["nombre"],
                'fecha_creacion' => $data["fecha_creacion"]
            ];
            $response = proccesaData($errores_campos, $data, 'Editoriales', $array);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;

        default:
            // Acción no reconocida
            echo json_encode(['error' => 'Acción no válida.']);
            break;
        case 'insertarLibro' :
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }


    }


if (isset($_GET['user']) && !empty($_GET['user'])) {
    $data = sql_usuario_id($_GET['user']);

    if (!$data) {
        $data = sql_obtener_usuario($_GET['user']);
    }

    $response = [$data];

    $endpoint = ['nombre', 'apellido', 'apellido2', 'contraseña', 'nombre_usuario', 'correo_electronico', 'fecha_registro', 'rol'];

    $response = array_merge($response, ValuesModify($endpoint, 'Usuarios', 'user', $_GET));

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}



if (isset($_GET['autor']) && !empty($_GET['autor'])) {
    $PARAMETROSAUTOR = "ID, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO";

    if ($_GET["autor"] == "all") {
        $response = sql_get_all($PARAMETROSAUTOR, 'Autores');

    } else {
        $autores = sql_get_row($PARAMETROSAUTOR, 'Autores', $_GET['autor']);
        $response = [$autores];

        $endpoint = ['nombre', 'apellido', 'fecha_nacimiento', 'fecha_creacion'];

        $response = array_merge($response, ValuesModify($endpoint, 'Autores', 'autor', $_GET));
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}


if (isset($_GET['editorial']) && !empty($_GET['editorial'])) {
    define('PARAMETROSEDITORIAL', "ID, NOMBRE, FECHA_CREACION, FECHA_MODIFICACION,ESTADO");

    if ($_GET["editorial"] == "all") {
        $response = sql_get_all(PARAMETROSEDITORIAL, 'Editoriales');

    } else {
        $editoriales = sql_get_row(PARAMETROSEDITORIAL, 'Editoriales', $_GET['editorial']);
        $response[] = $editoriales;

        $endpoint = ['nombre', 'fecha_creacion'];
        $response = array_merge($response, ValuesModify($endpoint, 'Editorial', 'editorial', $_GET));
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>