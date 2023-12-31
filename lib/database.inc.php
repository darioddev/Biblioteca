<?php
require_once('./config/bd.config.inc.php');

/**
 * Función para conectarnos a la base de datos.
 * Controlaremos con un booleano true en caso de conectarnos con éxito y false en caso de que no.
 *
 * @return mysqli|false Retorna un objeto mysqli en caso de conexión exitosa o false en caso de falla.
 */
function sql_conect()
{
    try {
        // Intentamos establecer una conexión a la base de datos utilizando los datos de configuración.
        $mysqli = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Si la conexión no se realizó con éxito, lanzamos una excepción con un mensaje de error.
        if ($mysqli->connect_error) {
            throw new Exception('FALLO EN LA BASE DE DATOS: ' . mysqli_connect_error());
        }
        // Devolvemos un objeto mysqli para indicar que la conexión se ha establecido con éxito.
        return $mysqli;

        // Capturamos cualquier excepción que se haya lanzado al intentar conectarse a la base de datos.
    } catch (Exception $e) {
        // Retornamos un boolean con valor false para indicar que la conexión ha fallado.
        return false;
    }
}
/**
 * Obtiene los datos de un usuario a partir de su nombre de usuario o correo electrónico.
 *
 * @param string $usuario Nombre de usuario o correo electrónico del usuario.
 *
 * @return array Datos del usuario o un array vacío si no se encuentra.
 */
function sql_obtener_usuario(string $usuario)
{
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();

        // Escapamos el valor de $usuario utilizando mysqli_real_escape_string.
        $usuario = mysqli_real_escape_string($mysqli, $usuario);

        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();

        // Preparamos la consulta SQL para obtener los datos del usuario.
        $consulta->prepare("SELECT ID, NOMBRE, APELLIDO, APELLIDO2, NOMBRE_USUARIO, CONTRASEÑA, CORREO_ELECTRONICO, ROL, FECHA_REGISTRO, FECHA_MODIFICACION,ESTADO FROM Usuarios WHERE Nombre_Usuario = ? OR Correo_Electronico = ?");

        // Vinculamos el parámetro a la consulta (se repite dos veces debido a que la consulta espera dos parámetros).
        $consulta->bind_param('ss', $usuario, $usuario);

        // Ejecutamos la consulta.
        $consulta->execute();

        // Obtenemos el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolvemos los datos del usuario en forma de array asociativo.
        return $resultado->fetch_assoc();

    } catch (Exception $e) {
        // En caso de error, devolvemos un array vacío.
        return [];
    } finally {
        // Cerramos la consulta después de su uso.
        if ($consulta) {
            $consulta->close();
        }

        // Cerramos la conexión después de utilizarla.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Obtiene los datos de un usuario a partir de su ID.
 *
 * @param mixed $id ID del usuario.
 *
 * @return array|false Datos del usuario o false si no se encuentra.
 */
function sql_usuario_id($id)
{
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();

        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();

        // Preparamos la consulta SQL para obtener los datos del usuario por su ID.
        $consulta->prepare("SELECT ID, NOMBRE, APELLIDO, APELLIDO2, NOMBRE_USUARIO, CONTRASEÑA, CORREO_ELECTRONICO, ROL, FECHA_REGISTRO, FECHA_MODIFICACION,ESTADO FROM Usuarios WHERE ID = ? ");

        // Vinculamos el parámetro a la consulta.
        $consulta->bind_param('s', $id);

        // Ejecutamos la consulta.
        $consulta->execute();

        // Obtenemos el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolvemos los datos del usuario en forma de array asociativo.
        return $resultado->fetch_assoc();

    } catch (Exception $e) {
        // En caso de error, devolvemos false.
        return false;
    } finally {
        // Cerramos la consulta después de su uso.
        if ($consulta) {
            $consulta->close();
        }

        // Cerramos la conexión después de utilizarla.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Función que permite insertar un nuevo usuario en la base de datos.
 * 
 * @param string $nombre El nombre introducido por el usuario.
 * @param string $apellido El primer apellido introducido por el usuario.
 * @param string $apellido2 El segundo apellido introducido por el usuario.
 * @param string $nombre_usuario El nombre de usuario introducido por el usuario.
 * @param string $correo_electronico El correo electrónico introducido por el usuario.
 * @param string $passwor La contraseña introducida por el usuario.
 * 
 * @return bool Retorna true si la inserción del usuario se realizo correctamente.
 *              Retorna false en caso de que no se haya podido introducir.
 */
function insertar_usuario(string $nombre, string $apellido, string $apellido2, string $nombre_usuario, string $correo_electronico, string $passwor, $fecha = null, $rol = 'LECTOR'): bool
{
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();

        if ($fecha === null || $fecha === '') {
            $fecha = date('Y-m-d');
        }

        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();
        $consulta->prepare("INSERT INTO Usuarios (Nombre, Apellido, Apellido2, Nombre_Usuario, Correo_Electronico, Contraseña , Fecha_Registro , Rol  ) VALUES (?,?,?,?,?,?,?,?)");

        // Hasheamos la contraseña introducida.
        $passwor = password_hash($passwor, PASSWORD_BCRYPT);

        $consulta->bind_param('ssssssss', $nombre, $apellido, $apellido2, $nombre_usuario, $correo_electronico, $passwor, $fecha, $rol);

        // Ejecutamos la consulta.
        $consulta->execute();

        // Retornamos true en caso de éxito.
        return true;

    } catch (Exception $e) {
        echo $e->getMessage();
        // Retornamos false en caso de error.
        return false;
    } finally {
        // Cerramos la consulta después de su uso.
        if ($consulta)
            $consulta->close();

        // Cerramos la conexión después de utilizarla.
        if ($mysqli)
            $mysqli->close();
    }
}


/**
 * Función para validar la existencia de un miembro de la biblioteca mediante su nombre de usuario o correo electrónico.
 * 
 * @param string $usuario El nombre de usuario o correo electrónico a verificar.
 * @return bool Retorna true si no existe nadie con el nombre de usuario o correo electrónico.
 *             Retorna false en caso de error o si el usuario ya existe.
 */
function sql_valida_usuario_correo(string $usuario): bool
{
    return !empty(sql_obtener_usuario($usuario));
}

/**
 * Funcion que valida la contraseña 
 * 
 * @param string $usuario El nombre de usuario o el correo electronico.
 * @param string $contrasena La contraseña que ha introducido el usuario en login.
 * 
 * @return bool retorna true en caso de que la contraseña coindica con la contraseña almacenada en la base de datos.
 *              retorna false en caso de que no coindica y en caso de que haya ocurrido algun tipo de error.
 */
function sql_valida_contrasena(string $usuario, string $contrasena): bool
{
    // Obtenemos los datos del usuario
    $datos = sql_obtener_usuario($usuario);
    // Verificamos si el usuario existe y si la contraseña coincide
    if (!empty($datos))
        return password_verify($contrasena, $datos['CONTRASEÑA']);

    return false;
}

/**
 * Función que valida el inicio de sesión de un usuario.
 * 
 * @param string $usuario El nombre de usuario o correo electrónico proporcionado por el usuario.
 * @param string $contrasena La contraseña proporcionada por el usuario.
 * 
 * @return bool Retorna true si el usuario existe y la contraseña es correcta, de lo contrario, retorna false.
 */
function sql_valida_login(string $usuario, $contrasena): bool
{
    return sql_valida_contrasena($usuario, $contrasena);
}

/**
 * Obtiene el estado de la tabla pasada por parametro 
 *
 * @param string $tabla Nombre de la tabla en la base de datos.
 * @param string $id Valor del identificador (ID) del usuario.
 * @param string $columna Nombre de la columna que contiene el ID en la tabla.
 * @param mixed $and Valor opcional para añadir una condición adicional en la consulta SQL.
 *
 * @return mixed Estado del usuario o null si no se encuentra.
 */
function sql_get_estado(string $tabla, string $id, string $columna = 'ID', $and = null)
{
    $mysqli = null;

    try {
        // Conectarse a la base de datos
        $mysqli = sql_conect();

        // Construir la consulta SQL base
        $query = 'SELECT ESTADO FROM ' . $tabla . ' WHERE ' . $columna . ' = ? ';

        // Agregar condición adicional si se proporciona
        if (!is_null($and)) {
            $query .= 'AND ESTADO = 1';
        }

        // Preparar la consulta
        $consulta = $mysqli->prepare($query);

        // Vincular el parámetro y ejecutar la consulta
        $consulta->bind_param('s', $id);
        $consulta->execute();

        // Obtener el resultado de la consulta
        $resultado = $consulta->get_result();

        // Obtener el valor del estado si existe
        $fila = $resultado->fetch_assoc();
        $estado = isset($fila['ESTADO']) ? $fila['ESTADO'] : null;

        // Cerrar la conexión a la base de datos
        $consulta->close();

        return $estado;

    } catch (Exception $e) {
        // Retornar falso en caso de error
        return false;
    } finally {
        // Cerrar la conexión si aún está abierta
        if ($mysqli !== null) {
            $mysqli->close();
        }
    }
}

/**
 * Obtiene todos los usuarios de la base de datos con opciones de filtrado y paginación.
 *
 * @param int|null $offset Desplazamiento para la paginación (valor predeterminado: 0).
 * @param int|null $count Cantidad de usuarios a recuperar (valor predeterminado: null, lo que significa todos los usuarios).
 * @param string|null $columna Nombre de la columna para ordenar los resultados (valor predeterminado: null, sin ordenar).
 * @param string $ordenTipo Tipo de orden (ASC o DESC) para ordenar los resultados (valor predeterminado: 'ASC').
 *
 * @return array Arreglo asociativo con la información de los usuarios o un arreglo vacío si no hay resultados.
 */
function sql_get_all_usuarios($offset = 0, $count = null, $columna = null, $ordenTipo = 'ASC')
{
    // Inicializar $resultado fuera del bloque try.
    $resultado = null;

    try {
        // Llamar a la función sql_conect y almacenar la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();

        // Inicializar una declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL base.
        $query = "SELECT ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO FROM Usuarios";

        // Agregar la cláusula ORDER BY si se proporciona $columna.
        if ($columna !== null) {
            $query .= " ORDER BY " . $columna . " " . strtoupper($ordenTipo);
        }

        // Agregar la cláusula LIMIT si se proporciona $count.
        if ($count !== null) {
            $query .= " LIMIT ?, ?";
        }

        // Preparar la consulta.
        $consulta = $mysqli->prepare($query);

        // Vincular parámetros si se proporciona $count.
        if ($count !== null) {
            $consulta->bind_param('ii', $offset, $count);
        }

        // Ejecutar la declaración preparada.
        $consulta->execute();

        // Obtener el conjunto de resultados.
        $resultado = $consulta->get_result();

        // Verificar si la consulta fue exitosa y devolver el resultado.
        if ($resultado) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "La ejecución de la consulta falló.";
            return [];
        }

    } catch (Exception $e) {
        return [];
    } finally {
        // Liberar el conjunto de resultados si no es nulo.
        if ($resultado) {
            mysqli_free_result($resultado);
        }

        // Cerrar la conexión a la base de datos después de usarla.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}

/**
 * Obtiene una fila específica de una o varias columnas y tablas en la base de datos.
 *
 * @param string $colums Columnas que se deben seleccionar (separadas por comas).
 * @param string $tablas Tablas de las que se deben seleccionar los datos.
 * @param string $id Valor del identificador (ID) para filtrar los resultados.
 *
 * @return mixed Array asociativo con los datos de la fila o false si hay un error.
 */
function sql_get_row($colums, $tablas, $id)
{
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();

        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();

        // Preparamos la consulta SQL para obtener los datos del usuario por su ID.
        $consulta->prepare("SELECT " . $colums . " FROM " . $tablas . " WHERE ID = ? ");

        // Vinculamos el parámetro a la consulta.
        $consulta->bind_param('s', $id);

        // Ejecutamos la consulta.
        $consulta->execute();

        // Obtenemos el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolvemos los datos del usuario en forma de array asociativo.
        return $resultado->fetch_assoc();

    } catch (Exception $e) {
        // En caso de error, devolvemos false.
        return false;
    } finally {
        // Cerramos la consulta después de su uso.
        if ($consulta) {
            $consulta->close();
        }

        // Cerramos la conexión después de utilizarla.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Ejecuta una consulta SELECT en la base de datos para recuperar datos de una tabla.
 *
 * @param string $columnas    Columnas que se seleccionarán en la consulta.
 * @param string $tabla       Nombre de la tabla de la base de datos.
 * @param int|null $offset    Desplazamiento para la cláusula LIMIT (opcional).
 * @param int|null $count     Número máximo de filas para recuperar (opcional).
 * @param string|null $columna Columna por la cual ordenar los resultados (opcional).
 * @param string $ordenTipo   Tipo de ordenación: 'ASC' (ascendente) o 'DESC' (descendente).
 *
 * @return array              Un array asociativo con los resultados de la consulta.
 *                            En caso de error, devuelve un array vacío.
 */
function sql_get_all($columnas, $tabla, $offset = null, $count = null, $columna = null, $ordenTipo = 'ASC'): array
{
    try {
        $mysqli = sql_conect();
        $consulta = $mysqli->stmt_init();
        $query = "SELECT " . $columnas . " FROM " . $tabla;

        // Agregar la cláusula ORDER BY si columna no es nulo
        if ($columna !== null) {
            $query .= " ORDER BY " . $columna . " " . strtoupper($ordenTipo);
        }

        // Agregar la cláusula LIMIT si $count y $offset  no son nulos
        if ($count !== null && $offset !== null) {
            $query .= " LIMIT ?, ?";
        }

        if ($consulta->prepare($query)) {
            // Pasamos parámetros para LIMIT
            if ($count !== null && $offset !== null) {
                $consulta->bind_param("ii", $offset, $count);
            }

            $consulta->execute();
            $resultado = $consulta->get_result();

            // Verificar si la ejecución fue exitosa
            if (!$resultado) {
                throw new Exception("Error en la ejecución de la consulta: " . $consulta->error);
            }

            // Devolver resultados en un array asociativo
            return $resultado->fetch_all(MYSQLI_ASSOC);

        } else {
            // En caso de error en caso de la consulta preparada lanza un error
            throw new Exception("Error en la preparación de la consulta de la base de datos: " . $mysqli->error);
        }
    } catch (Exception $e) {
        return [];
    } finally {
        // Liberar resultados y cerrar conexión, si están definidos
        if (isset($resultado)) {
            mysqli_free_result($resultado);
        }
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }

    return [];
}


/**
 * Ejecuta una consulta SELECT en la base de datos para buscar registros que coincidan con un valor específico.
 *
 * @param string $valores    Columnas que se seleccionarán en la consulta.
 * @param string $tabla      Nombre de la tabla de la base de datos.
 * @param string $clave      Columna en la que se realizará la búsqueda.
 * @param string $valor      Valor a buscar en la columna especificada.
 * @param int|null $offset   Desplazamiento para la cláusula LIMIT (opcional).
 * @param int|null $count    Número máximo de filas para recuperar (opcional).
 *
 * @return array            Un array asociativo con los resultados de la búsqueda.
 *                          En caso de error o falta de resultados, devuelve un array vacío.
 */
function sql_search($valores, $tabla, $clave, $valor, $offset = null, $count = null): array
{
    $resultado = false; // Inicializa $resultado
    $query = "SELECT " . $valores . " FROM " . $tabla . " WHERE " . $clave . " LIKE ? ";

    try {
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        if ($count !== null && $offset !== null) {
            $query .= " LIMIT ?,?";
        }

        if ($consulta->prepare($query)) {
            if ($count !== null && $offset !== null) {
                $consulta->bind_param("sii", $valor, $offset, $count);
            } else {
                $consulta->bind_param("s", $valor);
            }

            $consulta->execute();
            $resultado = $consulta->get_result();

            if (!$resultado) {
                throw new Exception("Error en la ejecución de la consulta: " . $consulta->error);
            }

            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception("Error en la preparación de la consulta de la base de datos: " . $mysqli->error);
        }
    } catch (Exception $e) {
        return []; // Devuelve un array vacío en caso de error
    } finally {
        if ($resultado) {
            mysqli_free_result($resultado);
        }
        if ($mysqli) {
            $mysqli->close();
        }
    }
    return [];
}


/**
 * Realiza una consulta SQL para actualizar un valor en una columna específica de una tabla.
 *
 * @param string $tabla Nombre de la tabla en la base de datos.
 * @param string $column Nombre de la columna que se va a actualizar.
 * @param mixed $valor Nuevo valor que se va a asignar a la columna.
 * @param string $id Valor del identificador (ID) para filtrar.
 *
 * @return bool True si la actualización fue exitosa, false si hay un error.
 */
function sql_query_update($tabla, $column, $valor, $id): bool
{
    try {
        // Conectarse a la base de datos
        $mysqli = sql_conect();
        $consulta = $mysqli->stmt_init();

        // Validar el valor según la columna especificada
        switch ($column) {
            case "nombre":
            case "apellido":
                // Validar existencia y formato del nombre o apellido
                if (!validaExistenciaVaribale($valor) || !validaNombreApellidos($valor)) {
                    throw new Exception("El $column no puede estar vacío y/o no puede contener caracteres especiales.");
                }
                break;

            case "apellido2":
                // Validar formato del segundo apellido si no está vacío
                if (!empty($valor) && !validaNombreApellidos($valor)) {
                    throw new Exception("El $column no puede contener caracteres especiales.");
                }
                break;

            case "nombre_usuario":
                // Validar existencia y formato del nombre de usuario
                if (!validaExistenciaVaribale($valor) || !validaUsuario($valor)) {
                    throw new Exception("El $column no puede estar vacío y/o no es válido.");
                }
                // Verificar si el nombre de usuario ya existe en la base de datos
                if (sql_valida_usuario_correo($valor)) {
                    throw new Exception("El $column introducido ya existe.");
                }
                break;

            case "correo_electronico":
                // Validar existencia y formato del correo electrónico
                if (!validaExistenciaVaribale($valor) || (!validaEmail($valor) && trim($valor) !== '')) {
                    throw new Exception("El $column no puede estar vacío y/o debe contener una dirección de correo electrónico válida.");
                }
                // Verificar si el correo electrónico ya existe en la base de datos
                if (sql_valida_usuario_correo($valor)) {
                    throw new Exception("El $column introducido ya existe.");
                }
                break;

            case "fecha_nacimiento":
            case "fecha_creacion":
                // Validar que la fecha no esté vacía
                if (empty($valor)) {
                    throw new Exception("La $column no puede estar vacía.");
                }
                break;
            case "dias_restantes":
                break;

            default:
                // Validar que el valor no esté vacío para otras columnas
                if (empty($valor) || $valor == '') {
                    throw new Exception("No pueden haber campos vacíos.");
                }
                break;
        }

        // Preparar la consulta SQL para actualizar la columna específica
        $consulta->prepare("UPDATE $tabla SET $column = ? WHERE ID = ?");
        $consulta->bind_param("ss", $valor, $id);

        // Ejecutar la consulta
        $success = $consulta->execute();

        // Verificar si la actualización fue exitosa y lanzar una excepción si no lo fue
        if (!$success) {
            throw new Exception($consulta->error);
        }

        return true;

    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    } finally {
        // Cerrar la conexión después de su uso
        if ($mysqli) {
            $mysqli->close();
        }
    }
    return false;
}


/**
 * Actualiza el estado de un usuario en la base de datos.
 *
 * @param string $tabla Nombre de la tabla la cual se ha obtener los datos.
 * @param int $id Identificador del usuario.
 *
 * @return array|mysqli|bool Resultado de la consulta.
 */
function sql_update_estado(string $tabla, int $id): array|mysqli|bool
{
    try {
        // Conectar a la base de datos
        $mysqli = sql_conect();

        // Inicializar la consulta preparada
        $consulta = $mysqli->stmt_init();

        // Obtener el estado actual y determinar el nuevo estado (activo o inactivo)
        $state = sql_get_estado($tabla, $id) ? 0 : 1;

        // Preparar la consulta SQL para actualizar el estado del usuario
        $consulta->prepare('UPDATE ' . $tabla . ' SET ESTADO = ? WHERE ID = ?');

        // Vincular los parámetros a la consulta
        $consulta->bind_param('ii', $state, $id);

        // Ejecutar la consulta
        $consulta->execute();

        // Devolver el resultado de la consulta
        return $consulta->get_result();

    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    } finally {
        // Cerrar la conexión a la base de datos después de usarla
        if ($mysqli) {
            $mysqli->close();
        }
    }
}

/**
 * Función que cuenta el número total de registros en una tabla.
 * 
 * @param string $tabla El nombre de la tabla.
 * 
 * @return int Retorna el número total de registros en la tabla.
 */
function sql_count_tabla($tabla)
{
    try {
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        $consulta->prepare('SELECT COUNT(*) as total FROM ' . $tabla);

        $consulta->execute();

        $resultado = $consulta->get_result();

        $fila = $resultado->fetch_assoc();

        return (int) $fila['total'];

    } catch (Exception $e) {
        return 0;
    } finally {
        if ($resultado) {
            mysqli_free_result($resultado);
        }

        if ($consulta) {
            $consulta->close();
        }

        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Realiza una consulta SQL para insertar un nuevo registro en una tabla con los datos proporcionados.
 *
 * @param string $tabla Nombre de la tabla en la base de datos.
 * @param array $datos Array asociativo con los datos a insertar (columna => valor).
 *
 * @return bool True si la inserción fue exitosa, false si hay un error.
 */
function sql_insertar_dato($tabla, $datos)
{
    try {
        // Conectarse a la base de datos
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        // Lista de columnas separadas por coma
        $columnas = implode(', ', array_keys($datos));

        // Lista de placeholders para los valores
        $placeholders = implode(', ', array_fill(0, count($datos), '?'));

        // Construir la consulta SQL
        $sql = "INSERT INTO " . $tabla . "(" . $columnas . ") VALUES (" . $placeholders . ")";

        // Preparar la consulta
        $consulta->prepare($sql);

        // Obtener los valores de los datos
        $valores = array_values($datos);

        // Unir el tipo de datos de los valores (s para cadena, i para entero, etc.)
        $tipos = str_repeat('s', count($valores));

        // Unir los valores como referencia
        $consulta->bind_param($tipos, ...$valores);

        // Ejecutar la consulta
        $consulta->execute();

        // Retornar true en caso de éxito
        return true;
    } catch (Exception $e) {
        // Capturar y mostrar cualquier excepción
        echo $e->getMessage();
        return false;
    } finally {
        // Cerrar la conexión después de utilizarla
        if ($mysqli) {
            $mysqli->close();
        }

        // Cerrar la consulta después de su uso
        if ($consulta) {
            $consulta->close();
        }
    }
}



/**
 * Obtiene información de libros de la base de datos.
 *
 * @param int|null $offset - Número de registros para omitir (para paginación).
 * @param int|null $count - Número máximo de registros a devolver (para paginación).
 * @param string|null $tipo - Tipo de libro a filtrar.
 * @param string|null $search - Término de búsqueda para filtrar por título.
 * @return array - Array asociativo con la información de los libros.
 */
function sql_get_all_libros($offset = null, $count = null, $tipo = null, $search = null, $columna = null, $ordenTipo = 'ASC', $state = null)
{
    try {
        // Establecer conexión a la base de datos.
        $mysqli = sql_conect();

        // Inicializar una nueva declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL básica.
        $query = "SELECT
            L.ID,
            L.ID_Autor,
            L.ID_Editorial,
            L.Imagen,
            L.Titulo,
            A.Nombre AS NombreAutor,
            E.Nombre AS NombreEditorial,
            L.fecha_creacion,
            L.Estado
        FROM
            Libros L
        JOIN
            Autores A ON L.ID_Autor = A.ID
        JOIN
            Editoriales E ON L.ID_Editorial = E.ID";

        if (!is_null($state)) {
            $query .= " WHERE L.Estado = 1";
        }
        if (!is_null($state) && !is_null($search) && !is_null($tipo)) {
            $query .= " AND ";
        }

        // Agregar condiciones para WHERE si se proporciona el término de búsqueda.
        if (!is_null($search) && !is_null($tipo)) {
            $query .= " WHERE $tipo LIKE ?";
        }

        // Agregar la cláusula ORDER BY si se proporciona $ordenarPor
        if (!is_null($columna)) {
            $query .= " ORDER BY " . $columna . " " . strtoupper($ordenTipo);
        }

        // Agregar condiciones para LIMIT si se proporcionan offset y count.
        if (!is_null($offset) && !is_null($count)) {
            $query .= " LIMIT ?, ?";
        }


        // Preparar la consulta SQL.
        $consulta->prepare($query);

        if (!is_null($search) && !is_null($offset) && !is_null($count)) {
            $searchParam = "%" . $search . "%";
            $consulta->bind_param("sii", $searchParam, $offset, $count);
        } elseif (!is_null($search)) {
            $searchParam = "%" . $search . "%";
            $consulta->bind_param("s", $searchParam);
        } elseif (!is_null($offset) && !is_null($count)) {
            $consulta->bind_param("ii", $offset, $count);
        }


        // Ejecutar la consulta.
        $consulta->execute();

        // Obtener el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolver los resultados como un array asociativo.
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        // Manejar excepciones devolviendo un array vacío en caso de error.
        return [];
    } finally {

        // Cerrar la conexión a la base de datos si está abierta.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}

/**
 * Obtiene información detallada de un libro a partir de su ID.
 *
 * @param int $id ID del libro que se desea obtener.
 * @param string $libro_id Nombre de la columna de ID en la tabla de Libros.
 * @param int|null $state Estado del libro (opcional, por defecto es null).
 *
 * @return array Array asociativo con la información detallada del libro o un array vacío si no se encuentra.
 */
function sql_get_libro_by_id($id, $libro_id = "L.ID", $state = null)
{
    try {
        // Establecer conexión a la base de datos.
        $mysqli = sql_conect();

        // Inicializar una nueva declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL básica.
        $query = "SELECT
            L.ID,
            L.Imagen,
            L.Titulo,
            L.ID_Autor,
            L.ID_Editorial,
            A.Nombre AS NombreAutor,
            E.Nombre AS NombreEditorial,
            L.fecha_creacion,
            L.Estado,
            L.Fecha_modificacion
        FROM
            Libros L
        JOIN
            Autores A ON L.ID_Autor = A.ID
        JOIN
            Editoriales E ON L.ID_Editorial = E.ID
        WHERE
            " . $libro_id . " = ?";

        // Agregar condición para el estado si se proporciona.
        if (!is_null($state)) {
            $query .= " AND L.Estado = 1 ";
        }

        // Preparar la consulta SQL.
        $consulta->prepare($query);

        // Vincular el parámetro ID.
        $consulta->bind_param("i", $id);

        // Ejecutar la consulta.
        $consulta->execute();

        // Obtener el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolver el resultado como un array asociativo.
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return [];
    } finally {
        // Cerrar la conexión a la base de datos si está abierta.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Verifica si un libro con el título dado ya existe en la base de datos.
 *
 * @param string $titulo Título del libro que se desea verificar.
 *
 * @return bool True si el libro ya existe, false si no existe o hay un error.
 */
function sql_valida_libro($titulo)
{
    try {
        $mysqli = sql_conect();

        // Escapar la cadena del título
        $titulo = mysqli_real_escape_string($mysqli, $titulo);

        // Preparar la consulta SQL
        $consulta = $mysqli->prepare("SELECT COUNT(*) FROM Libros WHERE Titulo = ?");

        // Vincular parámetros
        $consulta->bind_param('s', $titulo);

        // Ejecutar la consulta
        $consulta->execute();

        // Vincular el resultado a una variable
        $consulta->bind_result($resultado);

        // Obtener el valor
        $consulta->fetch();

        // Cerrar la consulta
        $consulta->close();

        // Devolver true si el libro existe, false si no existe
        return $resultado > 0;
    } catch (Exception $e) {
        // Manejar la excepción según tus necesidades
        // Puedes registrar el error, lanzar una excepción personalizada, etc.
        error_log($e->getMessage());
        return false; // Devuelve false en caso de error
    } finally {
        // Cerrar la conexión
        $mysqli->close();
    }
}
;

/**
 * Obtiene todos los registros activos de una tabla específica.
 *
 * @param string $columnas Columnas que se desean seleccionar en la consulta.
 * @param string $tabla Nombre de la tabla de la cual se obtendrán los registros.
 *
 * @return array Array asociativo con los resultados de la consulta o un array vacío si no hay resultados.
 */
function sql_get_all_activos($columnas, $tabla, $estado = "ESTADO")
{
    try {
        $mysqli = sql_conect();

        // Inicializar una nueva declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL para seleccionar registros activos.
        $query = "SELECT " . $columnas . " FROM " . $tabla . " WHERE " . $estado . " = 1 ";

        // Preparar la consulta SQL.
        $consulta->prepare($query);

        // Ejecutar la consulta.
        $consulta->execute();

        // Obtener el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Verificar si la ejecución fue exitosa.
        if (!$resultado) {
            throw new Exception("Error en la ejecución de la consulta: " . $consulta->error);
        }

        // Devolver resultados en un array asociativo.
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    } finally {
        // Liberar resultados y cerrar conexión, si están definidos.
        if (isset($resultado)) {
            mysqli_free_result($resultado);
        }
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }

    // Devolver un array vacío por defecto.
    return [];
}


/**
 * Obtiene información de libros de la base de datos.
 *
 * @param int|null $offset - Número de registros para omitir (para paginación).
 * @param int|null $count - Número máximo de registros a devolver (para paginación).
 * @param string|null $tipo - Tipo de libro a filtrar.
 * @param string|null $search - Término de búsqueda para filtrar por título.
 * @return array - Array asociativo con la información de los libros.
 */
function sql_get_all_prestamos($offset = null, $count = null, $tipo = null, $search = null, $columna = null, $ordenTipo = 'ASC', $state = null)
{
    try {
        // Establecer conexión a la base de datos.
        $mysqli = sql_conect();

        // Inicializar una nueva declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL básica.
        $query = "SELECT
        Prestamos.ID ,
        Libros.Imagen AS ImagenLibro,
        Usuarios.nombre AS NombreUsuario,
        Usuarios.nombre_usuario As UsuarioNombreUsuario,
        Usuarios.Correo_Electronico AS CorreoElectronico,
        Libros.titulo AS NombreLibro,
        Prestamos.Fecha_inicio,
        Prestamos.Fecha_devolucion,
        Prestamos.dias_restantes,
        Prestamos.estado AS EstadoPrestamo
    FROM Prestamos
    JOIN Usuarios ON Prestamos.ID_Usuario = Usuarios.ID
    JOIN Libros ON Prestamos.ID_Libro = Libros.ID
    ";
        if (!is_null($state)) {
            $query .= " WHERE Usuarios.ID = ?";
        }

        if (!is_null($state) && !is_null($search) && !is_null($tipo)) {
            $query .= " AND ";
        }

        // Agregar condiciones para WHERE si se proporciona el término de búsqueda.
        if (!is_null($search) && !is_null($tipo)) {
            $query .= " WHERE $tipo LIKE ?";
        }

        // Agregar la cláusula ORDER BY si se proporciona $ordenarPor
        if (!is_null($columna)) {
            $query .= " ORDER BY " . $columna . " " . strtoupper($ordenTipo);
        }

        // Agregar condiciones para LIMIT si se proporcionan offset y count.
        if (!is_null($offset) && !is_null($count)) {
            $query .= " LIMIT ?, ?";
        }


        // Preparar la consulta SQL.
        $consulta->prepare($query);

        // Bindear los parámetros según las condiciones.
        $bindParams = ""; // Cadena para almacenar los tipos de parámetros.
        $bindValues = []; // Array para almacenar los valores de parámetros.

        if (!is_null($state)) {
            $bindParams .= "i";
            $bindValues[] = $state;
        }

        if (!is_null($search)) {
            $searchParam = "%" . $search . "%";
            $bindParams .= "s";
            $bindValues[] = $searchParam;
        }

        if (!is_null($offset) && !is_null($count)) {
            $bindParams .= "ii";
            $bindValues[] = $offset;
            $bindValues[] = $count;
        }

        // Hacer la llamada a bind_param.
        if (!empty($bindParams) && !empty($bindValues)) {
            $consulta->bind_param($bindParams, ...$bindValues);
        }

        // Ejecutar la consulta.
        $consulta->execute();

        // Obtener el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolver los resultados como un array asociativo.
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        // Manejar excepciones devolviendo un array vacío en caso de error.
        return [];
    } finally {

        // Cerrar la conexión a la base de datos si está abierta.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}


/**
 * Obtiene información de préstamos por su ID.
 *
 * @param int $id ID del préstamo que se desea obtener.
 * @param string $prestamos_id Nombre de la columna ID en la tabla de préstamos.
 * @param mixed $state Estado del préstamo (opcional).
 *
 * @return array Array asociativo con la información del préstamo o un array vacío si no hay resultados.
 */
function sql_get_prestamos_by_id($id, $prestamos_id = "Prestamos.ID", $state = null)
{
    try {
        // Establecer conexión a la base de datos.
        $mysqli = sql_conect();

        // Inicializar una nueva declaración preparada.
        $consulta = $mysqli->stmt_init();

        // Construir la consulta SQL básica.
        $query = "SELECT
            Prestamos.ID,
            Usuarios.nombre AS NombreUsuario,
            Usuarios.nombre_usuario AS UsuarioNombreUsuario,
            Usuarios.Correo_Electronico AS CorreoElectronico,
            Libros.titulo AS NombreLibro,
            Prestamos.Fecha_inicio,
            Prestamos.Fecha_devolucion,
            Prestamos.dias_restantes,
            Prestamos.estado
        FROM Prestamos
        JOIN Usuarios ON Prestamos.ID_Usuario = Usuarios.ID
        JOIN Libros ON Prestamos.ID_Libro = Libros.ID
        WHERE
            " . $prestamos_id . " = ?";

        // Agregar condición para el estado si se proporciona.
        if (!is_null($state)) {
            $query .= " AND Prestamos.Estado = 1 ";
        }

        // Preparar la consulta SQL.
        $consulta->prepare($query);

        // Vincular el parámetro ID.
        $consulta->bind_param("i", $id);

        // Ejecutar la consulta.
        $consulta->execute();

        // Obtener el resultado de la consulta.
        $resultado = $consulta->get_result();

        // Devolver el resultado como un array asociativo.
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        // Manejar excepciones devolviendo un array vacío en caso de error.
        error_log($e->getMessage());
        return [];
    } finally {
        // Cerrar la conexión a la base de datos si está abierta.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}

/**
 * Funcion que obtiener de orden ascedente el numero de libros mas leidos
 */
function get_ranking_mas_leidos()
{
    try {
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        $consulta->prepare("SELECT
        Libros.ID,
        Libros.Titulo,
        Libros.Imagen,
        COUNT(Prestamos.ID_Libro) AS Prestamos,
        Autores.Nombre AS NombreAutor,
        Autores.Apellido AS ApellidoAutor
    FROM
        Prestamos
    JOIN
        Libros ON Prestamos.ID_Libro = Libros.ID
    JOIN
        Autores ON Libros.ID_Autor = Autores.ID
    GROUP BY
        Libros.ID
    ORDER BY
        Prestamos DESC");

        $consulta->execute();

        $resultado = $consulta->get_result();

        return $resultado->fetch_all(MYSQLI_ASSOC);

    } catch (error) {
        return [];

    } finally {
        if ($resultado) {
            mysqli_free_result($resultado);
        }
        if ($consulta) {
            $consulta->close();
        }

        if ($mysqli) {
            $mysqli->close();
        }


    }
}

/**
 * Funcion que obtiene el numero de usuarios registrados este ultimo mes 
 */
function get_count_mes($tabla = 'Usuarios', $campo = 'fecha_registro')
{
    try {

        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        $consulta->prepare("SELECT COUNT(*) AS Total 
        FROM " . $tabla . "
        WHERE MONTH(" . $campo . ") = MONTH(CURDATE()) AND YEAR(" . $campo . ") = YEAR(CURDATE())");

        $consulta->execute();

        $resultado = $consulta->get_result();

        $fila = $resultado->fetch_assoc();

        return (int) $fila['Total'];
    } catch (error) {
        return [];
    } finally {
        if ($resultado) {
            mysqli_free_result($resultado);
        }
        if ($consulta) {
            $consulta->close();
        }

        if ($mysqli) {
            $mysqli->close();
        }
    }
}
/**
 * Funcion que devuelve el numero de usuarios inactivos
 */
function get_usuarios_inactivos()
{
    try {
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        $consulta->prepare("SELECT COUNT(*) AS Total FROM Usuarios WHERE Estado = 0");

        $consulta->execute();

        $resultado = $consulta->get_result();

        $fila = $resultado->fetch_assoc();

        return (int) $fila['Total'];
    } catch (error) {
        return [];
    } finally {
        if ($resultado) {
            mysqli_free_result($resultado);
        }
        if ($consulta) {
            $consulta->close();
        }

        if ($mysqli) {
            $mysqli->close();
        }
    }
}

?>