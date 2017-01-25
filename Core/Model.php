<?php
/**
 * Created by PhpStorm.
 * User: Lolicon2017 & JasonT20015
 * Date: 23-1-2017
 * Time: 07:58
 */

namespace LolisAreLife\Core;


abstract class Model {
    protected $table;
    protected $key = "id";
    protected $keyType = "int";
    protected $dateFormat = "dd-mm-yyyy";
    public $increment = true;
    public $exists = false;
    protected static $models = [];
    const LAST_UPDATE = "last_update";


    public function __get($key) {
        $this->getDatabaseAttribute($key);
    }

    public function __set($key, $value) {
        $this->setDatabaseAttribute($key, $value);
    }

}