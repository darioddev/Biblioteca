<?php
    //Funcion que valida que los campos estan declarados y no son vacios
    function validaExistenciaVaribale($variable) : bool{
        return isset($variable) && !empty($variable);
    }
    //Funcion que valida que los nombres y apellidos no contengan ningun caracter extraño
    function validaNombreApellidos(string $param): bool {
        return preg_match('/^[\p{L}A-Za-z0-9]+$/u', $param);
    }
    
    //Funcion que valida el usuario
    //Permite cualquier letra , numeros y solamente guiones y guiones bajos
    function validaUsuario(string $user): bool {
        return preg_match('/^[\p{L}A-Za-z0-9_-]+$/u', $user);
    }

    //Funcion que valida el correo electronico
    function validaEmail(string $email): bool {
        return preg_match('/^[\p{L}A-Za-z0-9._%+-]+@[\p{L}A-Za-z0-9.-]+\.[\p{L}A-Za-z]{2,}$/u', $email);
    }
    
    //Funcion que valida una contraseña
    //Obliga a que sea minimom 8 , maximo 16
    //Minimo una mayusucula , una minuscula y un número.
    //Permite tambien caracteres especiales 
    function validaPassword(string $password) : bool {
        return preg_match('/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/u',$password);
    }
    
?>