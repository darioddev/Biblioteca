<?php
function iconAddDiv($classname, $classname2, $id, $icon, $href = "#")
{ ?>
    <div class="<?php echo $classname; ?>">
        <div class="<?php echo $classname2; ?>">
            <a href=<?php echo $href ?> id="<?php echo $id; ?>">
                <i class="<?php echo $icon; ?>"></i>
            </a>
        </div>
    </div>
<?php
}

function iconAddLi($classname, $route, $icon, $action = '' , $action2= '')
{
    ?>
    <li class="<?php echo $classname ?>">
        <a href="<?php echo $route ?>" data-action="<?php echo $action ?>" data-name="<?php echo $action2?>">
            <i class="<?php echo $icon ?>"></i>
        </a>
    </li>
<?php
}
?>