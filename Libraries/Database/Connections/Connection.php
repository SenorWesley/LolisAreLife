<?php
/**
 * Created by PhpStorm.
 * User: Wesley Peeters
 * Date: 24-1-2017
 * Time: 07:24
 */

namespace LolisAreLife\Libraries\Database\Connections;


class Connection {
    private $pdoOptions =  [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public function createNewConnection($config, $options = "", $dsn = "") {
        if (empty($dsn)){
            $dsn = $config["dbdriver"] . ":host=" . $config["dbhost"] . ":" . $config["dbport"] . ";dbname=" . $config["dbname"];
        }

        list($username, $password) = [
            $config["username"],
            $config["password"]
        ];

        $options = $options ?: $this->pdoOptions;

        return new \PDO($dsn, username, $password, $options);
    }
}