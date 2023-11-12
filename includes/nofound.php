<link rel="stylesheet" href="./assets/css/error.css">
<div class="error-center">
    <div class="error-container">
        <i class="far fa-frown sad-icon"></i>
        <h2 class="blink">Lo sentimos, no se han encontrado resultados.</h2>
    </div>
    <div class="button-container">
        <button class="custom-button">
            <a href="<?php echo $_SERVER["PHP_SELF"] . "?ruta=home"?>">Volver a home</a>
        </button>
        <button class="custom-button">
        <a href="<?php echo $_SERVER["PHP_SELF"] . "?ruta=" . (isset($_GET['ruta']) ? $_GET['ruta'] : "home"); ?>">Reiniciar página</a>
        </button>
    </div>
</div>