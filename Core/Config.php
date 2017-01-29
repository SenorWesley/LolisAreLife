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
 * All configuration of the application goes through this file.
 * The configuration file for each project is located in the root directory.
 * That config file then gets loded in this class where you can-
 * -use get, set and remove to alter this configuration file.
 *
 * Usage example
 *
 * [CODE]
 * Config::set("database", "hostname", "127.0.0.1", true);
 * echo Config::get("database", "hostname");
 * [/CODE]
 *
 * Class Config
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */
class Config {
    /**
     * Holds all of the config variables.
     *
     * @access private
     * @var array $config
     * @static
     */
    private static $config = [];

    /**
     * Loads the values from the configuration file into the `$config` array.
     *
     * @access public
     * @static
     */
    public static function load() {
        self::$config = parse_ini_file(dirname(__FILE__) . "/../configuration.loli", true);
    }

    /**
     * Get a variable from the configuration. If this variable is unavailible we either return the given default
     * or the name of the variable back.
     *
     * @access public
     * @param string $subject: The subject to search in.
     * @param string $key: The name of the variable to return.
     * @param mixed $default: The default value, to be returned if the requested variable isn't found in config.
     * @return mixed
     * @static
     */
    public static function get($subject, $key, $default = null) {
        if (!isset(self::$config[$subject][$key])) {
            if ($default === null)
                return $key;
            else
                return $default;
        }

        return self::$config[$subject][$key];
    }

    /**
     * Set a variable in the configuration. To make sure we don't accidentally overwrite values we will throw an
     * error when this is happening.  If the user feels its necessary to overwrite values they can force it.
     *
     * @access public
     * @param string $subject: The subject we want to write in.
     * @param string $key: The name of the variable we want to write.
     * @param mixed $value: The value of the variable we want to write.
     * @param bool|false $overwrite: Does the user want to overwrite the variable?
     * @return bool|void
     * @static
     */
    public static function set($subject, $key, $value, $overwrite = false) {
        if (isset(self::$config[$subject][$key]) && !$overwrite) {
            Logger::logMessage("Variable $key already exists in config and can't be overwritten.")->debug();
            return;
        }

        self::$config[$subject][$key] = $value;
        return true;
    }

    /**
     * Remove a variable from the configuration
     *
     * @access public
     * @param string $subject: The subject we want to delete from.
     * @param string $key: The name of the variable we want to delete.
     * @return bool: Did we manage to delete the variable from configuration?
     * @static
     */
    public static function delete($subject, $key) {
        if (isset(self::$config[$subject][$key])) {
            unset(self::$config[$subject][$key]);
            return true;
        }

        return false;
    }
}
