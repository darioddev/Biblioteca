<?php
require_once('./config/bd.config.inc.php');

/**
 * Función para conectarnos a la base de datos.
 * Controlaremos con un booleano true en caso de conectarnos con éxito y false en caso de que no.
 *
 * @return mysqli|false Retorna un objeto mysqli en caso de conexión exitosa o false en caso de falla.
 */
    function sql_conect() {
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
/**
 * Función para validar la existencia de un miembro de la biblioteca mediante su nombre de usuario o correo electrónico.
 * 
 * @param string $usuario El nombre de usuario o correo electrónico a verificar.
 * @return bool Retorna true si no existe nadie con el nombre de usuario o correo electrónico.
 *             Retorna false en caso de error o si el usuario ya existe.
 */
    function sql_valida_usuario_correo(string $usuario) : bool {
        try {
            // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli
            $mysqli = sql_conect();

            //Realizamos la consulta sql
            $sentencia_sql = $mysqli->query("SELECT COUNT(*) FROM Usuarios WHERE Nombre_Usuario = '$usuario' OR Correo_Electronico = '$usuario'");
            
            //La consulta sql solo va a devolver una unica posicion esta sera o 0 o 1 , si es distinto de 0 devolvemos true por que el usuario existiria
            // Si es distinto de 0, devolvemos true porque el usuario existe.
            // De lo contrario, devolvemos false.
            return mysqli_fetch_row($sentencia_sql)[0] != 0;
        
        //En caso de que se produzca un error en la parte de la consulta o la conexion la capturamos.
        } catch (Exception $e) {
            //Mostramos el mensaje de error mediante un echo con un echo
            echo 'Se produjo un error ' . $e->getMessage();
            
            //Retornamos falso.
            return false ;
        } finally {
            // Liberar el conjunto de resultados después de obtener los datos , solamente si hemos podido hacer la consulta
            // Cierra la conexión después de utilizarla
            if($mysqli) $mysqli->close();
        }
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
    function sql_valida_contrasena(string $usuario , string $contrasena) : bool{
        try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli
        $mysqli = sql_conect();

        //Inicializamos una sentencia preparada
        $consulta = $mysqli->stmt_init();
        $consulta-> prepare("SELECT CONTRASEÑA FROM Usuarios WHERE Nombre_Usuario = ? OR Correo_Electronico = ?");
        
        //Se debe pasar dos veces el parametro debido a que en la consulta espera 2 parametros.
        $consulta-> bind_param('ss',$usuario,$usuario);

        //Ejecutamos la sentencia
        $consulta-> execute() ;

        //Obtenemos los resultados de la sentencia SQL , en este caso la contraseña
        $consulta-> bind_result($hash);
        $consulta->fetch();

        //Comprobamos si la contraseña introducida coincide con la contraseña almacenada en la base de datos
        return password_verify($contrasena,$hash);
        }catch(Exception $e) {
            //Mostramos el mensaje de error.
            echo 'Se produjo un error ' . $e->getMessage();

            //Retornamos false
            return false;
        }finally {
        //Cerramos la consulta
        if($consulta) $consulta->close();
        //Cerramos la conexion despues de utilizarla
        if($mysqli) $mysqli->close();
        }
    }


/**
 * Función que valida el inicio de sesión de un usuario.
 * 
 * @param string $usuario El nombre de usuario o correo electrónico proporcionado por el usuario.
 * @param string $contrasena La contraseña proporcionada por el usuario.
 * 
 * @return bool Retorna true si el usuario existe y la contraseña es correcta, de lo contrario, retorna false.
 */
    function sql_valida_login(string $usuario , $contrasena) {
        //Primero se comprueba si el usuario existe , si el usuario existe comprueba si la contraseña es correcta.
        if(sql_valida_usuario_correo($usuario)) {
            if(sql_valida_contrasena($usuario,$contrasena)) return true;
        }
        //De lo contrario si en algun tipo de metodo devuelve false , retornamos falso.
        return false ;
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
function insertar_usuario(string $nombre, string $apellido, string $apellido2, string $nombre_usuario, string $correo_electronico, string $passwor) : bool {
    try {
        // Llamamos a la función sql_conect y almacenamos la conexión en una variable llamada $mysqli.
        $mysqli = sql_conect();
        
        // Inicializamos una sentencia preparada.
        $consulta = $mysqli->stmt_init();
        $consulta->prepare("INSERT INTO Usuarios (Nombre, Apellido, Apellido2, Nombre_Usuario, Correo_Electronico, Contraseña) VALUES (?,?,?,?,?,?)");
        
        // Hasheamos la contraseña introducida.
        $passwor = password_hash($passwor, PASSWORD_BCRYPT);

        $consulta->bind_param('ssssss', $nombre, $apellido, $apellido2, $nombre_usuario, $correo_electronico, $passwor);
        
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
        if ($consulta) $consulta->close();
        
        // Cerramos la conexión después de utilizarla.
        if ($mysqli) $mysqli->close();
    }
}
?>