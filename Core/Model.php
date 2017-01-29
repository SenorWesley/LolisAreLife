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
 * Creates the base code, on which custom models are built.
 *
 * Class Model
 * @package LolisAreLife\Core
 * @author Wesley Peeters [@link me@wesleypeeters.com]
 * @author Jason Tavernier [@link Jason@tavernier.nl]
 * @version V0.1
 * @since V0.1
 */
class Model {
    /**
     * Holds the name of the table
     *
     * @access protected
     * @var string $tablename
     */
    protected $tableName;

    /**
     * Holds the database table structure and its values
     *
     * @access protected
     * @var array $table
     */
    protected $table = [];

    /**
     * Holds the name of the primary key
     *
     * @access protected
     * @var string $key
     */
    protected $key = "id";

    /**
     * Holds the Type of the primary key
     *
     * @access protected
     * @var string $keyType
     */
    protected $keyType = "int";

    /**
     *  Does the current data exist in the table?
     *
     * @access private
     * @var bool $exists
     */
    private $exists = false;

    /**
     * Gets the table structure from the database when creating a new model
     *
     * @access public
     */
    public function __construct() {
        if (substr(get_class($this), -1) !== "s")
            $this->tableName = get_class($this) . "s";

        $result = Database::raw("DESCRIBE $this->tableName");
        foreach ($result as $column) {
            $this->table[] = $column["Field"];
        }
    }

    /**
     * Sets a variable in the current table
     *
     * @access public
     * @param string $key: the name of the column
     * @param string $value: the value the column has to be
     */
    public function __set($key, $value) {
        if (isset($this->table[$key])) {
            $this->table[$key] = $value;

        } else {
            //TODO Throw error
        }
    }

    /**
     * Selects a full row from the database based on the primary key
     *
     * @access public
     * @param string $value: the value we want the primary key to be
     * @return mixed
     */
    public function where($value) {
        $result = Database::table($this->tableName)
            ->select("*")
            ->where($this->key, $value)
            ->get(1);

        $this->table = $result;
        $this->exists = true;
        return $result;
    }

    /**
     * Saves the current table in the database
     *
     * @access public
     */
    public function save(){
        if ($this->exists) {
            Database::table($this->tableName)->where($this->key, $this->table[$this->key])->update($this->table);
        } else {
            Database::table($this->tableName)->insert($this->table);
        }
    }
}