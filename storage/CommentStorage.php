<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('storage.php');

class CommentStorage {
    private static $instance = null;
    private $context;

    private static $path = 'data/comments.json';

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
            self::$instance = new CommentStorage();
        }

        return self::$instance->context;
    }
}
