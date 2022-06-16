<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

function is_empty($value): bool
{
    return !(isset($value) && trim($value) !== '');
}