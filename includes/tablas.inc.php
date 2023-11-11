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
                        if ($propiedad == "ESTADO") {
                            $value = $value ? "Activo" : "Inactivo";
                            ?>
                            <td class="table-state">
                                <span>
                                    <?php echo $value ?>
                                </span>
                            </td>
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
                                iconAddLi($dataIcon[0][0], $dataIcon[0][1] . $dato['ID'], $dataIcon[0][2], $dataIcon[0][3]);
                                iconAddLi($dataIcon[1][0], $dataIcon[1][1] . $dato['ID'], $dataIcon[1][2], $dataIcon[1][3]);
                                iconAddLi($dataIcon[2][0], $dataIcon[2][1] . $dato['ID'], $dataIcon[2][2], $dataIcon[2][3]);
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