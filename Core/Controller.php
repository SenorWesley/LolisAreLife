<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 23-1-2017
 * Time: 07:50
 */

namespace LolisAreLife\Core;
use Error;

abstract class Controller {
    public function __call($method, $args) {
        Error::throwError("unknown.method", $method);
    }
}