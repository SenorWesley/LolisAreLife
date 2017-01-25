<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 24-1-2017
 * Time: 13:36
 */

namespace LolisAreLife\Core;


class Router {
    /**
     * Array of all registered routes.
     *
     * @var array $allRoutes
     */
    protected $allRoutes = [];

    /**
     * Array of possible routing methods.
     *
     * @var array $methods.
     */
    protected $methods = ["GET", "POST", "PUT", "DELETE"];

    /**
     * All types of end routes.
     *
     * @var array $types
     */
    protected $types = [
        "int" => "[0-9]++",
        "alpha" => "[0-9A-Za-z]++",
        "*" => ".+?",
        "**" => ".++",
        "" => "[^/\\.]++"
    ];

    /**
     * Return all registered routes.
     *
     * @return mixed
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Register a route into the `$allRoutes` array.
     *
     * @param string $method
     * @param string $route
     * @param string $action
     */
    public function __call($method, $route, $action) {
        if (!in_array($method, $this->methods))
            Error::throwError("unknown.route.protocol", $method)->fatal();
        else
            $this->allRoutes[] = [$method, $route, $action];
    }


    /**
     * Match a given routing request against routes.
     */
    public function dispatch() {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        foreach ($this->allRoutes as $route) {
            list($method, $route, $action) = $route;
            if (strlen(utf8_decode($route)) > 0) {
                if (substr($route, 0, 1) !== "/")
                    $route = "/" . $route;

                if (preg_match("@^" . $route . "$@", $uri, $arguments)) {
                    array_shift($arguments);
                    $action = explode("@", $action);
                    $callback[0] = "\\App\\Controllers\\" . $action[0];
                    $controller = new $callback[0]();

                    if (!call_user_func([$controller, $callback[1]], $arguments)) {
                        $method = $callback[1];
                        $controller->$method();
                        return;
                    } else {
                        call_user_func([$controller, $callback[1]], $arguments);
                        return;
                    }
                }
            }
        }
    }
}
