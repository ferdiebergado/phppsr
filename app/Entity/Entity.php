<?php

namespace App\Entity;

use App\Entity\EntityInterface;
use ParagonIE\EasyDB\Factory;

class Entity implements EntityInterface
{
    protected static $db;

    protected static $table;

    protected static $namespace = "App\\Entity\\";

    protected static $guarded = [];

    public function __construct()
    {
        if (empty(self::getTable())) {
            self::setTable();
        }
        self::$db = $this->db();
    }

    protected function getTable()
    {
        return self::$table;
    }

    protected function setTable()
    {
        self::$table = strtolower(str_replace(self::$namespace, '', (\get_class($this)))) . 's';
    }

    protected function db()
    {
        $config = require(CONFIG_PATH . 'database.php');
        $dsn = "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'] . ";charset=" . $config['charset'];
        return Factory::create($dsn, $config['username'], $config['password'], $config['options']);
    }

    public static function find($id)
    {
        return self::guard(self::$db->row("SELECT * FROM " . self::$table . " WHERE id = ?", $id));
    }

    public static function guard($array)
    {
        foreach (self::$guarded as $guard) {
            unset($array[$guard]);
        }
        return $array;
    }

}
