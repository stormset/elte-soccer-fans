<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('storage.php');

class MatchStorage {
    private static $instance = null;
    private $context;

    private static $path = 'data/matches.json';

    /**
     * @throws Exception
     */
    private function __construct()
    {
        $this->context = new Storage(new JsonIO(self::$path));
    }

    public static function getContext(): Storage
    {
        if(!self::$instance)
        {
            self::$instance = new MatchStorage();
        }

        return self::$instance->context;
    }
}
