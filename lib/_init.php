<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once 'utils/redirect.php';
include_once 'utils/is_empty.php';
include_once 'utils/flash_data.php';
include_once 'lib/validator.php';
include_once 'utils/stable_usort.php';
include_once 'storage/storage.php';

spl_autoload_register(function ($class) {
    // split by case
    $names = preg_split('/(?=[A-Z])/', $class, -1, PREG_SPLIT_NO_EMPTY);

    $path = "./";
    switch (end($names)) {
        case "Controller":
            $path .= "controllers";
            break;
        case "Model":
            $path .= "models";
            break;
        case "Storage":
            $path .= "storage";
            break;
    }

    include_once($path . "./" . $class . ".php");
});
