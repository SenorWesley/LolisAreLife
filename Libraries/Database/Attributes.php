<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 23-1-2017
 * Time: 22:35
 */

namespace LolisAreLife\Libraries\Database;


trait Attributes {
    /**
     * @var array $attributes:
     * Holds all the (database) attributes that can be filled from a model.
     */
    protected $attributes = [];


    /**
     * Here we set all database attributes into an array with their corresponding value.
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value) {
        if (!array_key_exists($key, $this->attributes))
            $this->attributes[$key] = $value;
    }


    /**
     * Get the value of a specific database attribute.
     * @param $key
     */
    public function getAttribute($key) {
        if (!$key)
            return;

        if (array_key_exists($key, $this->attributes))
            return $this->attributes[$key];

        if (method_exists(self::class, $key))
            return;
    }

}