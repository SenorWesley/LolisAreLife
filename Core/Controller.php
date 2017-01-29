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
 * Creates the base code, on which custom controllers are built.
 *
 * Class Controller
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */

use LolisAreLife\Core\Error;

abstract class Controller {

    /**
     * Handles all calls to undefined functions.
     *
     * @access public
     * @param string $method: the name of the method called.
     */
    public function __call($method) {
        Error::throwError("unknown.method", $method);
    }
}