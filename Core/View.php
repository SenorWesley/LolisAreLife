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
 * The views go through this file, basically everything you
 * see on the website is because of this file. Also handles
 * view caching based on physical files.
 *
 * Class View
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */

use LolisAreLife\Core\Store;
use LolisAreLife\Core\Storage\File;

class View {

    /**
     * the name of the view
     *
     * @access private
     * @var string $view
     */
    private $view;

    /**
     * all the variables of the view
     *
     * @access private
     * @var array $variables
     * @static
     */
    private static $variables = [];

    /**
     * variables specific to that view
     *
     * @access private
     * @var array $specificVariables
     */
    private $specificVariables = [];

    /**
     * Access point to an instance of the Store class
     *
     * @access private
     * @var \LolisAreLife\Core\Store $cache
     */
    private $cache;

    /**
     * Loads the view with the name and the specific variables
     *
     * @param string $viewName: the name of the view
     * @param array $specificVariables: the variables specific to that view
     */
    public function __construct($viewName, Array $specificVariables) {
        $this->cache = new Store(new File());
        $this->view = $viewName;
        $this->specificVariables = $specificVariables;
        if (strpos($this->view, "."))
            $path = $this->getPathFromFolder();
        else
            $path = $this->getPath();

        $this->load($path);
    }

    /**
     * Gets the path for a view, located in one or multiple folders
     *
     * @access private
     * @return string
     */
    private function getPathFromFolder() {
        $path = Config::get("path", "base") . Config::get("path", "views");

        $view = explode(".", $this->view);
        for ($i = 0; $i < (count($view) - 1); $i++) {
            $path = $path . $view[$i] . "/";
        }
        $last = array_values(array_slice($view, -1))[0];
        $path = $path . $last . ".loli.php";
        return $path;
    }

    /**
     * Gets the path for a view
     *
     * @access private
     * @return string
     */
    private function getPath() {
        $path = Config::get("path", "base") . Config::get("path", "views") . $this->view . ".loli.php";
        return $path;
    }

    /**
     * Loads the view, pushes it to the parse function and then echos it
     * to the page.
     *
     * @access private
     * @param string $path: the path to load
     */
    private function load($path) {
        ob_start();
        include("/" . $path);
        $data = ob_get_contents();
        ob_get_clean();
        $finalizedHtml = $this->parse(data);
        echo $finalizedHtml;
    }

    /**
     * Puts a variable into the variables array
     *
     * @access public
     * @param string $key: The name of variable
     * @param mixed $value: The value of the variable
     */
    public function putVariable($key, $value) {
        $this->variables[$key] = $value;
    }

    /**
     * Gets a variable from the variables array
     *
     * @access public
     * @param string $key: The name of the variable
     * @return bool
     */
    public function getVariable($key) {
        return isset($this->variables[$key]) ? $this->variables[$key] : false;
    }

    /**
     * parses the data and replaces variables.
     *
     * @access private
     * @param string $data: the data we want to parse
     * @return mixed
     */
    private function parse($data) {
        foreach (self::$variables as $key => $value) {
            $data = str_replace("{{" . $key . "}}", $value, $data);
        }

        foreach ($this->specificVariables as $key => $value) {
            $data = str_replace("{{" . $key . "}}", $value, $data);
        }

        if (Config::get("cache", "enabled"))
            $this->cache->put(Request::getUrl("-"), "<!-- Cached on " . date("r") . " -->" . $data);

        return $data;
    }
}