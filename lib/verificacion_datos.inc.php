<?php
//Funcion que comprueba la existencia de variables...
function formularioDatosUsuario($datosFormulario)
{
    $errores_campos = [];
    if (!validaExistenciaVaribale($datosFormulario['name']) || !validaNombreApellidos($datosFormulario['name']))
        $errores_campos['name'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    if (!validaExistenciaVaribale($datosFormulario['last_name1']) || !validaNombreApellidos($datosFormulario['last_name1']))
        $errores_campos['last_name1'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    if (validaExistenciaVaribale($datosFormulario['last_name2']) && !validaNombreApellidos($datosFormulario['last_name2']))
        $errores_campos['last_name2'] = 'No puede estar vacío y/o no puede contener caracteres especiales.';
    if (!validaExistenciaVaribale($datosFormulario['user']) || !validaUsuario($datosFormulario['user']))
        $errores_campos['user'] = 'No puede estar vacío y/o no es válido.';
    if (!validaExistenciaVaribale($datosFormulario['email']) || !validaEmail($datosFormulario['email']))
        $errores_campos['email'] = 'No puede estar vacío y/o debe contener una dirección de correo electrónico válida, por ejemplo, "nombre@ejemplo.com".';
    return $errores_campos;
}

function formularioDatosAutor($datosFormulario)
{
    $errores_campos = [];
    $errores_mensajes = [
        'nombre' => 'El campo nombre no puede estar vacío y/o no puede contener caracteres especiales.',
        'apellido' => 'El campo apellido no puede estar vacío y/o no puede contener caracteres especiales.',
        'fecha_nacimiento' => 'El campo fecha de nacimiento no puede estar vacío.',
    ];

    if (empty($datosFormulario['nombre']) || !validaNombreApellidos($datosFormulario['nombre'])) {
        $errores_campos['nombre'] = $errores_mensajes['nombre'];
    }
    if (empty($datosFormulario['apellido']) || !validaNombreApellidos($datosFormulario['apellido'])) {
        $errores_campos['apellido'] = $errores_mensajes['apellido'];
    }
    if (empty($datosFormulario['fecha_nacimiento'])) {
        $errores_campos['fecha_nacimiento'] = $errores_mensajes['fecha_nacimiento'];
    }

    return $errores_campos;
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