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
 * All database access goes through this file.
 * This file also includes a query builder so that running
 * queries will be easier. Database access credentials are
 * loaded from configuration.
 *
 * Usage example:
 *
 * [CODE]
 * Database::table("tablename")->select("username", "email")->where("id", 1)->orderBy("id", "ASC")->get(1);
 * [/CODE]
 *
 * Class Database
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */
class Database {

    /**
     * Holds the database connection
     *
     * @access private
     * @var \PDO $connection
     */
    private $connection;

    /**
     * Holds the string of what to select
     *
     * @access private
     * @var string $select
     */
    private $select = "";

    /**
     * Holds the name of the table
     *
     * @access private
     * @var string $table
     */
    private $table = "";

    /**
     * Holds all the elements that can be used in a query
     *
     * @access private
     * @var array $sql
     */
    private $sql = [
        "join" => "",
        "where" => "",
        "group" => "",
        "order" => "",
        "limit" => "",
        "offset" => ""
    ];

    /**
     * Connects to the database when creating the class
     *
     * @access private
     * @param $table: the table we want to perform mutations on.
     */
    private function __construct($table) {
        if ($this->connection !== null) {
            try {
                $this->connection = new \PDO('mysql:hostname=' . Config::get("database", "hostname") . ';charset=utf8;dbname=' .
                    Config::get("database", "database"),
                    Config::get("database", "username"),
                    Config::get("database", "password"));
                $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                Logger::logMessage("Database connection opened succesfully.")->info();
            } catch (\PDOException $e) {
                Logger::logMessage("Database connection failed")->fatal();
                throw new \PDOException('PDO error:' . $e->getMessage());
            }
        }

        $this->reset();
        $this->table = $table;
    }

    /**
     * Disconnects from the database when disposing the class instance
     *
     * @access public
     */
    public function __destruct() {
        $this->connection = null;
        Logger::logMessage("Database connection closed succesfully.")->info();
    }

    /**
     * Resets the database variables
     *
     * @access private
     */
    private function reset() {
        $this->select = "";
        $this->table = "";
        $this->sql = [
            "join" => "",
            "where" => "",
            "group" => "",
            "order" => "",
            "limit" => "",
            "offset" => ""
        ];
    }

    /**
     * Returns an instance of the database class
     *
     * @access public
     * @param $table: the table we want to perform mutations on
     * @return Database
     * @static
     */
    public static function table($table) {

        return new self($table);
    }

    /**
     * Sets the order by property in the query.
     *
     * @access public
     * @param string $order: the column we want to order by
     * @param string $sort: how we want to sort this
     * @return $this
     */
    public function orderBy($order, $sort = "DESC") {
        $this->sql["order"] = "ORDER BY '$order' $sort";
        return $this;
    }

    /**
     * Sets the offset property in the query
     *
     * @access public
     * @param string $offset: how many records we want to skip
     * @return $this
     */

    public function skip($offset) {
        $this->sql["offset"] = "OFFSET $offset";
        return $this;
    }

    /**
     * Sets the select property in the query
     *
     * @access public
     * @return $this
     */
    public function select() {
        $this->select = implode(", ", func_get_arg());
        return $this;
    }

    /**
     * Sets the where property in the query.
     *
     * @access public
     * @param string $key: the key we want to filter on
     * @param string $value: the value the key has to be
     * @param mixed $selector: the selector if we don't want to check =
     * @return $this
     */
    public function where($key, $value, $selector = null) {
        if ($selector == null)
            $this->sql["where"] = $this->addWhere($key . " = " . $value);
        else
            $this->sql["where"] = $this->addWhere($key . " " . $selector . " " . $value);

        return $this;
    }

    /**
     * Sets the where property in the query based on raw SQL
     *
     * @access public
     * @param string $key: the key we want to filter on
     * @param string $value: the value the key has to be
     * @return $this|void
     */
    public function rawWhere($key, $value) {
        if (gettype($value) !== "array")
            return;

        $key = str_replace(" ", "", $key);

        if (count($value) !== substr_count($key, "?"))
            return;

        $i = 0;
        $array = [];

        foreach(explode("and", $key) as $apartItem) {
            if (strpos($apartItem, "?")) {
                $item = str_replace("?", $value[$i], $apartItem);
                $i++;
            } else {
                $item = $apartItem;
            }

            $array[] = $item;
        }

        $this->sql["where"] = $this->addWhere(implode(" AND ", $array));
        return $this;
    }

    /**
     * Combines all wheres in the query
     *
     * @access private
     * @param string $where: the 'where' we want to add
     * @return string
     */
    private function addWhere($where) {
        if (empty($this->sql["where"]))
            return "WHERE " . $where;
        else
            return "WHERE " . $where . str_replace("WHERE", " AND", $this->sql["where"]);
    }

    /**
     * inserts a record into the database
     *
     * @access public
     * @return string
     */
    public function insert() {
        $keys = "";
        $values = "";

        foreach (func_get_args()[0] as $key => $value) {
            $keys .= $key . ", ";
            $values .= $value . ", ";
        }

        $query = "INSERT INTO $this->table ($keys) VALUES ($values)";
        $execute = $this->connection->prepare($query);
        $execute->execute();
        return $query;

    }

    /**
     * deletes a result from the database
     *
     * @access public
     * @return string
     */
    public function delete() {
        $query = "DELETE FROM $this->table " . $this->finishQuery();
        $execute = $this->connection->prepare($query);
        $execute->execute();
        return $query;
    }

    /**
     * updates a result from the database
     *
     * @access public
     */
    public function update() {
        $updatable = "";

        foreach (func_get_args()[0] as $key => $value) {
            $updatable .= $key . " = " . $value . ", ";
        }

        $updatable = rtrim($updatable, ",");

        $query = "UPDATE $this->table SET $updatable " . $this->finishQuery();
        $execute = $this->connection->prepare($query);
        $execute->execute();
    }

    /**
     * Gets the information in the database based on the properties
     * you set earlier
     *
     * @access public
     * @param int|null $limit: the limit of results we want
     * @param bool|false $array: if we want it in array format
     * @return mixed
     */
    public function get($limit = null, $array = false) {
        if ($limit !== null)
            $this->sql["limit"] = "LIMIT $limit";

        $query = "SELECT $this->select FROM $this->table " . $this->finishQuery();
        $execute = $this->connection->prepare($query);
        $execute->execute();
        if ($array) {
            $result = $execute->fetch(\PDO::FETCH_ASSOC);
        } else {
            $result = $execute->fetch(\PDO::FETCH_OBJ);
        }
        return $result;
    }

    /**
     * Runs a query based on raw SQL
     *
     * @access public
     * @param $query: the query we want to run
     * @return mixed
     * @static
     */
    public static function raw($query) {
        $execute = self::$connection->prepare($query);
        $execute->execute();
        return $execute->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * returns the amount of results in the table we want to check
     *
     * @access public
     * @param int $countable: table we want to count
     * @return mixed
     */
    public function count($countable = 1) {
        $query = "SELECT COUNT($countable) as count FROM $this->table" . $this->finishQuery();
        $execute = $this->connection->prepare($query);
        $execute->execute();
        $result = $execute->fetch(\PDO::FETCH_OBJ);
        return $result->count;
    }

    /**
     * finishes the query by joining the sql array elements
     *
     * @access public
     * @return string
     */
    private function finishQuery() {
        return (implode(" ", $this->sql));
    }
}