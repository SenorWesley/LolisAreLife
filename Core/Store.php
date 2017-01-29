<?php
/**
 *      _        _ _      _           _    _  __
 *     | |   ___| (_)___ /_\  _ _ ___| |  (_)/ _|___
 *     | |__/ _ \ | (_-</ _ \| '_/ -_) |__| |  _/ -_)
 *     |____\___/_|_/__/_/ \_\_| \___|____|_|_| \___|
 *
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @auhor Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @codename Lolicon
 * @since V0.1
 */

namespace LolisAreLife\Core;


use LolisAreLife\Core\Storage\Storage;

class Store implements Storage {

    /**
     * The storage interface we wish to use.
     *
     * @access public
     * @var $storageInterface
     */
    private $storageInterface;

    /**
     * State what storage interface we wish to use.
     *
     * @access public
     * @param $storageInterface
     */
    public function __construct(Storage $storageInterface) {
        $this->storageInterface = $storageInterface;
    }

    /**
     * Check if the variable exists in storage.
     *
     * @access public
     * @param string $key : The name of the wanted variable
     * @return boolean: Does the variable exsist?
     */
    public function has($key) {
        return $this->storageInterface->has($key);
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
        return $this->storageInterface->put($key, $value, $overwrite);
    }

    /**
     * Return a variable from storage.
     *
     * @access public
     * @param string $key : The name of the requested variable.
     * @return mixed: The data associated with the requested variable.
     */
    public function get($key) {
        return $this->storageInterface->get($key);
    }

    /**
     * Delete a variable from storage.
     *
     * @access public
     * @param string $key : The name of the variable we wish to remove.
     * @return boolean: Did we successfully delete the variable?
     */
    public function delete($key) {
        return $this->storageInterface->delete($key);
    }
}