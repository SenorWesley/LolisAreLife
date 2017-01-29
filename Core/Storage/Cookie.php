<?php
/**
 *      _        _ _      _           _    _  __
 *     | |   ___| (_)___ /_\  _ _ ___| |  (_)/ _|___
 *     | |__/ _ \ | (_-</ _ \| '_/ -_) |__| |  _/ -_)
 *     |____\___/_|_/__/_/ \_\_| \___|____|_|_| \___|
 *
 */

namespace LolisAreLife\Core\Storage;
use LolisAreLife\Core\Logger;

/**
 * Stores data within the end users cookie storage.
 *
 * Class Cookie
 * @package LolisAreLife\Core\Storage
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */

class Cookie implements Storage {

    /**
     * Check if the variable exists in storage.
     *
     * @access public
     * @param string $key : The name of the wanted variable
     * @return boolean: Does the variable exsist?
     */
    public function has($key) {
        return isset($_COOKIE[$key]);
    }

    /**
     * Save a variable in storage.
     *
     * @access public
     * @param string $key : The name of the variable we want to store.
     * @param mixed $value : The data we wish to store.
     * @param int $lifetime : The lifetime of the cookie to be stored. (in seconds)
     * @param bool|false $overwrite : Are we allowed to overwrite the variable if it already exists?
     * @return boolean: Did we sucessfully store the variable?
     */
    public function put($key, $value, $lifetime = 1314000, $overwrite = false) {
        if ($this->has($key) && !$overwrite) {
            Logger::logMessage("Variable $key already exists in cookie storage and can't be overwritten.")->debug();
            return;
        }

        setcookie($key, $value, $lifetime, "/", ".");
        return $this->has($key);
    }

    /**
     * Return a variable from storage.
     *
     * @access public
     * @param string $key : The name of the requested variable.
     * @return mixed: The data associated with the requested variable.
     */
    public function get($key) {
        if (!$this->has($key)) {
            Logger::logMessage("The requested variable ($key) does not exist in cookie storage.")->debug();
            return;
        }

        return $_COOKIE[$key];
    }

    /**
     * Delete a variable from storage.
     *
     * @access public
     * @param string $key : The name of the variable we wish to remove.
     * @return boolean: Did we successfully delete the variable?
     */
    public function delete($key) {
        if (!$this->has($key)) {
            Logger::logMessage("The requested variable ($key) does not exist in cookie storage.")->debug();
        }

        setcookie($key, "", (time() - 3000));
        return !$this->has($key);
    }
}