<?php

/**
 *      _        _ _      _           _    _  __
 *     | |   ___| (_)___ /_\  _ _ ___| |  (_)/ _|___
 *     | |__/ _ \ | (_-</ _ \| '_/ -_) |__| |  _/ -_)
 *     |____\___/_|_/__/_/ \_\_| \___|____|_|_| \___|
 *
 */

namespace LolisAreLife\Core\Storage;
use LolisAreLife\Core\Config;
use LolisAreLife\Core\Logger;
use LolisAreLife\Core\Request;

/**
 * Stores data within the frameworks file system.
 *
 * Class File
 * @package LolisAreLife\Core\Storage
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */
class File implements Storage {

    /**
     * Check if the variable exists in storage.
     *
     * @access public
     * @param string $key : The name of the wanted variable
     * @return boolean: Does the variable exsist?
     */
    public function has($key) {
        $path = Config::get("paths", "base") . Config::get("paths", "cache") . $key;

        if (Request::server("REQUEST_METHOD") == "POST" ||
            !Config::get("cache", "enabled")            ||
            !file_exists($path))
            return false;

        return (Request::server("REQUEST_TIME")) - filemtime($path) <= Config::get("cache", "life");
    }

    /**
     * Save a variable in storage.
     *
     * @access public
     * @param string $key : The name of the variable we want to store.
     * @param mixed $value : The data we wish to store.
     * @param bool|false $overwrite : Are we allowed to overwrite the variable if it already exists?
     * @return boolean: Did we sucessfully store the variable?
     */
    public function put($key, $value, $overwrite = false) {
        if ($this->has($key) && !$overwrite) {
            Logger::logMessage("Variable $key already exists in file storage and can't be overwritten.")->debug();
            return;
        }

        file_put_contents(Config::get("paths", "base") . Config::get("paths", "cache") . $key, $value);
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
            Logger::logMessage("The requested variable ($key) does not exist in file storage.")->debug();
            return;
        }

        return file_get_contents(Config::get("paths", "base") . Config::get("paths", "cache") . $key);
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
            Logger::logMessage("The requested variable ($key) does not exist in file storage.")->debug();
            return;
        }

        return unlink(Config::get("paths", "base") . Config::get("paths", "cache") . $key);
    }
}