<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

function set_flash_data($key, $value) {
    $_SESSION[$key] = $value;
}

function get_flash_data($key) {
    $value = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);
    return $value;
}
