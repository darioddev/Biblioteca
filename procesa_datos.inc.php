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


// Se comrpueba si el array esta vacio
if (!empty($data)) {
    switch ($data['action']) {
        // Acción para insertar un usuario
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

            // Validar el campo 'contrasena'.

            if (!validaExistenciaVaribale($contrasena) || !validaPassword($contrasena)) {
                $errores_campos['contraseña'] = 'La contraseña no es valida y/o no cumple los requisitos.';
            }

            if (empty($errores_campos)) {
                // Verificar si el usuario o el correo electrónico ya existen en la base de datos.
                if (sql_valida_usuario_correo($user)) {
                    echo json_encode(['error' => 'El usuario introducido ya existe, introduzca otro por favor.']);
                } elseif (sql_valida_usuario_correo($email)) {
                    echo json_encode(['error' => 'El correo electrónico introducido ya existe, introduzca otro por favor.']);
                } else {
                    // De lo contrario
                    // Insertar el usuario en la base de datos.
                    $value = insertar_usuario($name, $last_name1, $last_name2, $user, $email, $contrasena, $fechaRegistro, $rol);
                }

                // Verificar si el usuario se insertó correctamente.
                if ($value) {
                    // Mostrar un mensaje de éxito.
                    echo json_encode(['success' => 'El usuario ha sido introducido correctamente, introduzca otro por favor.']);
                } else {
                    // Mostrar un mensaje de error.
                    echo json_encode(['warning' => 'Hubo un problema en la insercción.']);
                }

                // De lo contrario
                // Mostrar los errores encontrados.
            } else {
                $errorsArray = formatearErrores($errores_campos);
                echo json_encode(['errors' => $errorsArray, 'error' => ''], JSON_UNESCAPED_UNICODE);
            }
            break;

        // Acción para insertar un autor
        case 'insertarAutor':
            // Validar los datos del formulario.
            $errores_campos = formularioDatosAutor($data);

            $array = [
                'nombre' => $data["nombre"],
                'apellido' => $data["apellido"],
                'fecha_nacimiento' => $data["fecha_nacimiento"]
            ];
            // Procesar los datos del formulario.
            $response = procesaData($errores_campos, $data, 'Autores', $array);
            // Imprimir la respuesta en formatom JSON.
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        // Acción para insertar una editorial
        case 'insertarEditorial':
            // Validar los datos del formulario.
            $errores_campos = formularioDatosEditorial($data);
            $array = [
                'nombre' => $data["nombre"],
                'fecha_creacion' => $data["fecha_creacion"]
            ];
            // Procesar los datos del formulario.
            $response = procesaData($errores_campos, $data, 'Editoriales', $array);
            // Imprimir la respuesta en formatom JSON.
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        /* Este insertar libro solo va a veirifcar el parametro de nombre*/
        case 'insertarLibro':
            if (!validaExistenciaVaribale($data['Titulo']) || !validaNombreApellidos($data['Titulo'])) {
                echo json_encode(['error' => 'El titulo esta vacio y/o tiene caracteres especiales', 'errors' => ''], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            }
            break;
        /* Esta accion es la que inserta los datos a la base de datos con la ruta de la imagen
        Esto es debido a que la logica de la imagen se en encuentra en otro fichero "procesa_imagen.inc.php"*/
        case 'insertarLibroSucces':
            $errores_campos = [];
            $array = [
                'titulo' => $data['Titulo'],
                'ID_Autor' => $data['ID_Autor'],
                'ID_Editorial' => $data['ID_Editorial'],
                'Imagen' => $data['nombreArchivo'],
            ];
            $response = procesaData($errores_campos, $data, 'Libros', $array);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;


        case 'verificaEstado':
            // Comprueba el estado de la tabla libros , con la ID obtenida sobre la columna obtenida de $data['ForeignKey'] y que el estado  de la tabla sea activo
            $response['response'] = sql_get_estado('Libros', $data['id'], $data['ForeignKey'], true);

            if (!is_null($response['response'])) {
                // Si la respuesta no es nula , se obtiene el libro por id
                $response['libros'] = sql_get_libro_by_id($data['id'], $data['keyBD'], true);
            }
            // Se obtiene los prestamos por id
            $response['prestamos'] = sql_get_prestamos_by_id($data['id'], 'Libros.ID_Autor', true);

            // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        case 'verificaReactivacion':
            // Comprueba el estado de la tabla Autores con el valor obtenido por parametro sobre la columna ID por defecto 
            $response['autor'] = sql_get_estado('Autores', $data['id']);
            // Comprueba el estado de la tabla Editoriales con el valor obtenido por parametro sobre la columna ID por defecto
            $response['editoriales'] = sql_get_estado('Editoriales', $data['ForeignKey']);

            // Si el estado de la tabla Autores es inactivo o el estado de la tabla Editoriales es inactivo
            if (!$response['autor'] || $response['autor'] == 0) {
                // Se imprime un error
                $response['errorAutor'] = 'El estado de autor es inactivo , para poder activar el libro tendras que activar el autor';
            }
            if ($response['editoriales'] == 0 || !$response['editoriales']) {
                // Se imprime un error
                $response['errorEditorial'] = 'El estado de editorial es inactivo , para poder activar el libro tendras que activar el autor';
            }
            // Se obtiene los prestamos por id_libro y que el estado sea activo
            $response['prestamos'] = sql_get_prestamos_by_id($data['ID_Libro'], 'ID_Libro', true);

            // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
            echo json_encode($response, JSON_UNESCAPED_UNICODE);

            break;
        case 'verificaPrestamo':
            // Comprueba el estado de la tabla prestamos con el valor obtenido ID , sobre la columna obtenida de ForeignKey y que el estado sea activo
            $response['response'] = sql_get_estado('Prestamos', $data['id'], $data['ForeignKey'], true);

            // Si la respuesta no es nula , se obtiene el prestamo por id
            if (!is_null($response['response'])) {
                $response['prestamos'] = sql_get_prestamos_by_id($data['id'], $data['keyBD'], true);
            }
            // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        // Acción para insertar un prestamo
        case 'insertarPrestamo':
            $errores_campos = [];
            $array = [
                'ID_Usuario' => $data['ID_Usuario'],
                'ID_Libro' => $data['ID_Libro'],
                'Fecha_inicio' => date("Y-m-d"),
                'dias_restantes' => $data['dias_restantes']
            ];
            $response = procesaData($errores_campos, $data, 'Prestamos', $array);
            sql_update_estado('Libros', $data["ID_Libro"]);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;

        default:
            // Acción no reconocida
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }


}

// Se comprueba si el parametro user esta vacio

if (isset($_GET['user']) && !empty($_GET['user'])) {
    // Se obtiene un usuario por id
    $data = sql_usuario_id($_GET['user']);

    // Si el resultado data da falso se obtiene el usuario por nombre
    if (!$data) {
        $data = sql_obtener_usuario($_GET['user']);
    }
    // Se guarda el resultado en un array
    $response = [$data];
    // Endpoint parametro que se puede acutalizar mediante get en la url
    // Por ejemplo http://localhost:8080/libros/?user=1&nombre=Juan
    //Esto actualizara el parametro nombre del usuario con id 1

    $endpoint = ['nombre', 'apellido', 'apellido2', 'contraseña', 'nombre_usuario', 'correo_electronico', 'fecha_registro', 'rol'];

    // Se mezcla el array de respuesta con el array de los valores que se pueden modificar
    $response = array_merge($response, ValuesModify($endpoint, 'Usuarios', 'user', $_GET));

    // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Se comprueba si el parametro all esta vacio

if (isset($_GET['all']) && !empty($_GET['all'])) {
    // Se define los parametros que se van a obtener de la base de datos
    $endpoint = 'id ,nombre, apellido, apellido2, contraseña, nombre_usuario, correo_electronico, fecha_registro, rol,estado';
    $endpoint2 = "id,Titulo, ID_Autor, ID_Editorial , fecha_creacion , Imagen";
    if ($_GET["all"] == "all") {
        // Se obtiene todos los autores
        $response['usuarios'] = sql_get_all_activos($endpoint, 'Usuarios');
        // Se obtiene todos los editoriales
        $response['libros'] = sql_get_all_activos($endpoint2, 'Libros');

    }
    // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

}

// Se comprueba si el parametro autor esta vacio
if (isset($_GET['autor']) && !empty($_GET['autor'])) {
    // Se define los parametros que se van a obtener de la base de datos
    $PARAMETROSAUTOR = "ID, NOMBRE, APELLIDO, FECHA_NACIMIENTO, FECHA_CREACION, FECHA_MODIFICACION, ESTADO";

    // Se comprueba si el parametro autor es igual a all
    if ($_GET["autor"] == "all") {
        // Se obtiene todos los autores
        $response = sql_get_all_activos($PARAMETROSAUTOR, 'Autores');

    } else {
        // De lo contrario
        // Se obtiene un autor por id
        $autores = sql_get_row($PARAMETROSAUTOR, 'Autores', $_GET['autor']);
        $response = [$autores];
        // Endpoint parametro que se puede acutalizar mediante get en la url
        // Por ejemplo http://localhost:8080/libros/?autor=1&nombre=Juan
        //Esto actualizara el parametro nombre del autor con id 1

        $endpoint = ['nombre', 'apellido', 'fecha_nacimiento', 'fecha_creacion'];
        // Se mezcla el array de respuesta con el array de los valores que se pueden modificar
        $response = array_merge($response, ValuesModify($endpoint, 'Autores', 'autor', $_GET));
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}


// Se comprueba si el parametro editorial esta vacio
if (isset($_GET['editorial']) && !empty($_GET['editorial'])) {
    // Se define los parametros que se van a obtener de la base de datos
    define('PARAMETROSEDITORIAL', "ID, NOMBRE, FECHA_CREACION, FECHA_MODIFICACION,ESTADO");

    // Se comprueba si el parametro editorial es igual a all
    if ($_GET["editorial"] == "all") {
        // Se obtiene todos los editoriales
        $response = sql_get_all_activos(PARAMETROSEDITORIAL, 'Editoriales');

    } else {
        // De lo contrario
        // Se obtiene un editorial por id
        $editoriales = sql_get_row(PARAMETROSEDITORIAL, 'Editoriales', $_GET['editorial']);
        $response[] = $editoriales;

        //Endpoint parametro que se puede acutalizar mediante get en la url
        // Por ejemplo http://localhost:8080/libros/?editorial=1&nombre=Planeta
        //Esto actualizara el parametro nombre del editorial con id 1

        $endpoint = ['nombre', 'fecha_creacion'];
        $response = array_merge($response, ValuesModify($endpoint, 'Editorial', 'editorial', $_GET));
    }
    // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Se comprueba si el parametro libro esta vacio
if (isset($_GET['libro']) && !empty($_GET['libro'])) {
    // Se comprueba si el parametro libro es igual a all
    if ($_GET["libro"] == "all") {
        // Se obtiene todos los libros
        $response = sql_get_all_libros();
    } else {
        // De lo contrario
        // Se obtiene un libro por id
        $response = sql_get_libro_by_id($_GET["libro"]);
        //Endpoint parametro que se puede acutalizar mediante get en la url
        // Por ejemplo http://localhost:8080/libros/?libro=1&Titulo=El%20principito
        //Esto actualizara el parametro titulo del libro con id 1
        $endpoint = ["Titulo", "ID_Autor", "ID_Editorial", "fecha_creacion", "Imagen"];
        $response = array_merge($response, ValuesModify($endpoint, 'Libros', 'libro', $_GET));
    }

    // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Se comprueba si el parametro prestamos esta vacio
if (isset($_GET['prestamos']) && !empty($_GET['prestamos'])) {

    // Se comprueba si el parametro prestamos es igual a all
    if ($_GET["prestamos"] == "all") {
        // Se obtiene todos los prestamos
        $response = sql_get_all_prestamos();

    } else {
        //De lo contrario
        // Se obtiene un prestamo por id
        $response = sql_get_prestamos_by_id($_GET["prestamos"]);
        //Endpoint parametro que se puede acutalizar mediante get en la url
        // Por ejemplo http://localhost:8080/libros/?prestamos=1&dias_restantes=5
        //Esto actualizara el parametro dias_restantes del prestamo con id 1
        $endpoint = ["dias_restantes"];
        $response = array_merge($response, ValuesModify($endpoint, 'Prestamos', 'prestamos', $_GET));
    }
    // Se imprime la respuesta por JSON que sera capturado con axios como objecto.
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

}

?>