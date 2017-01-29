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
 * Handles all requests.
 *
 * Class Cookie
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @auhor Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */


class Request {

    /**
     * The most recent URL visited by the user.
     *
     * @access private
     * @var $url
     */
    private static $url;

    /**
     * An entry point made for the GET superglobal.
     * @var $get.
     */
    private static $get;

    /**
     * An entry point made for the POST superglobal.
     * @var $post.
     */
    private static $post;

    /**
     * An entry point made for the SERVER superglobal.
     * @var $server.
     */
    private static $server;


    /**
     * Set the GET, POST and SERVER requests into their collaborative class var.
     * To create a common interface for these variables to accessed we unset the superglobals.
     *
     * @access public
     * @static
     */
    public static function getVariables() {
        self::$get    = $_GET;
        self::$post   = $_POST;
        self::$server = $_SERVER;
        unset($_GET, $_POST, $_SERVER);
    }

    /**
     * @access public
     * @param null $url: Alternative URL to use instead of the request url (FOR DEBUGGING ONLY)
     * @static
     */
    public static function setUrl($url = null) {
        self::$url = $url ?: $_SERVER['REQUEST_URI'];

        if (strpos(self::$url, Config::get('path', 'root')) === 0) {
            self::$url = '/' . substr(self::$url, strlen(Config::get('path', 'root')));
        }
    }

    /**
     * Return the URL that the user visited.
     *
     * @access public
     * @param null $replace: What to replace "/" with.
     * @return mixed|string
     * @static
     */
    public static function getUrl($replace = null) {
        if (empty(self::$url))
            return "index";

        if ($replace !== null && self::$url)
            return str_replace("/", $replace, self::$url);

        return self::$url;
    }

    /**
     * Get a GET variable.
     *
     * @access public
     * @param string $key: The name of the requested variable.
     * @param mixed $default: The default value, to be returned if the variable is unavailable.
     * @return mixed
     * @static
     */
    public static function get($key, $default = null) {
        if (!isset(self::$get[$key])) {
            if ($default === null)
                return $key;
            else
                return $default;
        }

        return self::$get[$key];
    }

    /**
     * Get a POST variable.
     *
     * @access public
     * @param string $key: The name of the requested variable.
     * @param mixed $default: The default value, to be returned if the variable is unavailable.
     * @return mixed
     * @static
     */
    public static function post($key, $default = null) {
        if (!isset(self::$post[$key])) {
            if ($default === null)
                return $key;
            else
                return $default;
        }

        return self::$post[$key];
    }

    /**
     * Get a SERVER variable.
     *
     * @access public
     * @param string $key: The name of the requested variable.
     * @param mixed $default: The default value, to be returned if the variable is unavailable.
     * @return mixed
     * @static
     */
    public static function server($key, $default = null) {
        if (!isset(self::$server[$key])) {
            if ($default === null)
                return $key;
            else
                return $default;
        }

        return self::$server[$key];
    }

    /**
     * Check if a request is sent via ajax.
     *
     * @access public
     * @return bool
     * @static
     */
    public static function requestIsAjax() {
        return isset(self::$server["HTTP_X_REQUESTED_WITH"])
        && strtolower(self::$server["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest";
    }
}