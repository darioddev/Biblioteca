<?php
    //Funcion para connectarnos a la base de datos
    //Que controlaremos con boolean true , en caso de connectarnos false en caso de que no.
    function sql_conect() {
        //Variables para la conexion con la base de datos
        $servername = "localhost";
        $username = "mibiblioteca";
        $password = "admin";
        $database = "biblioteca";
        try {
            $mysqli = mysqli_connect($servername, $username, $password, $database);
            
            //Si la conexion no se realizado , lo capturamos entonces
            //Verificamos si no se ha hecho , y lanzaremos un throw para despues en el catch capturarlo
            if (!$mysqli) throw new Exception("FALLO EN LA BASE DE DATOS: " . mysqli_connect_error());
    
            //En caso de que la variable $mysqli , no fuese falsa esque la conexion se ha realizado existosamente
            //Devolveremos true 
            return $mysqli; 

            //Capturamos el error con catch
        } catch (Exception $e) {
            //Lanzamos un mensaje informativo del error
            echo "Error: " . $e->getMessage();
            //Retornamos un boolean con valor false 
            return false; 
        }
    }
    
    function sql_query(string $consulta_sql) {
        // Llamamos a la funcion sql_conect y almacenamos la conexion en una variable llamada $mysqli
        $mysqli = sql_conect();
        // Si la conexion se establece con exito, el codigo continua ejecutandose; 
        //de lo contrario, no se hara nada y se mostrara un mensaje de error por parte de la funcion sql_conect
        if ($mysqli) {
            // Almacenamos la consulta SQL en una variable llamada $query utilizando mysqli_query
            $query = mysqli_query($mysqli, $consulta_sql);
            // Si la consulta se ejecuta correctamente
            if ($query) {
                // Recopilamos todos los resultados de la consulta en un array multidimensional y los almacenamos en la variable $resultados
                $resultados = mysqli_fetch_all($query);
                // Liberar el conjunto de resultados despues de obtener los datos
                mysqli_free_result($query);

                // Cierra la conexion despues de utilizarla
                mysqli_close($mysqli);
                // Devolvemos los resultados
                return $resultados;
            }}
        }
        

    
?>