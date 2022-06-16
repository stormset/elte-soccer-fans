<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php
class Router {
    private $routes = [];
    private $not_found_action = NULL;

    public function start() {
        $path = isset($_GET["page"]) ? ($_GET["page"] == "" ? "index" : $_GET["page"]) : "index";
        $http_method = $_SERVER["REQUEST_METHOD"];

        foreach ($this->routes as $route) {
            if ($route["path"] === $path && $route["http-method"] === $http_method) {
                $controller_name = $route["controller"];
                $method_name = $route["method"];

                $controller = new $controller_name();
                $controller->$method_name();
                return;
            }
        }

        // if the page was not found
        if ($this->not_found_action != NULL) {
            call_user_func($this->not_found_action);
        }
    }

    private function addRoute($path, $controller, $method, $http_method) {
        $this->routes[] = [
            "path" => $path,
            "http-method" => $http_method,
            "controller" => $controller,
            "method" => $method
        ];
    }

    public function get($path, $controller, $method) {
        $this->addRoute($path, $controller, $method, "GET");
    }

    public function post($path, $controller, $method) {
        $this->addRoute($path, $controller, $method, "POST");
    }
    public function put($path, $controller, $method) {
        $this->addRoute($path, $controller, $method, "PUT");
    }
    public function delete($path, $controller, $method) {
        $this->addRoute($path, $controller, $method, "DELETE");
    }

    public function not_found($action) {
        $this->not_found_action = $action;
    }
}