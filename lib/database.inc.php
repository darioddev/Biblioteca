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

        // Imprimimos un mensaje informativo del error.
        echo 'Error: ' . $e->getMessage();

        // Retornamos un boolean con valor false para indicar que la conexión ha fallado.
        return false;
    }
}

function sql_obtener_usuario(string $usuario)
{
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();
        // Escapamos el valor de $usuario utilizando mysqli_real_escape_string.
        $usuario = mysqli_real_escape_string($mysqli, $usuario);

        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();

        $consulta->prepare("SELECT ID,NOMBRE,APELLIDO,APELLIDO2,NOMBRE_USUARIO,CONTRASEÑA,CORREO_ELECTRONICO,ROL,FECHA_REGISTRO,ESTADO FROM Usuarios WHERE Nombre_Usuario = ? OR Correo_Electronico = ?");

        //Se debe pasar dos veces el parametro debido a que en la consulta espera 2 parametros.
        $consulta->bind_param('ss', $usuario, $usuario);

        $consulta->execute();

        $resultado = $consulta->get_result();

        return $resultado->fetch_assoc();

    } catch (Exception $e) {
        echo 'Exception ' . $e;
        return [];
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

        if ($fecha === null) {
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
        // Mostramos un mensaje de error con echo.
        echo 'Se produjo un error ' . $e->getMessage();

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
    $datos = sql_obtener_usuario($usuario);
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
 * 
 */
function sql_get_rol(string $usuario)
{
    $data = sql_obtener_usuario($usuario);
    return $data;
}

function sql_get_all_usuarios(int $ini, int $limit)
{
    try {
        // Call the sql_connect function and store the connection in a variable called $mysqli.
        $mysqli = sql_conect();

        // Initialize a prepared statement.
        $consulta = $mysqli->stmt_init();

        if ($consulta->prepare("SELECT ID, NOMBRE, NOMBRE_USUARIO, CORREO_ELECTRONICO, FECHA_REGISTRO, ROL, ESTADO FROM Usuarios LIMIT ?,? ")) {
            // Bind the parameter as an integer.
            $consulta->bind_param("ii", $ini, $limit);

            // Execute the prepared statement.
            $consulta->execute();

            // Get the result set.
            $resultado = $consulta->get_result();

            // Check if the query was successful and return the result.
            if ($resultado) {
                return $resultado->fetch_all(MYSQLI_ASSOC);
            } else {
                echo "Query execution failed.";
                return [];
            }
        } else {
            echo "Prepared statement initialization failed.";
            return [];
        }
    } catch (Exception $e) {
        echo '' . $e->getMessage();
        return [];
    } finally {
        // Close the prepared statement after use.
        if ($consulta) {
            $consulta->close();
        }

        // Close the database connection after using it.
        if ($mysqli) {
            $mysqli->close();
        }
    }
}

/**
 * Update
 */

function sql_update_row(string $correo)
{
    try {
        $mysqli = sql_conect();

        $consulta = $mysqli->stmt_init();

        $bloque = "ESTADO";

        $state = sql_get_rol($correo)[$bloque] ? 0 : 1;

        echo $state."";

        $consulta->prepare('UPDATE Usuarios SET ' . $bloque . ' = ? WHERE CORREO_ELECTRONICO = ?');

        $consulta->bind_param('is', $state, $correo);

        $consulta->execute();

        return  $consulta->get_result();

    } catch (Exception $e) {
        echo $e->getMessage();

    } finally {
        if ($consulta)
            $consulta->close();
        // Close the database connection after using it.
        if ($mysqli) {
            $mysqli->close();
        }

    }
}

