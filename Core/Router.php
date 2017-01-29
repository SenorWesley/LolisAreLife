<?php
/**
 *      _        _ _      _           _    _  __
 *     | |   ___| (_)___ /_\  _ _ ___| |  (_)/ _|___
 *     | |__/ _ \ | (_-</ _ \| '_/ -_) |__| |  _/ -_)
 *     |____\___/_|_/__/_/ \_\_| \___|____|_|_| \___|
 *
 */

namespace LolisAreLife\Core;

/**
 * All the routing of the website goes through this file
 *
 * Class Router
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @auhor Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */

class Router {
    /**
     * Array of all registered routes.
     *
     * @access protected
     * @var array $allRoutes
     */
    protected $allRoutes = [];

    /**
     * Array of possible routing methods.
     *
     * @access protected
     * @var array $methods.
     */
    protected $methods = ["GET", "POST", "PUT", "DELETE"];

    /**
     * All types of end routes.
     *
     * @access protected
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
     * @access public
     * @return mixed
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Register a route into the `$allRoutes` array.
     *
     * @access public
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
     *
     * @access public
     */
    public function dispatch() {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        foreach ($this->allRoutes as $route) {
            list($method, $route, $action) = $route;
            if (strlen(utf8_decode($route)) > 0) {
                if (substr($route, 0, 1) !== "/")
                    $route = "/" . $route;

                    Request::getVariables();
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
