<?php


/**
 * Actualiza los valores en una tabla específica según los parámetros proporcionados.
 *
 * @param array $endpoint Lista de parámetros a ser modificados.
 * @param string $tabla Nombre de la tabla en la que se realizarán las actualizaciones.
 * @param string $id Identificador único para la condición WHERE en la actualización.
 * @return array Un array que contiene la respuesta de las actualizaciones, incluyendo posibles errores.
 */
function ValuesModify(array $endpoint, $tabla, $id , $get)
{
    $response = []; // Inicializar el array de respuesta.

    // Iterar a través de los parámetros proporcionados.
    foreach ($endpoint as $value) {
        // Verificar si el parámetro está presente en la solicitud ($_GET).
        if (isset($get[$value])) {
            // Procesar la fecha de nacimiento si es necesario.
                // Actualizar el valor en la tabla.
                $sentencia = sql_query_update($tabla, $value, $get[$value], $get[$id]);
            // Verificar si la sentencia de actualización fue exitosa.
            if ($sentencia !== true) {
                // Agregar información sobre el error al array de respuesta.
                $response[] = ['error' => $sentencia];
            }
        }
    }

    // Devolver el array de respuesta después de procesar todos los parámetros.
    return $response;
}

/**
 * Valida los datos del formulario relacionados con la información del usuario.
 *
 * @param array $datosFormulario Datos del formulario.
 * @return array Un array que contiene los errores encontrados durante la validación.
 */
function formularioDatosUsuario($datosFormulario)
{
    $errores_campos = []; // Inicializar el array de errores.

    // Validar el campo 'name'.
    if (!validaExistenciaVaribale($datosFormulario['name']) || !validaNombreApellidos($datosFormulario['name'])) {
        $errores_campos['name'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    }

    // Validar el campo 'last_name1'.
    if (!validaExistenciaVaribale($datosFormulario['last_name1']) || !validaNombreApellidos($datosFormulario['last_name1'])) {
        $errores_campos['last_name1'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    }

    // Validar el campo 'last_name2'.
    if (validaExistenciaVaribale($datosFormulario['last_name2']) && !validaNombreApellidos($datosFormulario['last_name2'])) {
        $errores_campos['last_name2'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    }

    // Validar el campo 'user'.
    if (!validaExistenciaVaribale($datosFormulario['user']) || !validaUsuario($datosFormulario['user'])) {
        $errores_campos['user'] = 'No puede estar vacío y/o no es válido.';
    }

    // Validar el campo 'email'.
    if (!validaExistenciaVaribale($datosFormulario['email']) || !validaEmail($datosFormulario['email'])) {
        $errores_campos['email'] = 'No puede estar vacío y/o debe contener una dirección de correo electrónico válida, por ejemplo, "nombre@ejemplo.com".';
    }

    // Devolver el array de errores después de la validación.
    return $errores_campos;
}

/**
 * Valida los datos del formulario para la creacion de un autor.
 *
 * @param array $datosFormulario Datos del formulario a ser validados.
 * @return array Un array que contiene los errores encontrados durante la validación.
 */
function formularioDatosAutor($datosFormulario)
{
    $errores_campos = []; // Inicializar el array de errores.
    
    // Definir mensajes de error asociados a cada campo.
    $errores_mensajes = [
        'nombre' => 'El campo nombre no puede estar vacío y/o no puede contener caracteres especiales.',
        'apellido' => 'El campo apellido no puede estar vacío y/o no puede contener caracteres especiales.',
        'fecha_nacimiento' => 'El campo fecha de nacimiento no puede estar vacío.',
    ];

    // Validar el campo 'nombre'.
    if (empty($datosFormulario['nombre']) || !validaNombreApellidos($datosFormulario['nombre'])) {
        $errores_campos['nombre'] = $errores_mensajes['nombre'];
    }

    // Validar el campo 'apellido'.
    if (empty($datosFormulario['apellido']) || !validaNombreApellidos($datosFormulario['apellido'])) {
        $errores_campos['apellido'] = $errores_mensajes['apellido'];
    }

    // Validar el campo 'fecha_nacimiento'.
    if (empty($datosFormulario['fecha_nacimiento'])) {
        $errores_campos['fecha_nacimiento'] = $errores_mensajes['fecha_nacimiento'];
    }

    // Devolver el array de errores después de la validación.
    return $errores_campos;
}
/**
 * Valida los datos del formulario para la creación de una editorial.
 *
 * @param array $datosFormulario Array con los datos del formulario.
 * @return array Un array con los errores encontrados durante la validación.
 */
function formularioDatosEditorial($datosFormulario)
{
    $errores_campos = []; // Inicializar el array de errores.
    
    // Definir mensajes de error asociados a cada campo.
    $errores_mensajes = [
        'nombre' => 'El campo nombre no puede estar vacío y/o no puede contener caracteres especiales.',
        'fecha_creacion' => 'El campo fecha de creación no puede estar vacío.',
    ];

    // Validar el campo 'nombre'.
    if (empty($datosFormulario['nombre']) || !validaNombreApellidos($datosFormulario['nombre'])) {
        $errores_campos['nombre'] = $errores_mensajes['nombre'];
    }

    // Validar el campo 'fecha_creacion'.
    if (empty($datosFormulario['fecha_creacion'])) {
        $errores_campos['fecha_creacion'] = $errores_mensajes['fecha_creacion'];
    }

    // Devolver el array de errores después de la validación.
    return $errores_campos;
}

/**
 * Formatea los errores del formulario en un array para una respuesta JSON.
 *
 * @param array $errores_campos Array de errores del formulario.
 * @return array Un array formateado con los errores.
 */
function formatearErrores($errores_campos) {
    $errorsArray = [];

    // Recorre cada error y lo agrega al array formateado.
    foreach ($errores_campos as $key => &$value) {
        $errorsArray[] = [
            'text' => $key,          // La clave del error.
            'message' => strtolower($value), // El mensaje de error en minúsculas.
        ];
    }

    return $errorsArray;
}

function proccesaData($errores_campos,$data,$tabla,$array) {
    if (empty($errores_campos)) {
      
        $value = sql_insertar_dato($tabla, $array);

        if ($value) {
            $response = ['success' => 'Datos del autor insertados correctamente.'];
        } else {
            $response = ['warning' => 'Hubo un problema en la inserción de datos'];
        }

    } else {
        $errorsArray = formatearErrores($errores_campos);
        $response = ['error' => '', 'errors' => $errorsArray];
    }
    return $response;
}


//Funcion que valida que los campos estan declarados y no son vacios
function validaExistenciaVaribale($variable): bool
{
    return isset($variable) && !empty($variable);
}
//Funcion que valida que los nombres y apellidos no contengan ningun caracter extraño
function validaNombreApellidos(string $param): bool
{
    return preg_match('/^[\p{L}A-Za-z0-9\s]+$/u', $param);
}

//Funcion que valida el usuario
//Permite cualquier letra , numeros y solamente guiones y guiones bajos
function validaUsuario(string $user): bool
{
    return preg_match('/^[\p{L}0-9_-]+$/u', $user) && !preg_match('/[\'"]/', $user);
}

//Funcion que valida el correo electronico
function validaEmail(string $email): bool
{
    return preg_match('/^[\p{L}A-Za-z0-9._%+-]*@[\p{L}A-Za-z0-9.-]+\.[\p{L}A-Za-z]{2,}$/u', $email) && !preg_match('/[\'"]/', $email);
}

//Funcion que valida una contraseña
//Obliga a que sea minimom 8 , maximo 16
//Minimo una mayusucula , una minuscula y un número.
//Permite tambien caracteres especiales 
function validaPassword(string $password): bool
{
    return preg_match('/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/u', $password);
}

?>