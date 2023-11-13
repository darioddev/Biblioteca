<?php
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
                        if(strtolower($propiedad) == "id_autor" || strtolower($propiedad) == "id_editorial") {
                            continue;
                        }
                        if (strtolower($propiedad) == "estado") {
                            $value = $value ? "Activo" : "Inactivo";
                            ?>
                            <td class="table-state delete">
                                <span>
                                    <?php echo $value ?>
                                </span>
                            </td>
                            <?php
                        } elseif (strtolower($propiedad) == "imagen") {
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
                            iconAddLi($dataIcon[0][0], $dataIcon[0][1] . $dato['ID'], $dataIcon[0][2], $dataIcon[0][3], $dataIcon[0][4]);
                            iconAddLi($dataIcon[1][0], $dataIcon[1][1] . $dato['ID'], $dataIcon[1][2], $dataIcon[1][3], $dataIcon[1][4] ,$dato['ID'], $dataIcon[1][5], $dataIcon[1][6]);
                            iconAddLi($dataIcon[2][0], $dataIcon[2][1] . $dato['ID'], $dataIcon[2][2], $dataIcon[2][3], $dataIcon[2][4] ,isset($dato['ID_Autor']) ? $dato['ID_Autor'] : '' , isset($dato['ID_Editorial']) ? $dato['ID_Editorial'] : 0 , isset($dataIcon[2][5]) ? $dataIcon[2][5] : 0) ;
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