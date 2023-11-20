<?php
/**
 * Genera una tabla HTML con encabezados, datos y columnas de iconos.
 *
 * @param string $classname La clase CSS de la tabla.
 * @param array $heads Un array con los encabezados de la tabla.
 * @param array $dataTabla Un array con los datos de la tabla.
 * @param array $dataIcon Un array con las columnas de iconos para cada fila.
 */
function tableAdd(string $classname, array $heads, array $dataTabla, array $dataIcon)
{
    ?>
    <table class="table">
        <thead>
            <tr>
                <?php
                foreach ($heads as $head) {
                    ?>
                    <th>
                        <?php echo $head ?>
                    </th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dataTabla as $dato) {
                ?>
                <tr>
                    <?php
                    foreach ($dato as $propiedad => $value) {
                        $noMuestra = ['id_autor', 'id_editorial', 'usuarionombreusuario'];

                        if (isset($_SESSION['rol']) && $_SESSION['rol'] == "LECTOR") {
                            $noMuestra[] = 'estado';
                            $noMuestra[] = 'id';
                            $noMuestra[] = 'fecha_creacion';
                            $noMuestra[] = 'prestamoid';
                            $noMuestra[] = 'nombreusuario';
                            $noMuestra[] = 'usuarionombreusuario';
                            $noMuestra[] = 'correoelectronico';
                        } else {
                            $noMuestra[] = 'imagenlibro';
                        }

                        if (in_array(strtolower($propiedad), $noMuestra)) {
                            continue;
                        }


                        if (strtolower($propiedad) == "estado" || strtolower($propiedad) == "estadoprestamo") {
                            $value = $value ? "Activo" : "Inactivo";
                            ?>
                            <td class="table-state delete">
                                <span>
                                    <?php echo $value ?>
                                </span>
                            </td>
                            <?php
                        } elseif (strtolower($propiedad) == "imagen" || strtolower($propiedad) == "imagenlibro") {
                            ?>
                            <td><img src="<?php echo dirname($_SERVER["PHP_SELF"]) . "/" . $value ?>" alt="" width="300px"
                                    height="500px"></td>

                            <?php
                        } else {
                            ?>
                            <td>
                                <?php echo $value ?>
                            </td>
                            <?php
                        }
                    }
                    ?>
                    <td class="option-table">
                        <ul>
                            <?php
                            // Si el usuario es administrador, se muestran todos los iconos.
                            if (isset($_SESSION['rol']) && $_SESSION['rol'] == "ADMIN") {

                                if (isset($dataIcon[3][0])) {
                                    iconAddLi($dataIcon[3][0], $dataIcon[3][1] . $dato['ID'], $dataIcon[3][2], $dataIcon[3][3], "", $dato['ID'], "");
                                }

                                if (isset($dato['dias_restantes']) && $dato["dias_restantes"] == 0) {
                                    ?><strong>No se puede realizar acciones.</strong>
                                    <?php
                                } else {
                                    iconAddLi($dataIcon[0][0], $dataIcon[0][1] . $dato['ID'], $dataIcon[0][2], $dataIcon[0][3], $dataIcon[0][4]);
                                    iconAddLi($dataIcon[1][0], $dataIcon[1][1] . $dato['ID'], $dataIcon[1][2], $dataIcon[1][3], $dataIcon[1][4], $dato['ID'], $dataIcon[1][5], $dataIcon[1][6]);
                                    iconAddLi($dataIcon[2][0], $dataIcon[2][1] . $dato['ID'], $dataIcon[2][2], $dataIcon[2][3], $dataIcon[2][4], isset($dato['ID_Autor']) ? $dato['ID_Autor'] : '', isset($dato['ID_Editorial']) ? $dato['ID_Editorial'] : 0, isset($dataIcon[2][5]) ? $dataIcon[2][5] : 0);
                                }
                            } else {
                                // Si el usuario es lector
                                // Se muestran los iconos de solicitar.
                                // O si es la tabla prestamos se muestran los iconos de devolver.
                                // Y si ya esta devuelvto muestra un mensaje.
                                
                                if (isset($dato['dias_restantes']) && $dato["dias_restantes"] == 0) {
                                    ?><strong>No se puede realizar acciones.</strong>
                                    <?php
                                } else {
                                    iconAddLi($dataIcon[4][0], $dataIcon[4][1] . $dato['ID'], $dataIcon[4][2], $dataIcon[4][3], "", $dato['ID'], $dataIcon[4][4]);
                                }
                            }
                            ?>
                        </ul>
                    </td>
                    <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>