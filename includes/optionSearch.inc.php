<?php
function optionOrdenacion($classname, $idSelect, $tipo, $SESSION = '', $nameColumna = '', $nameOrdenacion = '')
{
    ?>
    <div class="<?php echo $classname ?>">
        <div>
            <select id=<?php echo $idSelect ?>>
                <?php
                switch ($tipo) {
                    case "UsuariosOrdenacion":
                        ?>
                        <option value="column=nombre&order=ASC" <?php echo ($SESSION[$nameColumna] == 'nombre' && $SESSION[$nameOrdenacion] == 'ASC') || isset($_GET['search']) ? 'selected' : ''; ?>>Ordenar
                            por...</option>
                        <optgroup label="ASCENDENTE">
                            <option value="column=ID&order=ASC" <?php echo ($SESSION[$nameColumna] == 'ID' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>ID</option>
                            <option value="column=nombre&order=ASC">NOMBRE</option>
                            <option value="column=nombre_usuario&order=ASC" <?php echo ($SESSION[$nameColumna] == 'nombre_usuario' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                            <option value="column=correo_electronico&order=ASC" <?php echo ($SESSION[$nameColumna] == 'correo_electronico' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                            <option value="column=fecha_registro&order=ASC" <?php echo ($SESSION[$nameColumna] == 'fecha_registro' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                        </optgroup>
                        <optgroup label="DESCENDENTE">
                            <option value="column=ID&order=DESC" <?php echo ($SESSION[$nameColumna] == 'ID' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>ID</option>
                            <option value="column=nombre&order=DESC" <?php echo ($SESSION[$nameColumna] == 'nombre' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>NOMBRE</option>
                            <option value="column=nombre_usuario&order=DESC" <?php echo ($SESSION[$nameColumna] == 'nombre_usuario' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>NOMBRE USUARIO</option>
                            <option value="column=correo_electronico&order=DESC" <?php echo ($SESSION[$nameColumna] == 'correo_electronico' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>CORREO ELECTRONICO</option>
                            <option value="column=fecha_registro&order=DESC" <?php echo ($SESSION[$nameColumna] == 'fecha_registro' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>FECHA REGISTRO</option>
                        <optgroup label="ESTADO">
                            <option value="column=ESTADO&order=ASC" <?php echo ($SESSION[$nameColumna] == 'ESTADO' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>INACTIVO</option>
                            <option value="column=ESTADO&order=DESC" <?php echo ($SESSION[$nameColumna] === 'ESTADO' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>ACTIVO</option>
                        </optgroup>
                    </select>
                </div>
                <?php
                break;
                    case "UsuariosBuscador":
                        ?>
                <optgroup label="Por defecto : Nombre"></optgroup>
                <option value="NOMBRE">Buscar por... </option>
                <option value="ID">ID</option>
                <option value="APELLIDO">APELLIDO</option>
                <option value="NOMBRE_USUARIO">NOMBRE USUARIO</option>
                <option value="CORREO_ELECTRONICO">CORREO ELECTRONICO</option>
                <option value="FECHA_REGISTRO">FECHA REGISTRO</option>
                <optgroup label="Para ver inactivos ponga : false"></optgroup>
                <optgroup label="Para ver activos ponga : 1"></optgroup>
                <option value="ESTADO">ESTADO</option>
                <option value="ROL">ROL</option>
                </select>
            </div>
            </div>
            <?php
            break;
                    case "AutorOrdenacion": ?>
            <option value="column=nombre&order=ASC" <?php echo ($SESSION[$nameColumna] == 'ID' && $SESSION[$nameOrdenacion] == 'ASC') || isset($_GET['search']) ? 'selected' : ''; ?>>Ordenar por...</option>
            <optgroup label="ASCENDENTE">
                <option value="column=ID&order=ASC" <?php echo ($SESSION[$nameColumna] == 'ID' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>ID</option>
                <option value="column=nombre&order=ASC">NOMBRE</option>
                <option value="column=apellido&order=ASC" <?php echo ($SESSION[$nameColumna] == 'apellido' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>APELLIDO</option>
                <option value="column=fecha_nacimiento&order=ASC" <?php echo ($SESSION[$nameColumna] == 'fecha_nacimiento' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>FECHA NACIMIENTO</option>
                <option value="column=fecha_creacion&order=ASC" <?php echo ($SESSION[$nameColumna] == 'fecha_creacion' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>FECHA CREACION</option>
                <option value="column=fecha_modificacion&order=ASC" <?php echo ($SESSION[$nameColumna] == 'fecha_modificacion' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>FECHA MODIFICACION</option>
                <option value="column=estado&order=ASC" <?php echo ($SESSION[$nameColumna] == 'estado' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>ESTADO</option>
            </optgroup>
            <optgroup label="DESCENDENTE">
                <option value="column=ID&order=DESC" <?php echo ($SESSION[$nameColumna] == 'ID' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>ID</option>
                <option value="column=nombre&order=DESC" <?php echo ($SESSION[$nameColumna] == 'nombre' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>NOMBRE</option>
                <option value="column=apellido&order=DESC" <?php echo ($_SESSION[$nameColumna] == 'apellido' && $_SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>APELLIDO</option>
                <option value="column=fecha_nacimiento&order=DESC" <?php echo ($SESSION[$nameColumna] == 'fecha_nacimiento' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>FECHA NACIMIENTO</option>
                <option value="column=fecha_creacion&order=DESC" <?php echo ($_SESSION[$nameColumna] == 'fecha_creacion' && $_SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>FECHA CREACION</option>
                <option value="column=fecha_modificacion&order=DESC" <?php echo ($SESSION[$nameColumna] == 'fecha_modificacion' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>FECHA MODIFICACION</option>
                <option value="column=estado&order=DESC" <?php echo ($SESSION[$nameColumna] == 'estado' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>ESTADO</option>
            </optgroup>
            <optgroup label="ESTADO">
                <option value="column=ESTADO&order=ASC" <?php echo ($SESSION[$nameColumna] == 'ESTADO' && $SESSION[$nameOrdenacion] == 'ASC') ? 'selected' : ''; ?>>INACTIVO</option>
                <option value="column=ESTADO&order=DESC" <?php echo ($SESSION[$nameColumna] === 'ESTADO' && $SESSION[$nameOrdenacion] == 'DESC') ? 'selected' : ''; ?>>ACTIVO</option>
            </optgroup>
            </select>
            </div>
            <?php
            break;
                    case "AutorBuscador":
                        ?>
            <optgroup label="Por defecto : Nombre"></optgroup>
            <option value="NOMBRE">Buscar por... </option>
            <option value="ID">ID</option>
            <option value="APELLIDO">APELLIDO</option>
            <optgroup label="Para buscar por fechas solo se admite"></optgroup>
            <optgroup label="numeros ej : junio: 06 "></optgroup>
            <option value="FECHA_NACIMIENTO">FECHA_NACIMIENTO</option>
            <option value="FECHA_CREACION">FECHA CREACION</option>
            <option value="FECHA_MODIFICACION">FECHA MODIFICACION</option>
            <optgroup label="Para ver inactivos ponga : false"></optgroup>
            <optgroup label="Para ver activos ponga : 1"></optgroup>
            <option value="ESTADO">ESTADO</option>
            <option value="ROL">ROL</option>
            </select>
            </div>
            </div>
            <?php
            break;
                }

                ?>

    <?php
}

function formSearch($route, $id)
{
    ?>
    <form method="GET" action="<?php echo $route ?>" class="form" id="<?php echo $id ?>">
        <button>
            <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                    stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round">
                </path>
            </svg>
        </button>
        <input class="inputSearch" placeholder="Buscar ... " required="" type="text" name="search" />
        <button class="reset" type="reset">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </form>
    <?php
}

?>