<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

function redirect($page) {
    header("Location: ${page}");
    exit();
}