<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página No Encontrada</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa !important;
            color: #343a40;
            text-align: center;
            padding: 50px;
        }

        h1 {
            font-size: 3em;
            color: #dc3545;
        }

        p {
            font-size: 1.2em;
            margin-top: 20px;
        }
        div{
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para el botón */
        a {
      
            width: 200px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>404 - Página No Encontrada</h1>
    <p>Lo sentimos, la página que estás buscando no existe.</p>
    <div>
    <a href="<?php echo $_SERVER["PHP_SELF"] . "?ruta=home"?>">Volver a home</a>
    </div>
</body>
</html>