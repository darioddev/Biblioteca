<?php

/**
 * Genera un bloque div con un enlace que contiene un ícono.
 *
 * @param string $classname La clase CSS del contenedor div externo.
 * @param string $classname2 La clase CSS del contenedor div interno.
 * @param string $id El ID del enlace.
 * @param string $icon La clase del ícono a mostrar.
 * @param string $data Información adicional asociada al ícono (opcional).
 * @param string $href La URL a la que apunta el enlace (opcional).
 */
function iconAddDiv($classname, $classname2, $id, $icon, $data = '', $href = "")
{ ?>
    <div class="<?php echo $classname; ?>">
        <div class="<?php echo $classname2; ?>">
            <a href="<?php echo $href ?>" id="<?php echo $id; ?>">
                <i class="<?php echo $icon; ?>" data-action="<?php echo $data ?>"></i>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Genera un elemento de lista li con un enlace que contiene un ícono.
 *
 * @param string $classname La clase CSS del elemento de lista li.
 * @param string $route La URL a la que apunta el enlace.
 * @param string $icon La clase del ícono a mostrar.
 * @param string $action Información adicional asociada al enlace (opcional).
 * @param string $action2 Información adicional asociada al enlace (opcional).
 * @param string $action3 Información adicional asociada al enlace (opcional).
 * @param string $action4 Información adicional asociada al enlace (opcional).
 * @param string $action5 Información adicional asociada al enlace (opcional).
 */
function iconAddLi($classname, $route, $icon, $action = '', $action2 = '', $action3 = '', $action4 = '', $action5 = '')
{
    ?>
    <li class="<?php echo $classname ?>">
        <a href="<?php echo $route ?>" data-action="<?php echo $action ?>" data-name="<?php echo $action2 ?>"
            data-id="<?php echo $action3 ?>" data-foreignKey="<?php echo $action4 ?>" data-state="<?php echo $action5 ?>">
            <i class="<?php echo $icon ?>"></i>
        </a>
    </li>
    <?php
}
?>