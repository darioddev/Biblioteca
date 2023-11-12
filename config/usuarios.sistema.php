<?php
/**
 * Todos los usuarios que se encuentran actualmente en el sistema excepto Administradores
 * que fueron registrado en interfaz , si se quiere implementar esto se debera cojer y implementar en :
 * database.inc.php
 */
$hoy = date('Y-m-d');
$usuarios = [
    [
        'nombre' => 'Juan',
        'apellido' => 'Pérez',
        'apellido2' => 'González',
        'nombre_usuario' => 'juanperez123',
        'correo_electronico' => 'juan.perez@example.com',
        'password' => 'Contraseña1',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Ana',
        'apellido' => 'Gómez',
        'apellido2' => 'López',
        'nombre_usuario' => 'anagomez456',
        'correo_electronico' => 'ana.gomez@example.com',
        'password' => 'Password2',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Carlos',
        'apellido' => 'Fernández',
        'apellido2' => 'Martínez',
        'nombre_usuario' => 'carlos_fernandez',
        'correo_electronico' => 'carlos.fernandez@example.com',
        'password' => 'Segura_123',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Laura',
        'apellido' => 'Rodríguez',
        'apellido2' => 'Sánchez',
        'nombre_usuario' => 'laurarodriguez7',
        'correo_electronico' => 'laura.rodriguez@example.com',
        'password' => 'L@uraP4ss',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Pedro',
        'apellido' => 'López',
        'apellido2' => 'Gutiérrez',
        'nombre_usuario' => 'pedro_lopez',
        'correo_electronico' => 'pedro.lopez@example.com',
        'password' => 'P3droL0pez',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'María',
        'apellido' => 'Hernández',
        'apellido2' => 'Díaz',
        'nombre_usuario' => 'mariahdez',
        'correo_electronico' => 'maria.hernandez@example.com',
        'password' => 'Maria1234',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),

    ],
    [
        'nombre' => 'Javier',
        'apellido' => 'Garrido',
        'apellido2' => 'López',
        'nombre_usuario' => 'javiergarrido',
        'correo_electronico' => 'javier.garrido@example.com',
        'password' => 'J@v13rL0pz',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Sofía',
        'apellido' => 'Martínez',
        'apellido2' => 'Gómez',
        'nombre_usuario' => 'sofiamartinez',
        'correo_electronico' => 'sofia.martinez@example.com',
        'password' => 'SofiaPass123',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Luis',
        'apellido' => 'Ramírez',
        'apellido2' => 'Ortega',
        'nombre_usuario' => 'luisramirez',
        'correo_electronico' => 'luis.ramirez@example.com',
        'password' => 'LuisR123$',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),

    ],
    [
        'nombre' => 'Elena',
        'apellido' => 'Santos',
        'apellido2' => 'Molina',
        'nombre_usuario' => 'elenasantos',
        'correo_electronico' => 'elena.santos@example.com',
        'password' => 'Elena2022!',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],   [
        'nombre' => 'Manuel',
        'apellido' => 'Ortiz',
        'apellido2' => 'Sánchez',
        'nombre_usuario' => 'manuelo',
        'correo_electronico' => 'manuel.ortiz@example.com',
        'password' => 'Manuel2023!',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),

    ],
    [
        'nombre' => 'Carmen',
        'apellido' => 'Silva',
        'apellido2' => 'García',
        'nombre_usuario' => 'carmens',
        'correo_electronico' => 'carmen.silva@example.com',
        'password' => 'CarmenP4ss!',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Alejandro',
        'apellido' => 'Ferrer',
        'apellido2' => 'Jiménez',
        'nombre_usuario' => 'alejandrof',
        'correo_electronico' => 'alejandro.ferrer@example.com',
        'password' => 'Alejandro123$',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Isabel',
        'apellido' => 'Mendez',
        'apellido2' => 'Rodriguez',
        'nombre_usuario' => 'isabelm',
        'correo_electronico' => 'isabel.mendez@example.com',
        'password' => 'IsabelR2023',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Víctor',
        'apellido' => 'Pérez',
        'apellido2' => 'López',
        'nombre_usuario' => 'victorp',
        'correo_electronico' => 'victor.perez@example.com',
        'password' => 'Victor_123',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Lucía',
        'apellido' => 'Torres',
        'apellido2' => 'Gómez',
        'nombre_usuario' => 'luciatorres',
        'correo_electronico' => 'lucia.torres@example.com',
        'password' => 'LuciaPass123!',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Miguel',
        'apellido' => 'Herrera',
        'apellido2' => 'Fernández',
        'nombre_usuario' => 'miguelh',
        'correo_electronico' => 'miguel.herrera@example.com',
        'password' => 'Miguel12345$',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Sara',
        'apellido' => 'Ríos',
        'apellido2' => 'Martín',
        'nombre_usuario' => 'sararios',
        'correo_electronico' => 'sara.rios@example.com',
        'password' => 'SaraPass2023',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Diego',
        'apellido' => 'Molina',
        'apellido2' => 'García',
        'nombre_usuario' => 'diegomolina',
        'correo_electronico' => 'diego.molina@example.com',
        'password' => 'Diego$1234',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],
    [
        'nombre' => 'Laura',
        'apellido' => 'Hernández',
        'apellido2' => 'Gómez',
        'nombre_usuario' => 'laurah',
        'correo_electronico' => 'laura.hernandez@example.com',
        'password' => 'Laura2023!',
        'fecha' => date('Y-m-d', strtotime($hoy . ' + ' . rand(1, 30) . ' days')),
    ],

];

    foreach ($usuarios as $usuario) {
        $nombre = $usuario['nombre'];
        $apellido = $usuario['apellido'];
        $apellido2 = $usuario['apellido2'];
        $nombre_usuario = $usuario['nombre_usuario'];
        $correo_electronico = $usuario['correo_electronico'];
        $password = $usuario['password'];
        $fecha = $usuario['fecha'];
    
        $resultado = insertar_usuario($nombre, $apellido, $apellido2, $nombre_usuario, $correo_electronico, $password, $fecha);
    
        if ($resultado) {
            echo 'Inserción exitosa para el usuario ' . $nombre_usuario . '<br>';
        } else {
            echo 'Error al insertar el usuario ' . $nombre_usuario . '<br>';
        }
    }

?>
?>