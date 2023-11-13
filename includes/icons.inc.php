<?php
function iconAddDiv($classname, $classname2, $id, $icon, $data = '' , $href = "")
{ ?>
    <div class="<?php echo $classname; ?>">
        <div class="<?php echo $classname2; ?>" >
            <a href="<?php echo $href ?>" id="<?php echo $id; ?>" >
                <i class="<?php echo $icon; ?>" data-action="<?php echo $data?>"></i>
            </a>
        </div>
    </div>
<?php
}

function iconAddLi($classname, $route, $icon, $action = '' , $action2= '' , $action3= '' , $action4= '' , $action5= '')
{
    ?>
    <li class="<?php echo $classname ?>">
        <a href="<?php echo $route ?>" data-action="<?php echo $action ?>" data-name="<?php echo $action2 ?>" data-id=<?php echo $action3?> data-foreignKey="<?php echo $action4?>" data-state="<?php echo $action5?>">
            <i class="<?php echo $icon ?>"></i>
        </a>
    </li>
<?php
}
?>